<?php

namespace common\modules\api\controllers\elearning;

use Yii;

use common\modules\api\models\elearning\Lesson;
use common\modules\api\models\elearning\Message;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\components\PaginationHelper;

class MessageController extends Controller
{
    protected $per_page = 10;
    public function actionList()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
      
        $query = Message::find()->where(['lesson_id'=>$_GET['id'], 'parent_id'=>0]);
        
        $countQuery = clone $query;
        $pagination = new PaginationHelper($countQuery->count(),$_GET['page'], $this->per_page );
        $models = $query->offset($pagination->from)
        ->limit($this->per_page)
        ->orderBy('message_id DESC')
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
            "page"=>$_GET['page'],
            
        );
       
        return $result;
    }
    // DONT USE
    public function actionView()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $course = Lesson::find()
            ->where(['or',
            ['slug'=>$_GET['id']],
            ['lesson_id'=>$_GET['id']]
        ])->one();;
        return $course;
    }

    public function actionStore()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $body = Yii::$app->request->getRawBody();
        $param = json_decode($body);
        
            // create
            $model = new Message();
            $model->user_id = $param->user_id;    
        
            // main data
            $model->message = $param->message;
            $model->parent_id = $param->parent_id;
            $model->lesson_id = $param->lesson_id;
            $model->is_admin = $param->is_admin;
           
           
            $model->status = 1;
            $model->save();
            
            return $model;
           
    }
    public function actionDelete()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $course = Message::findOne($_GET['id']);
        $course->delete();
        return $course;
    }
    
    

  
}
