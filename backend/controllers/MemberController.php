<?php

namespace backend\controllers;

use backend\models\ChangePasswordForm;
use backend\models\Tieuchuan;
use frontend\models\Order;
use frontend\models\OrderSearch;
use Yii;
use backend\models\Member;
use backend\models\MemberSearch;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use kartik\form\ActiveForm;

/**
 * MemberController implements the CRUD actions for Member model.
 */
class MemberController extends AcpController
{
    public function getControllerLabel()
    {
        return 'Khách hàng';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'index' => ['GET', 'POST'],
                    'create' => ['POST'],
                    'update' => ['POST'],
                    'delete' => ['POST'],
                    'delete-multiple' => ['POST'],
                    'delete-select' => ['POST'],

                    'delete-order-select' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Member models.
     * @return mixed
     */
    public function actionIndex($t = '20')
    {
        $searchModel = new MemberSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->pagination->pageSize=$t;

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
     * Displays a single Member model.
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
     * Creates a new Member model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreatem()
    {
        $model = new Member();
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if ($model->load(Yii::$app->request->post())) {
            if (Yii::$app->request->post('ajax')) return ActiveForm::validate($model);

            $salt = Yii::$app->params['salt'];
            $model->password = Member::hashMemberPassword($model->password, $salt);
            $model->salt = $salt;

            if ($model->save()) {
                $datajson = '1';
            } else {
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

    public function actionViewm($id = '' )
    {
        $model = $this->findModel($id);
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;        
        return $this->renderAjax('view', [
            'model' => $model,
        ]);
        
    }
    
    public function actionUpdatem($id = '' )
    {
        if (empty($id)) $id = Yii::$app->user->identity->user_id;

        $model = $this->findModel($id);
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if ($model->load(Yii::$app->request->post())) {
            if (Yii::$app->request->post('ajax')) return ActiveForm::validate($model);

            $change_pass = 'false';
            if ((int)$_POST['Member']['chg_password'] == 1) {
                $change_pass = 'true';
                $password = $_POST['Member']['password'];
                $salt = Yii::$app->params['salt'];
                $model->password = Member::hashMemberPassword($_POST['Member']['password'], $salt);
                $model->salt = $salt;
            }

            if ($model->save(false)) {
                if ($change_pass == 'true') {
                    Yii::$app->mailer->compose('mail_change_pass', ['password' => $password, 'user' => $model])
                        ->setFrom(['cuongphu.nd16@gmail.com' => 'Vsqi'])
                        ->setTo($model->email)
                        ->setSubject('[Vsqi] Thông báo thay đổi mật khẩu người dùng')
                        ->send();
                    $arr_['status'] = 'success';
                }
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

    public function actionDeleteSelect()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $pk = Yii::$app->request->post('pk');
        if (!$pk) {
            return 0;
        }
        if(Member::deleteAll(['user_id' => $pk])){
            Order::deleteAll(['member_id' => $pk]);
            return 1;
        }else{
            return 0;
        }
    }


    public function actionChangeInfo()
    {
        $id = Yii::$app->user->identity->user_id;

        $model = $this->findModel($id);
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if ($model->load(Yii::$app->request->post())) {
            if (Yii::$app->request->post('ajax')) return ActiveForm::validate($model);

            $change_pass = 'false';
            if ((int)$_POST['Member']['chg_password'] == 1) {
                $change_pass = 'true';
                $password = $_POST['Member']['password'];
                $salt = Yii::$app->params['salt'];
                $model->password = Member::hashMemberPassword($_POST['Member']['password'], $salt);
                $model->salt = $salt;
            }

            if ($model->save(false)) {
                if ($change_pass == 'true') {
                    // Yii::$app->mailer->compose('mail_change_pass', ['password' => $password, 'user' => $model])
                    //     ->setFrom(['cuongphu.nd16@gmail.com' => 'QuanLyIn'])
                    //     ->setTo($model->email)
                    //     ->setSubject('[QuanlyIn] Thông báo thay đổi mật khẩu người dùng')
                    //     ->send();
                    $arr_['status'] = 'success';
                }
                return 1;
                // return $this->redirect(['index']);
            }
        } else {
            return $this->renderAjax('changeInfo', [
                'model' => $model,
            ]);
        }
    }


    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

       return $this->redirect(['index']);
    }

    /**
     * Finds the Member model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Member the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Member::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionDeleteMultiple()
    {
        $pk = Yii::$app->request->post('pk'); // Array or selected records primary keys

        if (!$pk) {
            return;
        }

        return Member::deleteAll(['user_id' => $pk]);
    }

    public function actionChangePassword()
    {
        $model = new ChangePasswordForm();
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if ($model->load(Yii::$app->request->post())) {
            if (Yii::$app->request->post('ajax')) return ActiveForm::validate($model);
            try {
                $user = Member::find()->where([
                    'user_id' => Yii::$app->user->identity->user_id
                ])->one();
                $user->password = Member::hashMemberPassword($_POST['ChangePasswordForm']['password'], $user->salt);

                if ($user->save()) {
                    return 1;
                } else {
                    return 0;
                }
            } catch (Exception $e) {
                return 0;
            }
        } else {
            return $this->renderAjax('changePassword', [
                'model' => $model
            ]);
        }

    }

    /**
     * Displays a single Member model.
     * @param integer $id
     * @return mixed
     */
    public function actionOrder($id = '')
    {
        $model = $this->findModel($id);
        $list_order = Order::find()->where(['member_id' => $id])->orderBy(['create_time' => SORT_DESC])->all();
//        print_r($list_order);
        return $this->renderAjax('order', [
            'model' => $model,
            'list_order' => $list_order
        ]);
    }

    public function actionListOrder($t = '20')
    {   
        $searchModel = new OrderSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->setSort([
           'defaultOrder' => ['order_id'=>SORT_DESC]
        ]);
        $dataProvider->pagination->pageSize=$t;
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            return $this->renderAjax('listorder', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        }else{
            return $this->render('listorder', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        }
    }

    public function actionOrderView($id = '')
    {           
        $model = Order::find()->where(['order_id' => $id])->One();
        if($model){            
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            if ($model->load(Yii::$app->request->post())) {
                if($model->save()){
                    $return['status'] = 1;
                }else{
                    $return['status'] = 0;
                }
                return $return;
            }else{
                if($model->tinhtrang == Order::CHUAXULY){                    
                    $model->tinhtrang = Order::DAXEM;
                    $model->save();                    
                }
                return $this->renderAjax('orderview', [
                    'model' => $model,
                ]);
            }
        }        
    }

    public function actionOrderDelete($id = '')
    {   
        if (($model = Order::findOne($id)) !== null) {
            return $model->delete();
        }        
    }

     public function actionDeleteOrderSelect()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $pk = Yii::$app->request->post('pk');
        if (!$pk) {
            return 0;
        }
        if(Order::deleteAll(['order_id' => $pk])){
            return 1;
        }else{
            return 0;
        }
    }

}
