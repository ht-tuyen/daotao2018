<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\helpers\AcpHelper;
use kartik\date\DatePicker;
use yii\widgets\MaskedInput;
use dosamigos\ckeditor\CKEditor;
use backend\models\Tintuc;
/* @var $this yii\web\View */
/* @var $model backend\models\User */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="users-form">
    <br/>
    <?php //$form = ActiveForm::begin(['fieldClass' => 'common\components\xPActiveField',]); ?>
    <?php $form = ActiveForm::begin(['id' => 'users-form', 'options' => [
        'enctype' => 'multipart/form-data'
    ]]); ?>

    <div class="col-md-9 cot100">
        <?php
        echo $form->field($model, 'tieude', ['options' => ['class' => ''] ] )->textInput(['maxlength' => true]);
        ?>
    </div>

    <div class="col-md-3 cot100">
        <?php
        echo $form->field($model, 'trangthai')->dropDownList(Tintuc::getTrangthaiOptions(), ['maxlength' => true]);
        ?>
    </div>





    <div class="col-md-9">
        <?= $form->field($model, 'gioithieu')->textarea(['rows' => 5, 'maxlength' => true]) ?>
    </div>


    <div class="col-md-3    ">
        <?= $form->field($model, 'uploadfileanhdaidien')->fileInput(['id' => 'file-input', 'accept' => '']) ?>
        <div class="clearfix"></div>
        <!-- <div class="col-md-3"> -->
            <?php
            if (!empty($model->anhdaidien)) {
                echo Html::img(Yii::$app->request->hostInfo . '/acp/' . $model->anhdaidien, ['style' => 'max-height: 100px; max-width: 250px; position: absolute;left: 15px; top: 50px;']);
            }
            ?>
        <!-- </div> -->
    </div>



    <div class="col-md-6 cot100">
        <?php
        echo $form->field($model, 'idchuyenmuc')->dropDownList(Tintuc::getListChuyenmucOptions());?>
    </div>


    <div class="clearfix"></div>
    <div class="col-md-12">
        <?php
        echo $form->field($model, 'noidung', ['options' => ['class' => 'required ', 'tag' => false]])->widget(CKEditor::className(), [
            'options' => ['rows' => 6, 'value' => $model->noidung],
            'preset' => 'full'
        ]);
        ?>
    </div>
    <div class="clearfix"></div>


    
    <div class="col-md-12 cot100">
        <?php
        echo $form->field($model, 'slug', ['enableAjaxValidation' => true, 'options' => ['class' => '']])->textInput(['maxlength' => true])->label('Link bài viết');
        ?>
    </div>


    <div class="clearfix"></div>

    <div class="form-group col-md-12" style="text-align: right">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <button type="button" class="btn btn-default" data-dismiss="modal" aria-hidden="true">Đóng</button>
    </div>


    <?php ActiveForm::end(); ?>


    <script type="text/javascript">

        var Utils = {
            alias: function (str, strtolower) {
                if (Utils.trim(str) == "")
                    return false;

                str = str.replace(new RegExp("-", "g"), " ");
                str = Utils.strTrimTotal(Utils.stripUnicode(str));
                str = str.replace(/[^a-zA-Z0-9 ]+/g, "");
                str = Utils.trim(str).replace(new RegExp(" ", "g"), "-");
                if (strtolower)
                    str = Utils.strtolower(str);
                return str;
            },

            stripUnicode: function (str) {
                if (Utils.trim(str) == "")
                    return false;

                var uni_string = {
                    "a": "à,á,ạ,ả,ã,â,ầ,ấ,ậ,ẩ,ẫ,ă,ằ,ắ,ặ,ẳ,ẵ",
                    "A": "À,Á,Ạ,Ả,Ã,Â,Ầ,Ấ,Ậ,Ẩ,Ẫ,Ă,Ằ,Ắ,Ặ,Ẳ,Ẵ",
                    "e": "è,é,ẹ,ẻ,ẽ,ê,ề,ế,ệ,ể,ễ",
                    "E": "È,É,Ẹ,Ẻ,Ẽ,Ê,Ề,Ế,Ệ,Ể,Ễ",
                    "i": "ì,í,ị,ỉ,ĩ",
                    "I": "Ì,Í,Ị,Ỉ,Ĩ",
                    "o": "ò,ó,ọ,ỏ,õ,ô,ồ,ố,ộ,ổ,ỗ,ơ,ờ,ớ,ợ,ở,ỡ",
                    "O": "Ò,Ó,Ọ,Ỏ,Õ,Ô,Ồ,Ố,Ộ,Ổ,Ỗ,Ơ,Ờ,Ớ,Ợ,Ở,Ỡ",
                    "u": "ù,ú,ụ,ủ,ũ,ư,ừ,ứ,ự,ử,ữ",
                    "U": "Ù,Ú,Ụ,Ủ,Ũ,Ư,Ừ,Ứ,Ự,Ử,Ữ",
                    "y": "ỳ,ý,ỵ,ỷ,ỹ",
                    "Y": "Ỳ,Ý,Ỵ,Ỷ,Ỹ",
                    "d": "đ",
                    "D": "Đ"
                }

                var _split, rep_to, rep_from;
                for (rep_to in uni_string) {
                    rep_from = uni_string[rep_to].split(',');
                    for (var uni in rep_from) {
                        str = str.replace(new RegExp(rep_from[uni], "g"), rep_to);
                    }
                }
                return Utils.trim(str);
            },

            strTrimTotal: function (str) {
                if (Utils.trim(str) == "")
                    return false;

                str = str.split(" ");
                var str_out = '';
                for (var k in str) {
                    var c = Utils.trim(str[k]);
                    if (c != '')
                        str_out += ' ' + c;
                }
                return Utils.trim(str_out);
            },

            trim: function (str) {
                if (typeof jQuery == "function")
                    str = $.trim(str);
                return str;
            },

            strtolower: function (str) {
                return (str + '').toLowerCase();
            }
        }

        $(function () {
            $('#tintuc-tieude').keyup(function () {
                $('#tintuc-slug').val(Utils.alias(this.value, true));
            });
        });

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


</div>
<style>
    /*.field-user-re_password {
        display: none;
    }*/
    .field-member-password, .field-member-re_password {
        display: none;
    }
</style>
