<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\PasswordResetRequestForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Lấy lại mật khẩu';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-request-password-reset">

    <p>Nhập email của bạn. Link để cài đặt lại mật khẩu sẽ được gửi tới em</p>

    <div class="row">
        <div class="col-lg-5">
        <?php if (Yii::$app->session->getFlash('success')) {?> 
            <div class="alert alert-success"><?= Yii::$app->session->getFlash('success'); ?></div>
            <?php }?>
            <?php $form = ActiveForm::begin(['id' => 'request-password-reset-form']); ?>

                <?= $form->field($model, 'email')->textInput(['autofocus' => true]) ?>

                <div class="form-group">
                    <?= Html::submitButton('Gửi', ['class' => 'btn btn-primary']) ?>
                </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
