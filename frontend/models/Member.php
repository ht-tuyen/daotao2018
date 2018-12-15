<?php

namespace frontend\models;

use Yii;

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
            [['fullname', 'username', 'password', 'avatar', 'address', 'phone', 'token'], 'string', 'max' => 255],
            [['salt'], 'string', 'max' => 50],
            [['birthday'], 'string', 'max' => 20],
            [['email', 'created_by', 'updated_by'], 'string', 'max' => 100],
            [['mobile'], 'string', 'max' => 15],
            [['linhvucquantam'], 'string', 'max' => 255],
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
            'created_at' => 'Thời gian tạo',
            'created_by' => 'Created By',
            'updated_at' => 'Thời gian cập nhật',
            'updated_by' => 'Updated By',
            'status' => 'Status',
            'last_login_time' => 'Last Login Time',
            'first_change_pass' => 'First Change Pass',
            'about' => 'About',
            'token' => 'Token',
            'time_token' => 'Time Token',
            'linhvucquantam' => 'Lĩnh vực quan tâm',
        ];
    }

    public function behaviors()
    {
        return [
            // [
            //     'class' => BlameableBehavior::className(),
            //     'createdByAttribute' => 'created_by',
            //     'updatedByAttribute' => 'updated_by',
            // ],
            // 'timestamp' => [
            //     'class' => 'yii\behaviors\TimestampBehavior',
            //     'attributes' => [
            //         ActiveRecord::EVENT_BEFORE_INSERT => ['created_at'],
            //         ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
            //     ],
            //     'value' => new Expression('NOW()'),
            // ],
        ];
    }

    public function afterFind()
    {
        if(!empty($this->birthday)) $this->birthday = date("d-m-Y", strtotime($this->birthday));
        if(!empty($this->created_at)) $this->created_at = date("d-m-Y H:i", strtotime($this->created_at));
        if(!empty($this->updated_at)) $this->updated_at = date("d-m-Y H:i", strtotime($this->updated_at));
        parent::afterFind();
    }
}
