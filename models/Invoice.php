<?php

namespace app\models;

use Yii;

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
            [['owner_id', 'for_user_id', 'amount', 'login'], 'required'],
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
     * @return \yii\db\ActiveQuery
     */
    public function getOwner()
    {
        return $this->hasOne(User::className(), ['id' => 'owner_id']);
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
