<?php

namespace backend\modules\elearning\models;

use Yii;

/**
 * This is the model class for table "course_lessons".
 *
 * @property integer $lesson_id
 * @property string $name
 * @property integer $course_id
 * @property integer $lesson_format
 * @property string $lesson_resource
 * @property string $thumbnail
 * @property string $short_desc
 * @property string $full_desc
 * @property integer $state
 * @property integer $ordering
 * @property integer $created_by
 * @property string $created_date
 * @property integer $modified_by
 * @property string $modified_date
 *
 * @property Attachments[] $attachments
 * @property CourseItem $course
 */
class CourseLesson extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'course_lessons';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'course_id', 'lesson_format', 'lesson_resource', 'thumbnail', 'short_desc', 'full_desc', 'state', 'ordering', 'created_by', 'modified_by'], 'required'],
            [['course_id', 'lesson_format', 'state', 'ordering', 'created_by', 'modified_by'], 'integer'],
            [['short_desc', 'full_desc'], 'string'],
            [['created_date', 'modified_date'], 'safe'],
            [['name', 'lesson_resource', 'thumbnail'], 'string', 'max' => 255],
            [['course_id'], 'exist', 'skipOnError' => true, 'targetClass' => CourseItem::className(), 'targetAttribute' => ['course_id' => 'course_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'lesson_id' => 'Lesson ID',
            'name' => 'Name',
            'course_id' => 'Course ID',
            'lesson_format' => 'Lesson Format',
            'lesson_resource' => 'Lesson Resource',
            'thumbnail' => 'Thumbnail',
            'short_desc' => 'Short Desc',
            'full_desc' => 'Full Desc',
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
    public function getAttachments()
    {
        return $this->hasMany(Attachments::className(), ['lesson_id' => 'lesson_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCourse()
    {
        return $this->hasOne(CourseItem::className(), ['course_id' => 'course_id']);
    }
}
