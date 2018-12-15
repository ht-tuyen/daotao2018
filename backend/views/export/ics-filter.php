<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

?>

<div class="ics-form-filter">

    <h1>Export dữ liệu Mã phân loại ICS</h1>
	<?php $form = ActiveForm::begin( [
		'id'      => 'ics-form-fields',
		'options' => [
			'enctype' => 'multipart/form-data'
		],
		'enableClientValidation' => false
	] );

	?>
    <div><br/></div>
    <fieldset>
        <legend>Chọn các cột cần hiển thị</legend>
        <div class="col-md-12">
            <div class="checkbox"><label><?php echo Html::checkbox('', true, ['class'=> 'export_checkall','value' => '']);?>Check tất cả</label></div>
            <hr class="no-margin" />
            </div>

        
        <div class="col-md-12">
            <div class="checkbox"><label><?php echo Html::checkbox('cols[maphanloai]', true, ['value' => 'Mã phân loại']);?>Mã phân loại</label></div>

            <div class="checkbox"><label><?php echo Html::checkbox('cols[tentiengviet]', true, ['value' => 'Tên tiếng Việt']);?>Tên tiếng Việt</label></div>                        

            <div class="checkbox"><label><?php echo Html::checkbox('cols[tentienganh]', true, ['value' => 'Tên tiếng Anh']);?>Tên tiếng Anh</label></div>
            
        </div>
    </fieldset>
    <div class="form-group col-md-12 text-right">
        <button id="ban_ky_thuat_xuat" class="btn btn-success"><i class="glyphicon glyphicon-download"></i> Xuất dữ liệu</button>
        <button type="button" class="btn btn-default" data-dismiss="modal" aria-hidden="true">Đóng</button>
    </div>
	<?php ActiveForm::end(); ?>
</div>
<script type="text/javascript">

    $('form#ics-form-fields').on('beforeSubmit', function(e) { 
        e.preventDefault();
        var url = window.location.search;
        var form = $(this);
        var formData = form.serialize();
        var label_array = {};
        $('.bankythuat-search label').each(function () {
            var $this = window.parent.$(this);
            label_array[$this.attr('for')] = $this.text();
        });
        if(url == ''){
            para = '?'
        }else{
            para = '&'
        }           
        url = '<?php echo Url::toRoute('export/ics');?>' + url + para +'filter_texts='+ JSON.stringify(label_array) + '&cols=' + formData;
            window.location.href = url;
    }).on('submit', function(e){        
        e.preventDefault();
    });


</script>