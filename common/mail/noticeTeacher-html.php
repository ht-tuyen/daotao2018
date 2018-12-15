<?php
use yii\helpers\Html;
/* @var $this yii\web\View */
/* @var $user common\models\User */
?>

<div class="password-reset">
    <p>Xin chào <?= Html::encode($teacher->fullname) ?>,</p>

    <p>Học viên <?php echo $student->full_name?> vừa đăng ký khóa học <?php echo $course->name?> trên hệ thống TCVN E-Learning.</p>
    <p>Tài khoản: <?php echo $student->username?></p>
    <p>Email: <?php echo $student->email?></p>
    <p>Giảng viên vui lòng kiểm tra và kích hoạt tài khoản cho học viên</p>
    <p>Xin cảm ơn!</p>
</div>
