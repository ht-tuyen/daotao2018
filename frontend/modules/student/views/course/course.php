<?php 
use yii\helpers\Url;
use common\components\CourseSideBar;
use common\components\CourseBottom;
$this->title = $item->name;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-xs-12 col-sm-4">
        <!-- Nav tabs -->
        <div class="content-course-left">
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active">
                    <a href="#home" aria-controls="home" role="tab" data-toggle="tab">
                    <i class="fa fa-bars" aria-hidden="true"></i>
                    </a>
                </li>
                <li role="presentation">
                    <a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">
                    <i class="fa fa-comment-o" aria-hidden="true"></i>
                    </a>
                </li>
                <li class="last_tab" role="presentation"><a href="#messages" aria-controls="messages" role="tab" data-toggle="tab">
                    <i class="fa fa-plus" aria-hidden="true"></i>
                    </a>
                </li>
            </ul>

            <!-- Tab panes -->
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="home">
                <?= CourseSideBar::widget(['course_id' => $item->course_id]) ?>

                </div>
                <div role="tabpanel" class="tab-pane" id="profile">...</div>
                <div role="tabpanel" class="tab-pane" id="messages">...</div>
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-sm-8">
        <div class="content-course-right">
        <h3>Giới thiệu khóa học</h3>
        <?php echo $item->full_desc?>
        </div>
    </div>
</div>
<div class="fixed-bottom">
    
    <div class="course-bottom ">
        <div class="row">
            <div class="col-xs-12 col-sm-4">
                <?php echo CourseBottom::widget(['course_id' => $item->course_id]) ?>
                
            </div>
            <div class="col-xs-12 col-sm-8 text-center">
               
            </div>
        </div>
    </div>
</div>
