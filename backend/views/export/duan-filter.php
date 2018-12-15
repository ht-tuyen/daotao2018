<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

?>

<div class="duan-form-filter">
<style type="text/css">
    .modal-body{height: auto;}
</style>
    <h1>Export dữ liệu Dự án</h1>
	<?php $form = ActiveForm::begin( [
		'id'      => 'duan-form-fields',
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

        
        <div class="col-md-6">
            <div class="checkbox"><label><?php echo Html::checkbox('cols[sohieu]', true, ['value' => 'Số hiệu']);?>Số hiệu</label></div>

            <div class="checkbox"><label><?php echo Html::checkbox('cols[tenduan]', true, ['value' => 'Tên dự án']);?>Tên dự án</label></div>

            <div class="checkbox"><label><?php echo Html::checkbox('cols[kehoachnam]', true, ['value' => 'Kế hoạch']);?>Kế hoạch</label></div>

            <div class="checkbox"><label><?php echo Html::checkbox('cols[bonganh]', true, ['value' => 'Bộ ngành']);?>Bộ ngành</label></div>

            <div class="checkbox"><label><?php echo Html::checkbox('cols[linhvuc]', true, ['value' => 'Lĩnh vực']);?>Lĩnh vực</label></div>

            <div class="checkbox"><label><?php echo Html::checkbox('cols[coquanbiensoan]', true, ['value' => 'Ban kỹ thuật']);?>Ban kỹ thuật</label></div>
        </div>

        <div class="col-md-6">
            <div class="checkbox"><label><?php echo Html::checkbox('cols[tiendo]', true, ['value' => 'Tiến độ']);?>Tiến độ</label></div>

            <div class="checkbox"><label><?php echo Html::checkbox('cols[quyetdinh]', true, ['value' => 'Quyết định']);?>Quyết định</label></div>

            <div class="checkbox"><label><?php echo Html::checkbox('cols[hopdong]', true, ['value' => 'Hợp đồng']);?>Hợp đồng</label></div>

           
        </div>


    </fieldset>
    <div class="form-group col-md-12 text-right">
        <button id="ban_ky_thuat_xuat" class="btn btn-success"><i class="glyphicon glyphicon-download"></i> Xuất dữ liệu</button>
        <button type="button" class="btn btn-default" data-dismiss="modal" aria-hidden="true">Đóng</button>
    </div>
	<?php ActiveForm::end(); ?>
</div>
<script type="text/javascript">

    $('form#duan-form-fields').on('beforeSubmit', function(e) { 
        e.preventDefault();
        var url = window.location.search;
        var form = $(this);
        var formData = form.serialize();
        var label_array = {};
        $('.duan-search label').each(function () {
            var $this = window.parent.$(this);
            label_array[$this.attr('for')] = $this.text();
        });
        if(url == ''){
            para = '?'
        }else{
            para = '&'
        }           
        url = '<?php echo Url::toRoute('export/du-an');?>' + url + para +'filter_texts='+ JSON.stringify(label_array) + '&cols=' + formData;
            window.location.href = url;
    }).on('submit', function(e){        
        e.preventDefault();
    });


</script>