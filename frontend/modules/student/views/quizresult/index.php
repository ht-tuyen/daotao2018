<?php 


$this->title = 'Kết quả thi';
$this->params['breadcrumbs'][] = $this->title;
?>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Stt</th>
            <th>Bài thi</th>
            <th>Kết quả</th>
            <th>Thời gian làm bài</th>
            <th>Thời gian nộp bài</th>
        </tr>
    </thead>
    <?php $i = 1; foreach ($results as $result) {?>
        <tr>
            <td><?php echo $i?></td>
            <td><?php echo $result->quiz->name?></td>
            <td><?php echo $result->result?>/<?php echo $result->total?></td>
            <td><?php echo date("H:i d-m-Y",strtotime($result->started_time))?></td>
            <td><?php echo date("H:i d-m-Y",strtotime($result->submitted_time))?></td>
        </tr>
    <?php $i++;}?>
</table>