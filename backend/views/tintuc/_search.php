<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\TintucSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tintuc-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'tt_id') ?>

    <?= $form->field($model, 'tieude') ?>

    <?= $form->field($model, 'noidung') ?>

    <?= $form->field($model, 'gioithieu') ?>

    <?= $form->field($model, 'anhdaidien') ?>

    <?php // echo $form->field($model, 'slug') ?>

    <?php // echo $form->field($model, 'idchuyenmuc') ?>

    <?php // echo $form->field($model, 'ngaytao') ?>

    <?php // echo $form->field($model, 'ngaycapnhat') ?>

    <?php // echo $form->field($model, 'nguoitao') ?>

    <?php // echo $form->field($model, 'nguoicapnhat') ?>

    <?php // echo $form->field($model, 'trangthai') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
