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

<div class="tieuchuan-form-filter">

    <h1>Export dữ liệu tiêu chuẩn</h1>
	<?php $form = ActiveForm::begin( ['id' => 'tieuchuan-form-fields','options' => [
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
                <div class="checkbox"><label><?php echo Html::checkbox('cols[nambanhanh]', true, ['value' => 'Năm ban hành']);?>Năm ban hành</label></div>
                <div class="checkbox"><label><?php echo Html::checkbox('cols[tentiengviet]', true, ['value' => 'Tên tiếng việt']);?>Tên tiếng việt</label></div>
                <div class="checkbox"><label><?php echo Html::checkbox('cols[tentienganh]', true, ['value' => 'Tên tiếng anh']);?>Tên tiếng anh</label></div>
                <div class="checkbox"><label><?php echo Html::checkbox('cols[sotrang]', true, ['value' => 'Số trang']);?>Số trang</label></div>
                <div class="checkbox"><label><?php echo Html::checkbox('cols[chisophanloai]', true, ['value' => 'Chỉ số phân loại']);?>Chỉ số phân loại</label></div>
                <div class="checkbox"><label><?php echo Html::checkbox('cols[tinhtrang]', true, ['value' => 'Tình trạng']);?>Tình trạng</label></div>
                <div class="checkbox"><label><?php echo Html::checkbox('cols[tieuchuantuongduong]', true, ['value' => 'Tiêu chuẩn tương đương']);?>Tiêu chuẩn tương đương</label></div>                                
            </div>

            <div class="col-md-6">                           
                <div class="checkbox"><label><?php echo Html::checkbox('cols[mucdo]', true, ['value' => 'Mức độ tương đương']);?>Mức độ tương đương</label></div>
                <div class="checkbox"><label><?php echo Html::checkbox('cols[tieuchuanviendan]', true, ['value' => 'Tiêu chuẩn viện dẫn']);?>Tiêu chuẩn viện dẫn</label></div>
                <div class="checkbox"><label><?php echo Html::checkbox('cols[thaythecho]', true, ['value' => 'Thay thế cho']);?>Thay thế cho</label></div>
                <div class="checkbox"><label><?php echo Html::checkbox('cols[thaythebang]', true, ['value' => 'Thay thế bằng']);?>Thay thế bằng</label></div>                
                <div class="checkbox"><label><?php echo Html::checkbox('cols[quyetdinhbanhanh]', true, ['value' => 'Quyết định ban hành']);?>Quyết định ban hành</label></div>
                <div class="checkbox"><label><?php echo Html::checkbox('cols[phamviapdung]', true, ['value' => 'Phạm vi áp dụng']);?>Phạm vi áp dụng</label></div>
                <div class="checkbox"><label><?php echo Html::checkbox('cols[coquanxaydung]', true, ['value' => 'Cơ quan biên soạn/BKT']);?>Cơ quan biên soạn/BKT</label></div>
                <div class="checkbox"><label><?php echo Html::checkbox('cols[gia]', true, ['value' => 'Giá']);?>Giá</label></div>
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
                    var cols = $('#tieuchuan-form-fields').serialize();
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
                    url = '<?php echo Url::toRoute('export/tieu-chuan');?>' + url + para +'filter_texts='+ JSON.stringify(label_array) + '&cols=' + cols;
                    window.location.href = url;
                }else{                    
                    return false
                }
            }            
        })
                
    });
</script>