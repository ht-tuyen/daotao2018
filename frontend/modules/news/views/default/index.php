<?php 
use yii\widgets\LinkPager;
$this->title = 'Tin tức';
$this->params['breadcrumbs'][] = $this->title;
$this->params['class'] = "blog-page";
use yii\helpers\Url;

?>
 
<div class="container">
    <div class="row">
    <?php $i=0; foreach ($items as $item) {?> 
        <?php $url = Url::to(['default/viewitem', 'id' => $item->tt_id]);?>
            <div class=" col-sm-4">
                <div class="blog-slide">
                <div class="img">
                <a href="<?php echo $url?>"><img src="/uploads/tintuc/<?php echo $item->anhdaidien?>" alt="<?php echo $item->tieude?>">
                </a>
                    <div class="date">
                        <?php echo date('D',strtotime($item->ngaytao))?>
                        <span><?php echo date('M',strtotime($item->ngaytao))?></span>
                        </div>
                </div>
                <div class="info">
                    
                    <div class="name"><a href="<?php echo $url?>"><?php echo $item->tieude?></a></div>
                    
                    <p><?php echo $item->gioithieu?></p>
                    
                    <a href="<?php echo $url?>" class="btn2">Chi tiết</a>
                </div>
                </div>
            </div> 
            <?php $i++;
                if ($i %3 == 0) {
                    echo '<div class="clearfix"></div>';
                }
            ?>
        <?php }?>
        <?php
        echo LinkPager::widget([
            'pagination' => $pages,
            ]);
        ?>
    </div>        
</div>              

