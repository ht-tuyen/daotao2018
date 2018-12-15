<?php

namespace common\captcha;

use yii\captcha\CaptchaAction as DefaultCaptchaAction;

class CaptchaAction extends DefaultCaptchaAction
{
    protected function generateVerifyCode()
    {
        if ($this->minLength > $this->maxLength) {
            $this->maxLength = $this->minLength;
        }
        if ($this->minLength < 3) {
            $this->minLength = 3;
        }
        if ($this->maxLength > 8) {
            $this->maxLength = 8;
        }
        $length = mt_rand($this->minLength, $this->maxLength);
        $digits = '0123456789';
        $code = '';
        for ($i = 0; $i < $length; ++$i) {
            $code .= $digits[mt_rand(0, 9)];
        }
        return $code;
    }
}