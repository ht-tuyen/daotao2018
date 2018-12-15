<?php

/* @var $this yii\web\View */
use common\components\HomeCourses;
use common\components\HomeNews;
use common\modules\api\models\elearning\Feature;
use common\modules\api\models\elearning\Banner;
use common\modules\api\models\elearning\Feedback;
$feedbacks = Feedback::find()->where(['state'=>1])->orderBy(['ordering'=>SORT_ASC, 'created_date'=>SORT_DESC])->all();
$features = Feature::find()->where(['state'=>1])->orderBy(['ordering'=>SORT_ASC, 'created_date'=>SORT_DESC])->all();
$banners = Banner::find()->where(['state'=>1])->orderBy(['ordering'=>SORT_ASC, 'created_date'=>SORT_DESC])->all();
$this->title = 'Viện tiêu chuẩn chất lượng Việt Nam';
?>


        <section class="banner">
        	<div class="banner-img">
            <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
                

                <!-- Wrapper for slides -->
                <div class="carousel-inner" role="listbox">
                <?php $i=0; foreach ($banners as $banner) {?>
                    <div class="item <?php if ($i == 0) echo 'active'?>">
                        <img src="/uploads/elearning/banner/<?php echo $banner->source?>" alt="banner">
                    
                    </div>
                <?php $i++; }?>
                </div>

                <!-- Controls -->
                <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
                    <i class="fa fa-angle-left" aria-hidden="true"></i>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
                    <i class="fa fa-angle-right" aria-hidden="true"></i>
                    <span class="sr-only">Next</span>
                </a>
                </div>
            </div>
            <div class="banner-text">
            	<div class="container">
                	<h1>Viện Tiêu chuẩn Chất lượng Việt Nam</h1>
                    <p>Cung cấp các khóa học đào tạo trực tuyến</p>
                    <form action="/course" method="get">
                    
                        <div class="search-box">	
                            <input type="text" name="search" placeholder="Tìm kiếm hóa học">
                            <input type="submit" value="">
                        </div>
                    </form>
                    <div class="learning-btn">
                    	<a href="/course" class="btn">Bắt đầu học</a>
                    </div>
                </div>
            </div>
        </section>
        
        <section class="our-course">
        	<div class="container">
            	<div class="section-title">
                	<h2>Khóa học mới</h2>
                </div>
            	<div class="row">
                    <?= HomeCourses::widget() ?>

                </div>
            </div>
        </section>
        <section class="preparation">
        	<div class="container">
            	<div class="section-title white">
                	<h2>Nâng cao chất lượng học tập</h2>
                    
                </div>
                <div class="preparation-view">
                <?php foreach ($features as $feature) {?>
                	<div class="item">
                    	<div class="icon"><img style="width: 60px; height: 70px;" src="/uploads/elearning/feature/<?php echo $feature->source?>" alt="<?php echo $feature->name?>"></div>
                        <div class="course-name"><?php echo $feature->name?></div>
                        <p><?php echo $feature->description?></p>
                    </div>
                <?php }?>    
                    
                </div>
            </div>
        </section>
           
        <section class="student-feedback">
        	<div class="container">
            	<div class="section-title">
                	<h2>Cảm nhận của học viên</h2>
                </div>
                <div class="feedback-slider">
                    <?php foreach ($feedbacks as $feedback) {?> 
                        <div class="item">
                            <div class="student-img"><img  src="/uploads/elearning/feedback/<?php echo $feedback->source?>" alt="<?php echo $feedback->name?>"></div>
                            <div class="student-name"><?php echo $feedback->name?></div>
                            <div class="student-designation"><?php echo $feedback->position?></div>
                            <p><i class="fa fa-quote-left"></i> <?php echo $feedback->description?>. <i class="fa fa-quote-right"></i> </p>
                        </div>
                    <?php }?>
                	
                </div>
               
            </div>
        </section>
        
       
        <section class="start-learning">
        	<div class="container">
            	<p>Bạn đã thực sự sẵn sàng cho những kiến thức mới?</p>
                <a href="/course" class="btn">Bắt đầu học ngay</a>
            </div>
        </section>
        <section class="contact-block">
        	
        </section>