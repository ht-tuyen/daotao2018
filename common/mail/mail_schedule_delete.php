<?php
$this->beginContent('@common/mail/null.php', array(
        'title' =>  '[Thông báo] Thay đổi trạng thái lịch dự kiến', 'name' => 'QuanLyIn'
    )
);
?>

<p>Thành viên vừa thực hiện xóa lịch dự kiến <?= '"<strong>' . $model->title . '</strong>"' ?> trên đơn hàng: <span style='color: red'><?= $model->info->infoCode ?></span></p>
<br>
<br>
Best Regards, <br/><br/>
Quản lý in
<?php $this->endContent(); ?>
