<?php

namespace backend\modules\elearning\models;

use Yii; 

/**
 * This is the model class for table "course_item".
 *
 * @property integer $course_id
 * @property string $name
 * @property string $thumbnail
 * @property string $short_desc
 * @property string $full_desc
 * @property integer $category_id
 * @property integer $state
 * @property integer $ordering
 * @property integer $created_by
 * @property string $created_date
 * @property integer $modified_by
 * @property string $modified_date
 *
 * @property CourseForUser[] $courseForUsers
 * @property CourseCategory $category
 * @property CourseLessons[] $courseLessons
 * @property Quiz[] $quizzes
 */
class CourseItem extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'course_item';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'short_desc', 'full_desc', 'category_id', 'state'], 'required'],
            [['short_desc', 'full_desc'], 'string'],
            [['category_id', 'state', 'ordering', 'created_by', 'modified_by'], 'integer'],
            [['created_date', 'modified_date'], 'safe'],
            [['name'], 'string', 'max' => 250],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => CourseCategory::className(), 'targetAttribute' => ['category_id' => 'category_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'course_id' => 'Course ID',
            'name' => 'Name',
            'thumbnail' => 'Thumbnail',
            'short_desc' => 'Short Desc',
            'full_desc' => 'Full Desc',
            'category_id' => 'Category ID',
            'state' => 'State',
            'ordering' => 'Ordering',
            'created_by' => 'Created By',
            'created_date' => 'Created Date',
            'modified_by' => 'Modified By',
            'modified_date' => 'Modified Date',
        ];
    }
    public function upload()
    {
        if ($this->validate()) {
            $this->thumbnail->saveAs(Yii::getAlias('@anyname') . '/uploads/elearning/course/' . $this->thumbnail->baseName . '.' . $this->thumbnail->extension, false);
            return true;
        } else {
            return false;
        }
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCourseForUsers()
    {
        return $this->hasMany(CourseForUser::className(), ['course_id' => 'course_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(CourseCategory::className(), ['category_id' => 'category_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCourseLessons()
    {
        return $this->hasMany(CourseLessons::className(), ['course_id' => 'course_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQuizzes()
    {
        return $this->hasMany(Quiz::className(), ['course_id' => 'course_id']);
    }
}
