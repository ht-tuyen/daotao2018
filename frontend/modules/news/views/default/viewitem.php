<?php 
    $this->title = $item->tieude;
    $this->params['breadcrumbs'][] = $this->title;
    $this->params['class'] = "blog-details";
?>
<div class="">
    <div class="row">
        <div class="col-xs-12">
        <div class="name"><h2><?php echo $item->tieude?></h2></div>
        <p></p>
        <div><?php echo $item->noidung?></div>
        </div>
       
    </div>
</div>