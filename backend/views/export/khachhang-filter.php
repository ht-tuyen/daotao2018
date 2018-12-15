<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

?>

<div class="khachhang-form-filter">

    <h1>Export đơn hàng</h1>
	<?php $form = ActiveForm::begin( [
		'id'      => 'khachhang-form-fields',
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
            <div class="checkbox"><label><?php echo Html::checkbox('cols[fullname]', true, ['value' => 'Họ tên']);?>Họ tên</label></div>
            <div class="checkbox"><label><?php echo Html::checkbox('cols[username]', true, ['value' => 'Tên đăng nhập']);?>Tên đăng nhập</label></div>
            <div class="checkbox"><label><?php echo Html::checkbox('cols[email]', true, ['value' => 'Email']);?>Email</label></div>
            <div class="checkbox"><label><?php echo Html::checkbox('cols[mobile]', true, ['value' => 'Điện thoại']);?>Điện thoại</label></div>
            <div class="checkbox"><label><?php echo Html::checkbox('cols[address]', true, ['value' => 'Địa chỉ']);?>Địa chỉ</label></div>
            <div class="checkbox"><label><?php echo Html::checkbox('cols[linhvucquantam]', true, ['value' => 'Lĩnh vực quan tâm']);?>Lĩnh vực quan tâm</label></div>
            <div class="checkbox"><label><?php echo Html::checkbox('cols[created_at]', true, ['value' => 'Thời gian tạo']);?>Thời gian tạo</label></div>
        </div>
    </fieldset>
    <div class="form-group col-md-12 text-right">
        <button id="ban_ky_thuat_xuat" class="btn btn-success"><i class="glyphicon glyphicon-download"></i> Xuất dữ liệu</button>
        <button type="button" class="btn btn-default" data-dismiss="modal" aria-hidden="true">Đóng</button>
    </div>
	<?php ActiveForm::end(); ?>
</div>
<script type="text/javascript">

    $('form#khachhang-form-fields').on('beforeSubmit', function(e) { 
        e.preventDefault();
        var url = window.location.search;
        var form = $(this);
        var formData = form.serialize();
        var label_array = {};
        $('.donghang-search label').each(function () {
            var $this = window.parent.$(this);
            label_array[$this.attr('for')] = $this.text();
        });
        if(url == ''){
            para = '?'
        }else{
            para = '&'
        }           
        url = '<?php echo Url::toRoute('export/khach-hang');?>' + url + para +'filter_texts='+ JSON.stringify(label_array) + '&cols=' + formData;
            window.location.href = url;
    }).on('submit', function(e){        
        e.preventDefault();
    });


</script>