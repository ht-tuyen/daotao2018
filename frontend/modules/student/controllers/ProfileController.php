<?php

namespace frontend\modules\student\controllers;
use yii\web\UploadedFile;

use Yii;
use yii\web\Controller;
use common\modules\api\models\elearning\Student;
/**
 * Default controller for the `student` module
 */
class ProfileController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
    public function actionEdit()
    {
        $id = Yii::$app->user->id;
        $model = $this->findModel($id);

        if (Yii::$app->request->post()) {
            // if ($model->load(Yii::$app->request->post())) {
            //$model->avatar = UploadedFile::getInstance($model, 'avatar');
            //$model->avatar->saveAs(Yii::getAlias('@anyname') . '/uploads/avatars/' . $model->avatar->name );
            //var_dump($_FILES['upload_avatar']);
            if ($_FILES["upload_avatar"]["name"]) {
                $target_dir = Yii::getAlias('@anyname') . '/uploads/avatars/';
                $target_file = $target_dir . basename($_FILES["upload_avatar"]["name"]);
                $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
                move_uploaded_file($_FILES["upload_avatar"]["tmp_name"], $target_file);
            }
            

            //exit();
            $model->username = Yii::$app->request->post('username');
            $model->full_name = Yii::$app->request->post('full_name');
            $model->address = Yii::$app->request->post('address');
            $model->mobile = Yii::$app->request->post('mobile');
            $model->dob = date("Y-m-d",strtotime(Yii::$app->request->post('dob')));
            $model->gender = Yii::$app->request->post('gender');
            $model->company =Yii::$app->request->post('company');
            //$model->position =Yii::$app->request->post('position');
            $model->email = Yii::$app->request->post('email');
            if (Yii::$app->request->post('password'))
                $model->password_hash = Yii::$app->security->generatePasswordHash(Yii::$app->request->post('password'));
            if ($_FILES["upload_avatar"]["name"])
                $model->avatar = $_FILES["upload_avatar"]["name"];
            $model->academic = Yii::$app->request->post('academic');
            $model->degree = Yii::$app->request->post('degree');
            $model->bio = Yii::$app->request->post('bio');
            $model->save();
            Yii::$app->session->setFlash('success', 'Cập nhật thông tin thành công');
            return $this->redirect('/student/profile/edit');
        } else {
            return $this->render('edit', [
                'model' => $model,
            ]);
        }
        return $this->render('edit');
    }
    public function actionQuizresult()
    {
        return $this->render('results');
    }
    protected function findModel($id)
    {
        if (($model = Student::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    public function actionChangepass()
    {
        $request = Yii::$app->request;

        $id = Yii::$app->user->id;
        $model = $this->findModel($id);

        if (Yii::$app->request->post()) {
            $old_pass=  $request->post('old_password');
            $password = $request->post('password');
            $confirm_password = $request->post('confirm_password');

            $check_pass = Yii::$app->security->validatePassword($old_pass, $model->password_hash);
            if (!$check_pass) {
                $error="Mật khẩu hiện tại không đúng";
            }elseif ($password != $confirm_password) {
                $error="Mật khẩu xác nhận không khớp";
            }else{
                $model->password_hash = Yii::$app->security->generatePasswordHash($password);
                $model->save();
                $sucess = "Đổi mật khẩu thành công";

            }
            
            return $this->render('changepass',[
                'error' => $error,
                'success' => $sucess
            ]);
        } else {
            return $this->render('changepass', [
                'model' => $model,
                
            ]);
        }
        return $this->render('changepass');
    }
}
