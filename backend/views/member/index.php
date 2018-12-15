<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use backend\helpers\AcpHelper;
use frontend\models\Order;

$this->title = 'Danh sách Khách hàng';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="users-index">

<style type="text/css">
    .kv-panel-after, .panel-footer, .modal .panel-footer, .modal .kv-panel-after, .modal .panel-heading{
        display: block;
    }
</style>

    <?php

$toolbar = [];


if(AcpHelper::check_role('export')){
    $toolbar[] = ['content'=>
        Html::a('<i class="glyphicon glyphicon-download"></i> Xuất dữ liệu','javascript:;', ['onclick' => "openmodal('/acp/export/khach-hang-popup', 2);return false;", 'class'=>'btn btn-success', 'title'=>Yii::t('app', 'Xuất dữ liệu')])
    ];
}


$toolbar[] = ['content' =>
        Html::a('<i class="glyphicon glyphicon-repeat"></i> Tải lại & Xóa lọc trang', [''], ['data-pjax' => 0, 'class' => 'btn btn-default', 'title' => Yii::t('app', 'Reset Grid')])
    ];

$toolbar[] = ['content'=>
        Html::dropDownList('t', Yii::$app->request->get('t') != NULL ? Yii::$app->request->get('t') : [20 => 20], [20 => 20, 50 => 50, 100 => 100, 200 => 200], [
            'class' => 'page-i btn btn-default',
            'id' => ''
        ])
    ];


    $gridColumns = [
         [            
                'visible' => AcpHelper::check_role('delete-select'),    
                'class' => 'kartik\grid\CheckboxColumn',  
                'width' => '32px', 
                'cssClass' => 'itemcheck',
            ],

        // ['class' => 'kartik\grid\CheckboxColumn'],
        ['class' => 'yii\grid\SerialColumn'],
        // 'user_id',
        [
            'attribute' => 'fullname',
            'value' => function ($model) {
                return Html::a($model->fullname, 'javascript:;', [
                    'onclick' => "openmodal('/acp/member/updatem?id={$model->user_id}','2');return false;",
                ]);
            },
            'format' => 'raw',
        ],

         'username',
         'email',
         'mobile',

         'created_at',

        [
            'class' => 'common\components\xPActionColumn',
            'dropdown' => false,
            'width' => '100px',
            'vAlign' => 'middle',
            'template' => '{viewm} {updatem} {delete}',
            'buttons' => [
                'viewm' => function ($url, $model) {
                    $count = Order::find()->where(['member_id' => $model->id])->count();
                    if(!empty($count)){
                        // return Html::a('<span title="Thông tin đơn hàng" class="text-blue"><i class="glyphicon glyphicon-shopping-cart "></i></span>', 'javascript:;', [
                        //     'onclick' => "openmodal('/acp/member/order?id={$model->user_id}','13');return false;",
                        // ]);
                        return '<a target="_blank" href="/acp/donhang?OrderSearch%5Bmember_id%5D='.$model->user_id.'"><span title="Thông tin đơn hàng" class="text-blue"><i class="glyphicon glyphicon-shopping-cart "></i></span></a>';
                    }else{
                        return '';
                    }

                },
                'updatem' => function ($url, $model) {
                    return Html::a('<span class="text-blue"><i class="glyphicon glyphicon-pencil "></i></span>', 'javascript:;', [
                        'onclick' => "openmodal('/acp/member/updatem?id={$model->user_id}','2');return false;",
                    ]);
                },
            ],
        ],
    ];

    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,

        'panelTemplate' => '<div class="{prefix}{type}">
                            {panelHeading}
                            {panelBefore}                            
                            <div class="col-md-12">
                                {items}
                                <div id="delete-all" class="hide">
                                    <span id="delete-all-btn" data-url="member/delete-select" class="btn btn-danger"><span class="glyphicon glyphicon-trash"></span>Xóa lựa chọn</span>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            {panelAfter}
                            {panelFooter}
                        </div>',


        'columns' => $gridColumns,
        'toolbar' => $toolbar,


        // 'pjax' => true,
        'bordered' => true,
        'striped' => false,
        'condensed' => false,
        'responsive' => true,
        'hover' => true,
        'id' => 'user-lists',
        'showPageSummary' => false,
        'panel' => [
            'type' => GridView::TYPE_INFO
        ],
        'tableOptions' => [
            'class' => 'admintablelist'
        ],
//        'floatHeader' => true,
    ]);
    ?>
</div>
