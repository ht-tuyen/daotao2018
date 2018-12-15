<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\captcha\Captcha;
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

$this->title = 'Sign In';

$fieldOptions1 = [
    'options' => ['class' => 'form-group has-feedback'],
    'inputTemplate' => "{input}<span class='glyphicon glyphicon-envelope form-control-feedback'></span>"
];

$fieldOptions2 = [
    'options' => ['class' => 'form-group has-feedback'],
    'inputTemplate' => "{input}<span class='glyphicon glyphicon-lock form-control-feedback'></span>"
];
?>

<div class="login-box">


    <div class="login-logo text-center">
        <?php echo Html::a(Html::img('@web/images/vsqi.png', []), Yii::$app->homeUrl) ?>
    </div>
    <!-- /.login-logo -->
    <div class="login-box-body">
        <p class="text-center">ĐĂNG NHẬP HỆ THỐNG</p>
        <p style="font-style: italic; font-weight: normal; color: #080" class="text-center">Nếu chưa có tài khoản đăng nhập vui lòng liên hệ Người quản trị để được cấp tài khoản.</p>
        <br/>

        <?php if(!empty($lock)){
            echo '<p>Vui lòng đăng nhập lại sau 10 phút.</p>';
        }else{?>

            <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>
            <?= $form
                ->field($model, 'username', $fieldOptions1)
                ->label(false)
                ->textInput(['placeholder' => $model->getAttributeLabel('username')]) ?>

            <?= $form
                ->field($model, 'password', $fieldOptions2)
                ->label(false)
                ->passwordInput(['placeholder' => $model->getAttributeLabel('password')]) ?>

            <?= $form->field($model, 'verifycode')->widget(Captcha::className(), [
                        'options' => [
                            'placeholder' => 'Nhập mã xác nhận',
                            'autocomplete' => 'off',
                            'maxlength' => 20,
                            'class' => 'form-control',
                        ],
                        'template' => '<div class="row"><div class="col-md-5">{image}<span id="change_captcha">Đổi mã khác</span></div><div class="col-md-7">{input}</div></div>'
                    ])->label(false) ?>

            <style type="text/css">
                #loginform-verifycode-image{
                    opacity: 0; 
                }
            </style>
            <script type="text/javascript">
                window.onload = resetcaptcha
                function resetcaptcha(id = ''){    
                    document.getElementById('loginform-verifycode-image').click()                
                    setTimeout(function(){
                        document.getElementById('loginform-verifycode-image').style.opacity = "1";
                    },1000)
                }
            </script>

            <div class="row">
                <div class="col-xs-8">
                    <?= $form->field($model, 'rememberMe')->checkbox() ?>
                </div>
                <!-- /.col -->
                <div class="col-xs-4">
                    <?= Html::submitButton('Đăng nhập', ['class' => 'btn btn-primary btn-block btn-flat', 'name' => 'login-button']) ?>
                </div>
                <!-- /.col -->
                <div class="col-xs-12">
                    <a href="/acp/site/reset-password">Quên mật khẩu</a>
                </div>
            </div>

            <?php ActiveForm::end(); ?>
        <?php } ?>
    </div>
    <!-- /.login-box-body -->
</div><!-- /.login-box -->
