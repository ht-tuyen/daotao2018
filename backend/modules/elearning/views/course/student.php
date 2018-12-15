<div id="app">
    
    <h3><?php echo $course->name?></h3>
    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#create-item">
        <i class="glyphicon glyphicon-plus"></i> Tạo mới học viên
    </button>
	<button type="button" class="btn btn-success" data-toggle="modal" data-target="#create-item-excel">
		<i class="glyphicon glyphicon-upload"></i> Upload học viên từ file Excel
	</button>
	<a href="/api/elearning/course/downloaddetail?id=<?php echo $course->course_id?>" class="btn btn-success"> <i class="glyphicon glyphicon-download"></i> Tải dữ liệu </a>
    <h4>Thêm học viên từ danh sách</h4>
    <div class="row">
        <div class="col-xs-12 col-sm-8">
            <v-select  v-model="addStudent" :options="students" label="fullname"></v-select>
        </div>
        <div class="col-xs-12 col-sm-4">
            <button v-if="!loading" @click.prevent="assignStudent" class="btn btn-success">Thêm học viên</button>
            <div  v-if="loading">
                <img width="24px; height: 24px;" src="http://bestanimations.com/Science/Gears/loadinggears/loading-gear-3.gif" alt=""> 
                Đang lưu dữ liệu
            </div>
        </div>
    </div>
    
<table  id="myTable" class="admintablelist table table-bordered table-hover">
    <thead>
        <tr>
            
            <th>Học viên</th>
            <th>Ngày đăng ký</th>
            <th>Thời gian làm bài thi</th>
            <th>Thời gian nộp bài</th>
            <th>Kết quả</th>
            <th>Trạng thái</th>
            <th>Hành động</th>
        </tr>
        
    </thead>
    <tbody>
        <?php foreach ($students as $student) {?>
        <tr >
            
            
            <td>
           
            <?php echo $student->student->full_name?> - <?php echo $student->student->mobile?> - <?php echo $student->student->email?></td>
            <td><?php echo date("H:i d-m-Y",strtotime($student->created_date))?></td>
            <td><?php echo $student->getQuizResult()['started_time'] ? date("H:i d-m-Y",strtotime($student->getQuizResult()['started_time'])) : "" ?></td>
            <td><?php echo $student->getQuizResult()['submitted_time'] ? date("H:i d-m-Y",strtotime($student->getQuizResult()['submitted_time'])) : "" ?></td>
            <td class="text-center">
               
              
                <?php if ($student->getQuizResult()['reviewed']) {?>
                    <b class="text-green"><?php echo  $student->getQuizResult()['result'] . '</b>/' . $student->getQuizResult()['total'];?>
                <?php } elseif ( $student->getQuizResult()['total'] && ($student->main_teacher == Yii::$app->user->identity->user_id || Yii::$app->user->identity->role_id != 963) ) {?>
                    <button class="btn btn-primary" @click="review(<?php echo $student->getQuizResult()['quiz_result_id']?>)">Chấm điểm</button>
                <?php }?>
                
            </td>
            <td><?php echo $student->state == 1 ? "Đã duyệt" : "Chưa chuyệt"?></td>
            <td>
               <?php if ($student->state == 1) {?>
                    <button class="btn btn-danger" @click.prevent="deactiveItem(<?php echo $student->user_id ?>)">Khoá</button>
               <?php } else {?>
                    <button class="btn btn-success" @click.prevent="activeItem(<?php echo $student->user_id ?>)">Duyệt</button>
                <?php }?>
            </td>
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
	<div class="modal fade" id="create-item-excel" tabindex="-1" role="dialog" aria-labelledby="myModalLabelExcel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button v-on:click="clearinputItemExcel" type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>

                    <h4 class="modal-title" id="myModalLabel">Tải danh sách học viên từ file Excel</h4>
					
                    
                </div>
                <div class="modal-body">
					<div class="form-group">
						<label for="source">Chọn file:</label>
						<input type="file" ref="source" v-on:change="previewImage" id="student_upload" name="source" class="form-control" />
							
					</div>
					<template v-if="list_upload.length > 0">
					<table class="table table-bordered">
						<thead>
							<tr>
								<th>STT</th>
								<th>Họ và tên</th>
								<th>Đơn vị công tác</th>
								<th>Chức vụ</th>
								<th>Địa chỉ email</th>
								<th>Số điện thoại</th>
								<th v-if="account_created">Kết quả</th>
							</tr>
						</thead>
						<tr v-for="item in list_upload">
							<td>{{item[0]}}</td>
							<td>{{item[1]}}</td>
							<td>{{item[2]}}</td>
							<td>{{item[3]}}</td>
							<td>{{item[4]}}</td>
							<td>{{item[5]}}</td>
							<td v-if="account_created">
								<span v-if="item[6] == 1" class="text-success">Gửi email thành công</span>
								<span v-if="item[6] == 2" class="text-warning">Email không được bỏ trống</span>
								<span v-if="item[6] == 0" class="text-success">Tạo và gửi email thành công</span>
								<span v-if="item[6] == 3" class="text-warning">Học viên đã được gán cho khóa học trước đó</span>
							</td>
						</tr>
					</table>
					<button v-if="loading == false" class="btn btn-success" @click.prevent="createAccountBulk">Tạo tài khoản</button>
					<div  v-if="loading">
						<img width="24px; height: 24px;" src="http://bestanimations.com/Science/Gears/loadinggears/loading-gear-3.gif" alt=""> 
						Đang lưu dữ liệu
					</div>
					</template>
				</div>
			</div>
		</div>
	</div>
     <!-- Create Item Modal -->
     <div class="modal fade" id="create-item" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button v-on:click="clearinputItem" type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>

                    <h4 class="modal-title" id="myModalLabel">{{inputItem.id ? "Cập nhật học viên" : "Tạo học viên"}}</h4>
                
                    
                </div>
                <div class="modal-body">
                    <form action="">
                        <div class="row">
                        <div class="col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <label for="title">Họ tên:</label>
                                    <input type="text" name="full_name" class="form-control" v-model="inputItem.full_name" />
                                    <span v-if="showError.full_name" class="error text-danger">{{showError.full_name[0]}}</span>
                                </div>
                                <div class="form-group">
                                    <label for="title">Địa chỉ:</label>
                                    <input type="text" name="address" class="form-control" v-model="inputItem.address" />
                                    <span v-if="showError.address" class="error text-danger">{{showError.address[0]}}</span>
                                </div>
                                <div class="form-group">
                                    <label for="title">Email:</label>
                                    <input type="email" name="email" class="form-control" v-model="inputItem.email" />
                                    <span v-if="showError.email" class="error text-danger">{{showError.email[0]}}</span>
                                </div>
                                <div class="form-group">
                                    <label for="title">Tên đăng nhập:</label>
                                    <input type="text" name="username" class="form-control" v-model="inputItem.username" />
                                    <span v-if="showError.username" class="error text-danger">{{showError.username[0]}}</span>
                                </div>
                                <div class="form-group">
                                    <label for="title">Điện thoại:</label>
                                    <input type="text" name="mobile" class="form-control" v-model="inputItem.mobile" />
                                    <span v-if="showError.mobile" class="error text-danger">{{showError.mobile[0]}}</span>
                                </div>
                                <div class="form-group">
                                    <label for="title">Ngày sinh:</label>
                                    <input type="text" placeholder="31-12-1979" name="dob" class="form-control" v-model="inputItem.dob" />
                                    <span v-if="showError.dob" class="error text-danger">{{showError.dob[0]}}</span>
                                </div>
                                <div class="form-group">
                                    <label for="title">Trạng thái:</label>
                                    <select name="gender" v-model="inputItem.status" id="" class="form-control">
                                        <option value="10">Hoạt động</option>
                                        <option value="0">Khoá</option>
                                    </select>
                                    <span v-if="showError.gender" class="error text-danger">{{showError.gender[0]}}</span>
                                </div>
                                
                            </div>
                            <div class="col-xs-12 col-sm-6">
                                
                                <div class="form-group">
                                    <label for="title">Giới tính:</label>
                                    <select name="gender" v-model="inputItem.gender" id="" class="form-control">
                                        <option value="1">Nam</option>
                                        <option value="2">Nữ</option>
                                    </select>
                                    <span v-if="showError.gender" class="error text-danger">{{showError.gender[0]}}</span>
                                </div>
                                <div class="form-group">
                                    <label for="title">Đơn vị công tác:</label>
                                    <input type="text" name="company" class="form-control" v-model="inputItem.company" />
                                    <span v-if="showError.company" class="error text-danger">{{showError.company[0]}}</span>
                                </div>
                                <div class="form-group">
                                    <label for="title">Địa chỉ đơn vị công tác:</label>
                                    <input type="text" name="company_address" class="form-control" v-model="inputItem.company_address" />
                                    <span v-if="showError.company_address" class="error text-danger">{{showError.company_address[0]}}</span>
                                </div>
                                <div class="form-group">
                                    <label for="title">Học hàm:</label>
                                    <input type="text" name="academic" class="form-control" v-model="inputItem.academic" />
                                    <span v-if="showError.academic" class="error text-danger">{{showError.academic[0]}}</span>
                                </div>
                                <div class="form-group">
                                    <label for="title">Học vị:</label>
                                    <input type="text" name="degree" class="form-control" v-model="inputItem.degree" />
                                    <span v-if="showError.degree" class="error text-danger">{{showError.degree[0]}}</span>
                                </div>
                                <div class="form-group">
                                    <label for="title">Chức vụ:</label>
                                    <input type="text" name="position" class="form-control" v-model="inputItem.position" />
                                    <span v-if="showError.position" class="error text-danger">{{showError.position[0]}}</span>
                                </div>
                            </div>
                        </div>
                        </div>
                        <div class="form-group">
                            <a v-if="loading == false" v-on:click.prevent="storeItem" class="btn btn-success">Lưu</a>
                            <div  v-if="loading">
                                <img width="24px; height: 24px;" src="http://bestanimations.com/Science/Gears/loadinggears/loading-gear-3.gif" alt=""> 
                                Đang lưu dữ liệu
                            </div>
                        </div>
                    </form>
                    
                    
                </div>
            </div>
        </div>
   

    <!-- REVIEW QUIZ -->
    <div class="modal fade" id="review-quiz" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button v-on:click="clearinputItem" type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                    <h4 class="modal-title" id="myModalLabel">Chấm điểm bài thi</h4> 
                </div>
               
                <div class="modal-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Câu hỏi</th>
                                <th>Đáp án đúng</th>
                               
                                <th>Trả lời</th>
                                <th>Điểm</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template v-for="result in quiz_results">
                                <tr>
                                    <td><h4>{{result.question.name}}</h4>
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-3" v-for="(answer, index) in result.question.answers">
                                            {{index+1}}. {{answer}}
                                        </div>
                                    </div>
                                    </td>
                                    <td>{{result.correct_answer}}</td>
                                    <td>{{result.answer}}</td>
                                    <td>
                                        <div v-if="result.type == 4">
                                        <input @input="addEvent" style="width: 60px;" @change="addEvent"  v-bind:name="'question-'+result.question.question_id"  v-bind:id="'question-'+result.question.question_id" v-bind:question_id="result.question.question_id"  class="form-control" min="0" :max="result.question.points" :value="result.points" type="number">
                                        </div>
                                        <div :class="result.points ? 'text-success' : 'text-danger'" v-else>
                                            <b>{{result.points ? result.points : 0}}</b>
                                        </div>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                    <div>
                        <p class="text-center">
                            <button @click.prevent="reviewConfirm" class="btn btn-success">Xác nhận</button>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>


<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.5.2/css/buttons.dataTables.min.css">
<script src="https://momentjs.com/downloads/moment.min.js"></script>

    <link rel="stylesheet" href="//cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
   
<script>
   Vue.component('v-select', VueSelect.VueSelect);
    var app = new Vue({
        el: '#app',
        data: {
			list_upload: [],
			list_upload_name:'',
			account_created: 0,
            inputItem: {
                'username': '',
                'email': '',
                'full_name': '',
                'address': '',
                'mobile': '',
                'dob': '',
                'gender': 1,
                'company': '',
                'position': '',
                'academic': '',
                'degree': '',
                'status': 10
               
            },
            quiz_result_id:0,
            showError:{},
            loading : false,   
            addStudent: '',
            students: [
                <?php foreach ($users as $user) {?> 
                {
                    id: <?php echo $user['id']?>,
                    fullname: '<?php echo $user['full_name'];?> - <?php echo $user['email'];?> - <?php echo $user['phone_number'];?>'
                },
                <?php } ?>
            ],
            quiz_results:[],      
        },

       
        methods: {
            addEvent ({ type, target }) {
            var max = $(target).attr('max');
            max = parseInt(max);
            point = parseInt(target.value);
            if (point > max || point < 0) {
                var question_id = $(target).attr('question_id');
                $('#question-'+question_id).val("");
                alert('Điểm không được nhỏ hơn 0 và lớn hơn '+max);
            }else {
                var att = $(target).attr('newattr');
                let submitanswer= {
                        'quiz_result_id': this.quiz_result_id,
                        'question_id': $(target).attr('question_id'),
                        'points': target.value
                }
                console.log(submitanswer);
                this.submitAnswer(submitanswer);
            }
            },
            submitAnswer(submitanswer) {
                //console.log(submitanswer);
                axios.post('/api/elearning/quiz/reviewanswer', submitanswer)
                    .then(response => {
                        console.log(response);     
                    });
            },
            assignStudent: function(){
                if (this.addStudent) {
                    this.loading = true;
                    axios.post('/api/elearning/student/assign?course_id=<?php echo $course->course_id?>', this.addStudent).then((response) => {
                        console.log(response);
                        if (response.data.error) {
                            //console.log(response.data.error);
                            this.showError = response.data.error;
                        }else {
                            toastr.success('Học viên đã được thêm vào khóa học.', 'Thông báo', {
                                    timeOut: 5000
                                });
                                this.loading = false;
                                this. addStudent= '';
                        
                        }

                    }).catch(e => {
                        console.log(e);
                    });
                }else {
                    toastr.warning('Vui lòng chọn học viên trước.', 'Thông báo', {
                                    timeOut: 5000
                                });
                }
            },
            review: function (quiz_result_id) {
                this.quiz_result_id = quiz_result_id;
                $("#review-quiz").modal('show');
                axios.get('/api/elearning/result?id='+quiz_result_id)
                .then(response => {
                    
                    console.log(response);
                    this.quiz_results = response.data.results;
                  
                    
                });
            },
            reviewConfirm: function(){
                axios.post('/api/elearning/result/review?id='+this.quiz_result_id).then((response) => {
                    console.log(response);
                    if (response.data.error) {
                        //console.log(response.data.error);
                        this.showError = response.data.error;
                    }else {
                        toastr.success('Bài thi đã được chấm điểm.', 'Thông báo', {
                            timeOut: 5000
                        });
                        $("#review-quiz").modal('hide');
                        location.reload();
                    }

                }).catch(e => {
                    console.log(e);
                });;
            },
            storeItem: function() {
                this.loading = true;
                axios.post('/api/elearning/student/store?course_id=<?php echo $course->course_id?>', this.inputItem).then((response) => {
                    console.log(response);
                    if (response.data.error) {
                        //console.log(response.data.error);
                        this.showError = response.data.error;
                    }else {
                        if (this.inputItem.id) {
                            toastr.success('Học viên đã được cập nhật.', 'Thông báo', {
                                timeOut: 5000
                            });
                        } else {
                            toastr.success('Học viên đã được tạo.', 'Thông báo', {
                                timeOut: 5000
                            });
                        }
                        this.loading = false;
                       this.clearinputItem();
                        $("#create-item").modal('hide');
                        location.reload();
                    }

                }).catch(e => {
                    console.log(e);
                });;
            },
            getVueItems: function() {

            axios.post('/api/elearning/course/students?course_id=<?php echo $course->course_id?>&page=' + this.pagination.current_page )
                .then(response => {
					console.log(response);
                    this.pagination = response.data.pagination;
                    this.students = response.data.data;
                
                });
            },
            activeItem: function(id) {
            console.log(id);
            axios.post('/api/elearning/course/active?course_id=<?php echo $course->course_id?>&user_id='+id).then((response) => {
                toastr.success('Đã duyệt học viên này.', 'Thông báo', {
                    timeOut: 5000
                });
                console.log(response);
                this.getVueItems();
                 location.reload();


            }).catch(e => {
                console.log(e);
            });
             location.reload();
            },
            deactiveItem: function(id) {
            axios.post('/api/elearning/course/deactive?course_id=<?php echo $course->course_id?>&user_id='+id).then((response) => {
                toastr.success('Đã khoá học viên này.', 'Thông báo', {
                    timeOut: 5000
                });
                this.getVueItems();
                location.reload();


            }).catch(e => {
                console.log(e);
            });
                location.reload();
            },
           
            clearinputItem:function(){
                this.inputItem = {
                    'username': '',
                    'email': '',
                    'full_name': '',
                    'address': '',
                    'mobile': '',
                    'dob': '',
                    'gender': 1,
                    'company': '',
                    'position': '',
                    'status': 10,
                    'academic': '',
                    'degree': '',
                
                } 
            },
			clearinputItemExcel: function(){
				location.reload();
			},
			previewImage: function(event) {
                var input = event.target;
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = (e) => {
						console.log(e.target.result);
						axios.post('/api/elearning/student/upload', e.target.result).then((response) => {
							if (response.data.error) {
								console.log(response.data.error);
								this.showError = response.data.error;
							}else {
								this.list_upload = response.data.data;
								this.list_upload_name = response.data.file_name;
								console.log(response);
								toastr.success('Tải thành công.', 'Thông báo', {
                                timeOut: 5000
								});
								$('#student_upload').val('');

							}
						}).catch(e => {
							console.log(e);
						});
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            },
			createAccountBulk: function() {
				this.loading = true;
				let post_data = {
					file: this.list_upload_name,
					course_id:<?php echo $course->course_id?>
				}
				axios.post('/api/elearning/student/assignbulk', post_data).then((response) => {
					if (response.data.error) {
						console.log(response.data.error);
						this.showError = response.data.error;
					}else {
						console.log(response);
						this.account_created = 1;
						this.list_upload = response.data;
						toastr.success('Tạo tài khoản thành công.', 'Thông báo', {
							timeOut: 5000
						});
						
						this.loading = false;

					}
				}).catch(e => {
					console.log(e);
				});
			},
            



        }
        
    })
</script>
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
                