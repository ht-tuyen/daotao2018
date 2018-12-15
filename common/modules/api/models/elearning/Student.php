<?php

namespace common\modules\api\models\elearning;
use common\modules\api\models\elearning\CourseForUser;
use Yii;

/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property string $username
 * @property string $auth_key
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $full_name
 * @property string $address
 * @property string $mobile
 * @property string $dob
 * @property integer $gender
 * @property string $company
 * @property string $position
 */
class Student extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'auth_key', 'password_hash', 'email', 'created_at', 'full_name'], 'required'],
            [['status', 'created_at', 'updated_at', 'gender'], 'integer'],
            [['dob'], 'safe'],
            [['username', 'password_hash', 'password_reset_token', 'email', 'address', 'company', 'position', 'company_address','academic','degree'], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 32],
            [['full_name'], 'string', 'max' => 100],
            [['mobile'], 'string', 'max' => 15],
            [['username'], 'unique'],
            [['email'], 'unique'],
            [['password_reset_token'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Tài khoản',
            'auth_key' => 'Auth Key',
            'password_hash' => 'Password Hash',
            'password_reset_token' => 'Password Reset Token',
            'email' => 'Email',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'full_name' => 'Tên đầy đủ',
            'address' => 'Địa chỉ',
            'mobile' => 'Điện thoại',
            'dob' => 'Ngày sinh',
            'gender' => 'Giới tính',
            'company' => 'Đơn vị công tác',
            'position' => 'Chức vụ',
        ];
    }

    public function fields()
    {
        return [
            'id' ,
            'username',
            'auth_key' ,
            'password_hash' ,
            'password_reset_token',
            'email' ,
            'status',
            'created_at',
            'updated_at',
            'full_name' ,
            'address',
            'mobile',
            'dob' => function ($model) {
                return date("d-m-Y", strtotime($model->dob));
            },
            'gender',
            'company',
            'position',
            'academic',
            'degree',
            'company_address',
            'results' => function ($model) {
                return $model->results; // Return related model property, correct according to your structure
            },
        ];
    }
    public function getResults()
    {
        //return $this->hasMany(QuizResult::className(), ['user_id' => 'id']);
    }
	public function getCourses()
	{
		return CourseForUser::find()->where(['user_id'=>$this->id])->count();
	}
	public function getFinishedcourses()
	{
		return CourseForUser::find()->where(['user_id'=>$this->id, 'reviewed'=>1])->count();
	}
    
}
