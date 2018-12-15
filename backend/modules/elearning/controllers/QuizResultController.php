<?php

namespace backend\modules\elearning\controllers;

use Yii;
use yii\web\Controller;


/**
 * MemberController implements the CRUD actions for Member model.
 */
class QuizResultController extends Controller
{
    public function actionIndex()
    {
        return $this->render('vue');
    }
}
