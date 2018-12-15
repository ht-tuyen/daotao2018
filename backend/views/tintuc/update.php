<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Tintuc */

$this->title = 'Cập nhật bài viết tin tức';
$this->params['breadcrumbs'][] = ['label' => 'Tintucs', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->tt_id, 'url' => ['view', 'id' => $model->tt_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="tintuc-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
