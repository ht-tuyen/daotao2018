<?php
use yii\helpers\Html;
/* @var $this yii\web\View */
/* @var $user common\models\User */
$loginLink = Yii::$app->urlManager->createAbsoluteUrl(['/']);

?>

<div class="password-reset">
    <p>Xin chào <?= Html::encode($user->full_name) ?>,</p>
    <p>Khóa học <b><?php echo $course->name?></b> đã bị khóa.</p>
    <p>Bạn vui lòng liên hệ với giảng viên để biết thêm chi tiết</p>
    <p>Xin cảm ơn!</p>
</div>
