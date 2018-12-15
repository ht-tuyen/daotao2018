<?php
$this->beginContent('@common/mail/null.php', array(
        'title' => $title, 'name' => 'QuanLyIn'
    )
);
?>

    <p><?= $content ?></p>
    <br>
    Best Regards, <br/><br/>
    Quản lý in
<?php $this->endContent(); ?>