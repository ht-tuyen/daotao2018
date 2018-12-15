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
    <div class="users-pq-form">
        <h1>Phân quyền người dùng</h1>
        <br/>
        <?php //$form = ActiveForm::begin(['fieldClass' => 'common\components\xPActiveField',]); ?>
        <?php $form = ActiveForm::begin(['id' => 'users-pq-form']); ?>
       
            <div class="col-md-12 cot160">
                <?php
                    echo $form->field($model, 'role_id')->dropDownList(AcpHelper::getRoleOptions(), ['prompt' => 'Chọn quyền']);
               ?>
            </div>

            <div class="clearfix"></div>

            <div class="form-group col-md-12 text-right">
                    <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                    <button type="button" class="btn btn-default" data-dismiss="modal" aria-hidden="true">Đóng</button>
            </div>
           
        <?php ActiveForm::end(); ?>


        <script type="text/javascript">
            
        $('form#users-pq-form').on('beforeSubmit', function(e) {  
                loadimg();
                var form = $('form#users-pq-form');
                // var formData = form.serialize();
                var formData = new FormData(document.querySelector('form#users-pq-form'));

                $.ajax({
                    url: form.attr("action"),
                    type: form.attr("method"),
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (data) {
                        if(data.status == 1){
                            findclosemodal(form)
                            popthanhcong();
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