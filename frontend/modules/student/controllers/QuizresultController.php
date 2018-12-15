<?php

namespace frontend\modules\student\controllers;
use Yii;
use yii\web\Controller;
use common\modules\api\models\elearning\Student;
use common\modules\api\models\elearning\QuizResult;
/**
 * Default controller for the `student` module
 */
class QuizresultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        $id = Yii::$app->user->id;
        $results = QuizResult::find()
        ->where(['user_id' => $id])
        ->orderBy('quiz_result_id')
        ->all();

        return $this->render('index',['results'=>$results]);
    }
    public function actionEdit()
    {
        $id = Yii::$app->user->id;
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect('index');
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
}
