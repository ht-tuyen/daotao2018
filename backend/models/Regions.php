<?php

namespace backend\models;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%regions}}".
 *
 * @property integer $regionId
 * @property integer $countryId
 * @property string $region
 * @property string $region_url
 * @property string $code
 * @property string $ADM1Code
 * @property integer $user_edit
 * @property integer $status
 * @property integer $sort_order
 */
class Regions extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%regions}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['countryId', 'region'], 'required'],
            [['countryId', 'user_edit', 'status', 'sort_order'], 'integer'],
            [['region', 'region_url', 'code', 'ADM1Code'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'regionId' => Yii::t('app', 'ID'),
            'countryId' => Yii::t('app', 'Country'),
            'region' => Yii::t('app', 'Region'),
            'region_url' => Yii::t('app', 'Region Url'),
            'code' => Yii::t('app', 'Code'),
            'ADM1Code' => Yii::t('app', 'Adm1 Code'),
            'user_edit' => Yii::t('app', 'User Edit'),
            'status' => Yii::t('app', 'Status'),
            'sort_order' => Yii::t('app', 'Sort Order'),
        ];
    }

    public function getCountry(){
        return $this->hasOne(Countries::className(), ['countryId' => 'countryId']);
    }

    public static function getListOptions() {
        $regions = self::find()->where(['countryId' => '260'])->orderBy(['sort_order' => SORT_ASC, 'region' => SORT_ASC])->all();
        if (!empty($regions)) {
            $return = [
                '' => '-- Chọn --',
            ];  
            $regions =  ArrayHelper::map($regions, 'regionId', 'region');
            return ($return + $regions);
        }
        return [];
    }

     public static function getListLabel($value = NULL) {
        $array = self::getListOptions();
        if ($value === null || !array_key_exists($value, $array))
            return ' - ';
        return $array[$value];
    }


}
