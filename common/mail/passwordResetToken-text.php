<?php

/* @var $this yii\web\View */
/* @var $user common\models\User */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['site/reset-password', 'token' => $user->password_reset_token]);
?>
Xin chào <?= $user->username ?>,

Vui lòng click vào link dưới đây để đặt lại mật khẩu
<?= $resetLink ?>
