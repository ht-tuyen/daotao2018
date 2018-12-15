<?php
/**
 * Created by PhpStorm.
 * User: Mr.Phu
 * Date: 03/05/2017
 * Time: 10:32 AM
 */

namespace common\components;

use yii\helpers\ArrayHelper;
use yii\widgets\ActiveField;

class xPActiveField extends ActiveField
{
    public $labelOptions = ['class' => 'control-label'];
    public $templates = null;
    public $class_ = null;

    public function init()
    {
        $position = ArrayHelper::remove($this->options, 'right');

        $icon = $this->_setFieldIcon($this->options);

        $this->template = !empty($this->templates) ? $this->templates : '<div class="'.(!empty($this->class_[0]) ? $this->class_[0] : 'col-md-4').'">{label}</div><div class="'.(!empty($this->class_[1]) ? $this->class_[1] : 'col-md-8').' form-control-wrapper' . (!empty($position) ? ' form-control-icon-'.$position : '') . '">{input}' . $icon .
            '<div class="error">{error}{hint}</div></div><div class="clearfix"></div>';

        parent::init();
    }

    /**
     * @param $option array
     * @return string HTML
     */
    private function _setFieldIcon($option)
    {
        $icon = '';
        switch (ArrayHelper::getValue($option, 'icon', '')) {
            case 'text':
                $icon = '<i class="fa fa-text-width"></i>';
                break;
            case 'password':
                $icon = '<i class="fa fa-key" aria-hidden="true"></i>';
                break;
        }

        return $icon;
    }
}