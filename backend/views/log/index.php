<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use backend\models\User;
use backend\models\Log;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\LogSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = \backend\models\Log::getControllerLabel();
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="log-index">
<style type="text/css">
    .kv-panel-after, .panel-footer, .modal .panel-footer, .modal .kv-panel-after, .modal .panel-heading{
        display: block;
    }
</style>

    <?php
    $gridColumns = [
        
        [
            'class' => 'yii\grid\SerialColumn',
             'headerOptions' => [
                'style' => 'width:80px',
            ],   
        ],

         [
            'label' => 'Thời gian',
            'attribute' => 'create_time',
            'width' => '150px',
            'vAlign'=>'middle',
            'format'=>'raw',
            'value' => function ($model) {
                return date('H:i d-m-Y', strtotime($model->create_time));
            },
        ],

        [
            'label' => 'Hoạt động',
            'attribute' => 'action_type',
            'vAlign'=>'middle',
            'format'=>'raw',
            'width'=>'140px',
            'filter'=> Log::getActionOptions(),
            'filterType' => GridView::FILTER_SELECT2,
            'filterWidgetOptions'=>[
                'options' => ['placeholder'=>'-- Chọn --'],
                'pluginOptions'=>['allowClear'=>true],
            ],
            'value' => function ($model) {
                return $model->action_;
            },
        ],
    
         [
            'attribute' => 'action_controller',
            'vAlign'=>'middle',
            'format'=>'raw',            
            'width' => '180px',            
            'filter'=> Log::getControllerOptions(),
            'filterType' => GridView::FILTER_SELECT2,
            'filterWidgetOptions'=>[
                'options' => ['placeholder'=>'-- Chọn --'],
                'pluginOptions'=>['allowClear'=>true],
            ],
            'value' => function ($model) {
                return $model->action_controller;
            },            
        ],


         [
            'attribute' => 'action_model',
            'vAlign'=>'middle',
            'format'=>'raw',            
            'width' => '100px',            
            'filter'=> Log::getModelOptions(),
            'filterType' => GridView::FILTER_SELECT2,
            'filterWidgetOptions'=>[
                'options' => ['placeholder'=>'-- Chọn --'],
                'pluginOptions'=>['allowClear'=>true],
            ],
            'value' => function ($model) {
                return $model->action_model;
            },            
        ],

                
        [
            'attribute' => 'user_id',
            'vAlign'=>'middle',
            'format'=>'raw',
            'width' => '200px',
            'filter'=> Log::getUserOptions(),
            'filterType' => GridView::FILTER_SELECT2,
            'filterWidgetOptions'=>[
                'options' => ['placeholder'=>'-- Chọn --'],
                'pluginOptions'=>['allowClear'=>true],
            ],
            'value' => function ($model) {
                // return $model->user_id;
                return User::getHoten($model->user_id);
            },            
        ],

        [
            'attribute' => 'remote_addr',
            'vAlign'=>'middle',
            'format'=>'raw',
            'value' => function ($model) {
                return long2ip( $model->remote_addr );
            },
            'filter'=> Log::getIpOptions(),
            'filterType' => GridView::FILTER_SELECT2,
            'filterWidgetOptions'=>[
                'options' => ['placeholder'=>'-- Chọn --'],
                'pluginOptions'=>['allowClear'=>true],
            ],
            'width' => '120px',
            'noWrap' => true
        ],

        [
            'label' => 'Tình trạng',
            'attribute' => 'status',
            'vAlign'=>'middle',
            'format'=>'raw',
            'filter'=> Log::getStatusOptions(),
            'filterType' => GridView::FILTER_SELECT2,
            'filterWidgetOptions'=>[
                'options' => ['placeholder'=>'-- Chọn --'],
                'pluginOptions'=>['allowClear'=>true],
            ],            
            'value' => function ($model) {
                if(empty($model->status)) $model->xulynoidungthaydoi();

                if($model->status == 1){
                    return '<i>'.$model->getStatusLabel($model->status).'</i>';
                }else{
                    if($_GET['LogSearch']['status'] != 2){
                        return Html::a('<span class="text-blue"><b>'.$model->getStatusLabel($model->status).'</b></span>', 'javascript:;', [
                            'onclick' => "openmodal('/acp/log/viewm?&id={$model->log_id}','13');return false;",
                        ]);
                    }else{
                        $thaydoi = $model->xulynoidungthaydoi();
                        $html = '';
                        $html .= '<b>'.$thaydoi['row_capnhat'].'</b>';
                        $html .= '<table class="table table-bordered table-striped">';
                        $html .=  $thaydoi['html'];
                        $html .= '</table>';
                        return $html;
                    }
                }
            },
        ],

        [
            'class' => 'common\components\xPActionColumn',
            'dropdown' => false,
            'vAlign'=>'middle',
            'visible' => ($_GET['view'] == 'view'?1:0),
            'template' => '{viewm}',
            'buttons' => [                    
                'viewm' => function ($url, $model){                     
                    return Html::a('<span class="text-blue"><i class="glyphicon glyphicon-search "></i></span>', 'javascript:;', [
                            'onclick' => "openmodal('/acp/log/viewm?&id={$model->log_id}','13');return false;",
                        ]);
                }
            ],
        ],
    ];

    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => $gridColumns,
        'toolbar' =>  [
            ['content'=>               
                Html::a('<i class="glyphicon glyphicon-cloud-upload"></i> Danh sách có thay đổi dữ liệu', ['?LogSearch%5Bstatus%5D=2'], ['data-pjax'=>0, 'class'=>'btn btn-success', 'title'=>'Có thay đổi dữ liệu'])
            ],
            ['content'=>               
                Html::a('<i class="glyphicon glyphicon-align-justify"></i> Danh sách tất cả', ['index'], ['data-pjax'=>0, 'class'=>'btn btn-default', 'title'=> 'Danh sách tất cả'])
            ],
        ],
        // 'pjax' => true,
        'bordered' => true,
        'striped' => false,
        'condensed' => false,
        'responsive' => true,
        'hover' => true,
//        'floatHeader' => true,
        'showPageSummary' => false,
        'panel' => [
            'type' => GridView::TYPE_INFO
        ],

        'rowOptions'=>function($model){
                if($model->action_type == 2){
                    return ['class' => 'success'];
                }
                elseif($model->action_type == 3){
                    
                }
                elseif($model->action_type == 4){
                    return ['class' => 'danger'];
                }
        },
        

        'pager' => [
            'firstPageLabel' => '«',
            'lastPageLabel' => '»',

            'nextPageLabel' => '›',
            'prevPageLabel'  => '‹',
            
            'maxButtonCount'=>7, // Số page hiển thị ví dụ: (First  1 2 3 Last)
        ],


    ]);

    ?>
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
