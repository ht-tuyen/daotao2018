<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\web\View;
use backend\models\Tieuchuan;
use backend\models\Bankythuat;
use backend\models\Ics;
use kartik\select2\Select2;
//$this->registerJs($js, View::POS_END);
?>

<div class="tieuchuanquocte-form-filter">

    <h1>Export Góp ý tiêu chuẩn quốc tế</h1>
	<?php $form = ActiveForm::begin( ['id' => 'tieuchuanquocte-form-fields','options' => [
		'enctype' => 'multipart/form-data'
	]]); ?>

    <div><br/></div>
    <fieldset>
        <legend>Chọn các cột cần hiển thị</legend>
            <div class="col-md-12">
                <div class="checkbox"><label><?php echo Html::checkbox('', true, ['class'=> 'export_checkall','value' => '']);?>Check tất cả</label></div>
                <hr class="no-margin" />
            </div>



            <div class="col-md-6">
                <div class="checkbox"><label><?php echo Html::checkbox('cols[sohieu]', true, ['value' => 'Số hiệu']);?>Số hiệu</label></div>                
                <div class="checkbox"><label><?php echo Html::checkbox('cols[tentienganh]', true, ['value' => 'Tên tiếng anh']);?>Tên tiếng anh</label></div>
                <div class="checkbox"><label><?php echo Html::checkbox('cols[idduan]', true, ['value' => 'Mã số dự án']);?>Mã số dự án</label></div>
                <div class="checkbox"><label><?php echo Html::checkbox('cols[giaidoan]', true, ['value' => 'Giai đoạn']);?>Giai đoạn</label></div>
            </div>

            <div class="col-md-6">
                <div class="checkbox"><label><?php echo Html::checkbox('cols[bktqt]', true, ['value' => 'BKT Quốc tế']);?>BKT Quốc tế</label></div>
                <div class="checkbox"><label><?php echo Html::checkbox('cols[bktvn]', true, ['value' => 'BKT Việt Nam']);?>BKT Việt Nam</label></div>
                <div class="checkbox"><label><?php echo Html::checkbox('cols[gopy]', true, ['value' => 'Góp ý']);?>Góp ý</label></div>                
                <div class="checkbox"><label><?php echo Html::checkbox('cols[thoigianbatdau]', true, ['value' => 'Bắt đầu']);?>Bắt đàu</label></div>
                <div class="checkbox"><label><?php echo Html::checkbox('cols[thoigianketthuc]', true, ['value' => 'Kết thúc']);?>Kết thúc</label></div>
            </div>

                 
    </fieldset>

    <div class="form-group col-md-12 text-right">
        <button id="tieu_chuan_xuat" class="btn btn-success"><i class="glyphicon glyphicon-download"></i> Xuất dữ liệu</button>
        <button type="button" class="btn btn-default" data-dismiss="modal" aria-hidden="true">Đóng</button>
    </div>
	<?php ActiveForm::end(); ?>
</div>
<script type="text/javascript">

    $('#tieu_chuan_xuat').on('click', function (e) {
        e.preventDefault();
        $('.tieuchuan-index .summary b').each(function(){
            var a = $(this).text()
            if(a.indexOf('-') === -1){
                a = a.replace(',','')
                a = a.replace('.','')
                a = parseInt(a)
                if(confirm('Bạn muốn tải về dữ liệu của '+a+' Tiêu chuẩn?')){                    
                    var cols = $('#tieuchuanquocte-form-fields').serialize();
                    var url = window.location.search;
                    var label_array = {};
                    $('.tieuchuan-search label').each(function () {
                        var $this = window.parent.$(this);
                        label_array[$this.attr('for')] = $this.text();
                    });
                     if(url == ''){
                        para = '?'
                    }else{
                        para = '&'
                    } 
                    url = '<?php echo Url::toRoute('export/tieu-chuan-quoc-te');?>' + url + para +'filter_texts='+ JSON.stringify(label_array) + '&cols=' + cols;
                    window.location.href = url;
                }else{                    
                    return false
                }
            }            
        })
                
    });
</script>