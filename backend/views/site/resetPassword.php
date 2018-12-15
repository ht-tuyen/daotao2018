<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Khôi phục mật khẩu';
$this->params['breadcrumbs'][] = $this->title;
$fieldOptions1 = [
    'options' => ['class' => 'form-group has-feedback'],
    'inputTemplate' => "{input}<span class='glyphicon glyphicon-envelope form-control-feedback'></span>"
];

?>

<div class="login-box site-request-password-reset">
    <style type="text/css">
        p.help-block.help-block-error {
            position: relative;
            padding: 5px;
            text-align: right;
        }
    </style>
    <div class="login-logo text-center">
        <?php echo Html::a(Html::img('@web/images/vsqi.png', []), Yii::$app->homeUrl) ?>
    </div>
    <!-- /.login-logo -->
    <div class="login-box-body">
        <?php if(empty($success)){ ?>
            <p class="text-center">NHẬP MẬT KHẨU MỚI</p>   
            <p style="font-style: italic; font-weight: normal; color: #080" class="text-center">Vui lòng nhập mật khẩu mới.</p>
            <br/>        
            <?php $form = ActiveForm::begin(['id' => 'reset-password-form']); ?>
                <?= $form->field($model, 'password')->passwordInput(['autofocus' => true])->label('Mật khẩu') ?>
                <?= $form->field($model, 'password_repeat')->passwordInput()->label('Nhập lại mật khẩu') ?>
                <div class="row"> 
                    <div class="col-xs-8"></div> 
                    <div class="col-xs-4">
                        <?= Html::submitButton('Gửi', ['class' => 'btn btn-primary btn-block btn-flat', 'name' => 'login-button']) ?>        
                    </div>
                </div>
            <?php ActiveForm::end(); ?>
        <?php }else{ ?>
            <p class="text-center">KHÔI PHỤC MẬT KHẨU THÀNH CÔNG</p> 
            <br/>
            <p style="font-weight: bold; color: #080" class="text-center">Bạn đã có thể đăng nhập bằng mật khẩu mới. <a href="/acp/">Đăng nhập</a></p>
            
        <?php } ?>
    </div>
    <!-- /.login-box-body -->
</div><!-- /.login-box -->





