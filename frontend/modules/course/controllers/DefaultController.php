<?php

namespace frontend\modules\course\controllers;

use yii\web\Controller;
use common\modules\api\models\elearning\Category;
use common\modules\api\models\elearning\Course;
use common\modules\api\models\elearning\Lesson;
use common\modules\api\models\elearning\Quiz;
use common\modules\api\models\elearning\CourseForUser;
use common\modules\api\models\elearning\Log;
use backend\models\User;
use common\modules\api\models\elearning\Student;
use common\modules\api\models\elearning\QuizResult;
use common\modules\api\models\elearning\QuizResultDetail;
use yii\data\Pagination;
use yii;
/**
 * Default controller for the `course` module
 */
class DefaultController extends Controller
{
    public function actionIndex()
    {
         
        $categories = Category::find()->where(['state'=>1])->all();
        $items = Course::find()->where([
            'state' => 1,
        ])->orderBy(['ordering'=>SORT_ASC, 'created_date'=>SORT_DESC]);
        if ($_GET['search']) {
            $items->andFilterWhere(
                ['like', 'name', $_GET['search']]
            );
        }
        
        $countQuery = clone $items;
        $pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 20  ]);
        $models = $items->offset($pages->offset)
            ->limit($pages->limit)
            ->all();

        return $this->render('courses', [
            'items' => $models,
            'pages' => $pages,
            'categories'=>$categories
        ]);
    
    }
    public function actionCategory($id)
    {
        $category = Category::findOne($id);
        $items = Course::find()->where([
            'state' => 1,
            'category_id'=>$id
        ])->orderBy(['ordering'=>SORT_ASC, 'created_date'=>SORT_DESC]);
        $countQuery = clone $items;
        $pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 20  ]);
        $models = $items->offset($pages->offset)
            ->limit($pages->limit)
            ->all();

        return $this->render('category', [
            'items' => $models,
            'category' => $category,
            'pages' => $pages,
        ]);
    
    }
    public function actionTest() {
        echo "ok";
    }
  
    public function actionCourse($id)
    {
        //$this->log('1',$id);
        $item = Course::findOne($id);
        $canview = CourseForUser::find()
        ->where(['user_id' => Yii::$app->user->id, 'course_id' => $id])
        ->one();
        if (Yii::$app->user->id != 9){
            $cantest = QuizResult::find()->where(['user_id' => Yii::$app->user->id, 'quiz_id' => $item->quiz->quiz_id])->one();

        }
        else {
            $cantest = false;
        }
        return $this->render('course', [
            'item' => $item,
            'canview' => $canview,
            'cantest' => $cantest,
        ]);

    } 
    public function actionRegister($id)
    {
        $course = Course::findOne($id);
        $teacher = User::findOne($course->main_teacher);
        $register = CourseForUser::find()
        ->where(['user_id' => Yii::$app->user->id, 'course_id' => $id])
        ->one();
        if ($register) {
            return $this->redirect(['/course/default/course','id'=>$id]);
        }else {
            $register = new CourseForUser();
            $register->user_id = Yii::$app->user->id;
            $register->course_id = $id;
            //$register->state = 1;
            $register->save();

            $log = new Log();
            $log->item = $id;
            $log->user_id = Yii::$app->user->id;
            $log->type = 7;
            $log->date = date("Y-m-d H:i:s");
            $log->text = "Bạn đã đăng ký khóa học ".$course->name;
            $log->save();

            // Send email to main teacher
            Yii::$app
                ->mailer
                ->compose(
                    ['html' => 'noticeTeacher-html', 'text' => 'noticeTeacher-text'],
                    ['teacher' => $model, 'course'=>$course, 'student'=>Student::findOne(Yii::$app->user->id)]
                )
                ->setFrom([Yii::$app->params['supportEmail'] => 'TCVN E-learning'])
                ->setTo(Yii::$app->settings->get('email_quan_tri'))
                ->setSubject('Có học viên đăng ký khóa học trên TCVN E-learning')
                ->send();

            return $this->redirect(['/course/default/course','id'=>$id]);
        }
    }
    public function actionLesson($id)
    {
        //$this->log('2',$id);
        return $this->render('lesson', [
            'item' => Lesson::findOne($id)
        ]);

    }
    public function log($type, $item_id)
    {
        $log = new Log();
        $log->type = $type;
        $log->item = $item_id;
        $log->user_id=Yii::$app->user->id;
        $log->save();
    }
    public function actionQuiz($id)
    {
        return $this->render('quiz', [
            'item' => Quiz::findOne($id)
        ]);
    }
    public function actionDoquiz($id)
    {
        $quiz = Quiz::findOne($id);
      
        return $this->render('doquiz', [
            'item' => $quiz,
           
        ]);
    }
    public function actionSavequiz()
    {

        var_dump(Yii::$app->request->post());
        exit();
    }
}
