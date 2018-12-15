<?php

namespace frontend\modules\course\models;

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
 * @property string $students
 * @property integer $state
 * @property integer $ordering
 * @property integer $created_by
 * @property string $created_date
 * @property integer $modified_by
 * @property string $modified_date
 *
 * @property CourseForUser[] $courseForUsers
 * @property CourseCategory $category
 * @property CourseTeacher $teacher
 * @property CourseLessons[] $courseLessons
 * @property Quiz[] $quizzes
 * @property Student[] $students
 */
class Course extends \yii\db\ActiveRecord
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
            [['name', 'thumbnail', 'short_desc', 'full_desc', 'category_id', 'students', 'state', 'ordering', 'created_by', 'modified_by'], 'required'],
            [['short_desc', 'full_desc', 'students'], 'string'],
            [['category_id', 'state', 'ordering', 'created_by', 'modified_by'], 'integer'],
            [['created_date', 'modified_date'], 'safe'],
            [['name'], 'string', 'max' => 250],
            [['thumbnail'], 'string', 'max' => 255],
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
            'students' => 'Students',
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
    public function getCourseForUsers()
    {
        return $this->hasMany(CourseForUser::className(), ['course_id' => 'course_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['category_id' => 'category_id']);
    }

    public function getTeacher()
    {
        return $this->hasOne(User::className(), ['user_id' => 'created_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLessons()
    {
        return $this->hasMany(Lesson::className(), ['course_id' => 'course_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQuizzes()
    {
        return $this->hasMany(Quiz::className(), ['course_id' => 'course_id']);
    }
    public function getStudents()
    {
        return $this->hasMany(User::className(), ['user_id' => 'user_id'])
            ->viaTable('course_for_user', ['course_id' => 'course_id']);
    }
}
