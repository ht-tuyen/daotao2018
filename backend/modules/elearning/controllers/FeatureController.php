<?php

namespace backend\modules\elearning\controllers;

use Yii;

use yii\web\Controller;


/**
 * QuestionController implements the CRUD actions for Question model.
 */
class FeatureController extends Controller
{
    public function actionIndex()
    {
        return $this->render('vue');
    }
}
