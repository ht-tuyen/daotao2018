<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\Classify */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="classify-form">
    <div class="get_error">

    </div>
    <input type="hidden" value="<?= $model->classify_id ?>" name="id"/>

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data', 'id'=>'classify-form-']]); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'moc_doanh_thu')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'moc_so_luong')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'thoi_gian_tinh')->textInput() ?>

    <?= $form->field($model, 'symbol')->fileInput() ?>
    <?= $form->field($model, 'symbol')->hiddenInput()->label(false); ?>

    <?php
    if (!empty($model->symbol)) {
        echo Html::img('../../uploads/' . $model->symbol, ['class' => 'file-preview-image', 'alt' => 'Logo', 'title' => 'Logo', 'style' => 'max-height: 64px']);
    }
    ?>

    <?= $form->field($model, 'status')->checkbox(['label' => ''])->label(Yii::t('app', 'Status')); ?>

    <div class="form-group">

        <input type="button" value="<?= $model->isNewRecord ? "Tạo mới" : "Cập nhật" ?>" class="save_classify_ajax <?=  $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary'?>" data-id="<?= $model->isNewRecord ? "" : $model->classify_id ?>" />
    </div>

    <?php ActiveForm::end(); ?>

</div>