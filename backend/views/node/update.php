<?php


/* @var $this yii\web\View */
/* @var $model backend\models\Node */

$this->title = 'Cập nhật danh mục: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Danh mục', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['update', 'id' => $model->node_id]];
$this->params['breadcrumbs'][] = 'Cập nhật';
?>
<div class="node-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
