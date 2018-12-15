<?php

namespace backend\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;

/**
 * This is the model class for table "{{%tintuc}}".
 *
 * @property string $tt_id
 * @property string $tieude
 * @property string $noidung
 * @property string $gioithieu
 * @property string $anhdaidien
 * @property string $slug
 * @property integer $idchuyenmuc
 * @property string $ngaytao
 * @property string $ngaycapnhat
 * @property string $nguoitao
 * @property string $nguoicapnhat
 * @property integer $trangthai
 *
 * @property Chuyenmuc $idchuyenmuc0
 */
class Tintuc extends \yii\db\ActiveRecord
{
    public $uploadfileanhdaidien;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%tintuc}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $settings = Yii::$app->settings;
        $max_size_upload = $settings->get('max_size_upload');
        return [
            [['tieude', 'noidung'], 'required'],
            [['noidung'], 'string'],
            [['idchuyenmuc', 'nguoitao', 'nguoicapnhat', 'trangthai'], 'integer'],
            [['ngaytao', 'ngaycapnhat'], 'safe'],
            [['tieude'], 'string', 'max' => 225],
            [['gioithieu', 'anhdaidien', 'slug'], 'string', 'max' => 255],
            [['idchuyenmuc'], 'exist', 'skipOnError' => true, 'targetClass' => Chuyenmuc::className(), 'targetAttribute' => ['idchuyenmuc' => 'cm_id']],
            [['uploadfileanhdaidien'], 'file','maxSize' => $max_size_upload * 1024 * 1024, 'tooBig' => 'File tối đa '.$max_size_upload.'MB'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'tt_id' => 'Tt ID',
            'tieude' => 'Tiêu đề',
            'noidung' => 'Nội dung',
            'gioithieu' => 'Giới thiệu',
            'anhdaidien' => 'Ảnh đại diện',
            'uploadfileanhdaidien'=>'Ảnh đại diện',
            'slug' => 'Slug',
            'idchuyenmuc' => 'Chuyên mục',
            'ngaytao' => 'Ngaytao',
            'ngaycapnhat' => 'Ngaycapnhat',
            'nguoitao' => 'Nguoitao',
            'nguoicapnhat' => 'Nguoicapnhat',
            'trangthai' => 'Trạng thái',
        ];
    }


    public function attributeLabels_rules_show()
    {
        return [            
            'tieude' => 'Tiêu đề',
            'noidung' => 'Nội dung',
            'gioithieu' => 'Giới thiệu',
            'anhdaidien' => 'Ảnh đại diện',            
            'slug' => 'Đường dẫn',
            'idchuyenmuc' => 'Chuyên mục',            
            'trangthai' => 'Trạng thái',
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
        $row_capnhat = 'Tin tức';            
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



    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }
        date_default_timezone_set('asia/ho_chi_minh');

        
        if(empty($this->ngaytao)){
            $this->ngaytao = date('Y-m-d H:i:s', time());
        }
        
        $this->ngaycapnhat = date('Y-m-d H:i:s', time());
        return true;
    }

     public function afterFind()
    {      

        if (!empty($this->ngaycapnhat)) {
            $this->ngaycapnhat = date("H:i d-m-Y", strtotime($this->ngaycapnhat));            
        }        
     
        parent::afterFind();
    }



    public static function get10Moinhat()
    {
        $moinhat = [];
        $moinhat = Tintuc::find()                        
                        ->orderBy(['tt_id' => SORT_DESC])
                        ->andWhere(['trangthai' => 1])
                        ->limit(10)
                        ->asArray()
                        ->all();
        return $moinhat;
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdchuyenmuc0()
    {
        return $this->hasOne(Chuyenmuc::className(), ['cm_id' => 'idchuyenmuc']);
    }

    public function uploadanhdaidien()
    {
        $model = $this;
        $uploadfile = UploadedFile::getInstance($model, 'uploadfileanhdaidien');

        if (!empty($uploadfile->baseName)) {
            $tenfile = str_replace(' ', '_', $uploadfile->baseName);
            if (empty($model->tt_id)) {

                $model->anhdaidien = '';
                $model->save(false);
            }
            $link = Yii::getAlias('@anyname') . '/uploads/tintuc/' . $model->tt_id . '-' . strtolower($tenfile) . '.' . $uploadfile->extension;

            $uploadfile->saveAs($link);

            return $model->tt_id . '-' . strtolower($tenfile) . '.' . $uploadfile->extension;
        }
        $back = Chuyenmuc::findOne($model->tt_id);
        if ($back) {
            return $back->anhdaidien;
        }
        return '';
    }
    public function getChuyenmuc()
    {
        return $this->hasMany(Chuyenmuc::className(), ['cm_id' => 'idchuyenmuc']);
    }


    public static function getListChuyenmucOptions() {
        $cm = Chuyenmuc::find()->where(['trangthai' => '1'])->orderBy(['thutu' => SORT_ASC])->all();
        if (!empty($cm)) {
            $return = [
                '' => '-- Chọn --',
            ];
            $cm =  ArrayHelper::map($cm, 'cm_id', 'tenchuyenmuc');
            return ($return + $cm);
        }
        return [];
    }

    public static function getChuyenmucLabel($idchuyenmuc = '') {
        $cm = Chuyenmuc::find()->andwhere(['trangthai' => '1'])
                               ->andwhere(['cm_id' => $idchuyenmuc])
                               ->orderBy(['thutu' => SORT_ASC])
                               ->one();
        if (($cm)) {            
            return ($cm->tenchuyenmuc);
        }
        return '';
    }


    public static function getTrangthaiOptions() {        
        return [
            '1' => 'Xuất bản',
            '0' => 'Bản nháp',
        ];
    }

    public static function getTrangthaiLabel($value='')
    {
        $array = self::getTrangthaiOptions();
        if ($value === null || !array_key_exists($value, $array))
            return '';
        return $array[$value];
    }

}
