<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Đăng nhập';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">

    
   

    <div class="row">
        <div class="col-lg-5">
            <?php if (Yii::$app->session->getFlash('success')) {?> 
            <div class="alert alert-success"><?= Yii::$app->session->getFlash('success'); ?></div>
            <?php }?>
            <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

                <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>

                <?= $form->field($model, 'password')->passwordInput() ?>

                <?= $form->field($model, 'rememberMe')->checkbox() ?>

                <div style="color:#999;margin:1em 0">
                    Quên mật khẩu? <?= Html::a('tìm lại', ['site/request-password-reset']) ?>.
                </div>
                
                <div class="form-group">
                    <?= Html::submitButton('Đăng nhập', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                </div>
                <input type="hidden" name="return_url" value="<?php echo Yii::$app->request->referrer?>">
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
