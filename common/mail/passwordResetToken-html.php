<?php
use yii\helpers\Html;
/* @var $this yii\web\View */
/* @var $user common\models\User */
$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['site/reset-password', 'token' => $user->password_reset_token]);
?>
<div class="password-reset">
    <p>Gửi <?= Html::encode($user->username) ?>,</p>

    <p>Bạn vừa yêu cầu khôi phục mật khẩu, nếu không phải bạn yêu cầu thì bỏ qua email này:</p>

    <p>Đường dẫn khôi phục mật khẩu: <?= Html::a(Html::encode($resetLink), $resetLink) ?></p>
</div>
