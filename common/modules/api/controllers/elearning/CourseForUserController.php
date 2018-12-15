<?php

namespace common\modules\api\controllers\elearning;

use Yii;
use common\modules\api\models\elearning\CourseForUser;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Inflector;


class AttachmentController extends Controller
{
    protected $per_page = 10;
    public function actionList()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        
        $query = CourseForUser::find();
        $search_body = Yii::$app->request->getRawBody();
        $param = json_decode($search_body);
        if ($param->name !="") {
            $query->andFilterWhere(
                ['like', 'name',$param->name]
            );
        }
        if ($param->category !="") {
            $query->andFilterWhere(
                ['=', 'category_id',$param->category->value]
            );
        }
        if ($param->state !="") {
            $query->andFilterWhere(
                ['=', 'state',$param->state]
            );
        }
        if ($param->id !="") {
            $query->andFilterWhere(
                ['=', 'attachment_id',$param->id]
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
            "categories"=>$category->_cats
        );
        return $result;
    }
    public function actionView()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $attachment = Attachment::findOne($_GET['id']);
        return $attachment;
    }
    public function actionStore()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $body = Yii::$app->request->getRawBody();
        $param = json_decode($body);
        if ($param->attachment_id) {
            // update
            $model = Attachment::findOne($param->attachment_id);
            $model->modified_by = 1; 
        }else{
            // create
            $model = new Attachment();
            $model->created_by = 1;    
        }   
            // main data
            $model->name = $param->name;
           
            $model->description = $param->description;
          
            $model->category_id = $param->category_id->category_id;
            $model->state = $param->state;
            $model->slug = Inflector::slug($param->name);

            if($param->source_is_changed) {
                $thumb = new UploadImageHelper($param->thumbnail, $model->slug, 'attachment' );
                $model->source = $thumb->imageName;
            }
         
            // save data
            $model->save();
            $param->attachment_id =  $model->attachment_id;
            return  $param;
    }
    public function actionDelete()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $attachment = Attachment::findOne($_GET['id']);
        $attachment->delete();
        return $attachment;
    }
    public function actionBulkdelete()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $body = Yii::$app->request->getRawBody();
        $attachment = Attachment::deleteAll(['attachment_id' => json_decode($body)]);
        return $attachment;

    }
    

  
}
