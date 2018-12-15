<?php
namespace backend\models;

use Yii;
use yii\base\Model;

/**
 * Login form
 */
class ChangePasswordForm extends Model
{
    public $password;
    public $re_password;
    public $old_password;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['re_password', 'password', 'old_password'], 'required'],
            // rememberMe must be a boolean value
            // password is validated by validatePassword()
            ['old_password', 'validatePassword'],
            ['password', 'string', 'min' => 6],
            ['re_password','compare','compareAttribute'=>'password'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'password' => 'Mật khẩu mới',
            're_password' => 'Nhập lại mật khẩu',
            'old_password' => 'Mật khẩu hiện tại',
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute)
    {
        $user = User::find()->where([
            'username'=>Yii::$app->user->identity->username
        ])->one();
        $password = $user->password;
        if($password != User::hashUserPassword($this->old_password, $user->salt))
            $this->addError($attribute,Yii::t('app', 'Old password is incorrect'));
    }

}
