<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use backend\helpers\AcpHelper;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Danh sách người dùng';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="users-index">
    <style type="text/css">
        .kv-panel-after, .panel-footer {
    display: block;
}
    </style>
    <?php
    $gridColumns = [
        // ['class' => 'kartik\grid\CheckboxColumn'],
        ['class' => 'yii\grid\SerialColumn'],
        // 'user_id',
        [
            'attribute' => 'fullname',
            'value' => function($model){
                return Html::a($model->fullname, 'javascript:;', [
                            'onclick' => "openmodal('/acp/user/updatem?id={$model->user_id}','2');return false;",
                        ]);
            },
            'format'=>'raw',
        ],
        'username',
        // 'email:email',
        // 'phone',
        [
            'attribute' => 'role_id',
            'filter'=> AcpHelper::getRoleOptions(),
            'filterWidgetOptions'=>[
                'pluginOptions'=>['allowClear'=>true],
            ],
            'value' => function($model){
                return AcpHelper::getRoleValue($model->role_id);
            }
        ],
        [
            'class' => 'common\components\xPActionColumn',
            'dropdown' => false,
            'width' => '100px',
            'vAlign'=>'middle',
            'template' => '{updatem} {deletem}',
            'buttons' => [
                
                'updatem' => function ($url, $model) {
                    if($model->role_id == 1) return '';                    
                    return Html::a('<span class="text-blue"><i class="glyphicon glyphicon-pencil "></i></span>', 'javascript:;', [
                        'onclick' => "openmodal('/acp/user/updatem?id={$model->user_id}','2');return false;",
                    ]);                    
                }, 

                'deletem' => function ($url, $model) {
                    if($model->role_id == 1) return '';
                    return Html::a('<span title="Xóa" class="text-blue"><i class="glyphicon glyphicon-trash "></i></span>', 'javascript:;', [
                        'onclick' => "del('/acp/user/deletem?id={$model->user_id}');return false;",
                    ]);                    
                },                   
            ],   
        ],
    ];

    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => $gridColumns,
        'toolbar' =>  [           
            ['content'=>
               Html::a('<i class="glyphicon glyphicon-plus"></i> Thêm mới','javascript:;', ['onclick' => "openmodal('/acp/user/createm','2');return false;",'class'=>'btn btn-success', 'title'=>Yii::t('app', 'Thêm mới')])
            ],
       
            ['content'=>
                Html::a('<i class="glyphicon glyphicon-repeat"></i> Tải lại & Xóa lọc trang', ['index'], ['data-pjax'=>0, 'class'=>'btn btn-default', 'title'=>Yii::t('app', 'Reset Grid')])
            ],
//            '{export}',
            // '{toggleData}'
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
//        'floatHeader' => true,
         'pager' => [
            'firstPageLabel' => '«',
            'lastPageLabel' => '»',

            'nextPageLabel' => '›',
            'prevPageLabel'  => '‹',
            
            'maxButtonCount'=>7, // Số page hiển thị ví dụ: (First  1 2 3 Last)
        ],
    ]);
    ?>
</div>
