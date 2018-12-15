<?php

namespace backend\controllers;

use Yii;
use backend\models\Role;
use backend\models\RoleSearch;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * RoleController implements the CRUD actions for Role model.
 */
class RoleController extends AcpController {
    public function getControllerLabel() {
        return 'Quyền hạn';
    }
    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                    'delete-multiple' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Role models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new RoleSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Role model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($ri = '') {
        $model = new Role();

        if ($model->load(Yii::$app->request->post())) {
            $string = '';
            if (isset($_POST['Role']['acl_desc']) && $_POST['Role']['acl_desc'] == '') {
                unset($_POST['Role']['acl_desc']);
            } elseif ($_POST['Role']['acl_desc'] !== 'ALL_PRIVILEGES' && $_POST['Role']['acl_desc'] !== 'null') {
                $formArray = json_decode($_POST['Role']['acl_desc']);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $string = $model->formArrayToAclDesc($formArray);
                }else{
                    $string = $_POST['Role']['acl_desc'];
                }
            }else {
                $string = $_POST['Role']['acl_desc'];
            }
            if (!empty($_POST['Role']['list_status']))
                $model->list_status = serialize($_POST['Role']['list_status']);
            else
                $model->list_status = '';

            if (isset($_POST['Role']['role_setting']) && $_POST['Role']['role_setting'] != '')
                $model->role_setting = serialize($_POST['Role']['role_setting']);
            else
                $model->role_setting = '';

            $model->acl_desc = $string;
            if ($model->save()){
                //Create new tree role
                if (!empty($_POST['Role']['p_id'])){
                    $p_id = $_POST['Role']['p_id'];
                }else{
                    $p_id = 1;
                }
                $parent_modle = $this->findModel($p_id);
                $parent_p_role = $parent_modle->parentrole;
                $model->parentrole = $parent_p_role.'::'.$model->role_id;
                $model->depth = $parent_modle->depth + 1;
                $model->save();

                return $this->redirect(['index']);
            } else {
                print_r($model->getErrors());
            }
        } else {
            $ri = addslashes($ri);
            if(!empty($ri)){ //Có truyền vào Role Id;
                $model_seed = Role::findOne(['role_id' => $ri]);
                if($model_seed){
                    $model->role_label = $model_seed->role_label .' (Copy)';
                    $model->role_name =  $model_seed->role_name .'-copy';
                    $model->acl_desc = $model_seed->acl_desc;
                    $model->acl_type = $model_seed->acl_type;
                    $model->p_id = $model_seed->p_id;
                    $model->status = $model_seed->status;
                    $model->admin_use = $model_seed->admin_use;
                    $model->list_status = $model_seed->list_status;
                                    
                }
            }
            return $this->render('create', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Role model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id, $re = '') {
        $model = $this->findModel($id);
        
        if ($model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            $string = '';
            if (isset($_POST['Role']['acl_desc']) && $_POST['Role']['acl_desc'] == '') {
                unset($_POST['Role']['acl_desc']);
            } elseif ($_POST['Role']['acl_desc'] !== 'ALL_PRIVILEGES' && $_POST['Role']['acl_desc'] !== '') {
                $formArray = json_decode($_POST['Role']['acl_desc']);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $string = $model->formArrayToAclDesc($formArray);
                }else{
                    $string = $_POST['Role']['acl_desc'];
                }
            }else{
                $string = $_POST['Role']['acl_desc'];
            }
            if (!empty($_POST['Role']['list_status']))
                $model->list_status = serialize($_POST['Role']['list_status']);
            else
                $model->list_status = '';

            $model->acl_desc = $string;
            if (isset($_POST['Role']['role_setting']) && $_POST['Role']['role_setting'] != '')
                $model->role_setting = serialize($_POST['Role']['role_setting']);
            else
                $model->role_setting = '';
            //Create new tree role
            if (!empty($_POST['Role']['p_id'])){
                $p_id = $_POST['Role']['p_id'];
            }else{
                $p_id = 1;
            }
            $parent_modle = $this->findModel($p_id);
            $parent_p_role = $parent_modle->parentrole;
            $model->parentrole = $parent_p_role.'::'.$model->role_id;
            $model->depth = $parent_modle->depth + 1;

            if ($model->save()){

                $cache = Yii::$app->cache;
                $key = 'role_'.$id;  
                $cache->delete($key);  
                $key = 'role_'.$id.'_field';  
                $cache->delete($key);

                if($re == 'no'){
                    return 1;
                }else{
                    return $this->redirect(['index']);
                }
            }
            else
                if($re == 'no'){
                    return 0;
                }
                // print_r($model->getErrors());
        } else {
            return $this->render('update', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Role model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Role model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Role the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Role::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionDeleteMultiple() {
        $pk = Yii::$app->request->post('pk'); // Array or selected records primary keys

        if (!$pk) {
            return;
        }

        return Role::deleteAll(['role_id' => $pk]);
    }

}
