<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\SourceMessageSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Phrase');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="source-message-index">

    <?php
    $gridColumns = [
        'category',
        'message:ntext',
        [
            'format'=>'raw',
            'hAlign'=>'center',
            'value' => function ($model) {
                return Html::a(\Yii::t('app', 'Translate'), ['update', 'id' => $model->id]);
            }
        ],
        [
            'class' => 'common\components\xPActionColumn',
            'dropdown' => false,
            'vAlign'=>'middle',
            'template' => '{delete}',
        ],
    ];

    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => $gridColumns,
        'toolbar' =>  [
            ['content'=>
                Html::a('<i class="glyphicon glyphicon-plus"></i> Thêm mới', ['create'], ['class'=>'btn btn-success', 'title'=>Yii::t('app', 'Add New')]).' '.
                Html::a('<i class="glyphicon glyphicon-repeat"></i> Tải lại & Xóa lọc trang', ['index'], ['data-pjax'=>0, 'class'=>'btn btn-default', 'title'=>Yii::t('app', 'Reset Grid')])
            ],
            '{toggleData}'
        ],
        'pjax' => true,
        'bordered' => true,
        'striped' => false,
        'condensed' => false,
        'responsive' => true,
        'hover' => true,
        'showPageSummary' => false,
        'panel' => [
            'type' => GridView::TYPE_INFO
        ],
        'pager' => [
            'firstPageLabel' => '<i class="fa fa-angle-double-left fa-lg"></i>',
            'lastPageLabel' => '<i class="fa fa-angle-double-right fa-lg"></i>',
            'nextPageLabel' => '<i class="fa fa-angle-right fa-lg"></i>',
            'prevPageLabel' => '<i class="fa fa-angle-left fa-lg"></i>',
        ],
//        'floatHeader' => true,
    ]);
    ?>
</div>
