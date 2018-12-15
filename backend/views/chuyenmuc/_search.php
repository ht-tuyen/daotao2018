<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\ChuyenmucSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="chuyenmuc-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'cm_id') ?>

    <?= $form->field($model, 'tenchuyenmuc') ?>

    <?= $form->field($model, 'slug') ?>

    <?= $form->field($model, 'ngaytao') ?>

    <?= $form->field($model, 'ngaycapnhat') ?>

    <?php // echo $form->field($model, 'nguoitao') ?>

    <?php // echo $form->field($model, 'nguoicapnhat') ?>

    <?php // echo $form->field($model, 'anhdaidien') ?>

    <?php // echo $form->field($model, 'gioithieu') ?>

    <?php // echo $form->field($model, 'trangthai') ?>

    <?php // echo $form->field($model, 'thutu') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
