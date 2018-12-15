<?php
namespace backend\models;

use yii\base\Model;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $username;
    public $email;
    public $password;
    public $fullname;
    public $role_id;
    public $mobile;
    public $type;
    public $status;
    public $address;
    public $birthday;
    public $about;
    public $last_login_time;
    public $isNewRecord;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => '\backend\models\User', 'message' => 'This username has already been taken.'],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\backend\models\User', 'message' => 'This email address has already been taken.'],

            ['password', 'required'],
            ['password', 'string', 'min' => 6],

            [['type', 'user_type', 'role_id', 'status', 'first_change_pass'], 'integer'],
            [['last_login', 'created_at', 'updated_at', 'last_login_time'], 'safe'],
            [['about'], 'string'],
            [['fullname', 'avatar', 'address', 'phone'], 'string', 'max' => 255],
            [['salt'], 'string', 'max' => 50],
            [['birthday'], 'string', 'max' => 20],
            [['created_by', 'updated_by'], 'string', 'max' => 100],
            [['mobile'], 'string', 'max' => 15],
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }
        
        $user = new User();
        $user->username = $this->username;
        $user->email = $this->email;
        $user->fullname = $this->fullname;
        $user->setPassword($this->password);
        $user->generateAuthKey();
        
        return $user->save() ? $user : null;
    }
}
