<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Chuyenmuc */

$this->title = 'Cập nhật chuyên mục';
$this->params['breadcrumbs'][] = ['label' => 'Chuyenmucs', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->cm_id, 'url' => ['view', 'id' => $model->cm_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="chuyenmuc-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
