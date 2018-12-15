<?php

namespace frontend\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class ContactForm extends Model
{
    public $name;
    public $email;
    public $subject;
    public $body;
    public $address;
    public $phone;
    public $verifyCode;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // name, email, subject and body are required
            [['name', 'email', 'subject', 'body'], 'required', 'message'=>'{attribute} không hợp lệs'],
            // email has to be a valid email address
            ['email', 'email'],
            // verifyCode needs to be entered correctly
            ['verifyCode', 'captcha','message'=>'Captcha không hợp lệ'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'verifyCode' => 'Mã xác nhận',
            'name'=>'Họ tên',
            'subject'=>'Tiêu đề',
            'body'=>'Nội dung',
            'phone'=>'Số điện thoại',
            'address'=>'Địa chỉ'
        ];
    }

    /**
     * Sends an email to the specified email address using the information collected by this model.
     *
     * @param string $email the target email address
     * @return bool whether the email was sent
     */
    public function sendEmail($email)
    {
        return Yii::$app->mailer->compose()
            ->setTo($email)
            ->setFrom([Yii::$app->params['supportEmail'] => $this->name ." ".$this->phone." ".$this->address])
            ->setSubject($this->subject)
            ->setTextBody($this->body)
            ->send();
    }
}
