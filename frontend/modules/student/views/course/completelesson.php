<?php 
use yii\helpers\Url;
use common\components\CourseSideBar;
use common\components\CourseBottom;

$this->title = $item->name;
$this->params['breadcrumbs'][] = $item->course->name;
$this->params['breadcrumbs'][] = $this->title;
$this->params['breadcrumbs'][] = 'Bài kiểm tra ngắn';
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
                <?= CourseSideBar::widget(['course_id' => $item->course_id,'active_type'=>1,'active_id'=>$item->lesson_id]) ?>

                </div>
                <div role="tabpanel" class="tab-pane" id="profile">
                
                </div>
                <div role="tabpanel" class="tab-pane" id="messages">
                
                </div>
            </div>
        </div>

    </div>
    <div class="col-xs-12 col-sm-8">
        <div class="content-course-right">
            <?php if (Yii::$app->session->getFlash('success')) {?> 
                <div class="alert alert-success"><?= Yii::$app->session->getFlash('success'); ?></div>
            <?php }?>
        <h3>Bài kiểm tra ngắn</h3><br>
        <table class="table table-bordered text-center">
            <thead>
                <tr>
                    <th>Tên bài kiểm tra</th>
                    <th>Số câu hỏi</th>
                    <th>Thời gian làm bài</th>
                    <th>Kết quả mới nhất</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($quizes as $quiz) {?>
                    <?php $quiz_url = Url::to(['/student/course/doquiz', 'id' => $quiz->quiz_id]);?>
                    <tr>
                        <td><a href="<?php echo $quiz_url?>"><?php echo $quiz->name?></a></td>
                        <td><?php echo $quiz->number_questions?></td>
                        <td><?php echo $quiz->time?> phút</td>
                        <td>
                            <?php if ($quiz->result->total) {?>
                            <?php echo date("H:i d-m-Y", strtotime($quiz->result->started_time))?><br>
                            <?php echo $quiz->result->result?>/<?php echo $quiz->result->total?>
                            <?php }?>
                        </td>
                        <td><button class="btn btn-info"><a href="<?php echo $quiz_url?>"><?php echo $quiz->result->total ? "Làm lại":"Làm bài"?></a></button></td>

                    </tr>
                <?php }?>
            </tbody>
        </table>
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
