<?php

namespace common\modules\api\controllers\elearning;

use Yii;
use common\modules\api\models\elearning\Feedback;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Inflector;
use common\components\PaginationHelper;
use common\components\UploadImageHelper;

class FeedbackController extends Controller
{
    protected $per_page = 10;
    public function actionList()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
       
        $query = Feedback::find();
        $search_body = Yii::$app->request->getRawBody();
        $param = json_decode($search_body);
        if ($param->name !="") {
            $query->andFilterWhere(
                ['like', 'name',$param->name]
            );
        }
        if ($param->position !="") {
            $query->andFilterWhere(
                ['like', 'position',$param->position]
            );
        }
        
        if ($param->state !="") {
            $query->andFilterWhere(
                ['=', 'state',$param->state]
            );
        }
        if ($param->id !="") {
            $query->andFilterWhere(
                ['=', 'id',$param->id]
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
            
        );
        return $result;
    }
    public function actionView()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $feedback = Feedback::findOne($_GET['id']);
        return $feedback;
    }
    public function actionStore()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $body = Yii::$app->request->getRawBody();
        $param = json_decode($body);
        if ($param->id) {
            // update
            $model = Feedback::findOne($param->id);
            $model->modified_by = $param->user_id; 
        }else{
            // create
            $model = new Feedback();
            $model->created_by = $param->user_id;    
        }   
            // main data
            $model->name = $param->name;
            $model->position = $param->position;
            $model->state = $param->state;
            $model->description = $param->description;
        
            $model->slug = Inflector::slug($param->name);

            if($param->source_is_changed) {
                $thumb = new UploadImageHelper($param->source, $model->slug, 'feedback' );
                $model->source = $thumb->imageName;
            }
         
            // save data
            if ($model->validate()) {
                $model->save();
                $param->id =  $model->id;
                return  $param;
            }else{
                return ["error"=>$model->errors];
            }
    }
    public function actionDelete()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $feedback = Feedback::findOne($_GET['id']);
        $feedback->delete();
        return $feedback;
    }
    public function actionBulkdelete()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $body = Yii::$app->request->getRawBody();
        $feedback = Feedback::deleteAll(['id' => json_decode($body)]);
        return $feedback;

    }
    

  
}
