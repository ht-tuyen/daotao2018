<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\web\View;
use backend\models\Tieuchuan;
use backend\models\Bankythuat;
use backend\models\Regions;
use backend\models\ThanhvienfilterSearch;
use backend\models\Thanhvien;
use backend\models\Ics;
use kartik\select2\Select2;
use kartik\date\DatePicker;
//$this->registerJs($js, View::POS_END);
$model = new ThanhvienfilterSearch();

?>

<div class="thanhvien-form-filter">
    <h1>Export dữ liệu Thành viên</h1>
    <h3 class="hide">Lọc theo tiêu chí:</h3>
	<?php $form = ActiveForm::begin( [
		'id'      => 'thanhvien-form-fields',
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
        
        <div class="col-md-4">
            <div class="checkbox"><label><?php echo Html::checkbox('cols[hoten]', true, ['value' => 'Họ tên']);?>Họ tên</label></div>
            <div class="checkbox"><label><?php echo Html::checkbox('cols[ngaysinh]', true, ['value' => 'Ngày sinh']);?>Ngày sinh</label></div>
            <div class="checkbox"><label><?php echo Html::checkbox('cols[gioitinh]', true, ['value' => 'Giới tính']);?>Giới tính</label></div>
            <div class="checkbox"><label><?php echo Html::checkbox('cols[socmnd]', true, ['value' => 'Số CMND']);?>Số CMND</label></div>
            <div class="checkbox"><label><?php echo Html::checkbox('cols[ngaycap]', true, ['value' => 'Ngày cấp']);?>Ngày cấp</label></div>
            <div class="checkbox"><label><?php echo Html::checkbox('cols[noicap]', true, ['value' => 'Nơi cấp']);?>Nơi cấp</label></div>
        </div>

        <div class="col-md-4">
            <div class="checkbox"><label><?php echo Html::checkbox('cols[dienthoaicodinh]', true, ['value' => 'Điện thoại cố định']);?>Điện thoại cố định</label></div>
            <div class="checkbox"><label><?php echo Html::checkbox('cols[dienthoaididong]', true, ['value' => 'Điện thoại di động']);?>Điện thoại di động</label></div>
            <div class="checkbox"><label><?php echo Html::checkbox('cols[diachi]', true, ['value' => 'Địa chỉ']);?>Địa chỉ</label></div>
            <div class="checkbox"><label><?php echo Html::checkbox('cols[email]', true, ['value' => 'Email']);?>Email</label></div>
            <div class="checkbox"><label><?php echo Html::checkbox('cols[hocham]', true, ['value' => 'Học hàm']);?>Học hàm</label></div>
        </div>

        <div class="col-md-4">
            <div class="checkbox"><label><?php echo Html::checkbox('cols[hocvi]', true, ['value' => 'Học vị']);?>Học vị</label></div>
            <div class="checkbox"><label><?php echo Html::checkbox('cols[chuyennganh]', true, ['value' => 'Chuyên ngành']);?>Chuyên ngành</label></div>
            <div class="checkbox"><label><?php echo Html::checkbox('cols[coquan]', true, ['value' => 'Cơ quan']);?>Cơ quan</label></div>
            <div class="checkbox"><label><?php echo Html::checkbox('cols[chucvu]', true, ['value' => 'Chức vụ']);?>Chức vụ</label></div>
            <div class="checkbox"><label><?php echo Html::checkbox('cols[diachicoquan]', true, ['value' => 'Địa chỉ cơ quan']);?>Địa chỉ cơ quan</label></div>
            <div class="checkbox"><label><?php echo Html::checkbox('cols[idbankythuat]', true, ['value' => 'Ban kỹ thuật']);?>Ban kỹ thuật</label></div>
            
        </div>
    </fieldset>

    <div class="form-group col-md-12 text-right">
        <button id="thanh_vien_xuat" class="btn btn-success"><i class="glyphicon glyphicon-download"></i> Xuất dữ liệu</button>
        <button type="button" class="btn btn-default" data-dismiss="modal" aria-hidden="true">Đóng</button>
    </div>
	<?php ActiveForm::end(); ?>
</div>
<script type="text/javascript">
    $('#thanh_vien_xuat').on('click', function (e) {
        e.preventDefault();
        $('.thanhvien-index .summary b').each(function(){
            var a = $(this).text()
            if(a.indexOf('-') === -1){
                a = a.replace(',','')
                a = a.replace('.','')
                a = parseInt(a)
                if(confirm('Bạn muốn tải về dữ liệu của '+a+' Thành viên BKT?')){                    
                   var cols = $('#thanhvien-form-fields').serialize();
                    var url = window.location.search;
                    var label_array = {};
                    $('.thanhvien-search label').each(function () {
                        var $this = window.parent.$(this);
                        label_array[$this.attr('for')] = $this.text();
                    });
                    if(url == ''){
                        para = '?'
                    }else{
                        para = '&'
                    } 
                    url = '<?php echo Url::toRoute('export/thanh-vien');?>' + url + para +'filter_texts='+ JSON.stringify(label_array) + '&cols=' + cols;
                    window.location.href = url;
                }else{                    
                    return false
                }
            }            
        })
    });
</script>