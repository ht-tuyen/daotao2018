<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\helpers\AcpHelper;
use kartik\date\DatePicker;
use yii\widgets\MaskedInput;
?>
<div class="changepass-form">
    <h1>Đổi mật khẩu</h1>
    <?php $form = ActiveForm::begin(['id' => 'changepass-form']); ?>            
        <div class="panel-body">
            <?php
            echo $form->field($model, 'old_password', ['enableAjaxValidation' => true])->passwordInput(['maxlength' => true]);
            echo $form->field($model, 'password')->passwordInput(['maxlength' => true]);
            echo $form->field($model, 're_password')->passwordInput(['maxlength' => true]);
            ?>

        </div> 
            <div class="form-group col-md-12 text-right">
                <?= Html::submitButton(Yii::t('app', 'Update'), ['class' => 'btn btn-primary']) ?>
                 <button type="button" class="btn btn-default" data-dismiss="modal" aria-hidden="true">Đóng</button>
            </div>
           
    <?php ActiveForm::end(); ?>

    <script type="text/javascript">        
        $('form#changepass-form').on('beforeSubmit', function(e) {        
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
                        findclosemodal(form)
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
