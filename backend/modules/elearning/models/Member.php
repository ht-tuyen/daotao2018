<?php

namespace backend\modules\elearning\models;

use Yii;

/**
 * This is the model class for table "qli_member".
 *
 * @property integer $user_id
 * @property integer $type
 * @property integer $user_type
 * @property string $fullname
 * @property string $username
 * @property string $password
 * @property string $idthanhvien
 * @property string $salt
 * @property string $birthday
 * @property string $email
 * @property string $avatar
 * @property string $address
 * @property integer $role_id
 * @property string $last_login
 * @property string $phone
 * @property string $mobile
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property integer $status
 * @property string $last_login_time
 * @property integer $first_change_pass
 * @property string $about
 * @property string $token
 * @property string $time_token
 * @property string $linhvucquantam
 */
class Member extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'qli_member';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'user_type', 'idthanhvien', 'role_id', 'status', 'first_change_pass'], 'integer'],
            [['password', 'salt'], 'required'],
            [['last_login', 'created_at', 'updated_at', 'last_login_time', 'time_token'], 'safe'],
            [['about'], 'string'],
            [['fullname', 'username', 'password', 'avatar', 'address', 'phone', 'token', 'linhvucquantam'], 'string', 'max' => 255],
            [['salt'], 'string', 'max' => 50],
            [['birthday'], 'string', 'max' => 20],
            [['email', 'created_by', 'updated_by'], 'string', 'max' => 100],
            [['mobile'], 'string', 'max' => 15],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' => 'User ID',
            'type' => 'Type',
            'user_type' => 'User Type',
            'fullname' => 'Fullname',
            'username' => 'Username',
            'password' => 'Password',
            'idthanhvien' => 'Idthanhvien',
            'salt' => 'Salt',
            'birthday' => 'Birthday',
            'email' => 'Email',
            'avatar' => 'Avatar',
            'address' => 'Address',
            'role_id' => 'Role ID',
            'last_login' => 'Last Login',
            'phone' => 'Phone',
            'mobile' => 'Mobile',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'status' => 'Status',
            'last_login_time' => 'Last Login Time',
            'first_change_pass' => 'First Change Pass',
            'about' => 'About',
            'token' => 'Token',
            'time_token' => 'Time Token',
            'linhvucquantam' => 'Linhvucquantam',
        ];
    }
}
