<?php

namespace frontend\modules\student\controllers;
use common\modules\api\models\elearning\Course;
use common\modules\api\models\elearning\Lesson;
use common\modules\api\models\elearning\Quiz;
use common\modules\api\models\elearning\Log;
use common\modules\api\models\elearning\LessonForUser;
use common\modules\api\models\elearning\CourseHistory;

use common\modules\api\models\elearning\CourseForUser;
use common\modules\api\models\elearning\QuizResult;

use yii\web\Controller;
use Yii;
/**
 * Default controller for the `student` module
 */
class CourseController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        $courses = CourseForUser::find()
        ->where(['user_id' => Yii::$app->user->id])
        ->all();
        return $this->render('index',['courses'=>$courses]);
    }
    public function actionDetail($id)
    {
        $item = Course::findOne($id);
        $lessons = Lesson::find()->where(['course_id'=>$id])->all();
      
        $quiz = Quiz::find()->where(['course_id'=>$id])->one();
        // Save course history
        if (!CourseHistory::find()->where(['course_id'=>$id, 'user_id'=>Yii::$app->user->id , 'type'=>2])) {
            $history = new CourseHistory();
            $history->course_id = $id;
            $history->user_id = Yii::$app->user->id;
            $history->type = 2;
            $history->date = date("Y-m-d");
            $history->save();
        }
        

        return $this->render('course', [
            'item' => $item,
            'lessons'=>$lessons,
            'quiz'=>$quiz
        ]);
    }
    public function actionLesson($id)
    {
        $item = Lesson::findOne($id);
        //Get previous lesson
      

        return $this->render('lesson', [
            'item' => $item,
        ]);
    }
    public function actionCompletelesson($id)
    {
        $item = Lesson::findOne($id);

        if (!$item->quizes) {
            $lesson_for_user =  LessonForUser::find()->where([
                'lesson_id'=>$id,
                'user_id'=>Yii::$app->user->id
            ])->one();
            $lesson_for_user->finished = 1;
            $lesson_for_user->finished_date = date("Y-m-d H:i:s");
            $lesson_for_user->save();
            $log = new Log();
            $log->type = 4;
            $log->text = "Bạn đã hoàn thành nội dung ".$item->name;
            $log->item = $item->lesson_id;
            $log->date = date("Y-m-d H:i:s");
            $log->user_id = Yii::$app->user->id;
            $log->save();
            Yii::$app->session->setFlash('success', 'Chúc mừng bạn đã hoàn thành bài học này. Hãy tiếp tục tham gia các bài học khác nhé.');
        }elseif (!$item->finished){
            Yii::$app->session->setFlash('success', 'Hãy làm các bài kiểm tra dưới đây để hoàn thành bài học.');
 
        }
        $quizes = array();
        foreach ($item->quizes as $key =>$quiz){
            $completed_quiz = new \stdClass();
            $completed_quiz->result = QuizResult::find()->where(['quiz_id'=>$quiz->quiz_id, 'user_id'=> Yii::$app->user->id])->one();
            $completed_quiz->quiz_id = $quiz->quiz_id;
            $completed_quiz->name = $quiz->name;
            $completed_quiz->time = $quiz->time;
            $completed_quiz->number_questions = $quiz->number_questions;
            $quizes[]=$completed_quiz;
        }
        

        return $this->render('completelesson', [
            'item' => $item,
            'quizes'=>$quizes
        ]);
    }
    public function actionQuiz($id)
    {
        $item = Quiz::findOne($id);
        $completed_quiz = QuizResult::find()->where([
            'quiz_id'=>$item->quiz_id,
            'user_id'=> Yii::$app->user->id
        ])->orderBy(['quiz_result_id'=>SORT_DESC])->one();
        return $this->render('quiz', [
            'item' => $item,
            'completed_quiz'=>$completed_quiz
        ]);
    }

    public function actionDoquiz($id)
    {
        $quiz = Quiz::findOne($id);
        
        return $this->render('doquiz', [
            'item' => $quiz,
           
        ]);
    }
    public function actionDoquizfinal($id)
    {
        $quiz = Quiz::findOne($id);
        
        return $this->render('doquizfinal', [
            'item' => $quiz,
           
        ]);
    }
}
