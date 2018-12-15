<?php

namespace common\modules\api\controllers\elearning;

use Yii;
use common\modules\api\models\elearning\Category;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use common\components\PaginationHelper;
use common\components\UploadImageHelper;
use yii\helpers\Inflector;
/**
 * CourseCategoryController implements the CRUD actions for CourseCategory model.
 */
class CategoryController extends Controller
{
    //public $modelClass = 'backend\modules\elearning\models\CourseCategory';
    public $per_page = 50;
    /**
     * @inheritdoc
     
    
     * Lists all CourseCategory models.
     * @return mixed
     */
    public function actionIndex()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $category = new Category();
        $category->build_list();    
        $query = Category::find();
        $search_body = Yii::$app->request->getRawBody();
        $param = json_decode($search_body);
        if ($param->name !="") {
            $query->andFilterWhere(
                ['like', 'name',$param->name]
            );
        }
        if ($param->parent !="") {
            $query->andFilterWhere(
                ['=', 'parent_id',$param->parent->value]
            );
        }
        if ($param->state !="") {
            $query->andFilterWhere(
                ['=', 'state',$param->state]
            );
        }
        if ($param->id !="") {
            $query->andFilterWhere(
                ['=', 'category_id',$param->id]
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
    public function actionVue()
    {
        return $this->render('vue');
    }
    public function actionList()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $result = new Category();
        $result->build_list();
        return $result->_cats;
    }
    

    /**
     * Displays a single CourseCategory model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new CourseCategory model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionStore()
    {    
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $body = Yii::$app->request->getRawBody();
        $param = json_decode($body);
        if ($param->category_id) {
            // update
            $model = Category::findOne($param->category_id);
            $model->modified_by = $param->user_id; 
        }else{
            // create
            $model = new Category(); 
            $model->created_by = $param->user_id; 
        }
            $model->name = $param->name;
            $model->description = $param->description;
            $model->ordering = $param->ordering;
            if ($param->parent_id->value)
                $model->parent_id = $param->parent_id;
            else {
                $model->parent_id = 0;
            }    
            $model->state = $param->state;
            $model->slug = Inflector::slug($param->name);
            if($param->thumbnail_is_changed) {
                $thumb = new UploadImageHelper($param->thumbnail, $model->slug, 'category' );
                $model->thumbnail = $thumb->imageName;
            }
            if ($model->validate()) {
                $model->save();
                $param->category_id =  $model->category_id;
                return ["success"=>$param];
            }else{
                return ["error"=>$model->errors];
            }
            
           
           
        
    }

    /**
     * Updates an existing CourseCategory model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
 

    /**
     * Deletes an existing CourseCategory model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $item = Category::findOne($_GET['id']);
        $item->state = -1;
        $item->save();
        return $item;
    }
    public function actionBulkdelete()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $body = Yii::$app->request->getRawBody();
        Category::updateAll(['state' => -1], ['category_id' => json_decode($body)]);

       // $courses = Category::deleteAll(['category_id' => json_decode($body)]);
        return $courses;

    }

    /**
     * Finds the CourseCategory model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CourseCategory the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CourseCategory::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
