<?php
use backend\helpers\AcpHelper;
use yii\helpers\Html;
use backend\models\Tieuchuan;
use frontend\models\Order;
use yii\widgets\ActiveForm;
use backend\models\Regions;
use backend\models\Countries;
use kartik\date\DatePicker;

$donhangchitiet = json_decode($model->item,true);

// echo '<pre>';
// print_r($donhangchitiet);
// echo '</pre>';
if(empty($view)) $view = '';
?>
<div>
    <h1>Chi tiết đơn hàng</h1>
</div>

<div class="order-view col-md-12">   
    <style>
        .order-remove,
        .order-add{
            cursor: pointer;
        }
        .required.has-error {
            position: relative;
        }
    </style>


    <br/>
    <?php $form = ActiveForm::begin(['id' => 'order-form']); ?>
        <table class="table">
        <tr>
            <td class="col-md-3"><b>Họ tên</b></td>
            <td class="col-md-9">
                <?php if($view == 'view'){
                    echo $model->hoten;
                }else{
                    echo $form->field($model, 'hoten', ['options' => ['class' => '']])->textInput(['maxlength' => true, 'placeholder' => 'Họ tên',])->label(false);
                }
                ?>
            </td>
        </tr>

        <tr>    
            <td class="col-md-3"><b>Điện thoại</b></td>           
            <td class="col-md-9">
                <?php if($view == 'view'){
                    echo $model->sdt;
                }else{
                    echo $form->field($model, 'sdt', ['options' => ['class' => '']])->textInput(['maxlength' => true,'placeholder' => 'Điện thoại'])->label(false);
                }
                ?>
            </td>
        </tr>
        <tr>
            <td class="col-md-3"><b>Địa chỉ giao hàng</b></td>            
            <td class="col-md-9">
                <?php if($view == 'view'){
                    echo $model->diachi();
                }else{
                    echo $form->field($model, 'sonha', ['options' => ['class' => 'col-md-6']])->textInput(['maxlength' => true, 'placeholder' => 'Địa chỉ chi tiết'])->label(false);
                   
                    $tinhthanh = [
                        '' => '--- Tỉnh/Thành phố ---',
                    ];
                    $province = array_merge(Regions::getListOptions(), $tinhthanh);
                    
                    echo $form->field($model, 'province', ['options' => ['class' => 'col-md-3']])->dropDownList($province)->label(false);
            
                    $model->country_id = 260;
                    echo $form->field($model, 'country_id', ['options' => ['class' => 'col-md-3']])->dropDownList(Countries::getListOptions(), ['prompt' => '--- Chọn ---'])->label(false);                    
                }
                ?>
                
            </td>
        </tr>
        <tr>
            <td class="col-md-3"><b>Thời gian giao hàng</b></td>
            <td class="col-md-9">
                <?php if($view == 'view'){
                    echo $model->time;
                }else{  
                    echo $form->field($model, 'time',['options' => []])->widget(DatePicker::classname(), [
                        'options' => [
                            // 'readonly' => 'readonly',
                             'placeholder' => 'Thời gian giao hàng dự kiến',
                        ],
                        'type' => DatePicker::TYPE_COMPONENT_APPEND,
                        'pluginOptions' => [          
                            'startDate' => date('d-m-Y',strtotime($model->create_time)),
                            // 'endDate' => date($time_max),       
                            'autoclose' => true,
                            'clearBtn' => false,
                            'format' => 'dd-mm-yyyy',
                            'todayHighlight' => true,
                        ]
                    ])->label(false);                 
                }
                ?>
            </td>
        </tr>
        <tr>
            <td class="col-md-3"><b>Yêu cầu khác</b></td>
            <td class="col-md-9">
                <?php if($view == 'view'){
                    echo $model->yck;
                }else{
                    echo $form->field($model, 'yck', ['options' => ['class' => '']])->textArea(['maxlength' => true,'placeholder' => 'Yêu cầu khác'])->label(false);
                }
                ?>
            </td>
        </tr>
        <tr>
            <td class="col-md-3"><b>Thời gian tạo</b></td>
            <td class="col-md-9"><?= $model->create_time?></td>
        </tr>


        <tr>
            <td class="col-md-3"><b>Tình trạng</b></td>            
            <td class="col-md-9">
                <?php if($view == 'view'){
                    echo Order::getTinhtrangLabel($model->tinhtrang);
                }else{                    
                    echo $form->field($model, 'tinhtrang', ['options' => ['class' => 'col-md-3']])->dropDownList(Order::getTinhtrangOptions())->label(false);                                
                }
                ?>
                
            </td>
        </tr>

        <tr>
            <td class="col-md-3"><b>Ghi chú</b></td>
            <td class="col-md-9">
                <?php if($view == 'view'){
                    echo $model->ghichu;
                }else{
                    echo $form->field($model, 'ghichu', ['options' => ['class' => '']])->textArea(['maxlength' => true,'placeholder' => 'Ghi chú'])->label(false);
                }
                ?>
            </td>
        </tr>
        
        </table>




        <table class="table">
            <tr class="bg-gray">
                <th style="width:50px">STT</th>
                <th>Tiêu chuẩn</th>
                <th style="width:100px">Số lượng</th>
                <th style="width:150px">Giá</th>
                <th class="text-right" style="width:150px">Thành tiền</th>
                <th></th>
            </tr>
            <?php
                $dem = 0;
                $tong = 0;
                if(is_array($donhangchitiet)) foreach ($donhangchitiet as $k => $v) {
                    $tc = Tieuchuan::find()->where(['tc_id' => $k])->one();
                    if($tc){
                        $dem += 1;
                        echo '<tr>';
                        echo '<td>'.$dem.'</td>';

                        if($view == 'view'){
                            echo '<td>'.$tc->sohieu.'</td>';
                            echo '<td>'.$v['soluong'].'</td>';
                            echo '<td class="">'.number_format($v['dongia']).'</td>';
                            $thanhtien = $v['soluong'] * $v['dongia'];
                            $tong += $thanhtien;
                            echo '<td class="text-right">'.number_format($thanhtien).' đ</td>';
                        }else{
                            $model->item = [
                                $k => [
                                    'sohieu' => $tc->sohieu,
                                    'soluong' => $v['soluong'],
                                    'dongia' => $v['dongia'],                                    
                                ]
                            ];
                            
                            echo '<td>'.$form->field($model, "item[{$k}][sohieu]", ['options' => ['class' => '']])->textInput(['maxlength' => true,'placeholder' => 'Số hiệu Tiêu chuẩn'])->label(false).'</td>';

                            echo '<td>'.$form->field($model, "item[{$k}][soluong]", ['options' => []])->textInput(['min' => 1, 'type' => 'number','maxlength' => true,'placeholder' => 'Số lượng','class' => 'order-soluong change-order form-control'])->label(false).'</td>';

                            echo '<td class="">'.$form->field($model, "item[{$k}][dongia]", ['options' => []])->textInput(['min' => 0, 'type' => 'number', 'maxlength' => true,'placeholder' => 'Đơn giá','class' => 'order-dongia change-order form-control'])->label(false).'</td>';
                            $thanhtien = $v['soluong'] * $v['dongia'];
                            $tong += $thanhtien;
                            echo '<td class="text-right order-thanhtien ">'.number_format($thanhtien).' đ</td>';

                            echo '<td class="text-center"><span class="order-remove glyphicon glyphicon-remove text-red"></span></td>';
                        }                        
                        
                        echo '</tr>';
                    }
                }
            ?>  
            <tr>
                <td><span class="glyphicon glyphicon-plus order-add text-green"></span></td>     
                <td></td>
                <td></td>
                <td><b>Tổng: </b></td>
                <td class="text-right order-tongtien"><b> <?= number_format($tong);?> đ</b></td>
            </tr>
        </table>

        <?php
            if(empty($view)){
        ?>
            <div class="text-right">
                <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                <button type="button" class="btn btn-default" data-dismiss="modal" aria-hidden="true">Đóng</button>
            </div>
        <?php
            }
        ?>

    <?php ActiveForm::end(); ?>
   
    <?php
        if(empty($view)){
    ?>
    <script type="text/javascript">
        function reload_gia(table = ''){            
            var tongtien = 0
            table.find('.order-thanhtien').each(function(index ){
                text = $(this).text()
                tongtien  += parseInt(text.replace(/,/g,''))                
            })
            console.log(tongtien)
            $('.order-tongtien').html('<b>'+tongtien.toLocaleString()+' đ</b>')
        }
        
       


        $('.order-add').on('click',function(e){            
            var tr = $(this).parents('tr')  
            index = tr.index()          
            var html = '<tr>'
                html += '<td>'+index+'</td>'
                html += '<td><input type="text" id="order-item-'+index+'-sohieu" class="form-control" name="Order[item]['+index+'][sohieu]" value="" placeholder="Số hiệu Tiêu chuẩn"></td>'
                html += '<td><input type="number" id="order-item-'+index+'-soluong" class="order-soluong change-order form-control" name="Order[item]['+index+'][soluong]" value="1" min="1" placeholder="Số lượng"></td>'
                html += '<td><input type="number" id="order-item-'+index+'-dongia" class="order-dongia change-order form-control" name="Order[item]['+index+'][dongia]" value="0" min="0" placeholder="Đơn giá"></td>'
                html += '<td class="text-right order-thanhtien">0 đ</td>'
                html += '<td class="text-center"><span class="order-remove glyphicon glyphicon-remove text-red"></span></td>'
                html += '</tr>'

            $(html).insertBefore(tr);
        })
        


        $('form#order-form').on('beforeSubmit', function (e) {
            loadimg();
            var form = $(this);
            var formData = form.serialize();
            // var formData = new FormData(document.querySelector('form#order-form'));

            $.ajax({
                url: form.attr("action"),
                type: form.attr("method"),
                data: formData,
                // processData: false,
                // contentType: false,
                success: function (data) {
                    if (data.status == 1) {
                        <?php if($model->isNewRecord){ //Url::base(true)?>
                        rload('listorder-index')
                        findclosemodal(form)
                        <?php }else{ ?>
                        rload('listorder-index')
                        findclosemodal(form)
                        <?php }?>
                        popthanhcong();
                    } else {
                        popthatbai();
                    }
                    e.preventDefault();
                },
                error: function () {
                    popthatbai();
                    e.preventDefault();
                }
            });
        }).on('submit', function (e) {
            e.preventDefault();
        });
    </script>
    <?php
        }
    ?>
</div>


