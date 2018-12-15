<div class="panel panel-info">
<h2>Quản lý đề thi cho bài học: <?php echo $lesson->name?></h2>
<h2><button class="btn btn-success"><a style="color:#fff" href="/acp/elearning/quiz/create?type=1&id=<?php echo $lesson->lesson_id?>&name=<?php echo $lesson->name?>&category_id=<?php echo $lesson->course->category_id?>">Tạo bài kiểm tra</a></button>
</h2>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Mã</th>
            <th>Tên đề thi</th>
            <th>Số câu</th>
            <th>Thời gian làm bài</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($lesson->quizes as $quiz) {?>
            <tr>
                <td><?php echo $quiz->quiz_id?></td>
                <td><?php echo $quiz->name?></td>
                <td><?php echo $quiz->number_questions?></td>
                <td><?php echo $quiz->time?></td>
                <td><a style="color:#fff" href="/acp/elearning/quiz/update?type=1&id=<?php echo $lesson->lesson_id?>&name=<?php echo $lesson->name?>&category_id=<?php echo $lesson->course->category_id?>&quiz_id=<?php echo $quiz->quiz_id?>"><span class="text-blue"><i class="glyphicon glyphicon-pencil "></i></span></a></td>
            </tr>
        <?php }?>
    </tbody>
</table>
</div>