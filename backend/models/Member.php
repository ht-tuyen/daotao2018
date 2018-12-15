<?php

namespace backend\models;

use Yii;
use yii\base\NotSupportedException;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yii\behaviors\BlameableBehavior;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
/**
 * This is the model class for table "{{%member}}".
 *
 * @property integer $user_id
 * @property integer $type
 * @property integer $user_type
 * @property string $fullname
 * @property string $username
 * @property string $password
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
 */
class Member extends ActiveRecord implements IdentityInterface
{
    const ERROR_STATE_INVALID = 11; //tai khoan bi khoa hoac chua duoc kich hoat
    const NOT_BE_ALLOWED_ACCESS = 21; //khong duoc phep truy cap vao admincp
    const ERROR_NONE=0;
    const ERROR_USERNAME_INVALID=1;
    const ERROR_PASSWORD_INVALID=2;
    const ERROR_UNKNOWN_IDENTITY=100;

    const STATUS_ACTIVE = 1;

    public $re_password;
    public $chg_password;
    public $errorCode;

    private static $_all_staff = null;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%member}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'user_type', 'role_id', 'status', 'first_change_pass'], 'integer'],

            [['username','email', 'password', 'salt'], 'required'],

            [['last_login', 'created_at', 'updated_at', 'last_login_time'], 'safe'],
            [['about'], 'string'],
            [['fullname', 'username', 'password', 'avatar', 'address', 'phone'], 'string', 'max' => 255],
            [['salt'], 'string', 'max' => 50],
            [['token'], 'string','max' => 255],
            [['time_token'], 'safe'],
            [['birthday'], 'date', 'format' => 'php:Y-m-d'],
            ['birthday', 'default', 'value' => null],
            [['email', 'created_by', 'updated_by'], 'string', 'max' => 100],
            [['mobile'], 'string', 'max' => 15],
//            ['re_password','compare','compareAttribute'=>'password','message'=>'Mật khẩu phải khớp với mật khẩu nhập lại'],

            [['username'], 'unique', 'message' => 'Tên đăng nhập được sử dụng.','when'=> function($model) { return !empty($model->username);}],

            [['email'], 'unique', 'message' => 'Email đã được sử dụng.','when'=> function($model) { return !empty($model->email);}],

            ['email', 'email'],
            ['re_password', 'compare', 'compareAttribute'=>'password', 'message'=>"Mật khẩu không trùng khớp"],

            [['linhvucquantam'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' => 'Member ID',
            'type' => 'Type',
            'user_type' => 'Member Type',
            'fullname' => 'Họ tên',
            'username' => 'Tên đăng nhập',
            'password' => 'Mật khẩu',
            'salt' => 'Salt',
            'birthday' => 'Ngày sinh',
            'email' => 'Email',
            'avatar' => 'Avatar',
            'address' => 'Địa chỉ',
            'role_id' => 'Quyền hạn',
            'last_login' => 'Last Login',
            'phone' => 'Phone',
            'mobile' => 'Điện thoại',
            'created_at' => 'Thời gian tạo',
            'created_by' => 'Created By',
            'updated_at' => 'Thời gian cập nhật',
            'updated_by' => 'Updated By',
            'status' => 'Status',
            'last_login_time' => 'Last Login Time',
            'first_change_pass' => 'First Change Pass',
            'about' => 'About',
            're_password' => 'Nhập lại mật khẩu mới',
            'chg_password'=> 'Đổi mật khẩu',
            'linhvucquantam' => 'Lĩnh vực quan tâm',
        ];
    }

    public function attributeLabels_rules_show()
    {
        return [
            'fullname' => 'Họ tên',
            'username' => 'Tên đăng nhập',
            'password' => 'Mật khẩu',
            'birthday' => 'Ngày sinh',
            'email' => 'Email',
            'address' => 'Địa chỉ',
            'role_id' => 'Quyền hạn',
            'mobile' => 'Điện thoại',
        ];
    }

    public function afterFind()
    {
        if(!empty($this->birthday)) $this->birthday = date("d-m-Y", strtotime($this->birthday));
        parent::afterFind();
    }


    public function validateMemberPassword($password) {
        return self::hashMemberPassword($password, $this->salt) === $this->password;
    }

    public function hashPassword($password, $salt) {
        return md5($salt . $password);
    }

    public static function hashMemberPassword($password, $salt) {
        return md5(md5(md5("$password|$salt")));
    }

    public static function generateSalt() {
        return md5(uniqid('', true));
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['user_id' => $id]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByMembername($username)
    {
        return static::findOne(['username' => $username]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'token' => $token,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->salt;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password) {
        return $this->hashPassword($password, $this->salt) === $this->password;
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->salt = md5(uniqid('', true));
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->token = null;
    }

    public function authenticate($authModel) {

        if (!$authModel) {
            $this->errorCode = self::ERROR_USERNAME_INVALID;
        } else if ($authModel && $authModel->password !== self::hashMemberPassword($this->password, $authModel->salt)) {
            $role = Role::findOne(['role_id' => $authModel->role_id]);
            if ($role == '') {
                if (!$authModel->validatePassword($this->password)) {
                    $this->errorCode = self::ERROR_PASSWORD_INVALID;
                } else {
                    if ($authModel->username == 'admin') {
                        $authModel->role_id = 1;
                        $authModel->password = self::hashMemberPassword($this->password, Yii::$app->params['salt']);
                        $authModel->salt = Yii::$app->params['salt'];
                        $authModel->save();
                    } else {
                        $authModel->role_id = 0;
                        $authModel->save();
                    }

                    if ($role == NULL) {
                        $this->errorCode = self::NOT_BE_ALLOWED_ACCESS;
                    } else {
                        $admin_use = (int) $role->admin_use;
                        $role_status = (int) $role->status;
                        $acl_desc = $role->acl_desc;
                    }
                    if (($acl_desc != Yii::$app->params['fullAccess']) && ($admin_use != 1 || $role_status != 1)) {
                        //khong duoc phep truy cap vao admincp
                        $this->errorCode = self::NOT_BE_ALLOWED_ACCESS;
                    } else {
                        $this->errorCode = self::ERROR_NONE;
                    }
                }
            } else {
                $this->errorCode = self::ERROR_PASSWORD_INVALID;
            }
        } else if ($authModel && $authModel->status != 1) {
            $this->errorCode = self::ERROR_STATE_INVALID;
        } else {
            $admin_use = (int) $role->admin_use;
            $role_status = (int) $role->status;
            $acl_desc = $role->acl_desc;

            if (($acl_desc != Yii::$app->params['fullAccess']) && ($admin_use != 1 || $role_status != 1)) {
                //khong duoc phep truy cap vao admincp
                $this->errorCode = self::NOT_BE_ALLOWED_ACCESS;
            } else {
                $this->errorCode = self::ERROR_NONE;
            }
        }
        return !$this->errorCode;
    }

    public function behaviors()
    {
        return [
            [
                'class' => BlameableBehavior::className(),
                'createdByAttribute' => 'created_by',
                'updatedByAttribute' => 'updated_by',
            ],
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    public function beforeSave($insert) {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                // $salt = Yii::$app->params['salt'];
                // $this->password = self::hashMemberPassword($_POST['Member']['password'], $salt);
                // $this->salt = $salt;
            }

            if (empty($this->fullname))
                $this->fullname = ucfirst($this->username);

            return true;
        } else
            return false;
    }

    public function getName() {
        if (!empty($this->fullname)) {
            return $this->fullname;
        } else if (!empty($this->username)) {
            return $this->username;
        }
        return $this->email;
    }

    public static function getAllStaff() {
        if (!isset(self::$_all_staff)) {
            self::$_all_staff = self::find(['status' => 1])->orderBy(['fullname' => SORT_ASC])->all();
        }
        return self::$_all_staff;
    }

    public static function getStaffOptions() {
        $staff = self::getAllStaff();
        return ArrayHelper::map($staff, 'user_id', 'name');
    }
}
