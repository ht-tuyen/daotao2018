<?php

namespace backend\controllers;

use Yii;
use backend\models\Chuyenmuc;
use backend\models\ChuyenmucSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use kartik\form\ActiveForm;

/**
 * ChuyenmucController implements the CRUD actions for Chuyenmuc model.
 */
class ChuyenmucController extends Controller
{
    public function getControllerLabel() {
        return 'Chuyên mục';
    }

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                    'deletem' => ['POST'],
                    'updatem' => ['POST'],
                    'delete-multiple' => ['POST'],
                    'delete-select' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Chuyenmuc models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ChuyenmucSearch();
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
     * Displays a single Chuyenmuc model.
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
     * Creates a new Chuyenmuc model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Chuyenmuc();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->cm_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Chuyenmuc model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->cm_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Chuyenmuc model.
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
     * Finds the Chuyenmuc model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Chuyenmuc the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Chuyenmuc::findOne($id)) !== null) {
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
        $model = new Chuyenmuc();
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
        if(Chuyenmuc::deleteAll(['cm_id' => $pk])){
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

        return Chuyenmuc::deleteAll(['cm_id' => $pk]);
    }
}
