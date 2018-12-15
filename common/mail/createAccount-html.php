<?php
use yii\helpers\Html;
/* @var $this yii\web\View */
/* @var $user common\models\User */
$loginLink = Yii::$app->urlManager->createAbsoluteUrl(['site/login']);

?>

<div class="password-reset">
    <p>Xin chào <?= Html::encode($user->full_name) ?>,</p>

    <p>Bạn vừa được tạo tài khoản trên hệ thống TCVN E-Learning.</p>
    <p>Tài khoản: <?php echo $user->username?></p>
    <p>Mật khẩu: ABC@123</p>
    <p>Bạn vui lòng <a href="<?php echo $loginLink?>">đăng nhập</a> và đổi mật khẩu để bắt đầu học tập trên hệ thống E-Learning</p>
    <p>Xin cảm ơn!</p>
</div>
