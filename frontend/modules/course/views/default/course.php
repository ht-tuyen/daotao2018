<?php 
use yii\widgets\LinkPager;
$this->title = $item->name;
// echo "<pre>";
// var_dump($item->category->name);
// echo "</pre>";
$this->params['breadcrumbs'][] = $item->category->name;
$this->params['breadcrumbs'][] = $this->title;
use yii\helpers\Url;
$this->params['class'] = "course-details";
?>

            	
<div class="course-details-main">
    <div class="course-img">
    <img src="/uploads/elearning/course/<?php echo $item->full_image?>" alt="<?php echo $item->name?>">
    </div>
    
    
</div> 

<div class="info">
    <h4>Thông tin khóa học</h4>
    <div class="description">
        <?php echo $item->full_desc ?>
    </div>
    
</div>

<?php 
    if ($item->ready == 1) {

    
        if( !$canview) { ?>
            <?php if (Yii::$app->user->isGuest) { ?>
                <a href="/site/login" class="btn btn-success">Đăng nhập để đăng ký khoá học.</a>

            <?php }else {?> 
            <a href="/course/default/register?id=<?php echo $item->course_id?>" class="btn btn-success">Đăng ký tham gia khoá học</a>
            <?php }?>
        <?php }else {
            if ($canview->state == -1) {?>
                <div class="alert alert-warning">
                    Yêu cầu tham gia khoá học đang được xử lý.
                </div>
            <?php } 
            
        }
    }else {
        echo '<div class="alert alert-warning">Khóa học chưa được mở</div>';
    }
?>



                
            