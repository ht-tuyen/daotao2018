<?php
use yii\helpers\Html;
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

<style>
    .info-box-text {
        text-transform: none;
        font-weight: bold
    }
</style>

<?php
    // Màn hình chính của thành viên BKT
    echo Yii::$app->controller->renderPartial('../site/thanhvien', [
        'tuyen' => 'Tuyền',
    ]);



