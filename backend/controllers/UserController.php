<?php

namespace backend\controllers;

use backend\models\ChangePasswordForm;
use Yii;
use backend\models\User;
use backend\models\UserSearch;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use backend\helpers\AcpHelper;
use kartik\form\ActiveForm;
/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends AcpController
{
    public function getControllerLabel() {
        return 'Người dùng truy cập hệ thống';
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
                    'index' => ['GET','POST'],
                    'create' => ['POST'],
                    'update' => ['POST'],
                    'delete' => ['POST'],
                    'deletem' => ['POST'],
                    'delete-multiple' => ['POST'],
                    'pq' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            return $this->renderAjax('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        }else{
            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        }
    }

    /**
     * Displays a single User model.
     * @param integer $id
     * @return mixed
     */
    public function actionViewm($id)
    {
        $model = $this->findModel($id);
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if($model) return $this->renderAjax('view', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreatem()
    {
        $model = new User();
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if ($model->load(Yii::$app->request->post())) {
            if(Yii::$app->request->post('ajax')) return ActiveForm::validate($model);

            $salt = Yii::$app->params['salt'];
            $newpass = $model->password;
            $model->password = User::hashUserPassword($model->password, $salt);
            $model->email = $model->username;
            $model->salt = $salt;

            $noidung = '<b>Thông tin tài khoản</b><br/>
                        Tên đăng nhập: <b>'.$model->username.'</b><br/>
                        Mật khẩu: '.$newpass.'<br/>
                        Địa chỉ truy cập: <a href="http://'.$_SERVER["HTTP_HOST"].'/acp">http://'.$_SERVER["HTTP_HOST"].'/acp</a>';

            $message = Yii::$app->mailer->compose()
                                    ->setFrom(['tuyen.nt@htecom.vn' => 'Elearning - TCVN'])                                    
                                    ->setTo($model->email)
                                    ->setSubject('Tạo tài khoản truy cập')
                                    ->setHtmlBody($noidung);
            $message->send();


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

   
    public function actionPq($id = '') //Phân quyền
    {
        if(AcpHelper::check_role('phanquyen','Thanhvien')){
        }else{
            return '<div><h1>Thông báo</h1><h2>Bạn không có quyền.</h2></div>';
        }


        $model = $this->findModel($id);
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if ($model->load(Yii::$app->request->post())) {
            if(Yii::$app->request->post('ajax')) return ActiveForm::validate($model);
            
            if ($model->save(false)) {                
                $datajson = '1';
            }else{
                $datajson = '0';
            }
            $return['status'] = $datajson;
            return $return;
        } else {
            return $this->renderAjax('_pq', [
                'model' => $model,
            ]);
        }
    }


    public function actionUpdatem($id = '')
    {
        if(empty($id)) $id = Yii::$app->user->identity->user_id;

        $model = $this->findModel($id);
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if ($model->load(Yii::$app->request->post())) {
            if(Yii::$app->request->post('ajax')) return ActiveForm::validate($model);
            
            $change_pass = 'false';
            if ((int)$_POST['User']['chg_password'] == 1) {
                $change_pass = 'true';
                $password = $_POST['User']['password'];
                $salt = Yii::$app->params['salt'];
                $model->password = User::hashUserPassword($_POST['User']['password'], $salt);
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
                $datajson = '1';
                // return $this->redirect(['index']);
            }else{
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

    public function actionChangeInfo()
    {
        $id = Yii::$app->user->identity->user_id;

        $model = $this->findModel($id);
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if ($model->load(Yii::$app->request->post())) {
            if(Yii::$app->request->post('ajax')) return ActiveForm::validate($model);
            
            $change_pass = 'false';
            if ((int)$_POST['User']['chg_password'] == 1) {
                $change_pass = 'true';
                $password = $_POST['User']['password'];
                $salt = Yii::$app->params['salt'];
                $model->password = User::hashUserPassword($_POST['User']['password'], $salt);
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

//        return $this->redirect(['index']);
    }


    public function actionDeletem($id)
    {
        return $this->findModel($id)->delete();
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
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

        return User::deleteAll(['user_id' => $pk]);
    }

    public function actionChangePassword()
    {
        $id = Yii::$app->user->identity->user_id;
        $model = new ChangePasswordForm();
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if ($model->load(Yii::$app->request->post())) {
            if(Yii::$app->request->post('ajax')) return ActiveForm::validate($model);        
                try {
                    $user = User::find()->where([
                        'user_id' => $id
                    ])->one();

                    $user->password= User::hashUserPassword($_POST['ChangePasswordForm']['password'], $user->salt);

                    if ($user->save()) {                        
                        return 1;
                    } else {                
                        Yii::error(ActiveForm::validate($user));
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

}
