<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use backend\helpers\AcpHelper;
use backend\models\Chuyenmuc;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Danh sách chuyên mục';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="users-index">

    <?php
    $gridColumns = [
           [            
                'visible' => AcpHelper::check_role('delete-select'),    
                'class' => 'kartik\grid\CheckboxColumn',  
                'width' => '32px', 
                'cssClass' => 'itemcheck',
            ],
        
        // 'user_id',
        [
            'attribute' => 'tenchuyenmuc',
            'value' => function ($model) {
                return (AcpHelper::check_role('updatem','Chuyenmuc')?Html::a($model->tenchuyenmuc, 'javascript:;', [
                    'onclick' => "openmodal('/acp/chuyenmuc/updatem?id={$model->cm_id}','2');return false;",
                ]):Html::a($model->tenchuyenmuc, 'javascript:;'));
            },
            'format' => 'raw',
        ],
        // 'slug',
        // 'gioithieu',

        [
            'attribute' => 'trangthai',
            'value' => function ($model) {
                return $model->trangthai == 1 ? "Hiển thị" : "Không hiển thị";
            },
             'filter' => Chuyenmuc::getTrangthaiOptions(),
            'filterType' => GridView::FILTER_SELECT2,
            'filterWidgetOptions' => [
                'options' => ['placeholder'=>'-- Chọn --'],
                'pluginOptions' => ['allowClear' => true],
            ], 
            'format' => 'raw',
        ],
        [
            'class' => 'common\components\xPActionColumn',
            'dropdown' => false,
            'width' => '100px',
            'vAlign' => 'middle',
            'template' => '{updatem} {deletem}',
            'buttons' => [

                'updatem' => function ($url, $model) {                    
                    return (AcpHelper::check_role('updatem','Chuyenmuc')?Html::a('<span class="text-blue"><i class="glyphicon glyphicon-pencil "></i></span>', 'javascript:;', [
                        'onclick' => "openmodal('/acp/chuyenmuc/updatem?id={$model->cm_id}','2');return false;",
                    ]):'');
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
                                    <span id="delete-all-btn" data-url="chuyenmuc/delete-select" class="btn btn-danger"><span class="glyphicon glyphicon-trash"></span>Xóa lựa chọn</span>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            {panelAfter}
                            {panelFooter}
                        </div>',

        'columns' => $gridColumns,
        'toolbar' => [
            ['content' =>
                (AcpHelper::check_role('createm','Chuyenmuc')?Html::a('<i class="glyphicon glyphicon-plus"></i> Thêm mới', 'javascript:;', ['onclick' => "openmodal('/acp/chuyenmuc/createm','2');return false;", 'class' => 'btn btn-success', 'title' => Yii::t('app', 'Thêm mới')]):'' )
            ],
            ['content' =>
                Html::a('<i class="glyphicon glyphicon-repeat"></i> Tải lại & Xóa lọc trang', ['index'], ['data-pjax' => 0, 'class' => 'btn btn-default', 'title' => Yii::t('app', 'Reset Grid')])
            ],
           
        ],
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
    ]);
    ?>
</div>
