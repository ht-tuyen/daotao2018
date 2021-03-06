<?php 
use yii\widgets\LinkPager;
$this->title = $category->name;
$this->params['breadcrumbs'][] = "Khóa học";
$this->params['breadcrumbs'][] = $this->title;
use yii\helpers\Url;
$this->params['class'] = "courses-view";
?>
<div class="">
    <div class="container">
        <div class="row">
           
                <div class="list-courses ">
                <?php $i =0; foreach ($items as $item) {?> 
                    <div class="col-sm-6 col-md-3">
                    	<div class="course-post">
                        	<div class="img">
                            <img src="/uploads/elearning/course/<?php echo $item->thumbnail?>" alt="<?php echo $item->name?>">
                            <?php $url = Url::to(['default/course', 'id' => $item->course_id]);?>
                               
                            </div>
                            <div class="info">
                                <div class="name"><a href="<?php echo $url?>"><?php echo $item->name?></a></div>
                                <?php if(0){ ?>
                                    <div class="expert"><span>Giảng viên </span><?php echo $item->teacher->fullname?></div>
                                <?php } ?>
                            </div>
                            <div class="product-footer">
                                <div class="comment-box">	
                                    <div class="box"><i class="fa fa-users"></i><?php echo count($item->students)?> Học viên</div>
                                </div>
                                
                                <div class="view-btn">
                                    
                                	<a href="<?php echo $url?>" class="btn">Tham gia</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php $i++;
                    if ($i%4 == 0) {
                        echo '<div class="clearfix"></div>';
                    }?>
                 <?php }?>
                 </div>
                 <?php
                    echo LinkPager::widget([
                        'pagination' => $pages,
                        ]);
                    ?>
            </div>
           
        </div>    
    </div>              
</div>
