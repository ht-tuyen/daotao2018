<?php
use yii\widgets\Breadcrumbs;
use dmstr\widgets\Alert;

?>
<div class="content-wrapper custom-<?php echo Yii::$app->controller->id .'-'. Yii::$app->controller->action->id;?>">
   

    <section class="content">
        <?= Alert::widget() ?>
        <?= $content ?>
    </section>
</div>

<footer class="main-footer">
    <div class="pull-right hidden-xs">
        <b>Version</b> 2.0
    </div>
    <strong>Copyright &copy; 2018 VSQI.</strong> All rights
    reserved.
</footer>