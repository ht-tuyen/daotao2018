<?php

namespace common\modules\api\models\elearning;
use common\modules\api\models\elearning\Course;

use common\modules\api\models\elearning\Lesson;

use common\modules\api\models\elearning\Quiz;

use Yii;

/**
 * This is the model class for table "questions".
 *
 * @property integer $question_id
 * @property integer $quiz_id
 * @property string $name
 * @property string $description
 * @property integer $question_type
 * @property string $answers
 * @property string $correct_answer
 * @property integer $point
 *
 * @property Quiz $quiz
 * @property QuizResultDetail[] $quizResultDetails
 */
class Question extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'questions';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
             [['category_id', 'name',  'question_type', 'correct_answer'], 'required','message' => '{attribute} không được để trống'],
             [['category_id', 'question_type'], 'integer'],
             [['description', 'answers'], 'string'],
             [['name'], 'string', 'max' => 255],
             [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::className(), 'targetAttribute' => ['category_id' => 'category_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'question_id' => 'Question ID',
            //'quiz_id' => 'Bài thi',
            'name' => 'Câu hỏi',
            'description' => 'Mô tả',
            'question_type' => 'Loại câu hỏi',
            'answers' => 'Phương án',
            'correct_answer' => 'Đáp án đúng',
           
        ];
    }
    public function fields()
    {
        return [
            'points',
            'question_id',
            'category_id',
            'name',
            'description',
            'course_id',
            'lesson_id',
            'quiz_id',
            'question_type' ,
            'answers' => function ($model) {
                return json_decode($model->answers);
            },
            'correct_answer',
            'state',
            'filled' => function(){
                return 0;
            },
            // 'quiz' => function ($model) {
            //     return $model->quiz; // Return related model property, correct according to your structure
            // },
            'category'=> function ($model) {
                return $model->category;
            },
            'course'=> function ($model) {
                return $model->course;
            },
            'lesson'=> function ($model) {
                return $model->lesson;
            }
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    
    public function getQuiz()
    {
        return $this->hasMany(Quiz::className(), ['quiz_id' => 'quiz_id'])
            ->viaTable('question_quiz', ['question_id' => 'question_id']);
    }
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['category_id' => 'category_id']);

    }
    public function getCourse()
    {
      
        return (new \yii\db\Query())
        ->select('*')
        ->from('course_item')
       
        ->where(['course_id' => $this->course_id, 'state'=>1])
        ->one();
    }
    public function getLesson()
    {
        return (new \yii\db\Query())
        ->select('*')
        ->from('course_lessons')
       
        ->where(['lesson_id' => $this->lesson_id, 'state'=>1])
        ->one();
       // return Lesson::find()->where(['lesson_id'=>$this->lesson_id])->one();

    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQuizResultDetails()
    {
        return $this->hasMany(QuizResultDetail::className(), ['question_id' => 'question_id']);
    }
}
