<?php

namespace common\modules\api\controllers\elearning;

use Yii;
use common\modules\api\models\elearning\Banner;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Inflector;
use common\components\PaginationHelper;
use common\components\UploadImageHelper;

class BannerController extends Controller
{

    protected $per_page = 10;
    public function actionList()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
       
        $query = Banner::find();
        $search_body = Yii::$app->request->getRawBody();
        $param = json_decode($search_body);
                
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
        $banner = Banner::findOne($_GET['id']);
        return $banner;
    }
    public function actionStore()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $body = Yii::$app->request->getRawBody();
        $param = json_decode($body);


        if ($param->id) {
            // update
            $model = Banner::findOne($param->id);
            $model->modified_by = $param->user_id; 
        }else{
            // create
            $model = new Banner();
            $model->created_by = $param->user_id;    
        }   
            // main data
          
            $model->state = $param->state;
            $model->ordering = $param->ordering;
            

            if($param->source_is_changed) {
                $thumb = new UploadImageHelper($param->source, time(), 'banner' );
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
        $banner = Banner::findOne($_GET['id']);
        $banner->delete();
        return $banner;
    }
    public function actionBulkdelete()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $body = Yii::$app->request->getRawBody();
        $banner = Banner::deleteAll(['id' => json_decode($body)]);
        return $banner;

    }
    

  
}
