<?php

namespace common\modules\api\models\elearning;

use Yii;

/**
 * This is the model class for table "quiz_result".
 *
 * @property integer $quiz_result_id
 * @property integer $quiz_id
 * @property integer $user_id
 * @property integer $result
 * @property integer $total
 * @property string $started_time
 * @property string $submitted_time
 *
 * @property QuizDeleteLog[] $quizDeleteLogs
 * @property Quiz $quiz
 * @property QuizResultDetail[] $quizResultDetails
 */
class QuizResult extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'quiz_result';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['quiz_id', 'user_id', 'result', 'total', 'started_time'], 'required'],
            [['quiz_id', 'user_id', 'result', 'total'], 'integer'],
            [['started_time', 'submitted_time'], 'safe'],
            [['quiz_id'], 'exist', 'skipOnError' => true, 'targetClass' => Quiz::className(), 'targetAttribute' => ['quiz_id' => 'quiz_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'quiz_result_id' => 'Quiz Result ID',
            'quiz_id' => 'Quiz ID',
            'user_id' => 'User ID',
            'result' => 'Result',
            'total' => 'Total',
            'started_time' => 'Started Time',
            'submitted_time' => 'Submitted Time',
        ];
    }
    public function fields()
    {
        return [
            'quiz_result_id' ,
            'reviewed',
            'reviewed_date',
            'quiz_id' ,
            'user_id',
            'result' ,
            'total' ,
            'started_time' ,
            'submitted_time',
            'quiz_name' => function ($model) {
                return $model->quiz->name; // Return related model property, correct according to your structure
            },
            'student' => function ($model) {
                return $model->student; // Return related model property, correct according to your structure
            },
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQuizDeleteLogs()
    {
        return $this->hasMany(QuizDeleteLog::className(), ['quiz_result_id' => 'quiz_result_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQuiz()
    {
        return $this->hasOne(Quiz::className(), ['quiz_id' => 'quiz_id']);
    }

    public function getStudent()
    {
        return $this->hasOne(Student::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQuizResultDetails()
    {
        return $this->hasMany(QuizResultDetail::className(), ['quiz_result_id' => 'quiz_result_id']);
    }
}
