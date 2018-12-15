<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\SourceMessage */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Source Message',
]) . $model->message;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Source Messages'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->message, 'url' => ['update', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="source-message-update">

    <?= $this->render('_form', [
        'model' => $model,
        'languages' => $languages,
        'messages' => $messages
    ]) ?>

</div>
