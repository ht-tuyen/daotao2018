<?php
/**
 * Created by PhpStorm.
 * User: Mr.Phu
 * Date: 19/05/2017
 * Time: 11:23 AM
 */

namespace common\components;

use Yii;
use yii\web\Controller;
use yii\helpers\Url;

class XpController extends Controller{
    public function init() {
        // $route = $this->route === null ? Yii::$app->controller->getRoute() : $this->route;
        // if (Url::base() == '/frontend/web' && $route != 'baogia' || ($route == 'site' && !isset($_GET['r']) || (isset($_GET['r']) && $_GET['r'] != 'gii')))
        //     $this->redirect(Yii::$app->request->hostInfo.'/acp');

    }
}