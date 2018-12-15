<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\SourceMessage */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="source-message-form">
    <?php $form = ActiveForm::begin(['fieldClass' => 'common\components\xPActiveField',]); ?>
    <div class="panel panel-info">

        <div class="panel-heading"><?php echo \Yii::t('app', 'Information') ?></div>
        <div class="panel-body">
            <?php
                echo $form->field($model, 'category')->textInput();
                echo $form->field($model, 'message')->textarea();
            ?>
        </div>
        <div class="panel-heading"><?php echo \Yii::t('app', 'Translate') ?></div>
        <div class="panel-body">
            <?php
            if (!empty($languages)):
                foreach ($languages as $language):
                    echo '<div class="form-group"><label class="col-md-2">' . $language->title . '</label><div class="col-md-10">';
                    echo Html::textarea("Message[{$language->code}]", isset($messages) ? $messages[$language->code] : '', ['rows' => 2, 'class' => 'form-control']);
                    echo '</div><div class="clearfix"></div></div>';
                endforeach;
            endif;
            ?>

            <div class="form-group text-right">
                <div class="col-md-12">
                    <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>

    </div>
    <?php ActiveForm::end(); ?>
</div>
