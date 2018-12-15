<?php

namespace common\modules\api\controllers\elearning;

use Yii;
use common\modules\api\models\elearning\Course;

use common\modules\api\models\elearning\Log;
use common\modules\api\models\elearning\Quiz;
use common\modules\api\models\elearning\Question;
use common\modules\api\models\elearning\CourseForUser;

use common\modules\api\models\elearning\QuizResult;
use common\modules\api\models\elearning\QuizResultDetail;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Inflector;
use common\components\PaginationHelper;

class ResultController extends Controller
{
    public function actionIndex($id){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $results = QuizResultDetail::find()->where(['quiz_result_id'=>$id])->all();
        return [
            'results'=>$results
        ];
    }
    public function actionReview($id){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $quiz_result = QuizResult::findOne($id);
        $course_for_user = (new \yii\db\Query())
            ->select('course_for_user.id, quiz.minimum_points, quiz.total_points')
            ->from('course_for_user')
            ->innerJoin('course_item','course_item.course_id = course_for_user.course_id')
            ->innerJoin('quiz','quiz.course_id = course_item.course_id')
            ->innerJoin('quiz_result','quiz_result.quiz_id = quiz.quiz_id')
            ->where(['quiz_result.user_id'=>$quiz_result->user_id, 'quiz_result.quiz_result_id'=>$id])->one();
       
        $details = QuizResultDetail::find()->where(['quiz_result_id'=>$id])->all();
        $correct_points = 0;
        foreach ($details as $detail) {
            $correct_points+= $detail->points;
        }
        $quiz_result->result = $correct_points;
        $quiz_result->reviewed = 1;
        $quiz_result->reviewed_date = date("Y-m-d H:i:s");
        $quiz_result->save();

        // Save
        $course = CourseForUser::findOne($course_for_user['id']);
        $course->result = $correct_points;
        $course->total = $course_for_user['total_points'];
        $course->minimum_points = $course_for_user['minimum_points'];
        $course->finished = 1;
        $course->reviewed = 1;
        $course->save();

       
        return [
            'quiz_result'=>$quiz_result,
            'details'=>$details
        ];
    }
}