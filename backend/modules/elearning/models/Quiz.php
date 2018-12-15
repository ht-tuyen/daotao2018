<?php

namespace backend\modules\elearning\models;

use Yii;

/**
 * This is the model class for table "quiz".
 *
 * @property integer $quiz_id
 * @property string $name
 * @property integer $course_id
 * @property string $short_desc
 * @property integer $number_questions
 * @property string $published_start
 * @property string $published_end
 * @property integer $time
 * @property integer $state
 * @property integer $created_by
 * @property string $created_date
 * @property integer $modified_by
 * @property string $modified_date
 *
 * @property Questions[] $questions
 * @property CourseItem $course
 * @property QuizResult[] $quizResults
 */
class Quiz extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'quiz';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'course_id', 'short_desc', 'number_questions', 'published_start', 'published_end', 'time', 'state', 'created_by', 'modified_by'], 'required'],
            [['course_id', 'number_questions', 'time', 'state', 'created_by', 'modified_by'], 'integer'],
            [['short_desc'], 'string'],
            [['published_start', 'published_end', 'created_date', 'modified_date'], 'safe'],
            [['name'], 'string', 'max' => 255],
            [['course_id'], 'exist', 'skipOnError' => true, 'targetClass' => CourseItem::className(), 'targetAttribute' => ['course_id' => 'course_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'quiz_id' => 'Quiz ID',
            'name' => 'Name',
            'course_id' => 'Course ID',
            'short_desc' => 'Short Desc',
            'number_questions' => 'Number Questions',
            'published_start' => 'Published Start',
            'published_end' => 'Published End',
            'time' => 'Time',
            'state' => 'State',
            'created_by' => 'Created By',
            'created_date' => 'Created Date',
            'modified_by' => 'Modified By',
            'modified_date' => 'Modified Date',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQuestions()
    {
        return $this->hasMany(Questions::className(), ['quiz_id' => 'quiz_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCourse()
    {
        return $this->hasOne(CourseItem::className(), ['course_id' => 'course_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQuizResults()
    {
        return $this->hasMany(QuizResult::className(), ['quiz_id' => 'quiz_id']);
    }
}
