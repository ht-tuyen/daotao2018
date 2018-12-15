<?php
namespace frontend\models;

use yii\base\Model;
use common\models\User;
use yii\web\UploadedFile;
use Yii;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $username;
    public $email;
    public $password;
    public $full_name;
    public $address;
    public $mobile;
    public $dob;
    public $gender;
    public $company;
    public $position;
    public $company_address;
    public $confirm_token;
    public $avatar;
    public $academic;
    public $degree;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['username', 'trim'],
            [['address','mobile','dob','gender','company','academic','degree','avatar','company_address'],'string', 'min' => 1, 'max' => 255],
            [
                ['username','password','email','full_name'], 
                'required',
                'message' => '{attribute} không được để trống'
            ],
            ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => 'Tài khoản đã tồn tại.'],
            ['username', 'string', 'min' => 2, 'max' => 255],
          
            ['email', 'trim'],
           
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'Email đã tồn tại.'],
           
           
            ['password', 'string', 'min' => 6],
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function attributeLabels()
    {
        return [
            'username' => 'Tài khoản',
            'full_name'=>'Họ tên',
            'address'=>'Địa chỉ',
            'mobile'=>'Số điện thoại',
            'dob'=>'Ngày sinh',
            'gender'=>'Giới tính',
            'company'=>'Đơn vị công tác',
            'position'=>'Chức vụ',
            'password'=>'Mật khẩu',
            'avatar'=>'Ảnh đại diện',
            'academic'=>'Học hàm',
            'degree'=>'Học vị',
            'company_address'=>'Địa chỉ đơn vị công tác'



        ];
    }
    public function upload()
    {
     
        $this->avatar->saveAs(Yii::getAlias('@anyname') . '/uploads/avatars/' . $this->avatar->name );
           
    }
    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }

       
        $user = new User();
        $user->username = $this->username;
        $user->full_name = $this->full_name;
        $user->address = $this->address;
        $user->mobile = $this->mobile;
        $user->dob = date("Y-m-d",strtotime($this->dob));
        $user->gender = $this->gender;
        $user->company = $this->company;
        $user->academic = $this->academic;
        $user->degree = $this->degree;
        $user->email = $this->email;
        $user->position = $this->position;
        $user->company_address = $this->company_address;
        $user->status = 0;
        $user->setPassword($this->password);
        $user->confirm_token = sha1(mt_rand(10000, 99999).time().$this->email);
        $user->generateAuthKey();
        $user->avatar = $this->avatar;
        return $user->save() ? $user : null;
    }
}
