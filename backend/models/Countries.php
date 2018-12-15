<?php

namespace backend\models;

use Yii;
use yii\helpers\ArrayHelper;
use \yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%countries}}".
 *
 * @property integer $countryId
 * @property string $country
 * @property string $FIPS104
 * @property string $ISO2
 * @property string $ISO3
 * @property string $ISON
 * @property string $internet
 * @property string $capital
 * @property string $mapReference
 * @property string $nationalitySingular
 * @property string $nationalityPlural
 * @property string $currency
 * @property string $currencyCode
 * @property string $population
 * @property string $title
 * @property string $comment
 * @property string $country_description
 * @property string $country_slogan
 * @property string $tour_holiday_type
 * @property string $tour_id_string
 * @property string $LatLng
 * @property string $country_picture
 */
class Countries extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%countries}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['population'], 'integer'],
            [['country_description', 'tour_id_string'], 'string'],
            [['country', 'FIPS104', 'ISO2', 'ISO3', 'ISON', 'internet', 'capital', 'mapReference', 'nationalitySingular', 'nationalityPlural', 'currency', 'currencyCode', 'title', 'comment', 'tour_holiday_type', 'LatLng', 'country_picture'], 'string', 'max' => 255],
            [['country_slogan'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'countryId' => Yii::t('app', 'Country ID'),
            'country' => Yii::t('app', 'Country'),
            'FIPS104' => Yii::t('app', 'Fips104'),
            'ISO2' => Yii::t('app', 'Iso2'),
            'ISO3' => Yii::t('app', 'Iso3'),
            'ISON' => Yii::t('app', 'Ison'),
            'internet' => Yii::t('app', 'Internet'),
            'capital' => Yii::t('app', 'Capital'),
            'mapReference' => Yii::t('app', 'Map Reference'),
            'nationalitySingular' => Yii::t('app', 'Nationality Singular'),
            'nationalityPlural' => Yii::t('app', 'Nationality Plural'),
            'currency' => Yii::t('app', 'Currency'),
            'currencyCode' => Yii::t('app', 'Currency Code'),
            'population' => Yii::t('app', 'Population'),
            'title' => Yii::t('app', 'Title'),
            'comment' => Yii::t('app', 'Comment'),
            'country_description' => Yii::t('app', 'Country Description'),
            'country_slogan' => Yii::t('app', 'Country Slogan'),
            'tour_holiday_type' => Yii::t('app', 'Tour Holiday Type'),
            'tour_id_string' => Yii::t('app', 'Tour Id String'),
            'LatLng' => Yii::t('app', 'Lat Lng'),
            'country_picture' => Yii::t('app', 'Country Picture'),
        ];
    }

    public static function getListOptions(){
        $countries = self::find()->orderBy(['country' => SORT_ASC])->all();
        if( !empty($countries) ){
            return ArrayHelper::map($countries, 'countryId', 'country');
        }
        return array();
    }
}
