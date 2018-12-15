<?php
/**
 * Created by PhpStorm.
 * User: Mr.Phu
 * Date: 23/05/2017
 * Time: 10:13 AM
 */
namespace backend\behaviors;

use Yii;
use yii\base\Behavior;
use yii\db\BaseActiveRecord;
use backend\models\Log;

class AcpARB extends Behavior
{
    public function events()
    {
        return[
            BaseActiveRecord::EVENT_AFTER_INSERT => 'afterInsert',
            BaseActiveRecord::EVENT_AFTER_UPDATE => 'afterSave',
            BaseActiveRecord::EVENT_AFTER_DELETE => 'afterDelete',
            // BaseActiveRecord::EVENT_BEFORE_DELETE => 'beforeDelete',
        ];
    }

    public function afterInsert($insert)
    {
        $model= $insert->sender;
        $logModel = new Log();

        $thaydoi = [];
        foreach ($insert->changedAttributes as $k => $v) {           
            if($v != $model[$k] && !in_array($k,$model->_skip())){
                $thaydoi[] = [
                    'field' => $k,
                    'label' => $model->getAttributeLabel($k),
                    'before' => $v,
                    'after' => $model[$k],
                ];
            }
        }         
        // $actionInfo = 'Thêm mới ID#'. $model->getPrimaryKey();
        $actionInfo = ''.$model->getPrimaryKey();
        $logModel->actionRecord(Log::ACTION_TYPE_CREATE, array(
            'edited' => json_encode($thaydoi),
            'action_info'=> $actionInfo,
            'action_model'=> $model->formName(),
        ) );        
    }

    public function afterSave($insert)
    {
        $model= $insert->sender;
        $logModel = new Log();

        $thaydoi = [];
        foreach ($insert->changedAttributes as $k => $v) {           
            if($v != $model[$k] && !in_array($k,$model->_skip())){
                $thaydoi[] = [
                    'field' => $k,
                    'label' => $model->getAttributeLabel($k),
                    'before' => $v,
                    'after' => $model[$k],
                ];
            }
        } 

        if ( Yii::$app->session->hasFlash($model->tableName(). '_Orig_Attribute') ) {
            $origData= Yii::$app->session->getFlash($model->tableName(). '_Orig_Attribute');
            $newData= $model->attributes;
            $actionInfo = $insert = $modify = '';
            foreach ($newData as $k=> $v) {
                if ( !isset($origData[$k]) ) {

                    $insert .= "{$model->getAttributeLabel($k)},";
                } elseif ($newData[$k]!= $origData[$k]) {

                    $modify .= "{$model->getAttributeLabel($k)},";
                }
            }
            // $actionInfo .= $insert? 'bổ sung:"'. $insert: '"';
            // $actionInfo .= $modify? 'sửa đổi:"'. $modify: '"';

        } else {
            // $actionInfo = 'Cập nhật ID#'. $model->getPrimaryKey();
            $actionInfo = ''.$model->getPrimaryKey();

        }
        $logModel->actionRecord(Log::ACTION_TYPE_UPDATE, array(
            'action_info'=> $actionInfo,
            'action_model'=> $model->formName(),
            'edited' => json_encode($thaydoi),
        ) );
        
    }

    public function afterDelete($event)
    {
        $model= $event->sender;
        $logModel = new Log();

        $thaydoi = [];
        foreach ($model as $k => $v) {           
            if(!in_array($k,$model->_skip())){
                $thaydoi[] = [
                    'field' => $k,
                    'label' => $model->getAttributeLabel($k),
                    'before' => $v,
                    'after' => '',
                ];
            }
        }  
        // echo '<pre>';
        // print_r($thaydoi);
        // die;
        // $actionInfo = 'Xóa ID#'. $model->getPrimaryKey();
        $actionInfo = ''.$model->getPrimaryKey();
        $logModel->actionRecord(Log::ACTION_TYPE_DELETE, array(
            'edited' => json_encode($thaydoi),
            'action_info'=> $actionInfo,
            'action_model'=> $model->formName(),
        ) ); 
    }
}