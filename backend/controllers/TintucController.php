<?php

namespace backend\controllers;

use Yii;
use backend\models\Tintuc;
use backend\models\TintucSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use kartik\form\ActiveForm;

/**
 * TintucController implements the CRUD actions for Tintuc model.
 */
class TintucController extends Controller
{
     public function getControllerLabel() {
        return 'Bài viết';
    }

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                    'updatem' => ['POST'],
                    'delete-multiple' => ['POST'],

                    'delete-select' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Tintuc models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TintucSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            return $this->renderAjax('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        } else {
            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        }
    }

    /**
     * Displays a single Tintuc model.
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
     * Creates a new Tintuc model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Tintuc();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->tt_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Tintuc model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->tt_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Tintuc model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

     public function actionDeletem($id)
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if($this->findModel($id)->delete()){            
            $datajson =  '1';                
        }else{
            $datajson = '0';
        }
        return $datajson;        
    }

    /**
     * Finds the Tintuc model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Tintuc the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Tintuc::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionUpdatem($id = '')
    {

        $model = $this->findModel($id);
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if ($model->load(Yii::$app->request->post())) {
            if (Yii::$app->request->post('ajax')) return ActiveForm::validate($model);

            $model->anhdaidien = $model->uploadanhdaidien();
            if ($model->save(false)) {

                $datajson = '1';
                // return $this->redirect(['index']);
            } else {
                $datajson = '0';
            }
            $return['status'] = $datajson;
            return $return;
        } else {
            return $this->renderAjax('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreatem()
    {
        $model = new Tintuc();
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if ($model->load(Yii::$app->request->post())) {
            if(Yii::$app->request->post('ajax')) return ActiveForm::validate($model);
            $model->anhdaidien = $model->uploadanhdaidien();
            if($model->save()){
                $datajson =  '1';
            }else{
                $datajson = '0';
                Yii::error($model->errors);
            }
            $return['status'] = $datajson;
            return $return;
        } else {
            return $this->renderAjax('create', [
                'model' => $model,
            ]);
        }
    }

     public function actionDeleteSelect()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $pk = Yii::$app->request->post('pk');
        if (!$pk) {
            return 0;
        }
        if(Tintuc::deleteAll(['tt_id' => $pk])){
            return 1;
        }else{
            return 0;
        }
    }


    public function actionDeleteMultiple()
    {
        $pk = Yii::$app->request->post('pk'); // Array or selected records primary keys

        if (!$pk) {
            return;
        }
        return 1;
        // return Tintuc::deleteAll(['tt_id' => $pk]);
    }
}
