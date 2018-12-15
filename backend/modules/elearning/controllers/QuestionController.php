<?php

namespace backend\modules\elearning\controllers;
use common\modules\api\models\elearning\Category;

use Yii;

use yii\web\Controller;


/**
 * QuestionController implements the CRUD actions for Question model.
 */
class QuestionController extends Controller
{
    public function actionIndex()
    {
        return $this->render('vue',['cateogries'=>Category::find()]);
    }
}
