<?php

use yii\helpers\Html;


$this->title = 'Cập nhật thông tin khách hàng';
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->fullname, 'url' => ['update', 'id' => $model->user_id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="user-update">
	<h1><?= $this->title?></h1>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
