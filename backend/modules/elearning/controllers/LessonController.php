<?php

namespace backend\modules\elearning\controllers;
use common\modules\api\models\elearning\Lesson;
use common\modules\api\models\elearning\LessonForUser;

use Yii;
use yii\web\Controller;


/**
 * CourseLessonController implements the CRUD actions for CourseLesson model.
 */
class LessonController extends Controller
{
    public function actionIndex()
    {
        return $this->render('vue');
    }
    public function actionQuiz($id)
    {
        $lesson = Lesson::findOne($id);
        return $this->render('quiz',['lesson'=>$lesson]);
    }
    public function actionMessage($id)
    {
        $lesson = Lesson::findOne($id);
        return $this->render('message',['lesson_id'=>$id, 'lesson'=>$lesson]);
    }
    public function actionResult()
    {
        return $this->render('result');
    }
    public function actionStudent($id)
    {
        $model = Lesson::findOne($id);
        $students = LessonForUser::find()->where(['lesson_id' => $id])->all();
        
        return $this->render('student',['lesson'=>$model,'students'=>$students]);
      
    }
}
