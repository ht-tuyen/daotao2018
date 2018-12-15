<?php

namespace frontend\modules\news\models;

use Yii;

/**
 * This is the model class for table "qli_chuyenmuc".
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
 */
class NewsCategory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'qli_chuyenmuc';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tenchuyenmuc'], 'required'],
            [['ngaytao', 'ngaycapnhat'], 'safe'],
            [['nguoitao', 'nguoicapnhat', 'trangthai', 'thutu'], 'integer'],
            [['tenchuyenmuc', 'slug', 'anhdaidien', 'gioithieu'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cm_id' => 'Cm ID',
            'tenchuyenmuc' => 'Tenchuyenmuc',
            'slug' => 'Slug',
            'ngaytao' => 'Ngaytao',
            'ngaycapnhat' => 'Ngaycapnhat',
            'nguoitao' => 'Nguoitao',
            'nguoicapnhat' => 'Nguoicapnhat',
            'anhdaidien' => 'Anhdaidien',
            'gioithieu' => 'Gioithieu',
            'trangthai' => 'Trangthai',
            'thutu' => 'Thutu',
        ];
    }
}
