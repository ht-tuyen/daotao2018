<?php

/* @var $this yii\web\View */
/* @var $model backend\models\SourceMessage */

$this->title = Yii::t('app', 'Create Source Message');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Source Messages'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="source-message-create">

    <?= $this->render('_form', [
        'model' => $model,
        'languages' => $languages
    ]) ?>

</div>
