
<?php
$this->beginContent('@common/mail/null.php', array(
        'title' => $title, 'name' => 'QuanLyIn', 'signature' => $signature
    )
);
?>

<pre style="font-size: 13px"><?= $content ?></pre>

<br>
<br>
<?php
//if ($signature == 1):
//    print_r(Controller::settingGet('signature_send_mail'));
//endif;
?>
<?php $this->endContent(); ?>