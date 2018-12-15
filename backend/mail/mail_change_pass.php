<?php
$this->beginContent('@common/mail/null.php', array(
        'title' => 'Thay đổi mật khẩu người dùng', 'name' => 'Vsqi'
    )
);
?>

    <p style="font-size: 13px">
        Quản trị phần mềm Viện tiêu chuẩn chất lượng Việt Nam vừa thay đổi mật khẩu đăng nhập tài khoản của bạn
    </p>
    <p style="font-size: 13px">
        Vui lòng đăng nhập với mật khẩu mới thông tin sau:
    </p>
    <p>
        Tên tài khoản: <b><?= $user->username ?></b>
    </p>
    <p>
        Mật khẩu: <b><?= $password ?></b>
    </p>
    <br>
    <br>
    Best Regards, <br/><br/>
    Vsqi
<?php $this->endContent(); ?>