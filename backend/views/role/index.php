<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use backend\helpers\AcpHelper;
use yii\widgets\ActiveForm;
use dosamigos\ckeditor\CKEditor;
use yii\widgets\MaskedInput;
use kartik\color\ColorInput;
use kartik\dialog\Dialog;
use backend\assets\CustomAsset;
use kartik\widgets\SwitchInput;
use backend\models\Role;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\RoleSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Danh sách quyền hạn';
$this->params['breadcrumbs'][] = $this->title;

$bundle = CustomAsset::register(Yii::$app->view);
$this->registerCssFile($bundle->baseUrl . '/css/style_roles.css', ['depends' => [backend\assets\CustomAsset::className()]]);
$this->registerJsFile($bundle->baseUrl . '/js/jquery-ui-1.9.2/jquery-ui.min.js', ['depends' => [backend\assets\CustomAsset::className()]]);
$this->registerJsFile($bundle->baseUrl . '/js/roles.js', ['depends' => [backend\assets\CustomAsset::className()]]);
$this->registerJsFile($bundle->baseUrl . '/js/bootstrap-tooltip.js', ['depends' => [backend\assets\CustomAsset::className()]]);
?>
<div class="role-index">
    <div id="w0-pjax" data-pjax-container="" data-pjax-push-state="" data-pjax-timeout="1000">
        <div id="w0" class="grid-view hide-resize" data-krajee-grid="kvGridInit_d44a71a4">
            <div class="panel panel-info">
                <div class="clearfix treeView">
                    <ul>
                        <?php
                        $rootRole = Role::getBaseRole();
                        $string_return = '<ul>
                        <li data-role="1" data-roleid="1">
                            <div class="toolbar-handle"><a href="javascript:;" class="btn btn-inverse draggable droppable ui-draggable ui-droppable">Quản lý</a>
                                <div class="toolbar" title="Add Role" style="display: none;">&nbsp;<a
                                        href="/acp/role/create?parent_roleid=1"
                                        data-url="/acp/role/create?parent_roleid=1"
                                        data-action="modal"><span class="fa fa-plus-circle"></span></a></div>
                            </div>';

                        $string_return .= Role::getChildrenRoleTree($rootRole, 0, $string_return);
                        $string_return .= '</ul>';

                        echo $string_return;
                        ?>
                </div>
            </div>
        </div>
    </div>

</div>
