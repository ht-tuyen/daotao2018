<?php

namespace common\modules\api\models\elearning;

use Yii;

/**
 * This is the model class for table "feedbacks".
 *
 * @property integer $id
 * @property string $name
 * @property string $position
 * @property string $description
 * @property string $slug
 * @property string $thumb
 * @property integer $created_by
 * @property string $created_date
 * @property integer $modified_by
 * @property string $modified_date
 */
class Feedback extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'feedbacks';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'position'], 'required'],
            [['ordering'], 'integer'],
            [['description'], 'string'],
            [['created_by', 'modified_by'], 'integer'],
            [['created_date', 'modified_date'], 'safe'],
            [['name', 'position', 'slug', 'source'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'position' => 'Position',
            'description' => 'Description',
            'slug' => 'Slug',
            'source' => 'Source',
            'created_by' => 'Created By',
            'created_date' => 'Created Date',
            'modified_by' => 'Modified By',
            'modified_date' => 'Modified Date',
        ];
    }
}
