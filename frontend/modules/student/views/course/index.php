<?php 
use yii\helpers\Url;
$this->title = 'Khóa học của tôi';
$this->params['breadcrumbs'][] = $this->title;
?>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Khoá học</th>
            <th>Trạng thái</th>
            <th>Hoàn thành</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($courses as $course) {?>
            <?php $url = Url::to(['course/detail', 'id' => $course->course_id]);?>
            <tr>
                <td>
                    <?php if ($course->state == 1) {?> 
                        <a href="<?php echo $url?>">
                    <?php }?>
                    <?php echo $course->course->name ?>
                    <?php if ($course->state == 1) {?> 
                        </a>
                    <?php }?>
                </td>
                <td><?php echo $course->state == 1 ? 'Đã được duyệt' : 'Chờ duyệt' ;?></td>
                <td>
                <div class="progress">
                    <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="<?php echo $course->course->getCompleted()?>"
                    aria-valuemin="0" aria-valuemax="100" style="width:<?php echo $course->course->getCompleted()?>%">
                    <?php echo $course->course->getCompleted()?>%
                    </div>
                </div>
                </td>
            </tr>
        <?php }?>
    </tbody>
</table>