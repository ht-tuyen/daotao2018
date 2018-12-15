<?php
use yii\web\View;
use yii\helpers\Url;
use kartik\daterange\DateRangePicker;
use kartik\dialog\Dialog;

echo Dialog::widget();
$this->title = 'Báo cáo';
$this->params['breadcrumbs'][] = $this->title;

$link_doanh_so_char = Url::toRoute(['report/doanh-so-chart']);
$link_loi_nhuan_char = Url::toRoute(['report/loi-nhuan-chart']);
$link_don_hang_char = Url::toRoute(['report/don-hang-chart']);
$link_dskh_char = Url::toRoute(['report/ds-kh-chart']);
$link_export_ncc = Url::toRoute(['report/export-ncc']);
$link_export_don_hang = Url::toRoute(['report/export-don-hang']);
$link_get_person = Url::toRoute(['report/get-person']);
$link_export_doanh_thu = Url::toRoute(['report/export-doanh-thu']);

$js = <<<XP

    var doanh_so_chart = function (data = {}) {
        $.ajax({
            type: "GET",
            dataType: "json",
            data: data,
            url: '{$link_doanh_so_char}',
            success: function (jsondata) {
                var data = google.visualization.arrayToDataTable(jsondata);

                var options = {
                    legend: 'none',
                    title: null,
                    colors: ['#3B5998']
                };

                var chart = new google.visualization.ColumnChart(document.getElementById('doanh_so_ban_hang'));
                chart.draw(data, options);
            }
        });
    };
    var loi_nhuan_chart = function (data = {}) {
        $.ajax({
            type: "GET",
            dataType: "json",
            data: data,
            url: '{$link_loi_nhuan_char}',
            success: function (jsondata) {
                var data = google.visualization.arrayToDataTable(jsondata);
                var options = {
                    chart: {
                        legend: 'none',
                        title: '',
                        subtitle: '',
                    }
                };
                var chart = new google.visualization.ColumnChart(document.getElementById('bieu_do_loi_nhuan'));
                chart.draw(data, options);
            }
        });
    };
    var don_hang_chart = function (data = {}) {
        $.ajax({
            type: "GET",
            dataType: "json",
            data: data,
            url: '{$link_don_hang_char}',
            success: function (jsondata) {
                var data = google.visualization.arrayToDataTable(jsondata);

                var options = {
                    title: '',
                    'is3D': true,
                };

                var chart = new google.visualization.PieChart(document.getElementById('bieu_do_don_hang'));
                chart.draw(data, options);
            }
        });
    };
    var dtkh_chart = function (data = {}) {
        $.ajax({
            type: "GET",
            dataType: "json",
            data: data,
            url: '{$link_dskh_char}',
            success: function (jsondata) {
                var data = google.visualization.arrayToDataTable(jsondata);

                var options = {
                    legend: 'none',
                    title: '',
                };

                var chart = new google.visualization.ColumnChart(document.getElementById('bieu_do_doanh_thu_khach_hang'));
                chart.draw(data, options);
            }
        });
    };
    var get_chart = function(function_){
        $.ajax({
            url : 'https://www.google.com/jsapi?callback',
            cache: true,
            dataType: 'script',
            success: function () {
                google.load('visualization', '1', {
                    packages: ['corechart'], 'callback': function_
                });
            }
        });
    }
    get_chart(doanh_so_chart);
    get_chart(loi_nhuan_chart);
    get_chart(don_hang_chart);
    get_chart(dtkh_chart);
    
    $('select[name="kieu_thong_ke_doanh_thu_khach_hang"]').change(function(){
        var type = $(this).val();
        get_chart(dtkh_chart({type:type}));
    });
    
    $('input[name="doanh_so_date"]').change(function(){
        var date = $(this).val(),
        from_date = $('input[name="doanh_so_from_date"]').val(),
        to_date = $('input[name="doanh_so_to_date"]').val();
        get_chart(doanh_so_chart({date:date, from_date:from_date, to_date:to_date}));
    });
    
    $('input[name="loi_nhuan_date"]').change(function(){
        var date = $(this).val(),
        from_date = $('input[name="loi_nhuan_from_date"]').val(),
        to_date = $('input[name="loi_nhuan_to_date"]').val();
        get_chart(loi_nhuan_chart({date:date, from_date:from_date, to_date:to_date}));
    });
    
    $('input[name="don_hang_date"]').change(function(){
        var date = $(this).val(),
        from_date = $('input[name="don_hang_from_date"]').val(),
        to_date = $('input[name="don_hang_to_date"]').val();
        get_chart(don_hang_chart({date:date, from_date:from_date, to_date:to_date}));
    });
    
    $('input[name="doanh_thu_date"]').change(function(){
        var date = $(this).val(),
        from_date = $('input[name="doanh_thu_from_date"]').val(),
        to_date = $('input[name="doanh_thu_to_date"]').val();
        get_chart(dtkh_chart({date:date, from_date:from_date, to_date:to_date}));
    });
    
    $('#xuat_nha_cung_cap').on('click', function(){
        var id = $('select[name="excel_ncc"]').val();
        if(id > 0 && id != undefined){
            window.location.href = "{$link_export_ncc}?type="+id;
        }else{
            return false;
        }
    });
    
    $('#xuat_don_hang').on('click', function(){
        var from_date = $('input[name="report_order_from_date"]').val(),
        to_date = $('input[name="report_order_to_date"]').val();
        window.location.href = "{$link_export_don_hang}?from_date="+from_date+"&to_date="+to_date;
    });
    
    $('select[name="excel_doanh_thu_type"]').change(function () {
        if ($(this).val() == 0) {
            $('.doanh_thu_type select').val('');
            $('.doanh_thu_type').hide();
        }else{
            $.ajax({
                data: {type: $(this).val()},
                url: '{$link_get_person}',
                'type': 'get',
                'success': function (data) {
                    $(".doanh_thu_type select").html(data);
                    $(".doanh_thu_type").show();
                }
            });
        }
    });
    
    $('#xuat_doanh_thu').on('click', function(){
        var from_date = $('input[name="profit_order_from_date"]').val(),
        to_date = $('input[name="profit_order_to_date"]').val(),
        type = $('select[name="excel_doanh_thu_type"]').val(),
        person = $('select[name="excel_doanh_thu_person"]').val(),
        url = "{$link_export_doanh_thu}?from_date="+from_date+"&to_date="+to_date+"&type="+type;
        if(type > 0)
            url += "&person="+person;
        $.get(url, function(data){
            if(data){
                window.location.href = url;
            }else{
                krajeeDialog.alert('Dữ liệu xuất báo báo hiện không đầy đủ. Vui lòng thử lại sau.');
                return false;
            }
        });
    });
XP;
$this->registerJs($js, View::POS_END);
?>

<div class="row">
    <div class="col-md-6">
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">Doanh số bán hàng</h3>
                <div class="box-datepicker pull-right">
                    <div class="drp-container">
                        <?php
                        $value_date_range = date("Y-m-1", strtotime("-5 months")) . " - " . date('Y-m-d');
                        $pluginOptions_ = [
                            'startDate' => "moment().subtract(4, 'month').startOf('month')",
                            'endDate' => "moment()",
                            'locale' => ['format' => 'Y-m-d', 'customRangeLabel' => 'Chọn ngày', 'applyLabel' => 'Áp dụng', 'cancelLabel' => 'Thoát'],
                            'ranges' => [
//                                'Hôm nay' => ["moment().startOf('day')", "moment()"],
//                                'Hôm qua' => ["moment().startOf('day').subtract(1,'days')", "moment().endOf('day').subtract(1,'days')"],
//                                '7 ngày qua' => ["moment().startOf('day').subtract(6, 'days')", "moment()"],
//                                '30 ngày qua' => ["moment().startOf('day').subtract(29, 'days')", "moment()"],
                                'Tháng này' => ["moment().startOf('month')", "moment().endOf('month')"],
                                'Tháng trước' => ["moment().subtract(1, 'month').startOf('month')", "moment().subtract(1, 'month').endOf('month')"],
                                '3 tháng qua' => ["moment().subtract(2, 'month').startOf('month')", "moment()"],
                                '6 tháng qua' => ["moment().subtract(5, 'month').startOf('month')", "moment()"],
                            ]
                        ];
                        echo DateRangePicker::widget([
                            'name' => 'doanh_so_date',
                            'presetDropdown' => false,
                            'convertFormat' => true,
                            'hideInput' => true,
                            'startAttribute' => 'doanh_so_from_date',
                            'endAttribute' => 'doanh_so_to_date',
                            'value' => $value_date_range,
                            'pluginOptions' => $pluginOptions_
                        ]);
                        ?>
                    </div>
                </div>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                    <div class="btn-group">
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="box-body chart-responsive">
                <div id="doanh_so_ban_hang" style="height: 300px"></div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">Lợi nhuận</h3>
                <div class="box-datepicker pull-right">
                    <div class="drp-container">
                        <?php
                        echo DateRangePicker::widget([
                            'name' => 'loi_nhuan_date',
                            'presetDropdown' => false,
                            'convertFormat' => true,
                            'hideInput' => true,
                            'value' => $value_date_range,
                            'pluginOptions' => $pluginOptions_,
                            'startAttribute' => 'loi_nhuan_from_date',
                            'endAttribute' => 'loi_nhuan_to_date',
                        ]);
                        ?>
                    </div>
                </div>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                    <div class="btn-group">
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="box-body chart-responsive">
                <div id="bieu_do_loi_nhuan" style="height: 300px"></div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">Đơn hàng</h3>
                <div class="box-datepicker pull-right">
                    <div class="drp-container">
                        <?php
                        echo DateRangePicker::widget([
                            'name' => 'don_hang_date',
                            'presetDropdown' => false,
                            'convertFormat' => true,
                            'hideInput' => true,
                            'value' => $value_date_range,
                            'pluginOptions' => $pluginOptions_,
                            'startAttribute' => 'don_hang_from_date',
                            'endAttribute' => 'don_hang_to_date',
                        ]);
                        ?>
                    </div>
                </div>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                    <div class="btn-group">
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="box-body chart-responsive">
                <div id="bieu_do_don_hang" style="height: 325px"></div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">Doanh thu khách hàng</h3>
                <div class="box-datepicker pull-right">
                    <div class="drp-container">
                        <?php
                        echo DateRangePicker::widget([
                            'name' => 'doanh_thu_date',
                            'presetDropdown' => false,
                            'convertFormat' => true,
                            'hideInput' => true,
                            'value' => $value_date_range,
                            'pluginOptions' => $pluginOptions_,
                            'startAttribute' => 'doanh_thu_from_date',
                            'endAttribute' => 'doanh_thu_to_date',
                        ]);
                        ?>
                    </div>
                </div>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                    <div class="btn-group">
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="box-body chart-responsive">
                <div class="col-md-8 text-left">
                    <label class="control-label">Thống kê theo</label>
                    <?= \yii\helpers\Html::dropDownList('kieu_thong_ke_doanh_thu_khach_hang', 1, [1 => 'Doanh thu cao nhất', 2 => 'Lợi nhuận cao nhất']) ?>
                </div>
                <div class="clearfix"></div>
                <div id="bieu_do_doanh_thu_khach_hang" style="height: 300px"></div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title">Công nợ phải thu - 1 tháng tới</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                    <div class="btn-group">
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="box-body">
                <div class="table-responsive">
                    <table class="table no-margin admintablelist">
                        <thead>
                        <tr>
                            <th>Mã đơn hàng</th>
                            <th>Khách hàng</th>
                            <th>Ngày hẹn thanh toán</th>
                            <th>Chưa thanh toán</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        if (!empty($cong_no_phai_thu)):
                            foreach ($cong_no_phai_thu as $v):
                                ?>
                                <tr>
                                    <td>
                                        <a href="<?= Url::toRoute(['orders/view', 'id' => $v->order_id]) ?>"><?= $v->infoCode ?></a>
                                    </td>
                                    <td><?= !empty($v->customer->name) ? $v->customer->name : '' ?></td>
                                    <td><?= !empty($v->congno->payment_date) ? $v->congno->payment_date : '' ?></td>
                                    <td><?= !empty($v->congno->unpaid) ? $v->congno->unpaid . ' đ' : '' ?></td>
                                </tr>
                                <?php
                            endforeach;
                        else:
                            echo '<tr><td colspan="4">Không có dữ liệu.</td></tr>';
                        endif;
                        ?>
                        </tbody>
                    </table>
                    <!-- /.table-responsive -->
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title">Công nợ phải trả - 1 tháng tới</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                    <div class="btn-group">
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="box-body">
                <div class="table-responsive">
                    <table class="table no-margin admintablelist">
                        <thead>
                        <tr>
                            <th>Mã đơn hàng</th>
                            <th>Tổng chi phí</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        if (!empty($don_hang_sap_thanh_toan)):
                            foreach ($don_hang_sap_thanh_toan as $v):
                                ?>
                                <tr>
                                    <td>
                                        <a href="<?= Url::toRoute(['orders/view', 'id' => $v->order_id]) ?>"><?= $v->infoCode ?></a>
                                    </td>
                                    <td><?= !empty($v->orderdata->tongChiPhi_PhiVanChuyenPlus) ? $v->orderdata->tongChiPhi_PhiVanChuyenPlus . ' đ' : '' ?></td>
                                </tr>
                                <?php
                            endforeach;
                        else:
                            echo '<tr><td colspan="2">Không có dữ liệu.</td></tr>';
                        endif;
                        ?>
                        </tbody>
                    </table>
                    <!-- /.table-responsive -->
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="box box-warning">
            <div class="box-header with-border">
                <h3 class="box-title">Xuất dữ liệu</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                    <div class="btn-group">
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="box-body report_box">
                <div class="col-md-4">
                    <div class="bs-callout bs-callout-primary">
                        <h4>Hoa hồng nhân viên</h4>
                    </div>
                    <a href="<?= Url::toRoute('report/export-hoa-hong-nv') ?>" class="report_export"><i
                                class="fa fa-cloud-download" aria-hidden="true"></i> Xuất dữ liệu</a>
                </div>
                <div class="col-md-4">
                    <div class="bs-callout bs-callout-primary">
                        <h4>Hoa hồng khách hàng</h4>
                    </div>
                    <a href="<?= Url::toRoute('report/export-hoa-hong-kh') ?>" class="report_export"><i
                                class="fa fa-cloud-download" aria-hidden="true"></i> Xuất dữ liệu</a>
                </div>
                <div class="col-md-4">
                    <div class="bs-callout bs-callout-primary">
                        <h4>Danh sách khách hàng</h4>
                    </div>
                    <a href="<?= Url::toRoute('report/export-khach-hang') ?>" class="report_export"><i
                                class="fa fa-cloud-download" aria-hidden="true"></i> Xuất dữ liệu</a>
                </div>
                <div class="col-md-4">
                    <div class="bs-callout bs-callout-primary">
                        <h4>Công nợ</h4>
                    </div>
                    <a href="<?= Url::toRoute('report/export-cong-no') ?>" class="report_export"><i
                                class="fa fa-cloud-download" aria-hidden="true"></i> Xuất dữ liệu</a>
                </div>
                <div class="col-md-4">
                    <div class="bs-callout bs-callout-primary">
                        <h4>Nhà cung cấp</h4>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-8">
                            <?php
                            echo \yii\helpers\Html::dropDownList('excel_ncc', null, \backend\models\Supplier::getGroupOptions(), ['class' => 'form-control', 'prompt' => 'Chọn nhà cung cấp']);
                            ?>
                        </div>
                        <div class="col-md-4 text-right" style="padding-left: 0">
                            <button id="xuat_nha_cung_cap" type="button" class="btn btn-success">Xuất dữ liệu</button>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="bs-callout bs-callout-primary">
                        <h4>Danh sách đơn hàng</h4>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-8">
                            <?php
                            $pluginOptions = [
                                'startDate' => "moment().subtract(4, 'month').startOf('month')",
                                'endDate' => "moment()",
                                'locale' => ['format' => 'Y-m-d', 'customRangeLabel' => 'Chọn ngày', 'applyLabel' => 'Áp dụng', 'cancelLabel' => 'Thoát'],
                                'opens' => 'left',
                                'drops' => 'up',
                                'ranges' => [
                                    'Hôm nay' => ["moment().startOf('day')", "moment()"],
                                    'Hôm qua' => ["moment().startOf('day').subtract(1,'days')", "moment().endOf('day').subtract(1,'days')"],
                                    '7 ngày qua' => ["moment().startOf('day').subtract(6, 'days')", "moment()"],
                                    '30 ngày qua' => ["moment().startOf('day').subtract(29, 'days')", "moment()"],
                                    'Tháng này' => ["moment().startOf('month')", "moment().endOf('month')"],
                                    'Tháng trước' => ["moment().subtract(1, 'month').startOf('month')", "moment().subtract(1, 'month').endOf('month')"],
                                    '3 tháng qua' => ["moment().subtract(2, 'month').startOf('month')", "moment()"],
                                    '6 tháng qua' => ["moment().subtract(5, 'month').startOf('month')", "moment()"],
                                ]
                            ];
                            echo DateRangePicker::widget([
                                'name' => 'report_order_date',
                                'presetDropdown' => false,
                                'convertFormat' => true,
                                'hideInput' => true,
                                'startAttribute' => 'report_order_from_date',
                                'endAttribute' => 'report_order_to_date',
                                'value' => $value_date_range,
                                'pluginOptions' => $pluginOptions,
                            ]);
                            ?>
                        </div>
                        <div class="col-md-4 text-right" style="padding-left: 0">
                            <button id="xuat_don_hang" type="button" class="btn btn-success">Xuất dữ liệu</button>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 form-horizontal">
                    <div class="bs-callout bs-callout-primary">
                        <h4>Doanh thu</h4>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2 control-label text-right">Loại báo cáo</label>
                        <div class="col-md-4" style="margin-bottom: 5px">
                            <?php
                            echo \yii\helpers\Html::dropDownList('excel_doanh_thu_type', null, [0 => 'Tổng hợp kết quả kinh doanh', 'Tổng hợp doanh thu theo khách hàng', 'Tổng hợp doanh thu theo nhân viên'], ['class' => 'form-control'])
                            ?>
                        </div>
                        <div class="col-md-3">
                            <div class="doanh_thu_type" style="display: none">
                                <?php
                                echo \yii\helpers\Html::dropDownList('excel_doanh_thu_person', null, [], ['class' => 'form-control'])
                                ?>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <?php
                            echo DateRangePicker::widget([
                                'name' => 'profit_order_date',
                                'presetDropdown' => false,
                                'convertFormat' => true,
                                'hideInput' => true,
                                'startAttribute' => 'profit_order_from_date',
                                'endAttribute' => 'profit_order_to_date',
                                'value' => $value_date_range,
                                'pluginOptions' => $pluginOptions,
                            ]);
                            ?>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-12 text-right">
                            <button id="xuat_doanh_thu" type="button" class="btn btn-success">Xuất dữ liệu</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>