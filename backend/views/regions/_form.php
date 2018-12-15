<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\Regions */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="regions-form">

    <?php $form = ActiveForm::begin(['fieldClass' => 'common\components\xPActiveField',]); ?>

    <?php
    if(empty($model->countryId)){
        $model->countryId = 260;
    }
    echo $form->field($model, 'countryId')->dropDownList(\backend\models\Countries::getListOptions());

    echo $form->field($model, 'region')->textInput(['maxlength' => true]);
    ?>

    <div class="form-group text-right">
        <div class="col-md-12">
            <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
        <div class="clearfix"></div>
    </div>
    <?php ActiveForm::end(); ?>

</div>
