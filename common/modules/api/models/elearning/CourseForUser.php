<?php

namespace common\modules\api\models\elearning;
use common\modules\api\models\elearning\QuizResult;

use Yii;

/**
 * This is the model class for table "course_for_user".
 *
 * @property integer $id
 * @property integer $course_id
 * @property integer $user_id
 * @property integer $state
 * @property string $created_date
 * @property string $approved_date
 *
 * @property CourseItem $course
 */
class CourseForUser extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'course_for_user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['course_id', 'user_id'], 'required'],
            [['course_id', 'user_id', 'state','reviewed'], 'integer'],
            [['created_date', 'approved_date'], 'safe'],
            [['course_id'], 'exist', 'skipOnError' => true, 'targetClass' => Course::className(), 'targetAttribute' => ['course_id' => 'course_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'course_id' => 'Course ID',
            'user_id' => 'User ID',
            'state' => 'State',
            'created_date' => 'Created Date',
            'approved_date' => 'Approved Date',
        ];
    }

    public function fields()
    {
        return [
            'id',
            'course_id',
            'user_id' ,
            'state',
            'created_date' ,
            'approved_date',
            'reviewed',
            'student'=>function ($model){
                return $model->student;
            },
            
            'quiz_result'=>function ($model){
                return $model->quizResult;
            },
            'result',
            'total',
            'minimum_points',
            'finished',
            'finished_date',
            'result_text' => function($model){
                return $model->result;
            }
        ];
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getResult()
    {
        $result_perfect = (new \yii\db\Query())
        ->select('value')
        ->from('qli_settings')
        ->where(['key' => 'result_perfect'])
        ->one();

        $result_good = (new \yii\db\Query())
        ->select('value')
        ->from('qli_settings')
        ->where(['key' => 'result_good'])
        ->one();

        $result_normal = (new \yii\db\Query())
        ->select('value')
        ->from('qli_settings')
        ->where(['key' => 'result_normal'])
        ->one();
        
       
    

        if ($this->total) {
            $rank = ($this->result/$this->total)*100;
            if ($rank >= $result_perfect['value']) {
                return "Tốt";
            }elseif($rank >= $result_good['value']) {
                return "Khá";
            }elseif($rank >= $result_normal['value']) {
                return "Trung bình";
            }else {
                return "Kém";
            }
        }else {
            return "";
        }
    }
    public function getCourse()
    {
        return $this->hasOne(Course::className(), ['course_id' => 'course_id']);
    }
    public function getStudent()
    {
        return $this->hasOne(Student::className(), ['id' => 'user_id']);
    }
    public function getQuizResult()
    {
        return (new \yii\db\Query())
            ->select(['result','total','started_time','submitted_time','quiz_result_id','reviewed','reviewed_date'])
            ->from('quiz_result')
            ->innerJoin('quiz', 'quiz.quiz_id = quiz_result.quiz_id')
            ->where(['quiz_result.user_id' => $this->user_id, 'quiz.item_id'=>$this->course_id])
            ->one();
    }
}
