<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

?>

<div class="bankythuat-form-filter">

    <h1>Export dữ liệu ban kỹ thuật</h1>
	<?php $form = ActiveForm::begin( [
		'id'      => 'bankythuat-form-fields',
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
            <div class="checkbox"><label><?php echo Html::checkbox('cols[sohieu]', true, ['value' => 'Số hiệu']);?>Số hiệu</label></div>
            <div class="checkbox"><label><?php echo Html::checkbox('cols[tenbankythuat]', true, ['value' => 'Tên ban kỹ thuật']);?>Tên ban kỹ thuật</label></div>
            <div class="checkbox"><label><?php echo Html::checkbox('cols[phamvi]', true, ['value' => 'Phạm vi hoạt động']);?>Phạm vi hoạt động</label></div>
            <div class="checkbox"><label><?php echo Html::checkbox('cols[idtruongban]', true, ['value' => 'Trưởng ban']);?>Trưởng ban</label></div>
            <div class="checkbox"><label><?php echo Html::checkbox('cols[idthuky]', true, ['value' => 'Thư ký']);?>Thư ký</label></div>
            <div class="checkbox"><label><?php echo Html::checkbox('cols[banquocte]', true, ['value' => 'BKT QT tương đương']);?>BKT QT tương đương</label></div>
        </div>
    </fieldset>
    <div class="form-group col-md-12 text-right">
        <button id="ban_ky_thuat_xuat" class="btn btn-success"><i class="glyphicon glyphicon-download"></i> Xuất dữ liệu</button>
        <button type="button" class="btn btn-default" data-dismiss="modal" aria-hidden="true">Đóng</button>
    </div>
	<?php ActiveForm::end(); ?>
</div>
<script type="text/javascript">

    $('form#bankythuat-form-fields').on('beforeSubmit', function(e) { 
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
        url = '<?php echo Url::toRoute('export/ban-ky-thuat');?>' + url + para +'filter_texts='+ JSON.stringify(label_array) + '&cols=' + formData;
            window.location.href = url;
    }).on('submit', function(e){        
        e.preventDefault();
    });


</script>