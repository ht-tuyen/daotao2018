<?php 
use yii\helpers\Url;
use common\components\CourseSideBar;
use common\components\CourseBottom;

$this->title = $item->name;
$this->params['breadcrumbs'][] = $item->course->name;
$this->params['breadcrumbs'][] = 'Bài kiểm tra cuối khóa';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-xs-12 col-sm-4">
        <div class="content-course-left">
            <!-- Nav tabs -->
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
                <?= CourseSideBar::widget(['course_id' => $item->course_id,'active_type'=>2, 'active_id'=>$item->quiz_id]) ?>

                </div>
                <div role="tabpanel" class="tab-pane" id="profile">...</div>
                <div role="tabpanel" class="tab-pane" id="messages">...</div>
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-sm-8 text-center">
        <div class="content-course-right">
            <img src="https://structureofintellect.files.wordpress.com/2017/09/bubble-test.jpg" style="width: 300px;" alt="">
            <p></p>
            <h3><?php echo $item->name?></h3>
            <p></p>
            <p>Bài thi gồm <b><?php echo $item->number_questions?></b> câu hỏi</p>
            <p>Thời gian làm bài: <b><?php echo $item->time?></b> phút</p>
            <?php if ($completed_quiz) {?>
                <hr>
                <?php if ($completed_quiz->reviewed) {?> 
                <p><b>Kết quả bài thi</b></p>
                <p>Giờ làm bài: <?php echo date("H:i d-m-Y", strtotime($completed_quiz->started_time))?></p>
                <p>Giờ nộp bài: <?php echo date("H:i d-m-Y", strtotime($completed_quiz->submitted_time))?></p>
                <p>Kết quả: <?php echo $completed_quiz->result?>/<?php echo $completed_quiz->total?></p>
                <?php }else {?> 
                    Đang chấm thi!
                <?php }?>

            <?php } else{?>
            <a href="<?php echo Url::to(['/student/course/doquizfinal', 'id' => $item->quiz_id])?>" class="btn btn-info">Bắt đầu làm bài</a>
            <?php }?>
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
