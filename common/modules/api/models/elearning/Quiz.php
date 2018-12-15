<?php

namespace common\modules\api\models\elearning;

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
             [['name','number_questions', 'time', 'state','published_start','published_end'], 'required','message' => '{attribute} không được để trống'],
             [['number_questions', 'time', 'state', 'created_by', 'modified_by','total_points'], 'integer'],
             [['short_desc'], 'string'],
             [['published_start', 'published_end', 'created_date', 'modified_date'], 'safe'],
             [['name'], 'string', 'max' => 255],
            // [['course_id'], 'exist', 'skipOnError' => true, 'targetClass' => Course::className(), 'targetAttribute' => ['course_id' => 'course_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'quiz_id' => 'Quiz ID',
            'name' => 'Bài thi',
            'course_id' => 'Khóa học',
            'short_desc' => 'Thông tin',
            
            'number_questions' => 'Số lượng câu hỏi',
            'published_start' => 'Thời gian bắt đầu',
            'published_end' => 'Thời gian kết thúc',
            'time' => 'Thời gian làm bài',
            'state' => 'State',
            'created_by' => 'Created By',
            'created_date' => 'Created Date',
            'modified_by' => 'Modified By',
            'modified_date' => 'Modified Date',
        ];
    }

    public function fields()
    {
        return [
            'total_points',
            'quiz_id' ,
            'name',
           
            'course_id',
            'lesson_id',
            'short_desc',
            'item_id',
            'category_id',
            'quiz_type',
            'number_questions',
            'published_start' => function ($model) {
                return date("Y-m-d",strtotime($model->published_start));
            },
            'published_end' => function ($model) {
                return date("Y-m-d",strtotime($model->published_end));
            },
            'time',
            'state'  ,
            'created_by' ,
            'created_date',
            'modified_by',
            'modified_date',
            'minimum_points',
           
            'course' => function ($model) {
                return $model->course->name; // Return related model property, correct according to your structure
            },
            'lesson' => function ($model) {
                return $model->lesson->name; // Return related model property, correct according to your structure
            },
            'category' => function ($model) {
                return $model->category->name; // Return related model property, correct according to your structure
            },
            'questions' => function ($model) {
                return $model->questions; // Return related model property, correct according to your structure
            },
            'available_questions' => function ($model){
                return count($model->questions);
            },
            'available_points' => function ($model){
                $points = 0;
                foreach ($model->questions as $q) {
                    $points += $q->points;
                }
                return $points;
            },
            'quiz_result'=> function ($model) {
                return $model->quizResults;
            }
           
            
           
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQuestions()
    {
        //return $this->hasMany(Question::className(), ['quiz_id' => 'quiz_id']);
        return $this->hasMany(Question::className(), ['question_id' => 'question_id'])
            ->viaTable('question_quiz', ['quiz_id' => 'quiz_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCourse()
    {
        return $this->hasOne(Course::className(), ['course_id' => 'course_id']);
    }
    public function getLesson()
    {
        return $this->hasOne(Lesson::className(), ['lesson_id' => 'lesson_id']);
    }
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['category_id' => 'category_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQuizResults()
    {
        return $this->hasMany(QuizResult::className(), ['quiz_id' => 'quiz_id']);
    }
}
