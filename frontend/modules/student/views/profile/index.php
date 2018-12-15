<?php 


$this->title = 'Thông tin tài khoản';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="student-profile-index">
    <h3>Thông tin cá nhân</h3>
    <br>
    <table class="table table-bordered">
        <tr >
            <td width="200" rowspan="7"><img src="/uploads/avatars/<?php echo Yii::$app->user->identity->avatar?>" alt=""></td>
            <td>Họ tên</td>
            <td><?php echo Yii::$app->user->identity->full_name?></td>
        </tr>
        <tr>
            <td>Số điện thoại</td>
            <td><?php echo Yii::$app->user->identity->mobile?></td>
        </tr>
        <tr>
            <td>Địa chỉ</td>
            <td><?php echo Yii::$app->user->identity->address?></td>
        </tr>
        <tr>
            <td>Ngày sinh</td>
            <td><?php echo date('d-m-Y', strtotime(Yii::$app->user->identity->dob))?></td>
        </tr>
        <tr>
            <td>Đơn vị công tác</td>
            <td><?php echo Yii::$app->user->identity->company?></td>
        </tr>
        <tr>
            <td>Chức vụ</td>
            <td><?php echo Yii::$app->user->identity->position?></td>
        </tr>
        <tr>
            <td>Giới tính</td>
            <td><?php echo Yii::$app->user->identity->gender == 1 ? "Nam" : "Nữ"?></td>
        </tr>
    </table>
    <a href="/student/profile/edit" class="btn btn-info">Cập nhật</a>
    <a href="/student/profile/changepass" class="btn btn-info">Đổi mật khẩu</a>

    <br>
    
</div>
<br>
