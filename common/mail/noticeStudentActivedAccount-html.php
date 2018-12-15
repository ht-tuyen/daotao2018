<?php
use yii\helpers\Html;
/* @var $this yii\web\View */
/* @var $user common\models\User */
$loginLink = Yii::$app->urlManager->createAbsoluteUrl(['/']);

?>

<div class="password-reset">
    <p>Xin chào <?= Html::encode($user->full_name) ?>,</p>
    <p>Khóa học <b><?php echo $course->name?></b> đã được kích hoạt thành công.</p>
    <p>Bạn có thể truy cập vào <a href="<?php echo $loginLink?>">website</a> để bắt đầu học tập trên hệ thống E-Learning</p>
    <p>Xin cảm ơn!</p>
</div>
