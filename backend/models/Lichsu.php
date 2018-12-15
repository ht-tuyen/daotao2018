<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "qli_lichsu".
 *
 * @property integer $lichsu_id
 * @property integer $tonkho_id
 * @property integer $import_id
 * @property integer $lichsu_type
 * @property integer $lichsu_soluongcu
 * @property integer $lichsu_soluongmoi
 * @property integer $lichsu_dongiacu
 * @property integer $lichsu_dongiamoi
 * @property string $lichsu_nguoisua
 * @property string $create_time
 * @property string $update_time
 */
class Lichsu extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'qli_lichsu';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tonkho_id'], 'required'],
            [['lichsu_id', 'tonkho_id', 'import_id', 'lichsu_type', 'lichsu_soluongcu', 'lichsu_soluongmoi', 'lichsu_dongiacu', 'lichsu_dongiamoi'], 'integer'],
            [['create_time', 'update_time'], 'safe'],
            [['lichsu_nguoisua'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'lichsu_id' => 'Lichsu ID',
            'tonkho_id' => 'Tonkho ID',
            'import_id' => 'Import ID',
            'lichsu_type' => 'Lichsu Type',
            'lichsu_soluongcu' => 'Lichsu Soluongcu',
            'lichsu_soluongmoi' => 'Lichsu Soluongmoi',
            'lichsu_dongiacu' => 'Lichsu Dongiacu',
            'lichsu_dongiamoi' => 'Lichsu Dongiamoi',
            'lichsu_nguoisua' => 'Lichsu Nguoisua',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        ];
    }
}
