<?php
/**
 * Created by PhpStorm.
 * User: Mr.Phu
 * Date: 01/08/2017
 * Time: 8:40 AM
 */
namespace backend\behaviors;

use Yii;
use yii\base\Behavior;
use backend\helpers\AcpHelper;

class xP_PkRandom extends Behavior
{
    public function beforeSave($event) {
        $model = $event->sender;
        if ($model->isNewRecord) {
            if (empty($model->primaryKey))
                $model->{$model->getTableSchema()->primaryKey[0]} = AcpHelper::getDataTableID($model);
        }
        return $model;
    }
}