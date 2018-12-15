<?php

namespace frontend\modules\news\models;

use Yii;

/**
 * This is the model class for table "qli_tintuc".
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
 */
class NewsItem extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'qli_tintuc';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tieude', 'noidung'], 'required'],
            [['noidung'], 'string'],
            [['idchuyenmuc', 'nguoitao', 'nguoicapnhat', 'trangthai'], 'integer'],
            [['ngaytao', 'ngaycapnhat'], 'safe'],
            [['tieude'], 'string', 'max' => 225],
            [['gioithieu', 'anhdaidien', 'slug'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'tt_id' => 'Tt ID',
            'tieude' => 'Tieude',
            'noidung' => 'Noidung',
            'gioithieu' => 'Gioithieu',
            'anhdaidien' => 'Anhdaidien',
            'slug' => 'Slug',
            'idchuyenmuc' => 'Idchuyenmuc',
            'ngaytao' => 'Ngaytao',
            'ngaycapnhat' => 'Ngaycapnhat',
            'nguoitao' => 'Nguoitao',
            'nguoicapnhat' => 'Nguoicapnhat',
            'trangthai' => 'Trangthai',
        ];
    }
}
