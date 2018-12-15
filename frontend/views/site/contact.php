<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\ContactForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;

$this->title = 'Liên hệ';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-contact">

    <div class="row">
        <div class="col-xs-12 col-md-6">
            <h2>Gửi liên hệ</h2>
            <p></p><p></p>
        <?php if (Yii::$app->session->getFlash('success')) {?> 
            <div class="alert alert-success"><?= Yii::$app->session->getFlash('success'); ?></div>
            <?php }?>
            <?php $form = ActiveForm::begin(['id' => 'contact-form']); ?>

                <?= $form->field($model, 'name')->textInput(['autofocus' => true]) ?>

                <?= $form->field($model, 'email') ?>
                <?= $form->field($model, 'phone')->textInput() ?>

                <?= $form->field($model, 'address')->textInput() ?>


                <?= $form->field($model, 'subject') ?>

                <?= $form->field($model, 'body')->textarea(['rows' => 6]) ?>

                <?= $form->field($model, 'verifyCode')->widget(Captcha::className(), [
                    'template' => '<div class="row"><div class="col-lg-3">{image}</div><div class="col-lg-6">{input}</div></div>',
                ]) ?>

                <div class="form-group">
                    <?= Html::submitButton('Gửi', ['class' => 'btn btn-primary', 'name' => 'contact-button']) ?>
                </div>

            <?php ActiveForm::end(); ?>
        </div>
        <div class="col-xs-12 col-md-6">
            <div class="contact-detail">
                <ul>
                    <li><span>A:</span> <?php echo Yii::$app->settings->get('company_address','Số 8, Đường Hoàng Quốc Việt, Quận Cầu Giấy, Hà Nội')?></li>
                    <li><span>T:</span> <?php echo Yii::$app->settings->get('company_phone','(024) 38 361 467')?></li>
                    <li><span>F:</span> <?php echo Yii::$app->settings->get('company_fax','(024) 38 361 771')?></li>
                    <li><span>E:</span> <?php echo Yii::$app->settings->get('company_email','info@vsqi.gov.vn')?></li>
                </ul>
				<?php 
				$map_iframe = Yii::$app->settings->get('map');
				//$map_iframe = ' <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3723.6553226059723!2d105.80040135023584!3d21.046473085920013!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3135ab2329086cf3%3A0x2f5b2196253ee0d0!2zVOG7lW5nIGPhu6VjIFRpw6p1IGNodeG6qW4gxJBvIGzGsOG7nW5nIENo4bqldCBsxrDhu6NuZw!5e0!3m2!1svi!2s!4v1533480119573" width="600" height="250" frameborder="0" style="border:0" allowfullscreen></iframe>';
				preg_match('/src="([^"]+)"/', $map_iframe, $match);
				$url = $match[1];
				?>
                <iframe src="<?php echo $url?>" width="600" height="250" frameborder="0" style="border:0" allowfullscreen></iframe>
            </div>
        </div>
    </div>

</div>
