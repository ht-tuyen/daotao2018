<?php 
use yii\helpers\Url;

?>
<?php $i=0;foreach ($news as $item) {?> 
    <?php $url = Url::to(['/news/default/viewitem', 'id' => $item->tt_id]);?>
    <div class="col-xs-12 col-sm-6">
        <div class="item-box">
            <div class="item-name"><h4><a href="<?php echo $url?>"><?php echo $item->tieude?></a></h4></div>
            <div class="comment-row">
                
                <?php echo $item->gioithieu?>
            </div>
        </div>
    </div>
<?php $i++; if ($i==2) echo '<div class="clearfix"></div> <hr>';}?>
