<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "{{%sort_finished}}".
 *
 * @property integer $sort_id
 * @property integer $order_id
 * @property string $sort_html
 * @property integer $type
 */
class SortFinished extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%sort_finished}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id', 'type'], 'integer'],
            [['sort_html'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'sort_id' => 'Sort ID',
            'order_id' => 'Order ID',
            'sort_html' => 'Sort Html',
            'type' => 'Type',
        ];
    }
}
