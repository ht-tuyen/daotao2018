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
    <div class="users-form">
        <br/>
        <?php //$form = ActiveForm::begin(['fieldClass' => 'common\components\xPActiveField',]); ?>
        <?php $form = ActiveForm::begin(['id' => 'users-form']); ?>
        
            <div class="col-md-12 cot160">
                <?php
                    echo $form->field($model, 'fullname')->textInput(['maxlength' => true, 'placeholder' => 'Nhập họ tên...']);
                ?>
            </div>


            <div class="col-md-12 cot160">
                <?php
                    echo $form->field($model, 'username', ['enableAjaxValidation' => true,'options' => ['class' => '',]])->textInput(['maxlength' => true,'placeholder' => 'Nhập email...']);
                ?>
            </div>

            <?php if($model->isNewRecord){ ?>
            <div class="col-md-12 cot160">
                <?php                    
                 echo $form->field($model, 'password',['options' => ['class' => '']])->passwordInput(['maxlength' => true, 'placeholder' => 'Nhập mật khẩu...' ]);
                   ?>
            </div>
            <?php } ?>

            <div class="col-md-12 cot160">
                <?php
                    echo $form->field($model, 'role_id')->dropDownList(AcpHelper::getRoleOptions(), ['prompt' => 'Chọn quyền']);
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
                // echo $form->field($model, 're_password')->passwordInput(['maxlength' => true]);
                   ?>
            </div> -->

           
            <!-- <div class="col-md-12 cot160">
                <?php
                //echo $form->field($model, 'email')->textInput(['maxlength' => true])->widget(MaskedInput::className(), [
                //     'clientOptions' => [
                //         'alias' => 'email'
                //     ],
                // ]);
             ?>
            </div> -->

           
            <div class="col-md-12 cot160">
                <?php
                echo $form->field($model, 'mobile',['options' => ['class' => '']])->textInput(['maxlength' => true, 'placeholder' => 'Nhập số điện thoại...']);
               ?>
            </div>

           
            <div class="col-md-12 cot160">
                <?php
                echo $form->field($model, 'address')->textInput(['maxlength' => true, 'placeholder' => 'Nhập địa chỉ...']);
               ?>
            </div>

           
           <!--  <div class="col-md-12 cot160">
                <?php
                // echo $form->field($model, 'birthday')->textInput(['maxlength' => true])->widget(DatePicker::classname(), [
                //     'pluginOptions' => [
                //         'autoclose' => true,
                //         'format' => 'yyyy-mm-dd'
                //     ]
                // ]);
               ?>
            </div> -->


                <?php
                // echo $form->field($model, 'about')->textarea();
                // if (!$model->isNewRecord)
                    // echo $form->field($model, 'last_login_time')->textInput(['maxlength' => true]);
                ?>

                 <div class="clearfix"></div>


                <div class="form-group col-md-12 text-right">
                        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                        <button type="button" class="btn btn-default" data-dismiss="modal" aria-hidden="true">Đóng</button>
                </div>
           
        <?php ActiveForm::end(); ?>


        <script type="text/javascript">            
            $('form#users-form').on('beforeSubmit', function(e) { 
                    loadimg();
                    var form = $(this);
                    // var formData = form.serialize();
                    var formData = new FormData(document.querySelector('form#users-form'));

                    $.ajax({
                        url: form.attr("action"),
                        type: form.attr("method"),
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function (data) {
                            if(data.status == 1){
                                <?php if($model->isNewRecord){ //Url::base(true)?>
                                     rload('users-index')
                                     findclosemodal(form)
                                <?php }else{ ?>
                                    rload('users-index')
                                    findclosemodal(form)
                                <?php }?>  
                                popthanhcong();
                                <?php if($model->isNewRecord){?>
                                    alert('Đã gửi email thông báo tạo tài khoản.');
                                <?php } ?>
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


    </div>
    <style>
        /*.field-user-re_password {
            display: none;
        }*/
    </style>
<?php
$js = <<< XP
    // $('#user-chg_password').change(function(){
    //    if($(this).is(':checked')){
    //        $(".field-user-re_password").show();
    //        $('input[name*="[password]"]').val('');
    //        $('input[name*="[password]"]').attr('readonly',false);
    //    } else{
    //        $(".field-user-re_password").hide();           
    //        $('input[name*="[password]"]').attr('readonly',true);
    //    }
    // });    
XP;
$this->registerJs($js);
?>