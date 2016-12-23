<?php

namespace app\models;

use Yii;
use app\models\User;
use app\models\Billing;

/**
 * This is the model class for table "billing_operations".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $amount
 *
 * @property User $user
 */
class BillingOperations extends \yii\db\ActiveRecord
{
    public $login;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'billing_operations';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'login', 'amount'], 'required'],
            [['user_id'], 'integer'],
            [['amount'], 'number'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'amount' => 'Amount',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
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
            
            $this->user_id = $user->id;
        }
        
        return parent::beforeValidate();
    }
    
    public function beforeSave($insert)
    {
        // increment user billing
        $user = $this->getUser()->one();
        $userBilling = $user->getBilling()->one();
        
        // if user seel not have billing crete it
        if(!$userBilling) {
            $userBilling = new Billing;
            $userBilling->user_id = $user->id;
            $userBilling->balance = 0;
        }
        
        // it would bee better use transactions, and async SQL request
        $userBilling->balance = $userBilling->balance + $this->amount;
        
        $userBilling->save();
        
        return parent::beforeSave($insert);
    }
}
