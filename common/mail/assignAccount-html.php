<?php
use yii\helpers\Html;
/* @var $this yii\web\View */
/* @var $user common\models\User */
$loginLink = Yii::$app->urlManager->createAbsoluteUrl(['site/login']);

?>

<div class="password-reset">
    <p>Xin chào <?= Html::encode($user->full_name) ?>,</p>

    <p>Bạn vừa được mời tham gia khóa học <b><?php echo $course->name?></b>.</p>
    <p>Bạn vui lòng <a href="<?php echo $loginLink?>">đăng nhập</a> để bắt đầu học tập trên hệ thống E-Learning</p>
    <p>Xin cảm ơn!</p>
</div>
