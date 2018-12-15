<?php

namespace backend\modules\elearning\controllers;
use common\modules\api\models\elearning\Category;

use Yii;
use yii\web\Controller;


/**
 * QuizController implements the CRUD actions for Quiz model.
 */
class QuizController extends Controller
{
    public function actionIndex()
    {
        return $this->render('vue');
    }
    public function actionCreate($type, $id, $name, $category_id)
    {
        return $this->render('create',['type'=>$type, 'id'=>$id,'name'=>$name, 'category_id'=>$category_id]);
    }
    public function actionUpdate($type, $id, $name, $quiz_id, $category_id)
    {
        return $this->render('update',['type'=>$type, 'id'=>$id,'name'=>$name, 'quiz_id'=>$quiz_id, 'cateogries'=>Category::find(), 'category_id'=>$category_id]);
    }
}
