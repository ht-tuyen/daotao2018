<?php
namespace frontend\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use common\models\User;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use frontend\modules\news\models\NewsItem;
use yii\web\UploadedFile;
use common\modules\api\models\elearning\Log;
use common\modules\api\models\elearning\Category;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $categories = Category::find()->where(['state'=>1])->all();
        return $this->render('index',['categories'=>$categories]);
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->redirect('/student/history');
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            $log = new Log();
            $log->user_id = Yii::$app->user->id;
            $log->type = 3;
            $log->date = date("Y-m-d H:i:s");
            $log->item = 0;
            $log->text = "Bạn đã đăng nhập vào hệ thống";
            $log->save();
            //http://elearning.htecom.net/site/login

            $return_url = Yii::$app->request->post('return_url');
            if ($return_url && $return_url != "http://elearning.htecom.net/site/login") {
                $return_url = Yii::$app->request->post('return_url');
            }else {
                $return_url = '/';
            }
           
            return $this->redirect($return_url);
        } else {
            $model->password = '';

            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();
  
        return $this->redirect('/');

    }

    /**
     * Displays contact page.
     *
     * @return mixed
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('success', 'Cảm ơn bạn đã liên hệ với chúng tôi. Chúng tôi sẽ phản hồi trong thời gian sớm nhất.');
            } else {
                Yii::$app->session->setFlash('error', 'Có lỗi đã xảy ra.');
            }

            return $this->refresh();
        } else {
            return $this->render('contact', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Displays about page.
     *
     * @return mixed
     */
    public function actionAbout()
    {
        
        return $this->render('about',['item' => NewsItem::findOne(20)]);
    }

    public function actionPolicy()
    {
        
        return $this->render('policy',['item' => NewsItem::findOne(22)]);
    }

    public function actionTerm()
    {
        
        return $this->render('term',['item' => NewsItem::findOne(21)]);
    }
    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->redirect('/');
        }
        $model = new SignupForm();
        
        if ($model->load(Yii::$app->request->post())) {
			
            $model->avatar = $_FILES["avatar"]["name"];
			
            if ($user = $model->signup()) {
                if(!empty($model->avatar) && getimagesize($_FILES["avatar"]["tmp_name"])) {
                    $target_dir = Yii::getAlias('@anyname') . '/uploads/avatars/';
					$target_file = $target_dir . basename($_FILES["avatar"]["name"]);
					move_uploaded_file($_FILES["avatar"]["tmp_name"], $target_file);
	
                }   
                Yii::$app
                ->mailer
                ->compose(
                    ['html' => 'activeAccount-html', 'text' => 'activeAccount-text'],
                    ['user' => $user]
                )
                ->setFrom([Yii::$app->params['supportEmail'] => 'TCVN E-learning'])
                ->setTo($user->email)
                ->setSubject('Kích hoạt tài khoản TCVN E-learning')
                ->send();

                \Yii::$app->getSession()->setFlash('success', 'Tài khoản của bạn đã được tạo. Bạn vui lòng kiểm tra email và kích hoạt tài khoản theo hướng dẫn trong email.');
                
                    return $this->redirect('/site/login');
                
            }
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Vui lòng kiểm tra email và làm theo hướng dẫn để đặt lại mật khẩu.');
                return $this->redirect('/site/request-password-reset');
            } else {
                Yii::$app->session->setFlash('error', 'Xin lỗi, email này không hợp lệ.');
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        if (!Yii::$app->user->isGuest) {
            return $this->redirect('/');
        }
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'Đặt lại mật khẩu thành công. Vui lòng đăng nhập.');

            return $this->redirect('/site/login');
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }
    public function actionActive($confirm_token)
    {
        $user = User::find()->where(['confirm_token'=>$confirm_token])->one();
        if ($user) {
            Yii::$app->session->setFlash('success', 'Tài khoản của bạn đã được kích hoạt vui lòng đăng nhập');
            $user->status = 10;
            $user->save();
            //$user->login();
            return $this->redirect('/site/login');
        }else {
            Yii::$app->session->setFlash('warning', 'Mã xác nhận không tồn tại');
            return $this->redirect('/');

        }

    }
}
