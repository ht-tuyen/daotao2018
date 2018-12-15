<?php 
use yii\helpers\Url;
?>
<div class="history">
<h3>Lịch sử học tập của học viên <?php echo $user->full_name?></h3>
    <!-- Nav tabs -->
    <ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab">Tổng quan</a></li>
    <li role="presentation"><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">Khóa học</a></li>
    <li style="border-right: 1px solid #ccc" role="presentation"><a href="#messages" aria-controls="messages" role="tab" data-toggle="tab">Dòng thời gian</a></li>
    
    </ul>

    <!-- Tab panes -->
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="home">
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
                        ['Year', 'Khóa học ghi danh', 'Khóa học hoàn thành'],
                        <?php foreach ($chart1 as $chart) {?>
                            ['<?php echo date('d-m-Y', strtotime($chart['date']))?>',  <?php echo $chart['created']?>,       <?php echo $chart['finished']?>],
                        <?php }?>
                       
                        ]);

                        var options = {
                        title: 'THỐNG KÊ SỐ LƯỢNG KHOÁ HỌC GHI DANH VÀ KHOÁ HỌC HOÀN THÀNH(30 NGÀY GẦN ĐÂY)',
                        //curveType: 'function',
                        legend: { position: 'bottom' }
                        };

                        var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));

                        chart.draw(data, options);
                    }
                    </script>
                    <div id="curve_chart" style="width: 990px; height: 500px"></div>
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
                                title: 'THỐNG KÊ TRẠNG THÁI HOÀN THÀNH CÁC KHOÁ HỌC (30 NGÀY GẦN ĐÂY)'
                                
                            };

                            var chart = new google.visualization.PieChart(document.getElementById('piechart'));

                            chart.draw(data, options);
                        }
                    </script>
                    <div id="piechart" style="width: 880px; height: 450px;"></div>
                </div>
            </div>
        </div> 
        <!--End home-->
        <div role="tabpanel" class="tab-pane" id="profile">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Khoá học</th>
                        <th>Trạng thái</th>
                        <th>Kết quả</th>
                        <th>Xếp loại</th>
                       
                        <th>Ngày hoàn thành</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($courses as $course) {?>
                        <?php $url = Url::to(['course/detail', 'id' => $course->course_id]);?>
                        <tr>
                            <td>
                                
                                <?php echo $course->course->name ?>
                               
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
                                        echo "Đang học";
                                    }
                                }
                                ?>
                            </td>
                            <td>
                                <?php if ($course->total) {?> 
                                    <?php echo $course->result?>/<?php echo $course->total?>
                                <?php }?>
                            </td>
                            <td>
                            <?php echo $course->getResult()?>
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