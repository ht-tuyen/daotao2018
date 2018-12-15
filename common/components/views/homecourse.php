<?php 
use yii\helpers\Url;

?>
<?php $i =0; foreach ($courses as $course) {?> 
    <?php $url = Url::to(['/course/default/course', 'id' => $course->course_id]);?>
    <div class="col-md-4 col-sm-6">
        <div class="course-box">
            <div class="img">
                <img src="/uploads/elearning/course/<?php echo $course->thumbnail?>" alt="<?php echo $course->name?>">
                
                
            </div>
            <div class="course-name"><a href="<?php echo $url?>"><?php echo $course->name?></a><span><em> Giảng viên </em><?php echo $course->teacher->fullname?></span></div>
            <div class="comment-row">
                
                <div class="box"><i class="fa fa-users"></i><?php echo count($course->students)?> học viên</div>
                <div class="enroll-btn">	
                    
                    <a href="<?php echo $url?>" class="btn">Tham gia</a>
                </div>
            </div>
        </div>
    </div>
    <?php $i ++;
        if ($i%3 == 0) {
            echo '<div class="clearfix"></div>';
        }
    ?>
<?php }?>
