<?php

namespace backend\controllers;

// use backend\models\Customer;
// use backend\models\OrderInfo;
// use backend\models\Supplier;
use Yii;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use backend\models\LoginForm;
use backend\models\PasswordResetRequestForm;
use backend\models\ResetPasswordForm;
use backend\models\User;
use backend\models\Thanhvien;
use backend\models\Bankythuat;
// use backend\models\Orders;

/**
 * Site controller
 */
class SiteController extends AcpController
{

    public function getControllerLabel()
    {
        return 'Dashboard';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['notice'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'lichhop' => ['post'],
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                // 'class' => 'yii\captcha\CaptchaAction',
                'class' => 'common\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,                
                'height' => 30,
                'maxLength' => 3,
                'minLength' => 3,                
            ],
        ];
    }



   public function actionLichhop()
    {
        // if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        //idnguoinhan sẽ lấy từ id user đang đăng nhập, ở đây VD la 46
            $idnguoinhan = 46;
            return $this->render('lichhop', [            
                'idnguoinhan' => $idnguoinhan,
            ]);       
        // }
    }



    public function actionIndex()
    {
         if(!empty(Yii::$app->user->identity->idthanhvien)){
            $all = Thanhvien::findOne(['tv_id' => Yii::$app->user->identity->idthanhvien]);
            $tb = Bankythuat::find()->select('bkt_id')->andWhere(['idtruongban' => Yii::$app->user->identity->idthanhvien])->column();
            $tk = Bankythuat::find()->select('bkt_id')->andWhere(['like','idthuky','"'.Yii::$app->user->identity->idthanhvien.'"'])->column();  
        }
        $session = Yii::$app->session;
        $list = [];

        if($all){
            $list_tatca = $all->getIdbankythuats()->select('bkt_id')->column();
            $list['tatca'] = $list_tatca; //Danh sách các bkt người này có trong đó
        }
        if($tb) $list['truongban'] = $tb; //Danh sách các bkt người này làm trưởng ban
        if($tk) $list['thuky'] = $tk; //Danh sách các bkt người này làm thư ký

        if(is_array($list_tatca) && (is_array($td) || is_array($tk))){
            $list['thanhvien'] = array_diff($list_tatca, array_merge($tb, $tk));            
            $session['user_list_bkt'] = $list;
        }
        
        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        $session = Yii::$app->session;        
        $so_lan_that_bai = (int)$session['so_lan_that_bai'];


        if($so_lan_that_bai < 5){            
            $session['time_lock'] = '';
        }else{
            if(empty($session['time_lock']))
                $session['time_lock'] = time() + (10*60);
        }

        if(!empty($session['time_lock'])){
            if(time() < $session['time_lock']){
                $lock = 'lock';            
            }else{
                $session['so_lan_that_bai'] = 0;
                $session['time_lock'] = ''   ;
            }
        }


        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {

            if(!empty(Yii::$app->user->identity->idthanhvien)){
                $all = Thanhvien::findOne(['tv_id' => Yii::$app->user->identity->idthanhvien]);
                $tb = Bankythuat::find()->select('bkt_id')->andWhere(['idtruongban' => Yii::$app->user->identity->idthanhvien])->column();
                $tk = Bankythuat::find()->select('bkt_id')->andWhere(['like','idthuky','"'.Yii::$app->user->identity->idthanhvien.'"'])->column();  
            }
            $session = Yii::$app->session;
            $list = [];

            if($all){
                $list_tatca = $all->getIdbankythuats()->select('bkt_id')->column();
                $list['tatca'] = $list_tatca; //Danh sách các bkt người này có trong đó
            }
            if($tb) $list['truongban'] = $tb; //Danh sách các bkt người này làm trưởng ban
            if($tk) $list['thuky'] = $tk; //Danh sách các bkt người này làm thư ký

            if(is_array($list_tatca) && (is_array($td) || is_array($tk))){
                $list['thanhvien'] = array_diff($list_tatca, array_merge($tb, $tk));            
                $session['user_list_bkt'] = $list;
            }

            // echo '<pre>';
            // $user_list_bkt = $session->get('user_list_bkt');
            // print_r($user_list_bkt);
            // echo '</pre>';
            // die;
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
                'lock' => $lock
            ]);
        }
    }

    public function actionResetPassword($token = '')
    {
        if(!empty(Yii::$app->user->identity->user_id)) return $this->goHome();

        $this->layout = 'main-login';
        if(empty($token)){                         
            $model = new PasswordResetRequestForm();
            if ($model->load(Yii::$app->request->post()) && $model->validate()) {

                $user = User::findOne([
                    'status' => User::STATUS_ACTIVE,
                    'email' => $model->email,
                ]);
                if (!$user) return false;                
                if (!User::isPasswordResetTokenValid($user->token)) {
                    $user->generatePasswordResetToken();
                    if (!$user->save()) {
                        return false;
                    }
                }          

                // echo '<pre>';
                // print_r($user);
                // echo '</pre>';
                // die;

                Yii::$app->mailer
                    ->compose(
                        ['html' => 'passwordResetToken-html', 'text' => 'passwordResetToken-text'],
                        ['user' => $user]
                    )
                    // ->compose()
                    ->setFrom([Yii::$app->params['testEmail'] => 'VSQI'])                    
                    ->setTo($model->email)
                    ->setSubject('Khôi phục mật khẩu tài khoản')
                    ->send();

                // $noidung = 'Nội dung khôi phục mật khẩu';
                // $message = Yii::$app->mailer->compose()
                //             ->setFrom(['tuyen.nt@htecom.vn' => 'VQSI'])
                //             ->setTo($model->email)                            
                //             ->setHtmlBody($noidung);
                // $message->send();
                // return 'Vui lòng kiểm tra email';
                return $this->render('requestPasswordResetToken', [
                    'check_email' => 'Vui lòng kiểm tra email',
                ]);
                // return $this->goHome();               
            }
            return $this->render('requestPasswordResetToken', [
                'model' => $model,
            ]);
        }else{

            $exit = User::find()->andWhere(['token' => $token])->one();
            if(!$exit) return $this->goHome();

            try {
                $model = new ResetPasswordForm($token);
            } catch (InvalidParamException $e) {
                throw new BadRequestHttpException($e->getMessage());
            }

            if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
                return $this->render('resetPassword', [
                    'success' => 'Khôi phục thành công',
                ]);                
                // return $this->goHome();
            }

            return $this->render('resetPassword', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Notice action.
     *
     * @return string
     */
    public function actionNotice()
    {
        $messages = 'Bạn không đủ quyền để làm việc này';
        return $this->render('notice', [
            'messages' => $messages,
        ]);
    }

}
