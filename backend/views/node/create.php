<?php



/* @var $this yii\web\View */
/* @var $model backend\models\Node */

$this->title = 'Tạo mới danh mục quản trị';
$this->params['breadcrumbs'][] = ['label' => 'Nodes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="node-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
