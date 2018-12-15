<?php 
use yii\widgets\LinkPager;
$this->title = $item->name;
$this->params['breadcrumbs'][] = $this->title;
use yii\helpers\Url;
$this->params['class'] = "quiz-view";
?>

<div class="row">
    <div class="col-sm-4 col-md-3">
        <div class="time-info">Thời gian còn lại: 20m:00s </div>
        
        <div class="qustion-list">
            <div class="qustion-slide fill">
                <div class="qustion-number">Câu hỏi đã trả lời</div>
              
            </div>
            
            <div class="qustion-slide">
                <div class="qustion-number">Câu hỏi chưa trả lời</div>
                
            </div>
           
        </div>
    </div>
    <div class="col-sm-8 col-md-9">
        <div class="quiz-intro">
            <h3>Giới thiệu bài thi</h3>
            <p><?php echo $item->short_desc?></p>
            <div class="start-btn">
                <a href="<?php echo Url::to(['default/doquiz', 'id' => $item->quiz_id]);?>" class="btn">Bắt đầu làm bài</a>
            </div>
        </div>
    </div>
</div>
<script>
</script>