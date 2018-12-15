<?php

namespace backend\modules\elearning\controllers;
use backend\modules\elearning\models\CourseCategory;
use common\modules\api\models\elearning\Course;
use common\modules\api\models\elearning\Student;
use common\modules\api\models\elearning\CourseForUser;

use Yii;
use yii\web\Controller;


/**
 * CourseItemController implements the CRUD actions for CourseItem model.
 */
class CourseController extends Controller
{
    public $_cats;
    public function actionIndex()
    {
        return $this->render('vue');
    }
    
    public function actionStudent($id)
    {
        $model = Course::findOne($id);
        $students = CourseForUser::find()->where(['course_id' => $id])->all();
        $users = (new \yii\db\Query())
        ->select('s.*')
        ->from('user as s')
        // ->leftJoin('course_for_user as cu', 's.id = cu.user_id')
      
        // ->where(['s.status'=>10, 'cu.course_id'=>null, 'cu.course_id' => $id] )
       
        ->all();

        $results = (new \yii\db\Query())
            ->select(['qr.result','qr.total','started_time','submitted_time','email','full_name','mobile','cu.state','cu.created_date','qr.reviewed'])
            ->from('course_for_user as cu')
            ->leftJoin('quiz as q', 'q.course_id = cu.course_id')
            ->innerJoin('quiz_result as qr', 'qr.quiz_id = q.quiz_id')
            ->innerJoin('course_item as ci', 'ci.course_id = cu.course_id')
            ->leftJoin('user as u', 'u.id = cu.user_id')
            ->where(['cu.course_id'=>$id])
           
            ->all();
        return $this->render('student',['course'=>$model,'students'=>$students, 'results'=>$results, 'users'=>$users]);
    }
    public function actionQuiz($id, $course_id)
    {
        $model = Course::findOne($course_id);

        return $this->render('result',['course'=>$model]);
    }
    public function actionList2()
    {
      
        self::get_child2(0, "");
        echo "<pre>";
            var_dump($this->_cats);
        echo "</pre>";
        exit();
        
    }
    public function actionResult($id)
    {
        $model = Course::findOne($id);

        return $this->render('result',['course'=>$model]);
    }
    public function get_child2($parent = 0 , $level = 1) {
        $categories = CourseCategory::find()->where(['state' => 1,'parent_id'=>$parent])->all();
        $level ++;
        if ($categories) {
           
            foreach ($categories as $category) {
                if ($category->parent_id == 0) {
                    $level= 1;
                }
                $this->_cats[] = array(
                    "level"=>$level,
                    "value"=>$category->category_id,
                    "name"=>$category->name
                );
                self::get_child2($category->category_id, $level);

            }
        }
        return $this->_cats;
        
    }
}
