<?php

namespace backend\modules\elearning\controllers;

use yii\web\Controller;

/**
 * Default controller for the `course` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
    public function actionTest()
    {
        echo "ok";
        exit();
    }
}
