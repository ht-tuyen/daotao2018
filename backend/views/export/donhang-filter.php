<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

?>

<div class="donhang-form-filter">

    <h1>Export đơn hàng</h1>
	<?php $form = ActiveForm::begin( [
		'id'      => 'donhang-form-fields',
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
            <div class="checkbox"><label><?php echo Html::checkbox('cols[order_id]', true, ['value' => 'Mã đơn hàng']);?>
            Mã đơn hàng</label></div>

            <div class="checkbox"><label><?php echo Html::checkbox('cols[hoten]', true, ['value' => 'Tên khách hàng']);?>Tên khách hàng</label></div>

            <div class="checkbox"><label><?php echo Html::checkbox('cols[item]', true, ['value' => 'Chi tiết đơn hàng']);?>Chi tiết đơn hàng</label></div>

            <div class="checkbox"><label><?php echo Html::checkbox('cols[total]', true, ['value' => 'Thành tiền']);?>Thành tiền</label></div>
           
            <div class="checkbox"><label><?php echo Html::checkbox('cols[sdt]', true, ['value' => 'Điện thoại']);?>Điện thoại</label></div>
                                 
        </div>

        <div class="col-md-6">
            <div class="checkbox"><label><?php echo Html::checkbox('cols[sonha]', true, ['value' => 'Địa chỉ']);?>Địa chỉ</label></div> 
            
            <div class="checkbox"><label><?php echo Html::checkbox('cols[yck]', true, ['value' => 'Yêu cầu khác']);?>Yêu cầu khác</label></div>


            <div class="checkbox"><label><?php echo Html::checkbox('cols[create_time]', true, ['value' => 'Ngày đặt']);?>Ngày đặt</label></div>

            <div class="checkbox"><label><?php echo Html::checkbox('cols[time]', true, ['value' => 'Thời gian giao hàng']);?>Thời gian giao hàng</label></div>            
            
            <div class="checkbox"><label><?php echo Html::checkbox('cols[tinhtrang]', true, ['value' => 'Tình trạng']);?>Tình trạng</label></div>  
        </div>
    </fieldset>
    <div class="form-group col-md-12 text-right">
        <button id="ban_ky_thuat_xuat" class="btn btn-success"><i class="glyphicon glyphicon-download"></i> Xuất dữ liệu</button>
        <button type="button" class="btn btn-default" data-dismiss="modal" aria-hidden="true">Đóng</button>
    </div>
	<?php ActiveForm::end(); ?>
</div>
<script type="text/javascript">

    $('form#donhang-form-fields').on('beforeSubmit', function(e) { 
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
        url = '<?php echo Url::toRoute('export/don-hang');?>' + url + para +'filter_texts='+ JSON.stringify(label_array) + '&cols=' + formData;
            window.location.href = url;
    }).on('submit', function(e){        
        e.preventDefault();
    });


</script>