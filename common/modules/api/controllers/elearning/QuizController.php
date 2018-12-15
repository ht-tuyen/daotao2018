<?php

namespace common\modules\api\controllers\elearning;

use Yii;
use common\modules\api\models\elearning\Course;
use common\modules\api\models\elearning\CourseForUser;
use common\modules\api\models\elearning\CourseHistory;
use common\modules\api\models\elearning\LessonForUser;

use common\modules\api\models\elearning\Lesson;
use common\modules\api\models\elearning\Category;

use common\modules\api\models\elearning\Log;
use common\modules\api\models\elearning\Quiz;
use common\modules\api\models\elearning\Question;

use common\modules\api\models\elearning\QuizResult;
use common\modules\api\models\elearning\QuizResultDetail;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Inflector;
use common\components\PaginationHelper;

class QuizController extends Controller
{
    protected $per_page = 50;
    public function actionList()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
      
        $query = Quiz::find();
        $search_body = Yii::$app->request->getRawBody();
        $param = json_decode($search_body);
        if ($param->name !="") {
            $query->andFilterWhere(
                ['like', 'name',$param->name]
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
        if ($param->category !="") {
            $query->andFilterWhere(
                ['=', 'category_id',$param->category->category_id]
            );
        }
       
        if ($param->type !="") {
            $query->andFilterWhere(
                ['=', 'quiz_type',$param->type->value]
            );
        }
        
        if ($param->state !="") {
            $query->andFilterWhere(
                ['=', 'state',$param->state]
            );
        }
        if ($param->id !="") {
            $query->andFilterWhere(
                ['=', 'quiz_id',$param->id]
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
            "courses"=>Course::find()->where(['state'=>1])->select('course_id, name')->all(),
            "lessons"=>Lesson::find()->where(['state'=>1])->select('lesson_id, name')->all(),
            "categories"=>Category::find()->where(['state'=>1])->select('category_id, name')->all()
        );
       
        return $result;
    }
    public function actionView()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $quiz = Quiz::findOne($_GET['id']);
        
        if ($quiz->lesson_id) {
            $lesson = Lesson::findOne($quiz->lesson_id);
            $lesson->finished = 1;
            $lesson->save();
        }
        $model = new QuizResult();
        $model->quiz_id = $_GET['id'];
        $model->user_id = Yii::$app->user->id;
        $model->result = 0;
        $model->total = $quiz->total_points;
        $model->started_time = date("Y-m-d H:i:s");
        $model->save();
        $questions=array_rand($quiz->questions,$quiz->number_questions);
        $sliced_questions = array();
        foreach ($questions as $key) {
            $sliced_questions[] = $quiz->questions[$key];
        }
        $return_question = array();
        foreach ($sliced_questions as $key => $question) {
            $quest_result = new QuizResultDetail();
            $quest_result->quiz_result_id = $model->quiz_result_id;
            $quest_result->question_id = $question->question_id;
            $quest_result->correct_answer = $question->correct_answer;
            $quest_result->answer = "0";
            $quest_result->type = $question->question_type;
            $quest_result->save();
           // $sliced_questions[$key]->quiz_result_detail_id = $quest_result->quiz_result_detail_id;
        }
        return [
            'item' => $quiz,
            'quiz_result_id' => $model->quiz_result_id,
            'questions'=>$sliced_questions
        ];
    }
    public function actionDetail($id) {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $quiz = Quiz::findOne($_GET['id']);
        return $quiz;
    }
    public function actionAddquestion($id, $question_id) {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $quiz = Quiz::findOne($_GET['id']);
        $question = Question::findOne($_GET['question_id']);
        $question->link('quiz', $quiz);
        return $question;
    }
    public function actionRemovequestion($id, $question_id) {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $quiz = Quiz::findOne($_GET['id']);
        $question = Question::findOne($_GET['question_id']);
        $question->unlink('quiz', $quiz, true);
        return $question;
    }
    public function saveLog($type, $item, $text){
            $log = new Log();
            $log->type = $type;
            $log->item = $item;
            $log->text = $text;
            $log->user_id = Yii::$app->user->id;
            $log->date = date("Y-m-d H:i:s");
            $log->save();
    }
    public function actionReviewanswer()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $body = Yii::$app->request->getRawBody();
        $param = json_decode($body);
        $saveanswer = QuizResultDetail::find()->where(['quiz_result_id' => $param->quiz_result_id, 'question_id'=>$param->question_id])->one();
        $saveanswer->points = $param->points;
        $saveanswer->save();
        return ['param'=>$param, 'model'=>$saveanswer];

    }
    public function actionSaveanswer()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $body = Yii::$app->request->getRawBody();
        $param = json_decode($body);
        $saveanswer = QuizResultDetail::find()->where(['quiz_result_id' => $param->quiz_result_id, 'question_id'=>$param->question_id])->one();
        $saveanswer->answer = $param->answer;
       
        if ($saveanswer->type == 1 ||  $saveanswer->type == 2 || $saveanswer->type == 3 ){
            $saveanswer->correct_answer = str_replace(' ','',$saveanswer->correct_answer);
            $param->answer = str_replace(' ','',$param->answer);
           
            if ($param->answer == $saveanswer->correct_answer) {
                $saveanswer->points = $saveanswer->question->points;
            }else {
                $saveanswer->points = 0;
            }
            $saveanswer->answer = $param->answer;
        }
      
        $saveanswer->save();
        return ['param'=>$param, 'model'=>$saveanswer ];

    }
    
    public function actionShowresult()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $result_details = QuizResultDetail::find()->where(['quiz_result_id'=>$_GET['id']])->all();
        $correct = 0;
        $total_points = 0;
        foreach ($result_details as $detail) {
            if ($detail->correct_answer ==  $detail->answer) {
                $correct ++;
                $total_points+= $detail->points;
            }
        }
        $result = QuizResult::find()->where(['quiz_result_id' => $_GET['id']])->one();
        $result->result = $total_points;
        $result->submitted_time = date("Y-m-d H:i:s");
        $result->save();

        if ($_GET['quiz_type'] == 1) {
            // Lesson
            $lesson_for_user =  LessonForUser::find()->where([
                'lesson_id'=>$_GET['item'],
                'user_id'=>Yii::$app->user->id
            ])->one();
            $lesson_for_user->finished = 1;
            $lesson_for_user->finished_date = date("Y-m-d H:i:s");
            $lesson_for_user->save();
            $log_type = 4;
            $log_text = "Bạn đã hoàn thành nội dung ".$_GET['itemname'];
        }else {
            // Course
            $course_for_user =  CourseForUser::find()->where([
                'course_id'=>$_GET['item'],
                'user_id'=>Yii::$app->user->id
            ])->one();
            $course_for_user->finished = 1;
            $course_for_user->percentage = 100;
            $course_for_user->finished_date = date("Y-m-d H:i:s");
            // $course_for_user->result = $correct;
            // $course_for_user->total =  $result->total;
            $course_for_user->save();
            $log_type = 5;
            $log_text = "Bạn đã hoàn thành khóa học ".$_GET['itemname'];

            // Save course history
            $history = new CourseHistory();
            $history->course_id = $_GET['item'];
            $history->user_id = Yii::$app->user->id;
            $history->type = 3;
            $history->date = date("Y-m-d");
            $history->save();
        }
        $this->saveLog($log_type, $_GET['item'],$log_text);
        $this->saveLog(6, $_GET['quiz_id'],"Bạn đã hoàn thành bài kiểm tra ".$_GET['quiz_name']);
        return [
            'details' => $result_details,
            'correct' => $correct,
            'return' => $result,
            'total_points'=>$total_points
        ];
    }
    public function actionStore()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $body = Yii::$app->request->getRawBody();
        $param = json_decode($body);
        if ($param->quiz_id) {
            // update
            $model = Quiz::findOne($param->quiz_id);
            $model->modified_by = $param->user_id; 
        }else{
            // create
            $model = new Quiz();
            $model->created_by = $param->user_id;    
        }   
            // main data
            $model->name = $param->name;
            $model->short_desc = $param->short_desc;
            $model->number_questions = $param->number_questions;
            $model->quiz_type = $param->quiz_type;
            $model->course_id = $param->course_id;
            $model->lesson_id = $param->lesson_id;
            $model->total_points = $param->total_points;
            $model->minimum_points = $param->minimum_points;

            $model->item_id = $param->item_id;
            $model->state = $param->state;
            $model->published_start = $param->published_start;
            $model->published_end = $param->published_end;
            $model->slug = Inflector::slug($param->name);
            $model->time = $param->time;
            $model->category_id = $param->category_id;
            
            // save data
            if ($model->validate()) {
                $model->save();
                $param->quiz_id =  $model->quiz_id;
                return  $param;
            }else{
                return ['error'=>$model->errors];
            }
    }
    public function actionDelete()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $course = Quiz::findOne($_GET['id']);
        $course->state = -1;
        $course->save();
        return $course;
    }
    public function actionBulkdelete()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $body = Yii::$app->request->getRawBody();
        Quiz::updateAll(['state' => -1], ['quiz_id' => json_decode($body)]);

        //$courses = Quiz::deleteAll(['quiz_id' => json_decode($body)]);
        return $courses;

    }
    

  
}
