<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use backend\models\Log;
use \backend\models\Role;

abstract class AcpController extends Controller
{

    abstract public function getControllerLabel();

    public function init()
    {
        parent::init();
    }
   

    public function beforeAction($action)
    {
        $session = Yii::$app->session;
        $use_stmp = Yii::$app->settings->get('stmp_use');
        if ($use_stmp == 1) {
            if (Yii::$app->settings->get('stmp_ssl') == 1)
                $ssl = 'ssl';
            elseif (Yii::$app->settings->get('stmp_ssl') == 2)
                $ssl = 'tls';
            else
                $ssl = '';
            Yii::$app->set('mailer', [
                'class' => 'yii\swiftmailer\Mailer',
                'transport' => [
                    'class' => 'Swift_SmtpTransport',
                    // Values from db
                    'streamOptions' => [
                        'ssl' => [
                            'allow_self_signed' => true,
                            'verify_peer' => false,
                            'verify_peer_name' => false,
                        ],
                    ],
                    'host' => Yii::$app->settings->get('stmp_server'),
                    'username' => Yii::$app->settings->get('stmp_username'),
                    'password' => Yii::$app->settings->get('stmp_password'),
                    'port' => Yii::$app->settings->get('stmp_port'),
                    'encryption' => $ssl,
                ],
            ]);
        }
        // if (!Yii::$app->user->isGuest) {
        //     $role = Role::findOne(Yii::$app->user->identity->role_id);
        //     // allow quản trị
        //     if ($role && $role->admin_use == 1) return true;
        // }

        // if (!Yii::$app->user->isGuest) {
        //     $roleAcl = Role::getOneAclArray(Yii::$app->user->identity->role_id);
        //     $session['allowActions'] = $roleAcl;
        // } else {
        //     $session['allowActions'] = '';
        // }
        // $this_controller = ucfirst(Yii::$app->controller->id);
        // if (Yii::$app->controller->id === 'site' || $session->get('allowActions') === 'ALL_PRIVILEGES' || Yii::$app->request->isAjax || (isset($session->get('allowActions')[$this_controller]) && in_array(strtolower(Yii::$app->controller->action->id), (array)$session->get('allowActions')[$this_controller]))) {
        //     return true;
        // } else {
        //     $this->redirect(array('site/notice'));
        // }
        return parent::beforeAction($action);
    }

    public static function settingGet($key = NULL)
    {
        if ($key == NULL)
            return false;
        return Yii::$app->settings->get($key);
    }
}
