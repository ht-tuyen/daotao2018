<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use dosamigos\ckeditor\CKEditor;
use yii\widgets\MaskedInput;
use kartik\color\ColorInput;
use kartik\dialog\Dialog;
use backend\assets\CustomAsset;
use kartik\widgets\SwitchInput;
use backend\helpers\AcpHelper;

use backend\models\Tieuchuan;
use backend\models\Tailieu;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\SettingsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = \Yii::t('app', 'Settings');
$this->params['breadcrumbs'][] = $this->title;
$settings = Yii::$app->settings;
Yii::$app->settings->clearCache();

$bundle = CustomAsset::register(Yii::$app->view);
$this->registerCssFile($bundle->baseUrl . '/css/jquery.fancybox.css', ['depends' => [backend\assets\CustomAsset::className()]]);
$this->registerCssFile($bundle->baseUrl . '/js/jquery-ui-1.9.2/_css/custom-theme/jquery-ui-1.9.2.css', ['depends' => [backend\assets\CustomAsset::className()]]);
$this->registerJsFile($bundle->baseUrl . '/js/jquery-ui-1.9.2/_js/jquery-ui-1.9.2.js', ['depends' => [backend\assets\CustomAsset::className()]]);
$this->registerJsFile($bundle->baseUrl . '/js/jquery.fancybox.js', ['depends' => [backend\assets\CustomAsset::className()]]);
$this->registerJsFile($bundle->baseUrl . '/js/cleave.min.js', ['depends' => [backend\assets\CustomAsset::className()]]);
$this->registerJsFile($bundle->baseUrl . '/js/setting.js', ['depends' => [backend\assets\CustomAsset::className()]]);
?>
    <div class="settings-index">
        <?php $form = ActiveForm::begin(['id' => 'settings-form', 'options' => ['enctype' => 'multipart/form-data', 'class' => 'form-horizontal']]); ?>
        <div class="panel with-nav-tabs panel-info">
            <div class="panel-heading">
                <ul class="nav nav-tabs">
                    <?php
                    $role = \backend\models\Role::findOne(Yii::$app->user->identity->role_id);
                    $arr_allow = unserialize($role->role_setting);

                    ?>
                    <?php if ($role->acl_desc == 'ALL_PRIVILEGES' || in_array("info_company", $arr_allow)): ?>
                        <li class="active"><a href="#tab1default" data-toggle="tab">Thông tin công ty</a></li>
                    <?php endif; ?>
                   

                    <?php if ($role->acl_desc == 'ALL_PRIVILEGES' || in_array("table_cal", $arr_allow)): ?>
                        <li><a href="#tab4default" data-toggle="tab">Cài đặt mạng xã hội</a></li>
                    <?php endif; ?>

                    <?php if ($role->acl_desc == 'ALL_PRIVILEGES' || in_array("table_cal", $arr_allow)): ?>
                        <li><a href="#tab4quiz" data-toggle="tab">Cài đặt kết quả thi</a></li>
                    <?php endif; ?>

                    <?php if ($role->acl_desc == 'ALL_PRIVILEGES' || in_array("smtp_server", $arr_allow)): ?>
                        <li><a href="#tab6default" data-toggle="tab">Mail Server</a></li>
                    <?php endif; ?>

                    <?php if ($role->acl_desc == 'ALL_PRIVILEGES' || in_array("hethong", $arr_allow)): ?>
                        <li><a href="#tab_hethong" data-toggle="tab">Hệ thống</a></li>
                    <?php endif; ?>
                </ul>
            </div>
            <div class="panel-body">
                <div class="tab-content">
                    <div class="tab-pane fade in active" id="tab1default">
                        <ul class="list-group">
                            <li class="list-group-item">
                                <div class="form-group">
                                    <div class="col-md-3">
                                        <strong><?= Yii::t('app', 'Logo') ?></strong>
                                        <div><i><?= Yii::t('app', 'Maximum size') ?></i></div>
                                    </div>
                                    <div class="col-md-9">
                                        <?php
                                        echo kartik\widgets\FileInput::widget([
                                            'name' => 'Settings[logo]',
                                            'pluginOptions' => [
                                                'browseClass' => 'btn btn-success',
                                                'uploadClass' => 'btn btn-info',
                                                'removeClass' => 'btn btn-danger',
                                                'removeIcon' => '<i class="glyphicon glyphicon-trash"></i> ',
                                                'uploadUrl' => Url::to(['settings/file-upload']),
                                                'initialPreview' => $settings->get('logo') != '' ? [
                                                    Html::img('../../../uploads/' . $settings->get('logo'), ['class' => 'file-preview-image', 'alt' => 'Logo', 'title' => 'Logo', 'style' => 'max-height: 160px']),
                                                ] : [],
                                                'initialCaption' => $settings->get('logo') != '' ? $settings->get('logo') : '',
                                                'initialPreviewConfig' => $settings->get('logo') != '' ? [
                                                    ['caption' => $settings->get('logo'), 'key' => 'logo'],
                                                ] : [],
                                                'overwriteInitial' => false,
                                                'maxFileSize' => 2800,
                                                'deleteUrl' => Url::to(['settings/file-delete']),
                                                'layoutTemplates' => [
                                                    'actionZoom' => '',
                                                ],
                                            ],
                                        ]);
                                        ?>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                            </li>
                            <li class="list-group-item">
                                <div class="form-group">
                                    <div class="col-md-3">
                                        <label class="control-label">Tên công ty</label>
                                    </div>
                                    <div class="col-md-9">
                                        <?php echo Html::textInput("Settings[company_name]", $settings->get('company_name'), ['class' => 'form-control']); ?>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                            </li>
                            <li class="list-group-item">
                                <div class="form-group">
                                    <div class="col-md-3">
                                        <label class="control-label">Địa chỉ công ty</label>
                                    </div>
                                    <div class="col-md-9">
                                        <?php echo Html::textInput("Settings[company_address]", $settings->get('company_address'), ['class' => 'form-control']); ?>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                            </li>
                            <li class="list-group-item">
                                <div class="form-group">
                                    <div class="col-md-3">
                                        <label class="control-label">Email công ty</label>
                                    </div>
                                    <div class="col-md-9">
                                        <?php
                                        echo MaskedInput::widget([
                                            'name' => 'Settings[company_email]',
                                            'clientOptions' => [
                                                'alias' => 'email'
                                            ],
                                            'value' => $settings->get('company_email')
                                        ]);
                                        ?>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                            </li>
                            <li class="list-group-item">
                                <div class="form-group">
                                    <div class="col-md-3">
                                        <label class="control-label">Điện thoại công ty</label>
                                    </div>
                                    <div class="col-md-9">
                                        <?php echo Html::textInput("Settings[company_phone]", $settings->get('company_phone'), ['class' => 'form-control']); ?>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                            </li>
                            <li class="list-group-item">
                                <div class="form-group">
                                    <div class="col-md-3">
                                        <label class="control-label">Số Fax công ty</label>
                                    </div>
                                    <div class="col-md-9">
                                        <?php echo Html::textInput("Settings[company_fax]", $settings->get('company_fax'), ['class' => 'form-control']); ?>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                            </li>
                            <li class="list-group-item">
                                <div class="form-group">
                                    <div class="col-md-3">
                                        <label class="control-label"><?= Yii::t('app', 'The company information') ?></label>
                                    </div>
                                    <div class="col-md-9">
                                        <?php echo Html::textarea("Settings[contact_info]", $settings->get('contact_info'), ['class' => 'form-control', 'rows' => 5]); ?>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                            </li>
                            <li class="list-group-item">
                                <div class="form-group">
                                    <div class="col-md-3">
                                        <label class="control-label">Email quản trị</label>
                                    </div>
                                    <div class="col-md-9">
                                        <?php
                                        echo MaskedInput::widget([
                                            'name' => 'Settings[email_quan_tri]',
                                            'clientOptions' => [
                                                'alias' => 'email'
                                            ],
                                            'value' => $settings->get('email_quan_tri')
                                        ]);
                                        ?>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                            </li>

                            <li class="list-group-item">
                                <div class="form-group">
                                    <div class="col-md-3">
                                        <label class="control-label">Chữ ký gửi mail</label>
                                    </div>
                                    <div class="col-md-9">
                                        <?php
                                        echo $form->field($model, 'value', ['options' => ['tag' => false]])->widget(CKEditor::className(), [
                                            'options' => ['rows' => 6, 'value' => $settings->get('signature_send_mail'), 'name' => 'Settings[signature_send_mail]'],
                                            'id'=>'signature_send_mail',
                                            'preset' => 'full'
                                        ])->label(false);
                                        ?>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                            </li>
                            <li class="list-group-item">
                                <div class="form-group">
                                    <div class="col-md-3">
                                        <label class="control-label">Bản đồ</label>
                                    </div>
                                    <div class="col-md-9">
                                        <?php echo Html::textarea("Settings[map]", $settings->get('map'), ['class' => 'form-control', 'rows' => 5]); ?>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                            </li>
                            <li class=
                            
                        </ul>
                    </div>
                    <div class="tab-pane fade" id="tab2default">
                        
                    </div>




                    <div class="tab-pane fade" id="tab4default">
                    <ul class="list-group">
                            <li class="list-group-item">
                                <div class="form-group">
                                    <div class="col-md-5">
                                        <label class="control-label">Facebook</label>
                                    </div>
                                    <div class="col-md-7">
                                        <?php echo Html::textInput("Settings[facebook]", $settings->get('facebook'), ['class' => 'form-control']); ?>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                            </li>
                            <li class="list-group-item">
                                <div class="form-group">
                                    <div class="col-md-5">
                                        <label class="control-label">Youtube</label>
                                    </div>
                                    <div class="col-md-7">
                                        <?php echo Html::textInput("Settings[youtube]", $settings->get('youtube'), ['class' => 'form-control']); ?>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                            </li>
                            <li class="list-group-item">
                                <div class="form-group">
                                    <div class="col-md-5">
                                        <label class="control-label">Twitter</label>
                                    </div>
                                    <div class="col-md-7">
                                        <?php echo Html::textInput("Settings[twitter]", $settings->get('twitter'), ['class' => 'form-control']); ?>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                            </li>       
                         
                        </ul>
                    </div>
                    <div class="tab-pane fade" id="tab4quiz">
                    <ul class="list-group">
                            <li class="list-group-item">
                                <div class="form-group">
                                    <div class="col-md-5">
                                        <label class="control-label">Tốt</label>
                                    </div>
                                    <div class="col-md-7">
                                        <?php echo Html::textInput("Settings[result_perfect]", $settings->get('result_perfect'), ['class' => 'form-control']); ?>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                            </li>
                            <li class="list-group-item">
                                <div class="form-group">
                                    <div class="col-md-5">
                                        <label class="control-label">Khá</label>
                                    </div>
                                    <div class="col-md-7">
                                        <?php echo Html::textInput("Settings[result_good]", $settings->get('result_good'), ['class' => 'form-control']); ?>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                            </li>
                            <li class="list-group-item">
                                <div class="form-group">
                                    <div class="col-md-5">
                                        <label class="control-label">Trung bình</label>
                                    </div>
                                    <div class="col-md-7">
                                        <?php echo Html::textInput("Settings[result_normal]", $settings->get('result_normal'), ['class' => 'form-control']); ?>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                            </li>
                           
                           
                            
                           
                            
                            
                            
                           
                           
                         
                        </ul>
                    </div>                           

                    <div class="tab-pane fade" id="tab6default">
                        <li class="list-group-item">
                            <div class="form-group">
                                <div class="col-md-3">
                                    <label class="control-label">Sử dụng STMP Server</label>
                                </div>
                                <div class="col-md-9">
                                    <?php
                                    $use = empty($settings->get('stmp_use')) ? 0 : $settings->get('stmp_use');

                                    echo Html::radioList("Settings[stmp_use]", $use, [0 => 'Không sử dụng', 1 => 'Có sử dụng'], [
                                        'item' => function ($index, $label, $name, $checked, $value) {
                                            $return = '<label style="margin-right: 20px">';
                                            $return .= '<input type="radio" name="' . $name . '" ' . ($checked == 1 ? 'checked' : '') . ' value="' . $value . '"> ';
                                            $return .= ucwords($label);
                                            $return .= '</label>';

                                            return $return;
                                        }
                                    ]);
                                    ?>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                        </li>

                        <li class="list-group-item">
                            <div class="form-group">
                                <div class="col-md-3">
                                    <label class="control-label">STMP Server</label>
                                </div>
                                <div class="col-md-9">
                                    <?php echo Html::textInput("Settings[stmp_server]", $settings->get('stmp_server'), ['class' => 'form-control']); ?>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                        </li>
                        <li class="list-group-item">
                            <div class="form-group">
                                <div class="col-md-3">
                                    <label class="control-label">Port</label>
                                </div>
                                <div class="col-md-9">
                                    <?php echo Html::textInput("Settings[stmp_port]", $settings->get('stmp_port'), ['class' => 'form-control']); ?>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                        </li>
                        <li class="list-group-item">
                            <div class="form-group">
                                <div class="col-md-3">
                                    <label class="control-label">SSL</label>
                                </div>
                                <div class="col-md-9">
                                    <?php
                                    $ssl = empty($settings->get('stmp_ssl')) ? 0 : $settings->get('stmp_ssl');

                                    echo Html::radioList("Settings[stmp_ssl]", $ssl, [0 => 'Không', 1 => 'SSL', 2 => 'TLS'], [
                                        'item' => function ($index, $label, $name, $checked, $value) {
                                            $return = '<label style="margin-right: 20px">';
                                            $return .= '<input type="radio" name="' . $name . '" ' . ($checked == 1 ? 'checked' : '') . ' value="' . $value . '"> ';
                                            $return .= ucwords($label);
                                            $return .= '</label>';

                                            return $return;
                                        }
                                    ]);
                                    ?>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                        </li>
                        <li class="list-group-item">
                            <div class="form-group">
                                <div class="col-md-3">
                                    <label class="control-label">Người gửi</label>
                                </div>
                                <div class="col-md-9">
                                    <?php echo Html::textInput("Settings[stmp_fromname]", $settings->get('stmp_fromname'), ['class' => 'form-control']); ?>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                        </li>
                        <li class="list-group-item">
                            <div class="form-group">
                                <div class="col-md-3">
                                    <label class="control-label">Username</label>
                                </div>
                                <div class="col-md-9">
                                    <?php echo Html::textInput("Settings[stmp_username]", $settings->get('stmp_username'), ['class' => 'form-control']); ?>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                        </li>
                        <li class="list-group-item">
                            <div class="form-group">
                                <div class="col-md-3">
                                    <label class="control-label">Password</label>
                                </div>
                                <div class="col-md-9">
                                    <?php echo Html::passwordInput("Settings[stmp_password]", $settings->get('stmp_password'), ['class' => 'form-control']); ?>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                        </li>
                    </div> <!--tab5default-->


                    <div class="tab-pane fade" id="tab_hethong">
                        <li class="list-group-item">
                            <div class="form-group">
                                <div class="col-md-5">
                                    <label class="control-label">Tên miền download tài liệu trang Tra cứu</label>
                                </div>
                                <div class="col-md-7">
                                    <?php echo Html::textInput("Settings[domain_download_tracuu]", (!empty($settings->get('domain_download_tracuu')) ? $settings->get('domain_download_tracuu') : ''), ['type' => 'text','class' => 'form-control']); ?>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                        </li>
                    </div>


                </div>
            </div>
        </div>
        <div class="col-md-12 form-group text-right">
            <?= Html::submitButton(Yii::t('app', 'Update'), ['class' => 'btn btn-primary']) ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>




<?php

echo Dialog::widget();
// $url_delete_tbh = Url::toRoute('settings/deletetbh');
// $url_load_classify = Url::toRoute('settings/loadallclassify');
// $url_load_group_classify = Url::toRoute('settings/loadallgroupclassify');
// $url_delete_classify = Url::toRoute('settings/deleteclassify');
// $url_delete_group_classify = Url::toRoute('settings/deletegroupclassify');
// $url_update_classify = Url::toRoute('settings/updateclassify');
// $url_update_group_classify = Url::toRoute('settings/updategroupclassify');

$script = <<< XP

        $( function() {
            $( ".sortable" ).sortable();
            $( ".sortable" ).disableSelection();
        } );

        
        $(document).on('click', 'span.button-add-line', function(){
            var \$las_row = $(this).closest('table').find('tr.tr-line-input:last'),
                name = \$las_row.find('input:first').attr('name'),
                length =  $(this).closest('table').find('tr.tr-line-input').length
                \$html_clone = \$las_row.clone();
            \$las_row.after(\$html_clone);
            \$html_clone.find('input').val('');
            \$html_clone.find('input').each(function (i) {
                $(this).attr('name', $(this).attr('name').replace(/[0-9]+/g, length));
            });
        });
    
        $(document).on('click', 'span.button-remove-line', function () {
            var \$this_row = $(this).closest('tr.tr-line-input'),
                length = $(this).closest('table').find('tr.tr-line-input').length,
                id = \$this_row.find('input[name*="id"]').val();
            if (length == 1) {
                \$this_row.find('input').val('');
            } else {
                \$this_row.remove();
            }
        });
    
        jQuery(document).on('click', 'input[name*="Settings[tieu_chuan_quyen_max]"]', function() {
            var parent = $(this).closest('.adminlist');
            parent.find('input[type="radio"]').prop('checked', false);
            $(this).prop('checked', true);
        });
        
        jQuery(document).on('click', 'span.add-line', function () {
            var table = $(this).closest('table'), \$las_row = table.find('tr.line-input:last'),
                length = table.find('tr.line-input').length,
                name = \$las_row.find('input:first').attr('name');
            \$las_row.after(\$las_row.clone());
            table.find('tr.line-input:last').find('input').val('');
            table.find('tr.line-input:last').find('input').each(function () {
                $(this).attr('name', $(this).attr('name').replace('[' + (length - 1) + ']', '[' + length + ']'));
            });

        });
        jQuery(document).on('click', 'span.remove-line', function () {
            var \$this_row = jQuery(this).closest('tr.line-input'),
                id = \$this_row.find('input[name*="id"]').val();
            krajeeDialog.confirm("Bạn có chắc muốn xóa mục này?", function (result) {
                if(result) {
                    if (id > 0) {
                        jQuery.ajax({
                            'data': {'id': id},
                            'url': '{$url_delete_tbh}',
                            'type': 'get',
                            'success': function () {
                            }
                        });
                    }
                    if (jQuery('tr.line-input').length == 1) {
                        \$this_row.find('input').val('');
                    } else {
                        \$this_row.remove();
                    }
                }
            });
        });
                            
        function load_all_classify() {
            jQuery.ajax({                
                'url': '{$url_load_classify}',
                'type': 'get',
                'success': function (data) {
                    $('.table_contain_classify').html(data);
                }
            });            
        }
        load_all_classify();
        
        function load_all_group_classify() {
            $.ajax({
               'url': '{$url_load_group_classify}',
                'type': 'get',
                'success': function (data) {
                    $('.table_contain_group_classify').html(data);
                }
            });
        }
        load_all_group_classify();
        
        $(document).on('click', ".delete_classify", function () {
            var id = $(this).data('id');
            if (confirm("Bạn có chắc muốn xoá phân loại này?")) {
                $.ajax({
                    'url': '{$url_delete_classify}',                    
                    type: 'post',
                    data: {id: id},
                    success: function (data) {
                        if (data == 'success')
                            load_all_classify();
                    }
                });
            }
        });
        
         $(document).on('click', ".delete_group_classify", function () {
            var id = $(this).data('id');
            if (confirm("Bạn có chắc muốn xoá nhóm phân loại này?")) {
                $.ajax({
                    'url': '{$url_delete_group_classify}',     
                    type: 'post',
                    data: {id: id},
                    success: function (data) {
                        if (data == 'success')
                            load_all_group_classify();
                    }
                });
            }
        });
        
        $(document).on('click', ".save_classify_ajax", function () {    
                var id = $(this).data('id');
                var form = $('#classify-form-')[0];
                var formData = new FormData(form);          
                $.ajax({
                    'url': '{$url_update_classify}'+ '?id=' + id,     
                    type: 'post',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (html) {
                       if (html != undefined && html == 'success') {
                           parent.jQuery.fancybox.getInstance().close();
                            load_all_classify();                            
                        } else {
                            $('.get_error').html(html);
                            $('.get_error').show();
                        }
                    }
                });
            
        });    
        
        $(document).on('click', ".save_group_classify_ajax", function () {    
                var id = $(this).data('id');
                var form = $('#group-classify-form-')[0];
                var formData = new FormData(form);          
                $.ajax({
                    'url': '{$url_update_group_classify}'+ '?id=' + id,     
                    type: 'post',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (html) {                    
                       if (html != undefined && html == 'success') {
                           parent.jQuery.fancybox.getInstance().close();
                            load_all_group_classify();
                            
                        } else {
                            $('.get_error').html(html);
                            $('.get_error').show();
                        }
                    }
                });
            
        });
        
        $('.nav.nav-tabs a').click(function(e) {
          e.preventDefault();
          $(this).tab('show');
        });
        
        // store the currently selected tab in the hash value
        $("ul.nav-tabs > li > a").on("shown.bs.tab", function(e) {
          // var id = $(e.target).attr("href").substr(1);
          // window.location.hash = id;
        });
        
        // on load of the page: switch to the currently selected tab
        var hash = window.location.hash;
        $('.nav.nav-tabs a[href="' + hash + '"]').tab('show');
            
XP;
$this->registerJs($script);

$css = <<<XP
.fancybox-content{
max-width: 900px !important;
height: 500px ;
}


.classify-form {
  width: 500px;
  height: 600px;
  padding: 20px 20px 10px 20px;
  margin: 0;
  border-radius: .3em;
  box-shadow: 0 0.1em 0.4em rgba(0,0,0,.3);
  overflow: hidden;
}
.group-classify-form {
  width: 900px;
  height: 600px;
  padding: 20px 20px 10px 20px;
  margin: 0;
  border-radius: .3em;
  box-shadow: 0 0.1em 0.4em rgba(0,0,0,.3);
  overflow: hidden;
}
.add_classify, .add_group_classify {
    font-size: 16px;
    cursor: pointer;
    text-decoration: none;
}
#sortable-div{
        vertical-align: top;
    }
    
    .sortable-list {
        background-color: #f3f3f3;
        color: #fff;
        list-style: none;
        margin-bottom:10px;
        min-height: 30px;
        padding: 5px;
        min-height: 200px;
    }
    #dragdrop{
        width: 380px;
        display: inline-block;
        vertical-align: top;
    }
    .space{display: inline-block; width: 60px; font-size: 24px; font-weight: bold; text-align: center; color: #0b559b; padding-top: 30px}
    .sortable-list:last-child{ margin-bottom: 0;}

    .dragbleList{ max-height: 400px; overflow: auto;}
    .sortable-item {
        background-color:#adadac;
        color: #fff;
        cursor: move;
        display: block;
        margin-bottom: 2px;
        padding: 6px 6px 6px 28px;        
        font-size: 12px;
        font-weight: bold;

    }
    .sortable-item th{
        font-weight: normal;
        text-align: right !important;
        border-top: 1px solid #fff;
    }
    .sortable-item td{background: none !important; text-align: right !important;}
    
XP;
$this->registerCss($css);
?>