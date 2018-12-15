<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\helpers\AcpHelper;
use kartik\date\DatePicker;
use yii\widgets\MaskedInput;

/* @var $this yii\web\View */
/* @var $model backend\models\User */
/* @var $form yii\widgets\ActiveForm */
?>
    <div class="users-info-form">
        <h1>Cập nhật thông tin tài khoản</h1>
        <br/>
        <?php //$form = ActiveForm::begin(['fieldClass' => 'common\components\xPActiveField',]); ?>
        <?php $form = ActiveForm::begin(['id' => 'users-info-form']); ?>

            <div class="col-md-12">
                <div class="pull-left" style="width: 160px;"><b>Tên đăng nhập</b></div>
                <div class="pull-left"><?= $model->username;?></div>
            </div>


            <div class="col-md-12 cot160">
                <?php
                    echo $form->field($model, 'fullname')->textInput(['maxlength' => true]);
                ?>
            </div>

            <!--
            <div class="col-md-12 cot160">
                <?php
                // if(!$model->isNewRecord)
                // echo $form->field($model, 'chg_password')->checkbox();
               ?>
            </div>

           
            <div class="col-md-12 cot160">
                <?php
                // echo $form->field($model, 'password',['options' => ['class' => '']])->passwordInput(['maxlength' => true, 'readonly' => !$model->isNewRecord ? true : false]);
                   ?>
            </div>

           
            <div class="col-md-12 cot160">
                <?php
                // echo $form->field($model, 're_password')->passwordInput(['maxlength' => true]);
                   ?>
            </div> -->


            <div class="col-md-12 cot160">
                <?php
                echo $form->field($model, 'email',['options' => ['class' => '']])->textInput(['maxlength' => true])->widget(MaskedInput::className(), [
                    'clientOptions' => [
                        'alias' => 'email'
                    ],
                ]);
             ?>
            </div>


            <div class="col-md-12 cot160">
                <?php
                echo $form->field($model, 'mobile',['options' => ['class' => '']])->textInput(['maxlength' => true]);
               ?>
            </div>


            <div class="col-md-12 cot160">
                <?php
                echo $form->field($model, 'address')->textInput(['maxlength' => true]);
               ?>
            </div>


            <div class="col-md-12 cot160">
                <?php
                    echo $form->field($model, 'birthday')->textInput(['maxlength' => true])->widget(DatePicker::classname(), [
                        'options' => [
                            'readonly' => 'readonly',
                        ],
                        'type' => DatePicker::TYPE_COMPONENT_APPEND,
                        'pluginOptions' => [
                            'autoclose' => true,
                            'format' => 'dd-mm-yyyy',
                        ]
                    ]);
               ?>
            </div>


                <?php
                // echo $form->field($model, 'about')->textarea();
                // if (!$model->isNewRecord)
                    // echo $form->field($model, 'last_login_time')->textInput(['maxlength' => true]);
                ?>

                 <div class="clearfix"></div>
                 <br/>

                <div class="form-group col-md-12 text-right">
                        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                        <button type="button" class="btn btn-default" data-dismiss="modal" aria-hidden="true">Đóng</button>
                </div>

        <?php ActiveForm::end(); ?>


        <script type="text/javascript">
            $('form#users-info-form').on('beforeSubmit', function(e) {
                loadimg();
                var form = $(this);
                var formData = form.serialize();
                $.ajax({
                    url: form.attr("action"),
                    type: form.attr("method"),
                    data: formData,
                    success: function (data) {
                        if(data == 1){
                            popthanhcong()
                            setTimeout(function () {
                                window.location.reload()
                            }, 1000)
                        }else{
                            popthatbai();
                        }
                        e.preventDefault();
                    },
                    error: function () {
                        popthatbai();
                        e.preventDefault();
                    }
                });
            }).on('submit', function(e){
                e.preventDefault();
            });
        </script>


    <style>
        .field-user-re_password {
            display: none;
        }
    </style>
<?php
$js = <<< XP
    $('#user-chg_password').change(function(){
       if($(this).is(':checked')){
           $(".field-user-re_password").show();
           $('input[name*="[password]"]').val('');
           $('input[name*="[password]"]').attr('readonly',false);
       } else{
           $(".field-user-re_password").hide();           
           $('input[name*="[password]"]').attr('readonly',true);
       }
    });    
XP;
$this->registerJs($js);
?>

 </div>