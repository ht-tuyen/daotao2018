<?php

namespace backend\modules\elearning\models;

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
            [['quiz_id', 'name', 'description', 'question_type', 'answers', 'correct_answer', 'point'], 'required'],
            [['quiz_id', 'question_type', 'point'], 'integer'],
            [['description', 'answers'], 'string'],
            [['name', 'correct_answer'], 'string', 'max' => 255],
            [['quiz_id'], 'exist', 'skipOnError' => true, 'targetClass' => Quiz::className(), 'targetAttribute' => ['quiz_id' => 'quiz_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'question_id' => 'Question ID',
            'quiz_id' => 'Quiz ID',
            'name' => 'Name',
            'description' => 'Description',
            'question_type' => 'Question Type',
            'answers' => 'Answers',
            'correct_answer' => 'Correct Answer',
            'point' => 'Point',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQuiz()
    {
        return $this->hasOne(Quiz::className(), ['quiz_id' => 'quiz_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQuizResultDetails()
    {
        return $this->hasMany(QuizResultDetail::className(), ['question_id' => 'question_id']);
    }
}
