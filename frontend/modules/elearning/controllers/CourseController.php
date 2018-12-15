<?php

namespace frontend\modules\elearning\controllers;

class CourseController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

}
