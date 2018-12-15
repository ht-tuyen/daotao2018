<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "{{%language}}".
 *
 * @property string $language_id
 * @property string $title
 * @property string $code
 * @property string $charset
 * @property integer $status
 * @property string $icon
 * @property integer $sort_order
 */
class Language extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%language}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'code'], 'required'],
            [['status', 'sort_order'], 'integer'],
            [['title'], 'string', 'max' => 50],
            [['code'], 'string', 'max' => 12],
            [['charset'], 'string', 'max' => 15],
            [['icon'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'language_id' => Yii::t('app', 'Language ID'),
            'title' => Yii::t('app', 'Title'),
            'code' => Yii::t('app', 'Code'),
            'charset' => Yii::t('app', 'Charset'),
            'status' => Yii::t('app', 'Status'),
            'icon' => Yii::t('app', 'Icon'),
            'sort_order' => Yii::t('app', 'Sort Order'),
        ];
    }

    public function attributeLabels_rules_show()
    {
        return [            
            'sohieu' => 'Số hiệu',            
        ];
    }
}
