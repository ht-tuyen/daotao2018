<?php

/* @var $this \yii\web\View */
/* @var $content string */
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use common\widgets\Alert;
use common\modules\api\models\elearning\Category;
$categories = Category::find()->where(['state'=>1])->orderBy(['ordering'=>SORT_ASC, 'created_date'=>SORT_DESC])->all();
AppAsset::register($this);
?>
<?php $this->beginPage() ?>

<!DOCTYPE html>
<html  lang="<?= Yii::$app->language ?>>
<head>
    <!-- Meta information -->
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <!-- Title -->
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <!-- favicon icon -->
    <link rel="shortcut icon" href="images/Favicon.ico">
    <script src="https://use.fontawesome.com/ceada1aef5.js"></script>

  
    <link href="https://fonts.googleapis.com/css?family=Arima+Madurai:100,200,300,400,500,700,800,900%7CPT+Roboto:100,100i,300,300i,400,400i,500,500i,700,700i,900,900i" rel="stylesheet">
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->


    <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>


</head>
    
<body>

<?php $this->beginBody() ?>
<?php $this->endBody() ?>
    <div class="wapper">
    	
        <div class="quck-nav">
        	<div class="container">
            	<div class="contact-no"><a href="#"><i class="fa fa-map-marker"></i>
                    <?php echo (empty(Yii::$app->settings->get('company_address'))?'':Yii::$app->settings->get('company_address')) ?></a>
                </div>

        		<div class="contact-no"><a href="#"><i class="fa fa-phone"></i><?php echo (empty(Yii::$app->settings->get('company_phone'))?'':Yii::$app->settings->get('company_phone')) ?></a></div>


                <div class="contact-no"><a href="#"><i class="fa fa-globe"></i><?php echo (empty(Yii::$app->settings->get('company_email'))?'':Yii::$app->settings->get('company_email')) ?></a></div>

                
                <div class="quck-right">  
						<div class="right-link"><a href="/uploads/elearning/attachment/HDQT_Elearning_Hoc_vien.pdf" target="_blank"><i class="fa fa-question-circle-o" aria-hidden="true"></i>
 Hướng dẫn sử dụng</a></div>
                    <?php if (Yii::$app->user->isGuest) { ?>
                        <div class="right-link"><a href="/site/login"><i class="fa  fa-user"></i>Đăng nhập</a></div>
                        <div class="right-link"><a href="/site/signup"><i class="fa  fa-user"></i>Đăng ký</a></div>
                    <?php } else {?>
                        <div class="right-link"><a href="/student/profile/edit"><i class="fa  fa-user"></i> Tài khoản</a></div>
                       <!-- <div class="right-link"><a href="/student/course"><i class="fa fa-book" aria-hidden="true"></i> Khóa học của tôi</a></div>-->
                        <div class="right-link"><a href="/student/history"><i class="fa fa-history" aria-hidden="true"></i> Lịch sử học tập</a></div>
                        <!-- <div class="right-link"><a href="/student/quizresult"><i class="fa fa-graduation-cap" aria-hidden="true"></i> Kết quả thi</a></div> -->
                        <div class="right-link">   
                        <a  href="site/logout"
							onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
							Đăng xuất
						</a> 
                            <?php 
                                echo Html::beginForm(['/site/logout'], 'post',['id'=>'logout-form']);
                                echo Html::submitButton(
                                    'Logout (' . Yii::$app->user->identity->username . ')',
                                    ['class' => 'btn btn-link logout']
                                );
                                echo Html::endForm()
                            ?>
                        </div>
                    <?php }?>

                    
                </div>
            </div>
        </div>
        <header id="header">
        	<div class="container">
                <nav id="nav-main">
                    <div class="navbar navbar-inverse">
                        <div class="navbar-header">
                            <a href="/" class="navbar-brand"><img src="/images/vsqi.png" alt="" style="height: 50px;"></a>
                            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                            </button>
                        </div>
                       
                        <div class="navbar-collapse collapse">
                        
                            <ul class="nav navbar-nav">
                                <li class="<?php echo $this->context->route == 'site/index' ? 'active' : ''?>">
                                	<a href="/">Trang chủ </a> 
                                </li>
                                
                                
                                <li class="<?php echo $this->context->route == 'site/about' ? 'active' : ''?>"><a href="/site/about">Giới thiệu</a></li>
                                <li class="sub-menu <?php echo $this->context->route == 'course/default/index' || $this->context->route =='course/default/course' || $this->context->route =='course/default/lesson' || $this->context->route =='course/default/quiz' ? 'active' : ''?>">
                                	<a href="/course">Lĩnh vực <i class="fa fa-angle-down" aria-hidden="true"></i></a>
                                     <ul>
                                        <?php foreach ($categories as $category) {?>
                                    	    <li><a href="<?php echo Url::to(['/course/default/category', 'id' => $category->category_id])?>"><?php echo $category->name?></a></li>
                                        <?php }?>
                                    </ul>
                                </li>
                                <!-- <li class="<?php echo $this->context->route == 'news/default/index' || $this->context->route =='news/default/viewitem' ? 'active' : ''?>"><a href="/news">Tin tức</a></li> -->
                                <li class="<?php echo $this->context->route == 'site/contact' ? 'active' : ''?>"><a href="/site/contact">Liên hệ </a></li>
                            </ul>
                        </div>
                    </div>
                </nav>
            </div>
        </header>
        <?php if (isset($this->params['breadcrumbs'])) {?>
        <section class="banner inner-page">
        	<div class="banner-img" style="background-image: url("/images/banner/courses-banner.jpg");"><img src="/images/banner/courses-banner.jpg" alt="" style="display: none;"></div>
            <div class="page-title">	
	            <div class="container">
                    <h1><?= Html::encode($this->title) ?></h1>
                </div>
            </div>
        </section>
        <?php }?>
        <section class="breadcrumb">
            <div class="container">
            <?= Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>
            <?php //Alert::widget() ?>
            </div>
        </section>
        <?php //echo $this->context->route?>
        
        <section class="main <?php echo $this->params['class']?>">
            <div class="container">
            
            <?= $content ?>
            </div>
        </section>
        
        <footer id="footer">
        	
        	<div class="container">
            	<div class="row">
                	<div class="col-sm-8">
            			<div class="copy-right">
                        	<p>Copyright © <span class="year">2018</span> TCVN.</p>
                            <ul class="footer-link">
                            	<li><a href="/site/term">Quy định sử dụng</a></li>
                                <li><a href="/site/policy">Chính sách bảo mật</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-sm-4 ">	
                    	<div class="social-media">
                        	<ul>
                            	<?php if (Yii::$app->settings->get('facebook')) {?><li><a href="<?php echo Yii::$app->settings->get('facebook')?>"><i class="fa fa-facebook"></i></a></li><?php }?>
                                <?php if (Yii::$app->settings->get('twitter')) {?><li><a href="<?php echo Yii::$app->settings->get('twitter')?>"><i class="fa fa-twitter"></i></a></li><?php }?>
                                
                                <?php if (Yii::$app->settings->get('youtube')) {?><li><a href="<?php echo Yii::$app->settings->get('youtube')?>"><i class="fa fa-youtube"></i></a></li><?php }?>
                                
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
    </div>
    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    

 
  
</body>
</html>

<?php $this->endPage() ?>