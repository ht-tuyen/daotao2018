<?php 
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Thông tin cá nhân';
$this->params['breadcrumbs'][] = $this->title;
?>
<div id="app" class="update-info">
<h3>Thông tin cá nhân</h3>
<br>

            <?php $form = ActiveForm::begin(['id' => 'form-signup', 'options' => ['enctype' => 'multipart/form-data']]); ?>
            <?php if (Yii::$app->session->getFlash('success')) {?> 
                <div class="alert alert-success"><?= Yii::$app->session->getFlash('success'); ?></div>
            <?php }?>
                <?php //$form->field($model, 'username')->textInput(['autofocus' => true]) ?>
                <p><b><i>(*) Thông tin bắt buộc</i></b></p>
                <div class="row">
                    <div class="col-xs-12 col-sm-6">
                        <div class="row">
                            <div class="col-xs-12 col-sm-4 text-right">
                                Họ tên(*)
                            </div>
                            <div class="col-xs-12 col-sm-8">
                                <input type="text" id="student-full_name" class="form-control" name="full_name" value="<?php echo $model->full_name?>" aria-required="true">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-sm-4 text-right">
                                Tên đăng nhập (*)
                            </div>
                            <div class="col-xs-12 col-sm-8">
                                <input type="text" id="student-username" class="form-control" readonly name="username" value="<?php echo $model->username?>" aria-required="true">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-sm-4 text-right">
                                Email (*)
                            </div>
                            <div class="col-xs-12 col-sm-8">
                                <input type="text" id="student-email" class="form-control" readonly name="email" value="<?php echo $model->email?>" aria-required="true">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-sm-4 text-right">
                                Mật khẩu
                            </div>
                            <div class="col-xs-12 col-sm-8">
                                <input type="password" id="student-password" class="form-control"  name="password" value="" aria-required="true">
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6 text-center">
                        
                        <?php if ($model->avatar) {?>
                            <img src="/uploads/avatars/<?php echo $model->avatar?>" alt="" style="width:200px;">
                        <?php }?>
                        <p>Tải ảnh đại diện</p>
                        <input type="hidden" id="student-avatar" class="form-control" name="avatar" value="<?php echo $model->avatar?>" aria-required="true">
                        <input type="file" class="form-control" name="upload_avatar">
                    </div>
                </div>
                <hr>
                <p><i><b>Thông tin người dùng</b></i></p>
                <div class="row">
                    <div class="col-xs-12 col-sm-6">
                        <div class="row">
                            <div class="col-xs-12 col-sm-4 text-right">
                               Giới tính
                            </div>
                            <div class="col-xs-12 col-sm-8">
                                <select id="student-gender" class="form-control" name="gender" aria-required="true">
                                    <option value="">Chọn giới tính</option>
                                    <option value="1" <?php if ($model->gender == 1) echo "selected"?>>Nam</option>
                                    <option value="2" <?php if ($model->gender == 2) echo "selected"?>>Nữ</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-sm-4 text-right">
                                Ngày sinh
                            </div>
                            <div class="col-xs-12 col-sm-8">
                                <input type="text" placeholder="31-12-1979" id="student-dob" class="form-control" name="dob" value="<?php echo date("d-m-Y",strtotime($model->dob))?>" aria-required="true">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-sm-4 text-right">
                                Số điện thoại
                            </div>
                            <div class="col-xs-12 col-sm-8">
                                <input type="text" id="student-mobile" class="form-control" name="mobile" value="<?php echo $model->mobile?>" aria-required="true">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-sm-4 text-right">
                                Địa chỉ
                            </div>
                            <div class="col-xs-12 col-sm-8">
                                <input type="text" id="student-address" class="form-control" name="address" value="<?php echo $model->address?>" aria-required="true">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-sm-4 text-right">
                                Đơn vị công tác
                            </div>  
                            <div class="col-xs-12 col-sm-8">
                                <input type="text" id="student-company" class="form-control" name="company" value="<?php echo $model->company?>" aria-required="true">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-sm-4 text-right">
                                Địa chỉ đơn vị công tác
                            </div>  
                            <div class="col-xs-12 col-sm-8">
                                <input type="text" id="student-company-address" class="form-control" name="company_address" value="<?php echo $model->company_address?>" aria-required="true">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-sm-4 text-right">
                                Học hàm
                            </div>
                            <div class="col-xs-12 col-sm-8">
                                <input type="text" id="student-academic" class="form-control" name="academic" value="<?php echo $model->academic?>" aria-required="true"> 
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-sm-4 text-right">
                                Học vị
                            </div>
                            <div class="col-xs-12 col-sm-8">
                                <input type="text" id="student-degree" class="form-control" name="degree" value="<?php echo $model->degree?>" aria-required="true">   
                            </div>
                        </div>
                       
                    </div>
                    <div class="col-xs-12 col-sm-6">
                        <p>Tiểu sử</p>
                        <textarea name="bio" id="student-bio" class="form-control" cols="30" rows="10">
                            <?php echo $model->bio?>
                        </textarea>
                    </div>
                </div>
                
                <?php // $form->field($model, 'email') ?>
               

                <div class="form-group text-center" style="margin-top:20px;">
                    <?= Html::submitButton('Lưu', ['class' => 'btn btn-success', 'name' => 'signup-button']) ?>
                </div>

            <?php ActiveForm::end(); ?>
</div>
      
