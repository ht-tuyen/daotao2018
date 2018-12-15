<?php

namespace frontend\modules\course\models;

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
 * @property Quiz $quiz
 * @property Users $user
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
            [['quiz_id', 'user_id', 'result', 'total', 'started_time', 'submitted_time'], 'required'],
            [['quiz_id', 'user_id', 'result', 'total'], 'integer'],
            [['started_time', 'submitted_time'], 'safe'],
            [['quiz_id'], 'exist', 'skipOnError' => true, 'targetClass' => Quiz::className(), 'targetAttribute' => ['quiz_id' => 'quiz_id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['user_id' => 'user_id']],
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
    public function getUser()
    {
        return $this->hasOne(Users::className(), ['user_id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQuizResultDetails()
    {
        return $this->hasMany(QuizResultDetail::className(), ['quiz_result_id' => 'quiz_result_id']);
    }
}
