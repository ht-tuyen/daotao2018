<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Regions */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Regions',
]) . $model->regionId;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Regions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->regionId, 'url' => ['update', 'id' => $model->regionId]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="regions-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
