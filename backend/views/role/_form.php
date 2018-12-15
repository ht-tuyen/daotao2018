<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\helpers\AcpHelper;
use backend\assets\CustomAsset;

/* @var $this yii\web\View */
/* @var $model backend\models\Role */
/* @var $form yii\widgets\ActiveForm */
$bundle = CustomAsset::register(Yii::$app->view);
$this->registerCssFile($bundle->baseUrl . '/css/style_roles_edit.css', ['depends' => [backend\assets\CustomAsset::className()]]);
$this->registerJsFile($bundle->baseUrl . '/js/jquery-ui-1.9.2/_js/jquery-ui-1.9.2.js', ['depends' => [backend\assets\CustomAsset::className()]]);
?>

<div class="node-form">

    <?php $form = ActiveForm::begin(['id' => 'role-form']); ?>
    <div class="col-md-12">
        <div class="panel panel-info">

            <!--            <div class="panel-heading">--><?php //echo \Yii::t('app', 'Information') ?><!--</div>-->
            <div class="panel-body">
                <div class="first-input col-md-12" style="text-align: center">
                    <?php
                    if (!$model->isNewRecord && $model->list_status != "")
                        $model->list_status = unserialize($model->list_status);
                    echo $form->field($model, 'role_label')->textInput(['maxlength' => true]);
                    echo $form->field($model, 'role_name')->textInput(['maxlength' => true]);

                    if (!$model->p_id) $model->p_id = $_REQUEST['parent_roleid'];
                    echo $form->field($model, 'p_id')->dropDownList(AcpHelper::getRoleOptions($model->role_id), ['prompt' => 'Không chọn']);


                    
                    if (!$model->isNewRecord && $model->acl_desc == 'ALL_PRIVILEGES')
                        $model->acl_type = 'full';
                    elseif (!$model->isNewRecord && $model->acl_desc == '')
                        $model->acl_type = 'null';
                    elseif (!$model->isNewRecord && $model->acl_desc != '')
                        $model->acl_type = 'custom';
                    echo $form->field($model, 'acl_type')->dropDownList(AcpHelper::getRoleAcl(), []);
                    echo $form->field($model, 'acl_desc')->hiddenInput([])->label(false);

                    echo $form->field($model, 'status')->checkbox(['label' => '<label class="control-label" for="role-status">Trạng thái</label>'], false);
                    echo $form->field($model, 'admin_use')->checkbox(['label' => '<label class="control-label" for="role-status">Quyền Admin</label>'], false);

                    if($model->role_setting != '')
                        $model->role_setting = unserialize($model->role_setting);
                    echo $form->field($model, 'role_setting')->checkboxList(\backend\models\Role::getRoleSetting());
                    //echo $form->field($model, 'allow_change_user')->checkbox()->label(false);
                    //echo $form->field($model, 'allow_show_price')->checkbox()->label(false);

                    echo $form->field($model, 'field_st')->hiddenInput([])->label(false);

                    ?>
                </div>
                <div class="second-input col-md-12">
                </div>
            </div>

        </div>
    </div>

    <div class="col-md-12">
        <div class="panel panel-info">
            <!--            <div class="panel-heading">--><?php //echo \Yii::t('app', 'Quyền hạn') ?><!--</div>-->
            <div class="panel-body acl_custom_role" id="custom_form">
                <ul class="ul">
                    <?php echo AcpHelper::getRoleTreeHtml($model->acl_desc, $model->field_st); ?>
                </ul>
            </div>
        </div>
    </div>

    <div class="clearfix"></div>

    <div class="form-group text-right">
        <div class="col-md-12" style="margin-top: 2px; text-align: center">
            <?php if(!$model->isNewRecord){ ?>
                <span id="btn_save" class="btn btn-primary">Lưu</span>
            <?php } ?>

            <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : 'Lưu và đóng' , ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-default']) ?>            
        </div>
        <div class="clearfix"></div>
    </div>
    <?php ActiveForm::end(); ?>


</div>

<style>
    .profilesEditView.table-bordered tr td,
    .profilesEditView.table-bordered tr th{
        border: 1px solid #adadad !important;
    }
    .acl_custom_role div.clearfix {
        border: 1px solid #ccc;
    }
    div#role-role_setting input[type='checkbox'] {
        width: 15px !important;
        margin: 0;
        margin-top: 2px;
        margin-right: 7px;
    }
    div#role-role_setting label{
        text-align: left;
        width: auto;
        clear: none;
        margin-left: 0;
    }
</style>

<?php
$script = <<< XP
    
    //Lưu không reload
    $('span#btn_save').on('click', function(e) {        
        loadimg();
        var form = $('form#role-form');
        var url = form.attr("action");
        var formData = form.serialize();
        $.ajax({
            url: url+'&re=no',
            type: 'POST',
            data: formData,
            success: function (data) {
                if(data == 1){
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



    //Xu ly checked/uncheck nhung cai duoc chon
    var list = ['Bankythuat','Duan','Ics','Kehoachnam','Thanhvien','Tieuchuan','Tieuchuanquocte','User','Nguoinhan','Sendmail','Member','Chuyenmuc','Tintuc']
    list.map(function(val,index){
        divParent = $('#custom_form input[id="'+val+'"]').parents("tr")
        current = $('input#'+val)        
        if (current.is(':checked')) {
        } else{            
            divParent.addClass('disabled')     
            divParent.find('input').attr({'checked': false, 'disabled': true});
            divParent.find('button').attr({'disabled': true});
        }      
        current.attr({'disabled': false});   
    })
        duan_disable_child_load($('input#Duan'))

    function duan_disable_child_load(current = ''){
        var divChild = ['Nguoinhan','Sendmail']
        divChild.map(function(val,index){
            divParent = $('#custom_form input[id="'+val+'"]').parents("tr")
            if ($(current).is(':checked')) {
                divParent.removeClass('disabled')
                divParent.find('input').attr({'disabled': false});
                divParent.find('button').attr({'disabled': false});
            } else {       
                divParent.addClass('disabled')     
                divParent.find('input').attr({'disabled': true});
                divParent.find('button').attr({'disabled': true});
            } 
        })
    } 
    //


    //Neu input checked/unchecked thì on off cac child cua no
    $('input.modulesCheckBox').change(function () {
        current = this        
        divParent = $(current).parents("tr")
        if ($(current).is(':checked')) {
            divParent.removeClass('disabled')
            divParent.find('span input').attr({'disabled': false});
            divParent.find('span input').prop({'checked': true});
            divParent.find('button').attr({'disabled': false});            
        } else {       
            divParent.addClass('disabled')     
            divParent.find('span input').attr({'checked': false, 'disabled': true});
            divParent.find('button').attr({'disabled': true});
            divParent.next().addClass('hide')
        }      
    });


    //Neu chon Duan, thi on/off cai bi rang buoc voi Duan
    $('input#Duan').change(function () {        
        duan_disable_child(this)
    });

    function duan_disable_child(current = ''){
        var divChild = ['Nguoinhan','Sendmail']
        divChild.map(function(val,index){
            divParent = $('#custom_form input[id="'+val+'"]').parents("tr")
            if ($(current).is(':checked')) {
                divParent.removeClass('disabled')
                divParent.find('input').attr({'checked': false, 'disabled': false});
                divParent.find('button').attr({'disabled': false});
            } else {       
                divParent.addClass('disabled')     
                divParent.find('input').attr({'checked': false, 'disabled': true});
                divParent.find('button').attr({'disabled': true});
            } 
        })
    }



    function setFullAccessCheckbox() {
        $("#custom_form input[type='checkbox']").each(function () {
            $(this).attr({'checked': true, 'disabled': true});
        });
        //$('#custom_form').hide();
        $('#AdmincpRole_acl_desc').val('ALL_PRIVILEGES');
    }


    $('#role-acl_type').change(function () {
        if ($('#role-acl_type').val() == 'full') {
            setFullAccessCheckbox();
        } else {
            $('#AdmincpRole_acl_desc').val($('#role-acl_type').val());
        }

        if ($('#role-acl_type').val() == 'custom') {
            $('#custom_form').show();
            $('#custom_form input[type="checkbox"]').attr({'checked': false, 'disabled': false});
        }
    });

    //implement JSON.stringify serialization
    JSON.stringify = JSON.stringify || function (obj) {
            var t = typeof (obj);
            if (t != "object" || obj === null) {
                // simple data type
                if (t == "string")
                    obj = '"' + obj + '"';
                return String(obj);
            } else {
                // recurse array or object
                var n, v, json = [], arr = (obj && obj.constructor == Array);
                for (n in obj) {
                    v = obj[n];
                    t = typeof (v);
                    if (t == "string")
                        v = '"' + v + '"';
                    else if (t == "object" && v !== null)
                        v = JSON.stringify(v);
                    json.push((arr ? "" : '"' + n + '":') + String(v));
                }
                return (arr ? "[" : "{") + String(json) + (arr ? "]" : "}");
            }
        };

    $.fn.serializeObject = function () {
        var o = {};
        var a = this.serializeArray();        
        $.each(a, function () {
            if (o[this.name]) {
                if (!o[this.name].push) {
                    o[this.name] = [o[this.name]];
                }
                o[this.name].push(this.value || '');
            } else {
                o[this.name] = this.value || '';
            }
        });
        return JSON.stringify(o);
    };
    

    $("ul.sub input[type='checkbox']").change(function () {
        var ul = $(this).parents('ul.sub');
        var li_parent = $(this).parents('li.parent');
        if (this.checked) {
            var count_checkbox = $(':checkbox', ul).size();
            var count_checked = $(':checked', ul).size();
//            console.log(count_checkbox+'___'+count_checked);
            if (count_checkbox == count_checked)
                li_parent.find('.check_pr').attr('checked', true);
            else
                li_parent.find('.check_pr').attr('checked', false);
        } else {
            li_parent.find('.check_pr').attr('checked', false);
        }
        $('#role-acl_desc').val($('#custom_form input:not(.field_permission)').serializeObject());
    });

    $('li.parent input.check_pr').change(function () {
        if (this.checked) {
            $('ul :checkbox', $(this).parents('.parent')).attr('checked', true);
        } else {
            $('ul :checkbox', $(this).parents('.parent')).attr('checked', false);
        }
        $('#role-acl_desc').val($('#custom_form input:not(.field_permission)').serializeObject());
    });

    var acl_desc = '<?php echo $model->acl_desc ?>';
    if (acl_desc == 'ALL_PRIVILEGES') {
        setFullAccessCheckbox();
    }
    /*else{
     $('#role-acl_type').val('custom');
     }*/

    $('li.parent').each(function () {
        var ul_sub = $('ul.sub', this);
        var count_checkbox = $(':checkbox', ul_sub).size();
        var count_checked = $(':checked', ul_sub).size();
        if (count_checkbox == count_checked) {
            $('.check_pr', this).attr('checked', true);
        } else {
            $('.check_pr', this).attr('checked', false);
        }
    });

    $('.show_fields').click(function() {
        var hide_fields_class = $(this).attr('data-togglehandler');
        hide_fields = $('.'+hide_fields_class);
        if (hide_fields.hasClass("hide")) {
            hide_fields.removeClass("hide");
        }else{
            hide_fields.addClass("hide");
        }
    });

    $("#custom_form input[type='checkbox']").change(function () {
            $('#role-acl_desc').val($('#custom_form input:not(.field_permission)').serializeObject());
    });

    $('[data-range]').each(function(index, item) {
            item = $(item);
            var value = item.data('value');
            item.slider({
                min: 0,
                max: 2,
                value: value,
                disabled: item.data('locked'),
                slide: handleChangeOfPermissionRange
            });
        });

    $('[data-1-range]').each(function(index, item) {
            item = $(item);
            var value = item.data('value');
            item.slider({
                min: 0,
                max: 1,
                value: value,
                disabled: item.data('locked'),
                slide: handleChangeOfPermissionRange
            });
        });

        function handleChangeOfPermissionRange(e, ui) {
            var target = jQuery(ui.handle);
            if (!target.hasClass('mini-slider-control')) {
                target = target.closest('.mini-slider-control');
            }
            var input  = jQuery('[data-range-input="'+target.data('range')+'"]');
            input.val(ui.value);
            target.attr('data-value', ui.value);

            $('#role-field_st').val($('#custom_form input.field_permission').serializeObject());
        }
XP;
$this->registerJs($script);
?>
