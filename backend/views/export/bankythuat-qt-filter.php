<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

?>

<div class="bankythuat-qt-form-filter">

    <h1>Export dữ liệu ban kỹ thuật</h1>
	<?php $form = ActiveForm::begin( [
		'id'      => 'bankythuat-qt-form-fields',
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
            <div class="checkbox"><label><?php echo Html::checkbox('cols[sohieu]', true, ['value' => 'Số hiệu BKT QT']);?>Số hiệu BKT QT</label></div>
            <div class="checkbox"><label><?php echo Html::checkbox('cols[tenbankythuat]', true, ['value' => 'Tên BKT QT']);?>Tên BKT QT</label></div>                        
            <div class="checkbox"><label><?php echo Html::checkbox('cols[tucach]', true, ['value' => 'Tư cách thành viên']);?>Tư cách thành viên</label></div>
            <div class="checkbox"><label><?php echo Html::checkbox('cols[link]', true, ['value' => 'Link tới']);?>Link tới thành viên</label></div>
        </div>
    </fieldset>
    <div class="form-group col-md-12 text-right">
        <button id="ban_ky_thuat_xuat" class="btn btn-success"><i class="glyphicon glyphicon-download"></i> Xuất dữ liệu</button>
        <button type="button" class="btn btn-default" data-dismiss="modal" aria-hidden="true">Đóng</button>
    </div>
	<?php ActiveForm::end(); ?>
</div>
<script type="text/javascript">

    $('form#bankythuat-qt-form-fields').on('beforeSubmit', function(e) { 
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
        url = '<?php echo Url::toRoute('export/ban-ky-thuat-qt');?>' + url + para +'filter_texts='+ JSON.stringify(label_array) + '&cols=' + formData;
            window.location.href = url;
    }).on('submit', function(e){        
        e.preventDefault();
    });


</script>