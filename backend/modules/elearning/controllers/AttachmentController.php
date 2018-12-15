<?php

namespace backend\modules\elearning\controllers;

use Yii;
use yii\web\Controller;
class AttachmentController extends Controller
{
    public function actionIndex()
    {
        return $this->render('vue');
    }
}
