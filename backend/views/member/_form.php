<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\helpers\AcpHelper;
use kartik\date\DatePicker;
use yii\widgets\MaskedInput;

/* @var $this yii\web\View */
/* @var $model backend\models\User */
/* @var $form yii\widgets\ActiveForm */
if(empty($view)) $view = '';
?>
    <div class="users-form">
        <br/>
        <?php //$form = ActiveForm::begin(['fieldClass' => 'common\components\xPActiveField',]); ?>
        <?php $form = ActiveForm::begin(['id' => 'users-form']); ?>

        <div class="col-md-12 cot160">
            <?php
            echo $form->field($model, 'fullname',['options' => ['class' => '']])->textInput(['maxlength' => true]);
            ?>
        </div>


        <div class="col-md-12 cot160">
            <?php
            echo $form->field($model, 'username', ['enableAjaxValidation' => true, 'options' => ['class' => '']])->textInput(['maxlength' => true]);
            ?>
        </div>


        <div class="col-md-12 cot160">
            <?php
            echo $form->field($model, 'email', ['enableAjaxValidation' => true, 'options' => ['class' => '']])->textInput(['maxlength' => true])->widget(MaskedInput::className(), [
                'clientOptions' => [
                    'alias' => 'email'
                ],
            ]);
            ?>
        </div>


        <div class="col-md-12 cot160">
            <?php
            echo $form->field($model, 'mobile', ['options' => ['class' => '']])->textInput(['maxlength' => true]);
            ?>
        </div>


        <div class="col-md-12 cot160">
            <?php
            echo $form->field($model, 'address',['options' => ['class' => '']])->textInput(['maxlength' => true]);
            ?>
        </div>

        <div class="col-md-12 cot160">
            <?php
            echo $form->field($model, 'linhvucquantam',['options' => ['class' => '']])->textInput(['rows'=> 2, 'maxlength' => true]);
            ?>
        </div>
        <!-- <div class="col-md-12 cot160">
            <?php
            // echo $form->field($model, 'birthday')->textInput(['maxlength' => true])->widget(DatePicker::classname(), [
            //     'pluginOptions' => [
            //         'autoclose' => true,
            //         'format' => 'yyyy-mm-dd'
            //     ]
            // ]);
            ?>
        </div> -->

        <div class="clearfix"></div>
        <div class="col-md-3">
            &nbsp;
        </div>
        <div class="col-md-9">
            <?php
            echo $form->field($model, 'chg_password')->checkbox(['style'=>'margin-left: 15px']);
            // if (!$model->isNewRecord)
            // echo $form->field($model, 'last_login_time')->textInput(['maxlength' => true]);
            ?>

        </div>
        <div class="clearfix"></div>

        <div class="col-md-12 cot160">
            <?php
            echo $form->field($model, 'password', ['options' => ['class' => '']])->passwordInput(['maxlength' => true]);
            ?>
        </div>


        <div class="col-md-12 cot160">
            <?php
             echo $form->field($model, 're_password',['options' => ['class' => '']])->passwordInput(['maxlength' => true]);
            ?>
        </div>

        <?php
            if(empty($view)){
        ?>
            <div class="form-group col-md-12 text-right">
                <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                <button type="button" class="btn btn-default" data-dismiss="modal" aria-hidden="true">Đóng</button>
            </div>
        <?php
            }
        ?>

        <?php ActiveForm::end(); ?>

        <?php
            if(empty($view)){
        ?>
        <script type="text/javascript">
            $('form#users-form').on('beforeSubmit', function (e) {
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
                        if (data.status == 1) {
                            <?php if($model->isNewRecord){ //Url::base(true)?>
                            rload('users-index')
                            findclosemodal(form)
                            <?php }else{ ?>
                            rload('users-index')
                            findclosemodal(form)
                            <?php }?>
                            popthanhcong();
                        } else {
                            popthatbai();
                        }
                        e.preventDefault();
                    },
                    error: function () {
                        popthatbai();
                        e.preventDefault();
                    }
                });
            }).on('submit', function (e) {
                e.preventDefault();
            });
        </script>
        <?php
            }
        ?>

    </div>
    <style>
        /*.field-user-re_password {
            display: none;
        }*/
        .field-member-password, .field-member-re_password{
            display: none;
        }
    </style>
<?php
$js = <<< XP
     $('#member-chg_password').change(function(){
     console.log(3132332);
        if($(this).is(':checked')){
            $(".field-member-re_password").show();
            $(".field-member-password").show();
            $('input[name*="[password]"]').val('');
            $('input[name*="[password]"]').attr('readonly',false);
        } else{
            $(".field-member-re_password").hide();           
            $('input[name*="[password]"]').attr('readonly',true);
        }
     });    
XP;
$this->registerJs($js);
?>