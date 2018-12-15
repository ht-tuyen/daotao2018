<?php
$this->beginContent('@common/mail/null.php', array(
        'title' =>  $title, 'name' => 'QuanLyIn'
    )
);
?>

    <pre style="font-size: 13px"><?= $content ?> </pre>

    <br>
    <br>
    Best Regards, <br/><br/>
    Quản lý in
<?php $this->endContent(); ?>