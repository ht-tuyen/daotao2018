<?php 
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Đổi mật khẩu';
$this->params['breadcrumbs'][] = $this->title;
?>
<h3 class="text-center">Đổi mật khẩu</h3>
<br>

            <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>

                <?php //$form->field($model, 'username')->textInput(['autofocus' => true]) ?>
                
               <table style="width: 600px; margin: 0 auto; max-width: 100%" class="table">
                    <?php if ($error) {?>
                            <tr>
                                <td colspan="2">
                                    <div class="alert alert-warning">
                                        <?php echo $error?>
                                    </div>
                                </td>
                            </tr>
                    
                    <?php }?>
                    <?php if ($success) {?>
                            <tr>
                                <td colspan="2">
                                    <div class="alert alert-success">
                                        <?php echo $success?>
                                    </div>
                                </td>
                            </tr>
                    
                    <?php }?>
                    
                   <tr>
                       <td>Mật khẩu hiện tại</td>
                       <td><input type="password" name="old_password" class="form-control"></td>
                   </tr>
                   <tr>
                       <td>Mật khẩu mới</td>
                       <td><input type="password" name="password" class="form-control"></td>
                   </tr>
                   <tr>
                       <td>Xác nhận mật khẩu mới</td>
                       <td><input type="password" name="confirm_password" class="form-control"></td>
                   </tr>
                   <tr>
                       <td colspan="2">
                       <div class="form-group">
                    <?= Html::submitButton('Đổi mật khẩu', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
                </div>
                       </td>
                   </tr>
               </table>

                

            <?php ActiveForm::end(); ?>
      
