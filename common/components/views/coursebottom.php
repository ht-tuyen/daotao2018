
<div class="progress">
    <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="<?php echo $course->getCompleted()?>"
    aria-valuemin="0" aria-valuemax="100" style="width:<?php echo $course->getCompleted()?>%">
    <?php echo $course->getCompleted()?>%
    </div>
</div>
        