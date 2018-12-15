<?php

namespace backend\modules\elearning\models;

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
class CourseCategory extends \yii\db\ActiveRecord
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
            [['name'], 'required'],
            [['description'], 'string'],
            [['parent_id', 'state', 'ordering', 'created_by', 'modified_by'], 'integer'],
            [['created_date', 'modified_date'], 'safe'],
            [['name'], 'string', 'max' => 100],
            [['thumbnail'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg']
          
        ];
    }
    public function upload()
    {
        if ($this->validate()) {
            $this->thumbnail->saveAs(Yii::getAlias('@anyname') . '/uploads/elearning/category/' . $this->thumbnail->baseName . '.' . $this->thumbnail->extension, false);
            return true;
        } else {
            return false;
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'category_id' => 'ID',
            'name' => 'Danh mục',
            'thumbnail' => 'Ảnh đại diện',
            'description' => 'Thông tin',
            'parent_id' => 'Danh mục cha',
            'state' => 'Trạng thái',
            'ordering' => 'Thứ tự',
            'created_by' => 'Tạo bởi',
            'created_date' => 'Ngày tạo',
            'modified_by' => 'Sửa bởi',
            'modified_date' => 'Ngày sửa',
        ];
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCourseItems()
    {
        return $this->hasMany(CourseItem::className(), ['category_id' => 'category_id']);
    }

    // public function relations()
    // {
    //     return array(
    //         'parent'=>array(self::BELONGS_TO, 'CourseCategory', 'parent_id'),
    //     );
    // }
}
