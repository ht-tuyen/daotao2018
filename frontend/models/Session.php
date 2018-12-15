<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "qli_session".
 *
 * @property integer $session_id
 * @property string $device_id
 * @property integer $user_id
 * @property string $token
 * @property string $x_token
 * @property string $create_time
 * @property string $last_login
 */
class Session extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'qli_session';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id','member_id'], 'integer'],
            [['create_time', 'last_login', 'member_login_time'], 'safe'],
            [['device_id', 'token','x_token'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'session_id' => 'Session ID',
            'device_id' => 'Device ID',
            'user_id' => 'User ID',
            'token' => 'Token',
            'create_time' => 'Create Time',
            'last_login' => 'Last Login',
        ];
    }
}
