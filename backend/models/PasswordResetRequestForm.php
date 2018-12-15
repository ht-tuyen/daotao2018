<?php
namespace backend\models;

use Yii;
use yii\base\Model;
use backend\models\User;

class PasswordResetRequestForm extends Model
{
    public $email;
    public $verifycode;

    public function rules()
    {
        return [
            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email', 'message' => 'Không đúng định dạng email.'],
            ['email', 'exist',
                'targetClass' => '\backend\models\User',
                'filter' => ['status' => User::STATUS_ACTIVE],
                'message' => 'Email không tồn tại.'
            ],
            ['verifycode', 'captcha', 'message' => 'Mã không đúng'],
        ];
    }

    public function sendEmail()
    {
        /* @var $user User */
        $user = User::findOne([
            'status' => User::STATUS_ACTIVE,
            'email' => $this->email,
        ]);

        if (!$user) {
            return false;
        }
        
        if (!User::isPasswordResetTokenValid($user->token)) {
            $user->generatePasswordResetToken();
            if (!$user->save()) {
                return false;
            }
        }
        // return 1;
        return Yii::$app
            ->mailer
            ->compose(
                ['html' => 'passwordResetToken-html', 'text' => 'passwordResetToken-text'],
                ['user' => $user]
            )
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'])
            ->setTo($this->email)
            ->setSubject('Password reset for ' . Yii::$app->name)
            ->send();
    }
}
