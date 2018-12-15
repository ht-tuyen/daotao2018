<div id="app">
    
    <h3><?php echo $lessson->name?></h3>

<table id="myTable" class="admintablelist table table-bordered table-hover">
    <thead>
        <tr>
            
            <th>Học viên</th>
            <th>Ngày học</th>
            <th>Trạng thái</th>
            <th>Ngày hoàn thành</th>
        </tr>
        
    </thead>
    <tbody>
        <?php foreach ($students as $student) {?>
        <tr >
            
            
            <td>
           
            <?php echo $student->getStudent()->full_name?> - <?php echo $student->getStudent()->mobile?> - <?php echo $student->getStudent()->email?></td>
            <td><?php echo date("d-m-Y", strtotime($student->created_date))?></td>
            <td><?php echo $student->finished == 1 ? "<button class='btn btn-success'>Đã hoàn thành</button>" : "<button class='btn btn-warning'>Chưa hoàn thành</button>"?></td>
            <td><?php echo date("d-m-Y", strtotime($student->finished_date))?></td>
        </tr>
        <?php }?>
    </tbody>
    <tfoot>

    </tfoot>

</table>
<!-- Pagination -->

<!-- <nav>
        <ul class="pagination">
            <li v-if="pagination.current_page > 1">
                <a href="#" aria-label="Previous" @click.prevent="changePage(pagination.current_page - 1)">
                    <span aria-hidden="true">«</span>
                </a>
            </li>
            <li v-for="page in pagesNumber" v-bind:class="[ page == isActived ? 'active' : '']">
                <a href="#" @click.prevent="changePage(page)">{{ page }}</a>
            </li>
            <li v-if="pagination.current_page < pagination.last_page">
                <a href="#" aria-label="Next" @click.prevent="changePage(pagination.current_page + 1)">
                    <span aria-hidden="true">»</span>
                </a>
            </li>
        </ul>
    </nav> -->
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
                        title: '<?php echo $course->name?>'
                    },
                    {
                        extend: 'pdfHtml5',
                        title: '<?php echo $course->name?>',
                        orientation: 'landscape',
                        pageSize: 'LEGAL'
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
                