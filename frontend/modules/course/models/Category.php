<?php

namespace frontend\modules\course\models;

use Yii;

/**
 * This is the model class for table "course_category".
 *
 * @property integer $category_id
 * @property string $name
 * @property string $thumbnail
 * @property string $description
 * @property integer $parent_id
 * @property integer $state
 * @property integer $ordering
 * @property integer $created_by
 * @property string $created_date
 * @property integer $modified_by
 * @property string $modified_date
 *
 * @property CourseItem[] $courseItems
 */
class Category extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'course_category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'thumbnail', 'description', 'parent_id', 'state', 'ordering', 'created_by', 'modified_by'], 'required'],
            [['description'], 'string'],
            [['parent_id', 'state', 'ordering', 'created_by', 'modified_by'], 'integer'],
            [['created_date', 'modified_date'], 'safe'],
            [['name'], 'string', 'max' => 100],
            [['thumbnail'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'category_id' => 'Category ID',
            'name' => 'Name',
            'thumbnail' => 'Thumbnail',
            'description' => 'Description',
            'parent_id' => 'Parent ID',
            'state' => 'State',
            'ordering' => 'Ordering',
            'created_by' => 'Created By',
            'created_date' => 'Created Date',
            'modified_by' => 'Modified By',
            'modified_date' => 'Modified Date',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCourses()
    {
        return $this->hasMany(Course::className(), ['category_id' => 'category_id']);
    }
}
