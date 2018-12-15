<?php
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use yii\web\View;
use frontend\models\Order;
use backend\models\Countries;
use backend\models\Regions;

use backend\helpers\AcpHelper;

$toolbar = [];


if(AcpHelper::check_role('export_donhang')){
    $toolbar[] = ['content'=>
        Html::a('<i class="glyphicon glyphicon-download"></i> Xuất dữ liệu','javascript:;', ['onclick' => "openmodal('/acp/export/don-hang-popup', 2);return false;", 'class'=>'btn btn-success', 'title'=>Yii::t('app', 'Xuất dữ liệu')])
    ];
}

$toolbar[] = ['content'=>
        Html::a('<i class="glyphicon glyphicon-repeat"></i> Tải lại & Xóa lọc trang', ['list-order'], ['data-pjax'=>0, 'class'=>'btn btn-default', 'title'=>Yii::t('app', 'Tải lại & Xóa lọc trang')])
];

$toolbar[] = ['content'=>
    Html::dropDownList('t', Yii::$app->request->get('t') != NULL ? Yii::$app->request->get('t') : [20 => 20], [20 => 20, 50 => 50, 100 => 100, 200 => 200], [
        'class' => 'page-i btn btn-default',
        'id' => ''
    ])
];

$buttons = [];
// if(AcpHelper::check_role('updatem')){
    $buttons['update'] = function ($url, $model) {
        return Html::a('<span class="text-blue"><i class="glyphicon glyphicon-pencil "></i></span>', 'javascript:;', [
            'onclick' => "openmodal('/acp/member/order-view?id={$model->order_id}','13');return false;",
        ]);
    };
    $buttons['deletem'] = function ($url, $model) {
        return Html::a('<span class="text-blue"><i class="glyphicon glyphicon-trash "></i></span>', 'javascript:;', [
            'onclick' => "del('/acp/member/order-delete?id={$model->order_id}');return false;",
        ]);
    };
// }

$this->title = Yii::t('app', 'Danh sách đơn hàng');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="listorder-index">
  <style type="text/css">
      .kv-panel-after, .panel-footer{
        display: block;
      }
  </style> 
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,

        'panelTemplate' => '<div class="{prefix}{type}">
                            {panelHeading}
                            {panelBefore}                            
                            <div class="col-md-12">
                                {items}
                                <div id="delete-all" class="hide">
                                    <span id="delete-all-btn" data-url="member/delete-order-select" class="btn btn-danger"><span class="glyphicon glyphicon-trash"></span>Xóa lựa chọn</span>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            {panelAfter}
                            {panelFooter}
                        </div>',

        'bordered' => true,
        'striped' => false,
        'condensed' => false,
        'responsive' => true,
        'hover' => true,
        // 'pjax' => true,
        'panel' => [
            'type' => GridView::TYPE_INFO,
        ],
        'tableOptions' => [
            'class' => 'admintablelist'
        ],
        'rowOptions' => function ($model, $key, $index, $grid) {            
            return ['class' => Order::getTinhtrangClass($model->tinhtrang)];
        },
        'columns' => [
             [            
                'visible' => AcpHelper::check_role('delete-order-select'),    
                'class' => 'kartik\grid\CheckboxColumn',  
                'width' => '32px', 
                'cssClass' => 'itemcheck',
            ],

            ['class' => 'yii\grid\SerialColumn'],

             [
                'label' => 'Đơn hàng',
                'attribute' => 'item',
                'value' => function($model){
                    $html_name = '';
                    $thanh_tien = 0;
                    $decode = json_decode($model->item, true);
                    if(is_array($decode)) foreach ($decode as $v => $k) {
                        // $tieuchuan = \backend\models\Tieuchuan::findOne(['tc_id' => $v]);
                        // if($tieuchuan){
                        //     $html_name .= '<a href="javascript:;" title="Xem chi tiết" onclick="openmodal(\'/acp/tieuchuan/viewm?id='.$tieuchuan->tc_id.'\');return false;">'.$tieuchuan->sohieu . '</a>, ';                            
                        // }                        
                    }      
                    $madonhang = 'ID:'.$model->order_id;              
                    return $madonhang. "<br/>" .substr($html_name, 0, -2);
                },                
                'format'=>'raw',
            ],


            [
                'label' => 'Tên khách hàng',
                'attribute' => 'hoten',
                'value' => function($model){               
                    if(empty($model->member_id)){
                        return Order::getXungdanhLabel($model->anh_chi) .' '. Html::encode($model->hoten);
                    }else{
                        return '<a  href="javascript:;" onclick="openmodal(\'/acp/member/updatem?id='.$model->member_id.'\',\'2\');return false;">'.Order::getXungdanhLabel($model->anh_chi) .' '. Html::encode($model->hoten).'</a>';
                    }

                },                
                'format'=>'raw',
            ],

            [
                'label' => 'Điện thoại',
                'attribute' => 'sdt',
                'value' => function($model){               
                    return $model->sdt;
                },                
                'format'=>'raw',
            ],


            [
                'label' => 'Địa chỉ nhận hàng',
                'attribute' => 'sonha',
                'value' => function($model){ 
                    $country = Countries::findOne($model->country_id);
                    $province = Regions::getListLabel($order->province);
                    return $model->sonha . (!empty($province) && $province != ' - ' ? " - " . $province : "") . (!empty($country) ? " - " . $country->title : "");
                },                
                'format'=>'raw',
            ],


            [
                'label' => 'Thành tiền',
                'attribute' => 'total',
                'value' => function($model){ 
                    // $thanh_tien = 0;
                    // $decode = json_decode($model->item, true);
                    // if(is_array($decode)) foreach ($decode as $v => $k) {  
                    //     if(!empty($k['soluong']) && !empty($k['dongia'])) $thanh_tien +=  ($k['soluong'] * $k['dongia']);
                    // }
                    // return number_format($thanh_tien). ' (đ)';
                    return number_format($model->total). ' (đ)';
                },                
                'format'=>'raw',
            ],

            
            [
                'label' => 'Thời gian giao hàng',
                'attribute' => 'time',
                'value' => function($model){ 
                    return empty($model->time)?'':date('d-m-Y', strtotime($model->time));
                },                
                'format'=>'raw',
            ],
           

             [
                'label' => 'Ngày tạo đơn hàng',
                'attribute' => 'create_time',
                'value' => function($model){ 
                    return empty($model->create_time)?'':date('d-m-Y H:i', strtotime($model->create_time));                    
                },                
                'format'=>'raw',
            ],

            [
                // 'label' => 'Ngày tạo đơn hàng',
                'attribute' => 'tinhtrang',
                'value' => function($model){ 
                    return Order::getTinhtrangLabel($model->tinhtrang);
                },                
                'format'=>'raw',
                'filter' => Order::getTinhtrangOptions(),
                'filterType' => GridView::FILTER_SELECT2,
                'filterWidgetOptions' => [
                    'options' => ['placeholder'=>'-- Chọn --'],
                    'pluginOptions' => ['allowClear' => true],
                ],
            ],


            [
                'label' => 'Khách hàng có đăng ký',
                'attribute' => 'member_id',
                'value' => function($model){
                    return '<a  href="javascript:;" onclick="openmodal(\'/acp/member/updatem?id='.$model->member_id.'\',\'2\');return false;">'.Order::getMemberLabel($model->member_id).'</a>';
                },                
                'format'=>'raw',
                'filter' => Order::getMemberOptions(),
                'filterType' => GridView::FILTER_SELECT2,
                'filterWidgetOptions' => [
                    'options' => ['placeholder'=>'-- Chọn --'],
                    'pluginOptions' => ['allowClear' => true],
                ],
            ],
           
           
             [
                'class' => 'common\components\xPActionColumn',
                'width'=>'100px',
                'template' => '{update} {deletem}',
                'buttons' => $buttons,
            ],
        ],
        'toolbar' =>  $toolbar,

         'pager' => [
            'firstPageLabel' => '«',
            'lastPageLabel' => '»',

            'nextPageLabel' => '›',
            'prevPageLabel'  => '‹',
            
            'maxButtonCount'=>7, // Số page hiển thị ví dụ: (First  1 2 3 Last)
        ],

    ]); ?>


<div class="custom-page pull-right">
<?= Html::dropDownList('t', Yii::$app->request->get('t') != NULL ? Yii::$app->request->get('t') : [20 => 20], [20 => 20, 50 => 50, 100 => 100, 200 => 200], [    
        'class' => 'page-i btn btn-default',
        'id' => ''
    ])
 ?>
 </div>
<?php
$script = <<< XP
     $('.custom-page').prependTo($('.panel-footer'))     
XP;
$this->registerJs($script);
?>
   
</div>
