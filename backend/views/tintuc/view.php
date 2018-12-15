<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\Tintuc */

$this->title = $model->tt_id;
$this->params['breadcrumbs'][] = ['label' => 'Tintucs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tintuc-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->tt_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->tt_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'tt_id',
            'tieude',
            'noidung:ntext',
            'gioithieu',
            'anhdaidien',
            'slug',
            'idchuyenmuc',
            'ngaytao',
            'ngaycapnhat',
            'nguoitao',
            'nguoicapnhat',
            'trangthai',
        ],
    ]) ?>

</div>
