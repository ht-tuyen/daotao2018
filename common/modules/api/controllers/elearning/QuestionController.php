<?php

namespace common\modules\api\controllers\elearning;

use Yii;
use common\modules\api\models\elearning\Question;
use common\modules\api\models\elearning\Quiz;
use common\modules\api\models\elearning\Course;
use common\modules\api\models\elearning\Lesson;
use common\modules\api\models\elearning\Category;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Inflector;
use common\components\PaginationHelper;

class QuestionController extends Controller
{
    protected $per_page = 50;
    public function actionList()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
      
        $query = Question::find();
        $search_body = Yii::$app->request->getRawBody();
        $param = json_decode($search_body);
        if ($param->name !="") {
            $query->andFilterWhere(
                ['like', 'name',$param->name]
            );
        }
        if ($param->category !="") {
            $query->andFilterWhere(
                ['=', 'category_id',$param->category->category_id]
            );
        }
        if ($param->course !="") {
            $query->andFilterWhere(
                ['=', 'course_id',$param->course->course_id]
            );
        }
        if ($param->lesson !="") {
            $query->andFilterWhere(
                ['=', 'lesson_id',$param->lesson->lesson_id]
            );
        }
        if ($param->state !="") {
            $query->andFilterWhere(
                ['=', 'state',$param->state]
            );
        }
        if ($param->id !="") {
            $query->andFilterWhere(
                ['=', 'question_id',$param->id]
            );
        }
        $countQuery = clone $query;
        $pagination = new PaginationHelper($countQuery->count(),$_GET['page'], $this->per_page );
        $models = $query->offset($pagination->from)
        ->limit($this->per_page)
        ->orderBy($_GET['sortBy'].' '.$_GET['sortType'])
        ->all();
        $result = array(
            "data"=>$models,
            "pagination"=> array(
                'total' =>  $countQuery->count(),
                'per_page' => $this->per_page,
                'current_page' => $pagination->current_page,
                'last_page' => $pagination->total_page,
                'from' => $pagination->from + 1,
                'to' => $pagination->to
            ),
            "quizzes"=>Quiz::find()->where(['state'=>1])->select('quiz_id, name')->all(),
            "categories"=>Category::find()->where(['state'=>1])->all(),
            "courses"=>Course::find()->where(['state'=>1])->all(),

            "lessons"=>Lesson::find()->where(['state'=>1])->all(),

        );
       
        return $result;
    }
    public function actionView()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $course = Question::findOne($_GET['id']);
        return $course;
    }
    public function actionStore()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $body = Yii::$app->request->getRawBody();
        $param = json_decode($body);
        if ($param->question_id) {
            // update
            $model = Question::findOne($param->question_id);
            $model->modified_by = $param->user_id; 
        }else{
            // create
            $model = new Question();
            $model->created_by = $param->user_id;    
        }   
            // main data
            $model->name = $param->name;
            $model->description = $param->description;
            $model->category_id = $param->category_id;
            $model->course_id = $param->course_id;
            $model->lesson_id = $param->lesson_id;
            $model->quiz_id = $param->quiz_id;
            $model->points = $param->points;

            $model->question_type = $param->question_type;
            $model->state = $param->state;
            $model->answers = json_encode($param->answers);
            $model->correct_answer = $param->correct_answer;
           
            
            // save data
            if ($model->validate()) {
                $model->save();
                $param->question_id =  $model->question_id;

                if ($param->quiz_id) {
                    // create question_quiz record
                    $quiz = Quiz::findOne($param->quiz_id);
                    $model->link('quiz', $quiz);


                }
                return  $param;
            }else{
                return ['error'=> $model->errors];
            }
    }
    public function actionBank($category_id, $existed){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $bank = Question::find()->select('question_id, name')->where('category_id = '.$category_id.' and question_id not in ('.$existed.')')->all();
        return $bank;

    }
    public function actionDelete()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $course = Question::findOne($_GET['id']);
        $course->state = -1;
        $course->save();
        return $course;
    }
    public function actionBulkdelete()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $body = Yii::$app->request->getRawBody();
        Question::updateAll(['state' => -1], ['question_id' => json_decode($body)]);

        //$courses = Question::deleteAll(['question_id' => json_decode($body)]);
        return $courses;

    }
    

  
}
