<?php
use yii\helpers\Html;
/* @var $this yii\web\View */
/* @var $user common\models\User */
$confirmLink = Yii::$app->urlManager->createAbsoluteUrl(['site/active', 'confirm_token' => $user->confirm_token]);
?>
<div class="password-reset">
    <p>Xin chào <?= Html::encode($user->full_name) ?>,</p>

    <p>Bạn vừa đăng ký thành viên trên hệ thống TCVN.</p>

    <p>Vui lòng click vào đường link này để kích hoạt tài khoản: <?= Html::a(Html::encode($confirmLink), $confirmLink) ?></p>
</div>
