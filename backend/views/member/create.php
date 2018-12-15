<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\User */

$this->title = 'Thêm người dùng';
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-create">
	<h1><?= $this->title?></h1>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
