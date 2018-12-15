<?php

namespace backend\models;

use Yii;
use yii\web\UploadedFile;

/**
 * This is the model class for table "{{%chuyenmuc}}".
 *
 * @property integer $cm_id
 * @property string $tenchuyenmuc
 * @property string $slug
 * @property string $ngaytao
 * @property string $ngaycapnhat
 * @property string $nguoitao
 * @property string $nguoicapnhat
 * @property string $anhdaidien
 * @property string $gioithieu
 * @property integer $trangthai
 * @property integer $thutu
 *
 * @property Tintuc[] $tintucs
 */
class Chuyenmuc extends \yii\db\ActiveRecord
{

    public $uploadfileanhdaidien;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%chuyenmuc}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $settings = Yii::$app->settings;
        $max_size_upload = $settings->get('max_size_upload');
        return [
            [['tenchuyenmuc'], 'required'],
            [['ngaytao', 'ngaycapnhat'], 'safe'],
            [['nguoitao', 'nguoicapnhat', 'trangthai', 'thutu'], 'integer'],
            [['tenchuyenmuc', 'slug', 'anhdaidien', 'gioithieu'], 'string', 'max' => 255],
            [['uploadfileanhdaidien'], 'file','maxSize' => $max_size_upload * 1024 * 1024, 'tooBig' => 'File tối đa '.$max_size_upload.'MB'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cm_id' => 'Cm ID',
            'tenchuyenmuc' => 'Tên chuyên mục',
            'slug' => 'Slug',
            'ngaytao' => 'Ngày tạo',
            'ngaycapnhat' => 'Ngày cập nhật',
            'nguoitao' => 'Người tạo',
            'nguoicapnhat' => 'Người cập nhật',
            'anhdaidien' => 'Ảnh đại diện',
            'gioithieu' => 'Giới thiệu',
            'trangthai' => 'Trạng thái',
            'thutu' => 'Thứ tự',
            'uploadfileanhdaidien'=>'Ảnh đại diện'
        ];
    }


    public function attributeLabels_rules_show()
    {
        return [                        
            'tenchuyenmuc' => 'Tên chuyên mục',
            'slug' => 'Đường dẫn',            
            // 'anhdaidien' => 'Ảnh đại diện',
            // 'gioithieu' => 'Giới thiệu',
            'trangthai' => 'Trạng thái',
            'thutu' => 'Thứ tự',
            // 'uploadfileanhdaidien'=>'Ảnh đại diện'
        ];
    }

   
     public function behaviors()
    {
        return [
            [
                'class' => 'backend\behaviors\AcpARB',
            ],           
        ];       
    }


     public function _skip()
    {
        //Trả về danh sách các field bỏ qua, ko cần so sánh
        return [                        
            
        ];
    }

    public function _change($data = '', $id = '')
    {        
        // $ics = Ics::findone(['ics_id' => $id]);
        $row_capnhat = '';
        // if($ics){
        $row_capnhat = 'Chuyên mục';            
        // }

        $html_before = $data['before'];
        $html_after = $data['after'];
        switch ($data['field']) {           
            default:                
                break;
        }

        $return = [];
        if(!empty($html_after) || !empty($html_before)){
            $return['html_before'] = $html_before;
            $return['html_after'] = $html_after; 
            $return['row_capnhat'] = $row_capnhat;           
        }        

        return $return;
    }



    public function getTintucs()
    {
        return $this->hasMany(Tintuc::className(), ['idchuyenmuc' => 'cm_id']);
    }


     public static function getTrangthaiOptions() {        
        return [
            '1' => 'Hiển thị',
            '0' => 'Không hiển thị',
        ];
    }

    public static function getTrangthaiLabel($value='')
    {
        $array = self::getTrangthaiOptions();
        if ($value === null || !array_key_exists($value, $array))
            return '';
        return $array[$value];
    }



    public function uploadanhdaidien()
    {
        $model = $this;
        $uploadfile = UploadedFile::getInstance($model, 'uploadfileanhdaidien');

        if (!empty($uploadfile->baseName)) {
            $tenfile = str_replace(' ', '_', $uploadfile->baseName);
            if (empty($model->cm_id)) {

                $model->anhdaidien = '';
                $model->save(false);
            }
            $link = 'filedinhkem/cm/' . $model->cm_id . '-' . strtolower($tenfile) . '.' . $uploadfile->extension;

            $uploadfile->saveAs($link);

            return $link;
        }
        $back = Chuyenmuc::findOne($model->cm_id);
        if ($back) {
            return $back->anhdaidien;
        }
        return '';
    }
}
