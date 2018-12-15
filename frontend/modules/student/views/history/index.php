<?php 
use yii\helpers\Url;
$this->title = 'Lịch sử học tập';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="history">

    <!-- Nav tabs -->
    <ul class="nav nav-tabs" role="tablist">
    <li role="presentation" ><a href="#home" aria-controls="home" role="tab" data-toggle="tab">Tổng quan</a></li>
    <li role="presentation" class="active"><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">Khóa học</a></li>
    <li style="border-right: 1px solid #ccc" role="presentation"><a href="#messages" aria-controls="messages" role="tab" data-toggle="tab">Dòng thời gian</a></li>
    
    </ul>

    <!-- Tab panes -->
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane " id="home">
            <ul class="stats">
                <li>
                    <i class="fa fa-book" aria-hidden="true"></i>
                    <span><?php echo $registrations?></span>
                    khóa học đã đăng ký
                </li>
                <li>
                    <i class="fa fa-check-square-o" aria-hidden="true"></i>
                    <span><?php echo $completed?></span>
                    khóa học đã hoàn thành
                </li>
                <li>
                    <i class="fa fa-sign-in" aria-hidden="true"></i>
                    <span><?php echo $week?></span>
                    lượt truy cập trong tuần
                </li>
                <li>
                    <i class="fa fa-sign-in" aria-hidden="true"></i>
                    <span><?php echo $month?></span>
                    lượt truy cập trong tháng
                </li>
                <li>
                    <i class="fa fa-sign-in" aria-hidden="true"></i>
                    Lần truy cập gần nhất 
                    <span><?php echo date("H:i:s d-m-Y", strtotime($last_login->date))?></span>
                    
                </li>
            </ul>
            <div class="row">
              <!-- <pre>
                <?php var_dump($chart2);?>
              </pre> -->
                <div class="col-xs-12 col-sm-12">
                    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
                    <script type="text/javascript">
                    google.charts.load('current', {'packages':['corechart']});
                    google.charts.setOnLoadCallback(drawChart);

                    function drawChart() {
                        var data = google.visualization.arrayToDataTable([
                        ['Year', 'Đã ghi danh', 'Đã hoàn thành'],
                        <?php foreach ($chart1 as $chart) {?>
                            ['<?php echo date('d-m-Y', strtotime($chart['date']))?>',  <?php echo $chart['created']?>,       <?php echo $chart['finished']?>],
                        <?php }?>
                       
                        ]);

                        var options = {
                        title: 'THỐNG KÊ SỐ LƯỢNG KHOÁ HỌC GHI DANH VÀ KHOÁ HỌC HOÀN THÀNH(30 NGÀY GẦN ĐÂY)',
                        //curveType: 'function',
                       
                            width:990,
                            height:500
                        };

                        var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));

                        chart.draw(data, options);
                    }
                    </script>
					<div class="over-flow-x">
						<div id="curve_chart" style="width: 990px; height: 500px;"></div>
					</div>
                    

                </div>
                <div class="col-xs-12 col-sm-12">
                    <script type="text/javascript">
                        google.charts.load('current', {'packages':['corechart']});
                        google.charts.setOnLoadCallback(drawChart);

                        function drawChart() {

                            var data = google.visualization.arrayToDataTable([
                            ['Task', 'Hours per Day'],
                            ['Chưa bắt đầu',  <?php echo $chart2[0]['created']?>],
                            ['Đang học',    <?php echo $chart2[0]['actived']?>],
                            ['Hoàn thành',  <?php echo  $chart2[0]['finished']?>],
                           
                            ]);

                            var options = {
                                title: 'THỐNG KÊ TRẠNG THÁI HOÀN THÀNH CÁC KHOÁ HỌC (30 NGÀY GẦN ĐÂY)',
                                width:990,
                                height:500
                            };

                            var chart = new google.visualization.PieChart(document.getElementById('piechart'));

                            chart.draw(data, options);
                        }
                    </script>
					<div class="over-flow-x">
                   <div id="piechart" style="width: 900px; height: 500px; "></div>
				   </div>
                </div>
            </div>
        </div> 
        <!--End home-->
        <div role="tabpanel" class="tab-pane active" id="profile">
            <div style="padding: 20px;">
            <table id="myTable" class="table table-bordered course-history text-center">
                <thead>
                    <tr>
                        <th class="text-left">Khoá học</th>
                        <th class="text-center">Trạng thái</th>
                        <th class="text-center">Kết quả</th>
                        <th class="text-center">Đạt?</th>
                        <th class="text-center">Xếp loại</th>
                        <th class="text-center">Ngày bắt đầu</th>
                        <th class="text-center">Ngày hoàn thành</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($courses as $course) {?>
                        <?php $url = Url::to(['course/detail', 'id' => $course->course_id]);?>
                        <tr>
                            <td class="text-left">
                                <?php if ($course->state == 1) {?> 
                                    <a href="<?php echo $url?>">
                                <?php }?>
                                <?php echo $course->course->name ?>
                                <?php if ($course->state == 1) {?> 
                                    </a>
                                <?php }?>
                            </td>
                            <td>
                                <?php if ($course->state == -1 ) {
                                    echo 'Chờ duyệt';
                                }else {
                                    if ($course->course->getCompleted() == 100) {
                                        echo "Đã hoàn thành";
                                    }elseif($course->course->getCompleted() == 0) {
                                        echo "Chưa bắt đầu";
                                    }else {
                                        echo "Đang học";?>
                                        <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="<?php echo $course->course->getCompleted()?>"
                                            aria-valuemin="0" aria-valuemax="100" style="width:<?php echo $course->course->getCompleted()?>%">
                                            <?php echo $course->course->getCompleted()?>%
                                            </div>
                                        </div>
                                    <?php }
                                }
                                ?>
                            </td>
                            <td>
                                <?php if ($course->reviewed) {?> 
                                    <?php echo $course->result?>/<?php echo $course->total?>
                                <?php } else {
                                    echo 'Đang chấm thi';
                                }?>
                            </td>
                            <td>
                                <?php if ($course->reviewed ) {?> 
                                <?php if ($course->result >= $course->minimum_points) {?>
                                    <b class=" text-success">Đạt</b>
                                <?php } else {?>
                                    <b class=" text-warning">Không đạt</b>
                                <?php }?>
                                <?php }?>
                            </td>
                            <td>
                            <?php if ($course->reviewed) echo $course->getResult()?>
                            </td>
                           <td>
                           <?php echo date('d/m/Y',strtotime($course->approved_date))?>
                           </td>
                            <td>
                                <?php if ($course->total) {?> 
                                    <?php echo date('d/m/Y',strtotime($course->finished_date))?>
                                <?php }?>
                            </td>
                        </tr>
                    <?php }?>
                </tbody>
            </table>
            </div>
        </div>
        <!--End profile-->
        <div role="tabpanel" class="tab-pane" id="messages">
              <table class="table">
                  <?php foreach ($logs as $log) {?>
                  <tr>
                      <td class="text-center"><span class="log-label"><?php echo $log->getLabel()?></span></td>
                      <td><?php echo $log->text?> vào lúc <?php echo date("H:i:s d/m/Y", strtotime($log->date))?></td>
                  </tr>
                  <?php }?>
              </table>                      
        </div>
        <!--End messages-->
  
    </div>

</div>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.5.2/css/buttons.dataTables.min.css">


    <link rel="stylesheet" href="//cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">

 <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
 <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.5.2/js/dataTables.buttons.min.js"></script>
	<script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
	<script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
	<script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
	<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.html5.min.js"></script>
    <script>
    jQuery(document).ready(function($){
            $('#myTable').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'excelHtml5',
                        title: 'Lịch sử học tập'
                    }
                    
                ],
                language: {
                    "sProcessing":   "Đang xử lý...",
                    "sLengthMenu":   "Xem _MENU_ mục",
                    "sZeroRecords":  "Không tìm thấy dòng nào phù hợp",
                    "sInfo":         "Đang xem _START_ đến _END_ trong tổng số _TOTAL_ mục",
                    "sInfoEmpty":    "Đang xem 0 đến 0 trong tổng số 0 mục",
                    "sInfoFiltered": "(được lọc từ _MAX_ mục)",
                    "sInfoPostFix":  "",
                    "sSearch":       "Tìm kiếm:",
                    "sUrl":          "",
                    "oPaginate": {
                        "sFirst":    "Đầu",
                        "sPrevious": "Trước",
                        "sNext":     "Tiếp",
                        "sLast":     "Cuối"
                    }
                },
                "pageLength": 100
            });
        });
        </script>