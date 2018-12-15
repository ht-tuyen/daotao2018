<?php

use backend\helpers\AcpHelper;
// use backend\assets\CustomAsset;
use yii\web\View;
// use backend\models\Orders;
use yii\helpers\Url;

$this->title = 'Viện Tiêu chuẩn Chất lượng Việt Nam';
$this->params['breadcrumbs'][] = $this->title;
$asset = backend\assets\AppAsset::register($this);
$baseUrl = $asset->baseUrl;

// $bundle = CustomAsset::register(Yii::$app->view);
// $this->registerJsFile($bundle->baseUrl . '/js/dashboard.js', ['depends' => [CustomAsset::className()]]);

$html_chart = [];
$html_ul = [];
$arr_color = [
    0 => ['color' => '#00c0ef', 'text' => 'text-aqua'],
    1 => ['color' => '#dd4b39', 'text' => 'text-red'],
    2 => ['color' => '#f56954', 'text' => 'text-yellow'],
    3 => ['color' => '#0073b7', 'text' => 'text-blue'],
    4 => ['color' => '#00a65a', 'text' => 'text-green'],
    5 => ['color' => '#d2d6de', 'text' => 'text-gray'],
    6 => ['color' => '#39cccc', 'text' => 'text-teal'],
    7 => ['color' => '#3d9970', 'text' => 'text-olive'],
    8 => ['color' => '#01ff70', 'text' => 'text-lime'],
    9 => ['color' => '#ff851b', 'text' => 'text-orange'],
    10 => ['color' => '#f012be', 'text' => 'text-fuchsia'],
    11 => ['color' => '#605ca8', 'text' => 'text-purple'],
    12 => ['color' => '#d81b60', 'text' => 'text-maroon'],
    13 => ['color' => '#7a869d', 'text' => 'text-muted'],
    14 => ['color' => '#3c8dbc', 'text' => 'text-light-blue'],
    15 => ['color' => '#001f3f', 'text' => 'text-navy'],
    16 => ['color' => '#111111', 'text' => 'text-black'],
];
if (!empty($orders)):
    $i = -1;
    foreach ($orders as $order):
        if (!empty($order->products)): $i++;
            $html_chart[] = [
                'value' => $order->total,
                'color' => $arr_color[$i]['color'],
                'highlight' => $arr_color[$i]['color'],
                'label' => $order->products->title
            ];
            $html_ul[] = "<li><i class=\"fa fa-circle-o {$arr_color[$i]['text']}\"></i> {$order->products->title} ({$order->total})</li>";
        endif;
    endforeach;
endif;
$echo_html_chart = json_encode($html_chart);

$google_data_table = [];

if (!empty($arr_month_data)) {
    foreach ($arr_month_data as $month => $subtotal) {
        $google_data_table[] = [
            'y' => "{$month}",
            'item' => AcpHelper::numberFormat($subtotal / 1000000000, 2),
        ];
    }
}

$html_area = json_encode($google_data_table, JSON_UNESCAPED_UNICODE);

$js = <<<XP
var PieData = {$echo_html_chart};

var area = new Morris.Area({
    element   : 'revenue-chart',    resize    : true,redraw: true,
    data      : {$html_area},
    xkey      : 'y',
    ykeys     : ['item'],
    labels    : ['Tổng doanh thu'],
    lineColors: ['#3c8dbc'],
    hideHover : 'auto',
    units: ' tỷ'
});
XP;
$this->registerJs($js, View::POS_END);
?>
<!-- Info boxes -->
<div class="row">
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
            <span class="info-box-icon bg-aqua"><i class="ion ion-clipboard"></i></span>

            <div class="info-box-content">
                <span class="info-box-text">Đơn hàng trong tháng</span>
                <span class="info-box-number"></span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <!-- /.col -->
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
            <span class="info-box-icon bg-yellow"><i class="fa ion-ios-people-outline"></i></span>

            <div class="info-box-content">
                <span class="info-box-text">Khách hàng trong tháng</span>
                <span class="info-box-number"></span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <!-- /.col -->

    <!-- fix for small devices only -->
    <div class="clearfix visible-sm-block"></div>

    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
            <span class="info-box-icon bg-green"><i class="ion ion-social-usd-outline"></i></span>

            <div class="info-box-content">
                <span class="info-box-text">Doanh thu trong tháng</span>
                <span class="info-box-number"></span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <!-- /.col -->
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
            <span class="info-box-icon bg-red"><i class="ion ion-cash"></i></span>

            <div class="info-box-content">
                <span class="info-box-text">Công nợ tồn đọng</span>
                <span class="info-box-number"></span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <!-- /.col -->
</div>
<!-- /.row -->

<div class="row">
    <div class="col-md-6">
        <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title">Đơn hàng sắp đến hạn giao - trong 7 ngày tới</h3>
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
                            <th>Sản phẩm</th>
                            <th>Thời gian</th>
                        </tr>
                        </thead>
                        <tbody>
                       
                        </tbody>
                    </table>
                    <!-- /.table-responsive -->
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title">Đơn hàng sắp đến hạn thanh toán - trong 7 ngày tới</h3>
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
                            <th>Sản phẩm</th>
                            <th>Ngày hẹn</th>
                        </tr>
                        </thead>
                        <tbody>
                       
                        </tbody>
                    </table>
                    <!-- /.table-responsive -->
                </div>
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
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">Doanh thu 6 tháng gần nhất</h3>

                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                    <div class="btn-group">
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i>
                        </button>
                    </div>
                </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div class="row">
                    <div class="col-md-8">
                        <div id="revenue-chart" style="position: relative; height: 300px;"></div>
                        <!-- /.chart-responsive -->
                    </div>
                    <!-- /.col -->
                    <div class="col-md-4">
                        <p>
                            <strong>Tình trạng đơn hàng</strong>
                        </p>
                       
                        <!-- /.progress-group -->
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
            </div>
            <!-- ./box-body -->
            <!-- /.box -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
</div>
<!-- Main row -->
<div class="row">
    <!-- Left col -->
    <div class="col-md-8">

        <!-- TABLE: LATEST ORDERS -->
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">Đơn hàng mới nhất</h3>

                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                class="fa fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i>
                    </button>
                </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div class="table-responsive">
                    <table class="table no-margin admintablelist">
                        <thead>
                        <tr>
                            <th>Mã đơn hàng</th>
                            <th>Tên đơn hàng</th>
                            <th class="text-center">Trạng thái đơn hàng</th>
                            <th class="text-right">Tổng giá trị</th>
                        </tr>
                        </thead>
                        <tbody>
                       
                        </tbody>
                    </table>
                </div>
                <!-- /.table-responsive -->
            </div>
            <!-- /.box-body -->
            <div class="box-footer clearfix">
                <a href="<?php //Url::toRoute('orders/create') ?>" class="btn btn-sm btn-info btn-flat pull-left">Tạo
                    đơn hàng mới</a>
                <a href="<?php //Url::toRoute('orders/index') ?>" class="btn btn-sm btn-default btn-flat pull-right">Quản
                    lý danh sách đơn hàng</a>
            </div>
            <!-- /.box-footer -->
        </div>
        <!-- /.box -->
    </div>
    <!-- /.col -->

    <div class="col-md-4">

        <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title">Thống kê sản phẩm</h3>

                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                class="fa fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i>
                    </button>
                </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div class="row">
                    <div class="col-md-7">
                        <div class="chart-responsive">
                            <canvas id="pieChart" height="150"></canvas>
                        </div>
                        <!-- ./chart-responsive -->
                    </div>
                    <!-- /.col -->
                    <div class="col-md-5">
                        <ul class="chart-legend clearfix">
                            <?= !empty($html_ul) ? implode("\n", $html_ul) : ''; ?>
                        </ul>
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
            </div>
            <!-- /.box-body -->
            <!-- /.footer -->
        </div>
        <!-- /.box -->

    </div>
    <!-- /.col -->
</div>
<!-- /.row -->
<style>
    .info-box-text {
        text-transform: none;
        font-weight: bold
    }
</style>