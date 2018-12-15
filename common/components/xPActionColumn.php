<?php
namespace common\components;

use Yii;
use yii\helpers\ArrayHelper;
use kartik\grid\ActionColumnAsset;
use kartik\grid\ActionColumn;
use yii\helpers\Html;
use yii\helpers\Json;
use backend\models\Role;
use backend\helpers\AcpHelper;

class xPActionColumn extends ActionColumn
{
    protected function initDefaultButtons()
    {
        $roleAcl = Role::getOneAclArray(Yii::$app->user->identity->role_id);
        $this_controller = ucfirst(Yii::$app->controller->id);
        $role = Role::findOne(Yii::$app->user->identity->role_id);

        if (!isset($this->buttons['view']) && AcpHelper::check_role('view')) {
            $this->buttons['view'] = function ($url) {                
                $options = $this->viewOptions;
                $title = Yii::t('kvgrid', 'View');
                $icon = '<span class="text-blue"><i class="glyphicon glyphicon-search"></i></span>';
                $label = ArrayHelper::remove($options, 'label', ($this->_isDropdown ? $icon . ' ' . $title : $icon));
                $options = array_replace_recursive(['title' => $title, 'data-pjax' => '0'], $options);
                if ($this->_isDropdown) {
                    $options['tabindex'] = '-1';
                    return '<li>' . Html::a($label, $url, $options) . '</li>' . PHP_EOL;
                } else {
                    return Html::a($label, $url, $options);
                }
            };
        }
        if (!isset($this->buttons['viewm']) ) {
            $this->buttons['viewm'] = function ($url,$model) {
                if(AcpHelper::check_role('viewm') || AcpHelper::check_own('viewm',$model) ){

                    $options = $this->viewOptions;
                    $title = Yii::t('kvgrid', 'Xem chi tiết');
                    $icon = '<span class="text-blue"><i class="glyphicon glyphicon-search"></i></span>';
                    $label = ArrayHelper::remove($options, 'label', ($this->_isDropdown ? $icon . ' ' . $title : $icon));
                    $options = array_replace_recursive(['title' => $title, 'onclick' => "openmodal('".$url."');return false;"], $options);
                    if ($this->_isDropdown) {
                        $options['tabindex'] = '-1';
                        return '<li>' . Html::a($label,'javascript:;', $options) . '</li>' . PHP_EOL;
                    } else {
                        return Html::a($label,'javascript:;', $options);
                    }
                }
            };
        }

        if (!isset($this->buttons['updatem'])) {
            $this->buttons['updatem'] = function ($url) {
                if(AcpHelper::check_role('updatem')){
                    $options = $this->updateOptions;
                    $title = Yii::t('kvgrid', 'Cập nhật');
                    $icon = '<span class="text-blue"><i class="glyphicon glyphicon-pencil"></i></span>';
                    $label = ArrayHelper::remove($options, 'label', ($this->_isDropdown ? $icon . ' ' . $title : $icon));
                    $options = array_replace_recursive(['title' => $title,'onclick' => "openmodal('".$url."');return false;" ], $options);
                    if ($this->_isDropdown) {
                        $options['tabindex'] = '-1';
                        return '<li>' . Html::a($label,'javascript:;', $options) . '</li>' . PHP_EOL;
                    } else {
                        return Html::a($label,'javascript:;', $options);
                    }
                }
            };
        }
        if (!isset($this->buttons['updatem2']) && AcpHelper::check_role('updatem2')) {
            $this->buttons['updatem2'] = function ($url) {
                $options = $this->updateOptions;
                $title = Yii::t('kvgrid', 'Cập nhật');
                $icon = '<span class="text-blue"><i class="glyphicon glyphicon-pencil"></i></span>';
                $label = ArrayHelper::remove($options, 'label', ($this->_isDropdown ? $icon . ' ' . $title : $icon));
                $options = array_replace_recursive(['title' => $title,'onclick' => "openmodal('".$url."','2');return false;" ], $options);
                if ($this->_isDropdown) {
                    $options['tabindex'] = '-1';
                    return '<li>' . Html::a($label,'javascript:;', $options) . '</li>' . PHP_EOL;
                } else {
                    return Html::a($label,'javascript:;', $options);
                }
            };
        }



        if (!isset($this->buttons['update']) && AcpHelper::check_role('update')) {
            $this->buttons['update'] = function ($url) {
                $options = $this->updateOptions;
                $title = Yii::t('kvgrid', 'Update');
                $icon = '<span class="text-blue"><i class="glyphicon glyphicon-pencil"></i></span>';
                $label = ArrayHelper::remove($options, 'label', ($this->_isDropdown ? $icon . ' ' . $title : $icon));
                $options = array_replace_recursive(['title' => $title, 'data-pjax' => '0'], $options);
                if ($this->_isDropdown) {
                    $options['tabindex'] = '-1';
                    return '<li>' . Html::a($label, $url, $options) . '</li>' . PHP_EOL;
                } else {
                    return Html::a($label, $url, $options);
                }
            };
        }
        if (!isset($this->buttons['delete']) && AcpHelper::check_role('delete')) {
            $this->buttons['delete'] = function ($url) {
                $options = $this->deleteOptions;
                $title = Yii::t('kvgrid', 'Delete');
                $icon = '<span class="text-black"><i class="glyphicon glyphicon-trash"></i></span>';
                $label = ArrayHelper::remove($options, 'label', ($this->_isDropdown ? $icon . ' ' . $title : $icon));
                $msg = ArrayHelper::remove($options, 'message', Yii::t('kvgrid', 'Are you sure to delete this item?'));
                $defaults = ['title' => $title, 'data-pjax' => 'false'];
                $pjax = $this->grid->pjax ? true : false;
                $pjaxContainer = $pjax ? $this->grid->pjaxSettings['options']['id'] : '';
                if ($pjax) {
                    $defaults['data-pjax-container'] = $pjaxContainer;
                }
                $options = array_replace_recursive($defaults, $options);
                $css = $this->grid->options['id'] . '-action-del';
                Html::addCssClass($options, $css);
                $view = $this->grid->getView();
                $delOpts = Json::encode(
                    [
                        'css' => $css,
                        'pjax' => $pjax,
                        'pjaxContainer' => $pjaxContainer,
                        'lib' => ArrayHelper::getValue($this->grid->krajeeDialogSettings, 'libName', 'krajeeDialog'),
                        'msg' => $msg,
                    ]
                );
                ActionColumnAsset::register($view);
                $js = "kvActionDelete({$delOpts});";
                $view->registerJs($js);
                $this->initPjax($js);
                if ($this->_isDropdown) {
                    $options['tabindex'] = '-1';
                    return '<li>' . Html::a($label, $url, $options) . '</li>' . PHP_EOL;
                } else {
                    return Html::a($label, $url, $options);
                }
            };
        }

        if (!isset($this->buttons['deletem']) && AcpHelper::check_role('deletem')) {
            $this->buttons['deletem'] = function ($url) {
                $options = $this->updateOptions;
                $title = Yii::t('kvgrid', 'Xóa');
                $icon = '<span class="text-black"><i class="glyphicon glyphicon-trash"></i></span>';
                $label = ArrayHelper::remove($options, 'label', ($this->_isDropdown ? $icon . ' ' . $title : $icon));
                $options = array_replace_recursive(['title' => $title,'onclick' => "del('".$url."');return false;" ], $options);
                if ($this->_isDropdown) {
                    $options['tabindex'] = '-1';
                    return '<li>' . Html::a($label,'javascript:;', $options) . '</li>' . PHP_EOL;
                } else {
                    return Html::a($label,'javascript:;', $options);
                }
            };
        }

         if (!isset($this->buttons['deletem2']) && AcpHelper::check_role('deletem2')) {
            $this->buttons['deletem2'] = function ($url) {
                $options = $this->updateOptions;
                $title = Yii::t('kvgrid', 'Xóa');
                $icon = '<span class="text-black"><i class="glyphicon glyphicon-trash"></i></span>';
                $label = ArrayHelper::remove($options, 'label', ($this->_isDropdown ? $icon . ' ' . $title : $icon));
                $options = array_replace_recursive(['title' => $title,'onclick' => "del('".$url."','2');return false;" ], $options);
                if ($this->_isDropdown) {
                    $options['tabindex'] = '-1';
                    return '<li>' . Html::a($label,'javascript:;', $options) . '</li>' . PHP_EOL;
                } else {
                    return Html::a($label,'javascript:;', $options);
                }
            };
        }
        
    }
}