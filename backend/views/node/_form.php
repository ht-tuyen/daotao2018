<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\models\Node;
use backend\models\Role;
use backend\helpers\AcpHelper;

/* @var $this yii\web\View */
/* @var $model backend\models\Node */
/* @var $form yii\widgets\ActiveForm */
$role = Role::findOne(Yii::$app->user->identity->role_id);
?>

<div class="node-form">

    <?php $form = ActiveForm::begin(['fieldClass' => 'common\components\xPActiveField',]); ?>
    <div class="panel panel-info">

        <div class="panel-heading"><?php echo \Yii::t('app', 'Information') ?></div>
        <div class="panel-body">
            <?php
            echo $form->field($model, 'title')->textInput(['maxlength' => true]);
            echo $form->field($model, 'roles')->widget(\kartik\widgets\Select2::classname(), [
	            'data' => Role::getRoleTreeOptions(),
	            'options' => ['placeholder' => 'Chọn nhóm quản trị'],
	            'pluginOptions' => [
		            'allowClear' => true,
		            'multiple' => true,
	            ],
            ]);

            echo $form->field($model, 'p_id')->dropDownList(Node::getNodeTreeOptions(), ['prompt'=>'Không chọn']);

            if ($role->admin_use == 1 ) {
	            echo $form->field($model, 'code')->textInput(['maxlength' => true]);
	            $exceptionControllers = $model->getRemainControllers($model->controller);
	            $array_ = AcpHelper::getControllerOptions(true, $exceptionControllers);
	            echo $form->field($model, 'controller')->dropDownList($array_, ['prompt'=>'-----']);
	            echo $form->field($model, 'url')->textInput(['maxlength' => true]);
            } else {
	            echo $form->field($model, 'code')->hiddenInput(['maxlength' => true])->label(false);
	            $exceptionControllers = $model->getRemainControllers($model->controller);
	            $array_ = AcpHelper::getControllerOptions(true, $exceptionControllers);
	            echo $form->field($model, 'controller')->hiddenInput()->label(false);
	            echo $form->field($model, 'url')->hiddenInput(['maxlength' => true])->label(false);
            }

            echo $form->field($model, 'class_name')->textInput(['maxlength' => true]);
            echo $form->field($model, 'sort_order')->textInput(['maxlength' => true]);
            if($model->isNewRecord)
                $model->status = 1;
            echo $form->field($model, 'status')->checkbox(['label'=>''])->label(Yii::t('app', 'Status')); ;
            ?>

            <div class="form-group text-right">
                <div class="col-md-12">
                    <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>

    </div>
    <?php ActiveForm::end(); ?>

</div>
