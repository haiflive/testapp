<?php

namespace app\models;

use Yii;
use yii\web\ForbiddenHttpException;

/**
 * This is the model class for table "invoice".
 *
 * @property integer $id
 * @property integer $owner_id
 * @property integer $for_user_id
 * @property integer $status
 * @property string $amount
 *
 * @property User $forUser
 * @property User $owner
 */
class Invoice extends \yii\db\ActiveRecord
{
    public $login;
    
    const STATUS_NEW = 1;
    const STATUS_ACCEPTED = 2;
    const STATUS_REJECTED_BY_SENDER = 3;
    const STATUS_REJECTED_BY_RECEIVER = 4;
    
    const SCENARIO_ACCEPT = 'accept';
    const SCENARIO_REJECT = 'reject';
    
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_ACCEPT] = ['login'];
        $scenarios[self::SCENARIO_REJECT] = ['login'];
        return $scenarios;
    }
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'invoice';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['owner_id', 'for_user_id', 'amount'], 'required'],
            [['login'], 'required', 'on' => self::SCENARIO_DEFAULT],
            [['owner_id', 'for_user_id', 'status'], 'integer'],
            [['amount'], 'number', 'min'=>0],
            [['for_user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['for_user_id' => 'id']],
            [['owner_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['owner_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'owner_id' => 'Owner ID',
            'for_user_id' => 'For User ID',
            'status' => 'Status',
            'amount' => 'Amount',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getForUser()
    {
        return $this->hasOne(User::className(), ['id' => 'for_user_id']);
    }

    /**
     *  Invlice Initiator, user who send invoice
     * @return \yii\db\ActiveQuery
     */
    public function getOwner()
    {
        return $this->hasOne(User::className(), ['id' => 'owner_id']);
    }
    
    /**
     *  accept invoice
     *  @return bool
     */
    public function accept()
    {
        if($this->status !== self::STATUS_NEW) // can accept only once
            throw new ForbiddenHttpException;
        
        $this->scenario = self::SCENARIO_ACCEPT;
        
        // invoice can accept only user for whom this Invoice
        if($this->for_user_id !== Yii::$app->user->id)
            throw new ForbiddenHttpException;
        
        // pass money to invoice owner
        $model = new BillingOperations();
        
        $model->user_id = Yii::$app->user->id;
        $model->login = $this->getOwner()->one()->login;
        $model->amount = $this->amount;
        
        $this->status = self::STATUS_ACCEPTED;
        
        //? transactions
        return $this->save() && $model->save();
    }
    
    /**
     *  reject invoice
     *  @return bool
     */
    public function reject()
    {
        if($this->status !== self::STATUS_NEW) // can reject only once
            throw new ForbiddenHttpException;
        
        $this->scenario = self::SCENARIO_REJECT;
            
        if($this->for_user_id !== Yii::$app->user->id) {
            $this->status = self::STATUS_REJECTED_BY_SENDER;
            $this->save();
            return true; // exit
        }
        
        if($this->owner_id !== Yii::$app->user->id) {
            $this->status = self::STATUS_REJECTED_BY_RECEIVER;
            $this->save();
            return true; // exit
        }
        
        throw new ForbiddenHttpException;
    }
    
    public function beforeValidate()
	{
        if(!empty($this->login) && $this->getIsNewRecord()) {
            // find user ID
            $user = User::findByUsername($this->login);
            
            // register user if not exists
            if ($user === null) {
                $user = User::userRegistration($this->login);
            }
            
            $this->owner_id = Yii::$app->user->id;
            $this->for_user_id = $user->id;
        }
        
        return parent::beforeValidate();
    }
}
