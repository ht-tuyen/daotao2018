<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;

$this->title = 'Khôi phục mật khẩu';
$this->params['breadcrumbs'][] = $this->title;
$fieldOptions1 = [
    'options' => ['class' => 'form-group has-feedback'],
    'inputTemplate' => "{input}<span class='glyphicon glyphicon-envelope form-control-feedback'></span>"
];
?>

<div class="login-box site-request-password-reset">
    <style type="text/css">
        /*p.help-block.help-block-error {
            position: relative;
            padding: 5px;
            text-align: right;
        }*/
    </style>
    <div class="login-logo text-center">
        <?php echo Html::a(Html::img('@web/images/vsqi.png', []), Yii::$app->homeUrl) ?>
    </div>
    <!-- /.login-logo -->
    <div class="login-box-body">
        <p class="text-center">KHÔI PHỤC MẬT KHẨU</p>   
        <?php if(empty($check_email)){ ?>
            <p style="font-style: italic; font-weight: normal; color: #080" class="text-center">Mã xác nhận sẽ được gửi về email.</p>
            <br/>
            <?php $form = ActiveForm::begin(['id' => 'request-password-reset-form']); ?>
            <?= $form
                ->field($model, 'email', $fieldOptions1)
                ->label(false)
                ->textInput(['placeholder' => 'Email','autofocus' => true]) ?>

            <?= $form->field($model, 'verifycode')->widget(Captcha::className(), [
                    'options' => [
                        'placeholder' => 'Nhập mã xác nhận',
                        'autocomplete' => 'off',
                        'maxlength' => 20,
                        'class' => 'form-control',
                    ],
                    'template' => '<div class="row"><div class="col-md-5">{image}<span id="change_captcha">Đổi mã khác</span></div><div class="col-md-7">{input}</div></div>'
                ])->label(false) ?>

            <div class="row"> 
                <div class="col-xs-8"></div> 
                <div class="col-xs-4">
                    <?= Html::submitButton('Gửi', ['class' => 'btn btn-primary btn-block btn-flat', 'name' => 'login-button']) ?>        
                </div>
            </div>

            <style type="text/css">
                #passwordresetrequestform-verifycode-image{
                    opacity: 0; 
                }
            </style>
            <script type="text/javascript">                
                window.onload = resetcaptcha
                function resetcaptcha(id = ''){    
                    document.getElementById('passwordresetrequestform-verifycode-image').click()                    
                    setTimeout(function(){
                        document.getElementById('passwordresetrequestform-verifycode-image').style.opacity = "1";
                    },1000)
                }
            </script>

            <?php ActiveForm::end(); ?>

        <?php }else{ ?>
            <br/>
            <p style=" font-weight: bold; color: #080" class="text-center">Mã xác nhận đã được gửi về email. Vui lòng kiểm tra email.</p>
        <?php } ?>
        

    </div>
    <!-- /.login-box-body -->
</div><!-- /.login-box -->

