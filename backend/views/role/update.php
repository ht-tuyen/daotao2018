<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Role */

$this->title = 'Cập nhật Quyền: ' . $model->role_label;
$this->params['breadcrumbs'][] = ['label' => 'Quyền hạn', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->role_label, 'url' => ['update', 'id' => $model->role_id]];
$this->params['breadcrumbs'][] = 'Cập nhật';
?>
<div class="role-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
