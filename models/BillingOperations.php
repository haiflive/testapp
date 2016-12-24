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
 * @property integer $reciver_id
 *
 * @property User $reciver
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
            [['user_id', 'reciver_id', 'amount', 'login'], 'required'],
            [['user_id', 'reciver_id'], 'integer'],
            [['amount'], 'number', 'min'=>0],
            [['reciver_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['reciver_id' => 'id']],
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
            'reciver_id' => 'Reciver ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReciverUser()
    {
        return $this->hasOne(User::className(), ['id' => 'reciver_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSenderUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
    
    public function beforeValidate()
	{
        //!? add check pass himself, it can create balance async error
        
        if(!empty($this->login) && $this->getIsNewRecord()) {
            // find user ID
            $user = User::findByUsername($this->login);
            
            // register user if not exists
            if ($user === null) {
                $user = User::userRegistration($this->login);
            }
            
            $this->user_id = Yii::$app->user->id;
            $this->reciver_id = $user->id;
        }
        
        return parent::beforeValidate();
    }
    
    public function beforeSave($insert)
    {
        //-- increment reciver user billing
        $userReciver = $this->getReciverUser()->one();
        $userBilling = $userReciver->getBilling()->one();
        
        // if user seel not have billing crete it
        if($userBilling === null) {
            $userBilling = new Billing;
            $userBilling->user_id = $userReciver->id;
            $userBilling->balance = 0;
        }
        
        //-- decrement sender user billing
        $userSender = $this->getSenderUser()->one();
        $senderBilling = $userSender->getBilling()->one();
        
        // if sender seel not have billing crete it
        if($senderBilling === null) {
            $senderBilling = new Billing;
            $senderBilling->user_id = $userSender->id;
            $senderBilling->balance = 0;
        }
        
        //! it would bee better use transactions, or async SQL command
        $userBilling->balance = $userBilling->balance + $this->amount;
        $senderBilling->balance = $senderBilling->balance - $this->amount;
        
        $userBilling->save();
        $senderBilling->save();
        
        return parent::beforeSave($insert);
    }
}
