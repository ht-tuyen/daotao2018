<?php use yii\helpers\Url;?>
<div class="course-sidebar">
    <h3><?php echo '<a href="'. Url::to(['/student/course/detail', 'id' => $course_id]).'">'. $course->name .'</a>'?></h3>
                
    <ul>
        <?php foreach ($lessons as $lesson) {?>
            <li id="lesson_<?php echo $lesson->lesson_id?>" class="<?php echo $active_type == 1 && $active_id == $lesson->lesson_id ? 'active' : ''?>">
            <?php $lesson_url = Url::to(['/student/course/lesson', 'id' => $lesson->lesson_id]);?>
                <i class="fa fa-chevron-down"> </i> 
                <a href="<?php echo $lesson_url?>"><?php echo $lesson->name?></a>
                <?php echo $lesson->checkCompleted() ? '<i class="fa fa-check" style="color:green; float:right"> </i>' : ''?>
                <?php if($lesson->finished && $lesson->quizes) { ?>
                    <ul class="short-quiz">
                        <li> <?php echo '<a href="'. Url::to(['/student/course/completelesson', 'id' => $lesson->lesson_id]).'">Bài kiểm tra ngắn</a>'?></li>
                    </ul>
                   
                <?php }?>
            </li>
        <?php }?>
    </ul>
    <ul>
        <li class="final_quiz <?php echo $active_type == 2 && $active_id == $quiz->quiz_id ? 'active' : ''?>">
            <?php if($course->getCompleted() < 100) {?>
                <span class="text-danger">Vui lòng hoàn thành khóa học để làm bài kiểm tra cuối khóa</span>
            <?php }else {?>
                <a href="<?php echo Url::to(['/student/course/quiz', 'id' => $quiz->quiz_id])?>"><i class="fa fa-question-circle" aria-hidden="true"></i>
                 <?php echo $quiz->name?></a>
                 <?php echo $completed_quiz->quiz_result_id ? '<i class="fa fa-check" style="color:green; float:right"> </i>' : ''?>
            <?php }?>    
        </li>
    </ul>
    
</div>
