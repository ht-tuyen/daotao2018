<?php

namespace common\modules\api\models\elearning;

use Yii;

/**
 * This is the model class for table "quiz_result_detail".
 *
 * @property integer $quiz_result_detail_id
 * @property integer $quiz_result_id
 * @property integer $question_id
 * @property string $correct_answer
 * @property string $answer
 *
 * @property QuizResult $quizResult
 * @property Questions $question
 */
class QuizResultDetail extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'quiz_result_detail';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['quiz_result_id', 'question_id', 'correct_answer'], 'required'],
            [['quiz_result_id', 'question_id'], 'integer'],
            [['correct_answer', 'answer'], 'string', 'max' => 255],
            [['quiz_result_id'], 'exist', 'skipOnError' => true, 'targetClass' => QuizResult::className(), 'targetAttribute' => ['quiz_result_id' => 'quiz_result_id']],
            [['question_id'], 'exist', 'skipOnError' => true, 'targetClass' => Question::className(), 'targetAttribute' => ['question_id' => 'question_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'quiz_result_detail_id' => 'Quiz Result Detail ID',
            'quiz_result_id' => 'Quiz Result ID',
            'question_id' => 'Question ID',
            'correct_answer' => 'Correct Answer',
            'answer' => 'Answer',
        ];
    }
    public function fields()
    {
        return [
            'quiz_result_detail_id' ,
            'quiz_result_id' ,
            'question_id',
            'correct_answer',
            'answer',
            'type',
            'points',
            'question' => function ($model) {
                return $model->question; // Return related model property, correct according to your structure
            },
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQuizResult()
    {
        return $this->hasOne(QuizResult::className(), ['quiz_result_id' => 'quiz_result_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQuestion()
    {
        return $this->hasOne(Question::className(), ['question_id' => 'question_id']);
    }
}
