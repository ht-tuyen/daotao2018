<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\RegionsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Regions');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="log-index">

    <?php
    $gridColumns = [
        ['class' => 'kartik\grid\CheckboxColumn'],
        [
            'attribute' => 'countryId',
            'vAlign'=>'middle',
            'format'=>'raw',
            'filter'=>\backend\models\Countries::getListOptions(),
            'value' => function($model){
                return $model->country ? $model->country->country : '';
            },
            'filterWidgetOptions'=>[
                'pluginOptions'=>['allowClear'=>true],
            ],
        ],
        [
            'attribute' => 'region',
            'vAlign'=>'middle',
            'format'=>'raw',
            'filterWidgetOptions'=>[
                'pluginOptions'=>['allowClear'=>true],
            ],
        ],
        [
            'class' => 'common\components\xPActionColumn',
            'dropdown' => false,
            'vAlign'=>'middle',
            'template' => '{update} {delete}',
        ],
    ];

    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => $gridColumns,
        'toolbar' =>  [
            ['content'=>
                Html::a('<i class="glyphicon glyphicon-plus"></i> Thêm mới', ['create'], ['class'=>'btn btn-success', 'title'=>Yii::t('app', 'Add New')]).' '.
                Html::button('<i class="glyphicon glyphicon-remove-circle"></i> Xóa chọn', ['data-pjax'=>0, 'type'=>'button', 'title'=>Yii::t('app', 'Add New'), 'class'=>'btn btn-danger delete-checked', 'onclick' => 'krajeeDialog.confirm("Bạn có chắc muốn xóa mục này?", function (result) {
                if(result) {
                    jQuery.post(
                        "'.Url::to('regions/delete-multiple').'",
                        {
                            pk : jQuery("#w0").yiiGridView("getSelectedRows")
                        },
                        function () {
                            jQuery.pjax.reload({container:"#w0"});
                        }
                    );
                }
            });']) . ' '.
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
