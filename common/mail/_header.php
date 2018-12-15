<?php
$assets_dir = Yii::app()->request->hostInfo . '' . Yii::app()->baseUrl . '/themes/' . Yii::app()->theme->name . '/assets/';
?>
<div align="center">
    <div style="margin-bottom:10px"><img src="<?= $assets_dir ?>/images/mail/logo.jpg" width="247" height="56" style="border: none" /></div>
    <div style="text-align:center; background:url(<?= $assets_dir ?>/images/mail/head_line.gif) no-repeat center center;">
        <strong style="display:inline-block; background:#fff; padding:2px 5px; position:relative; z-index:2; font-size:16px;">
            <?= Yii::t('main', 'Dear') ?> <span style="color:#ad2726"><?php echo $name; ?><!--mailname--></span>
        </strong>
    </div>
</div>