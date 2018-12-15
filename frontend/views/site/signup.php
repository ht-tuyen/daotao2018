<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\SignupForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Đăng ký tài khoản';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-signup">
  

    <p>Vui lòng điền các thông tin dưới đây để đăng ký tài khoản:</p>

    
        
    <?php $form = ActiveForm::begin(['id' => 'form-signup', 'options' => ['enctype' => 'multipart/form-data']] ); ?>
    <div class="row">
        <div class="col-xs-12 col-sm-6">
           
            <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>

            <?= $form->field($model, 'email') ?>
            <?= $form->field($model, 'full_name') ?>
            <?= $form->field($model, 'address') ?>
            <?= $form->field($model, 'mobile') ?>
            <?= $form->field($model, 'academic') ?>
            <?= $form->field($model, 'degree') ?>
            <div class="form-group">
                <?= Html::submitButton('Đăng ký', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
            </div>
        </div>
        <div class="col-xs-12 col-sm-6">
       <div class="form-group field-signupform-dob required">
        <label class="control-label" for="signupform-dob">Ảnh đại diện</label>
		<input type="file" id="avatar" name="avatar">
		</div>
        <div class="form-group field-signupform-dob required">
        <label class="control-label" for="signupform-dob">Ngày sinh</label>
        <input type="text" placeholder="31-12-1979" id="signupform-dob" class="form-control" name="SignupForm[dob]" aria-required="true">

        <p class="help-block help-block-error"></p>
        </div>
            <?= $form->field($model, 'gender')->dropdownList([
                    1 => 'Nam', 
                    2 => 'Nữ'
                ],
                ['prompt'=>'Chọn giới tính']
            ) ?>
            <?= $form->field($model, 'position') ?>
            <?= $form->field($model, 'company') ?>
            <?= $form->field($model, 'company_address') ?>
            
            <?= $form->field($model, 'password')->passwordInput() ?>
                
        </div>
    </div>
    <?php ActiveForm::end(); ?>
       
</div>
