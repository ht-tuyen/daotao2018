<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use backend\models\Node;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\NodeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Danh sách danh mục quản trị';
$this->params['breadcrumbs'][] = $this->title;
$role = \backend\models\Role::findOne(Yii::$app->user->identity->role_id);
?>
<div class="node-index">

    <?php
    $gridColumns = [
        ['class' => 'kartik\grid\CheckboxColumn'],
        [
            'attribute' => 'node_id',
            'width'=>'80px',
        ],
        [
            'attribute' => 'p_id',
            'filter' => Node::getNodeTreeOptions(),
            'format'=>'raw',
            'value' => function ($model) {
                return $model->parent ? $model->parent->title : '';
            },
        ],
        [
            'attribute' => 'title',
            'value' => function($model){
                return Html::a($model->title, ['update', 'id' => $model->node_id]);
            },
            'format'=>'raw',
        ],
        [
            'attribute'=>'sort_order',
            'width'=>'80px',
            'hAlign'=>'center',
        ],
        [
            'class'=>'kartik\grid\BooleanColumn',
            'attribute'=>'status',
            'vAlign'=>'middle',
            'width'=>'120px',
        ],
        [
            'class' => 'common\components\xPActionColumn',
            'dropdown' => false,
            'vAlign'=>'middle',
            'template' => '{update}' . ($role->admin_use == 1 ? ' {delete}' : ''),
        ],
    ];


    $toolbar = ['content'=>
	                Html::a('<i class="glyphicon glyphicon-repeat"></i> Tải lại & Xóa lọc trang', ['index'], ['data-pjax'=>0, 'class'=>'btn btn-info', 'title'=>Yii::t('app', 'Reset Grid')])
    ];

    if ($role->admin_use == 1) {
	    $toolbar[] = ['content'=>
		                  Html::a('<i class="glyphicon glyphicon-plus"></i> Thêm mới', ['create'], ['class'=>'btn btn-success', 'title'=>Yii::t('app', 'Add New')])
	    ];
	    $toolbar[] = ['content'=>
		                  Html::button('<i class="glyphicon glyphicon-remove-circle"></i> Xóa chọn', ['data-pjax'=>0, 'type'=>'button', 'title'=>Yii::t('app', 'Add New'), 'class'=>'btn btn-danger delete-checked', 'onclick' => 'krajeeDialog.confirm("Bạn có chắc muốn xóa mục này?", function (result) {
                if(result) {
                    jQuery.post(
                        "'.Url::toRoute('node/delete-multiple').'",
                        {
                            pk : jQuery("#w0").yiiGridView("getSelectedRows")
                        },
                        function () {
                            jQuery.pjax.reload({container:"#w0"});
                        }
                    );
                }
            });'])
	    ];
    }

    $toolbar[] = '{toggleData}';

    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => $gridColumns,
        'toolbar' =>  $toolbar,
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
//        'floatHeader' => true,
    ]);
    ?>
</div>
