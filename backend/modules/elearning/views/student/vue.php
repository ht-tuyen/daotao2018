<?php 
    $this->title = "Quản lý học viên";
    $this->params['breadcrumbs'][] = $this->title;
?>

<div id="app" class="panel panel-info">
    <div class="panel-heading">
        <div class="pull-right">
            <div class="summary">Hiển thị <b>{{pagination.from}}-{{pagination.to}}</b> trong số <b>{{pagination.total}}</b> mục.</div>
        </div>
        <h3 class="panel-title">
        </h3>
        <div class="clearfix"></div>
    </div>
    <div class="kv-panel-before" style="padding: 10px;border-bottom: 1px solid #ccc;">
        <div class="pull-right">
            <div class="btn-toolbar kv-grid-toolbar" role="toolbar">
                <div class="btn-group">
                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#create-item">
                        <i class="glyphicon glyphicon-plus"></i> Tạo học viên
                    </button>
					<button type="button" class="btn btn-success" data-toggle="modal" data-target="#create-item-excel">
                        <i class="glyphicon glyphicon-upload"></i> Tải học viên từ file Excel
                    </button>
					<a href="/api/elearning/student/download" class="btn btn-success">
                        <i class="glyphicon glyphicon-download"></i> Xuất danh sách học viên 
                    </a>
                    <a class="btn btn-default" href="/acp/elearning/course" title="Reset Grid" data-pjax="0"><i class="glyphicon glyphicon-repeat"></i> Tải lại &amp; Xóa lọc trang</a>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
    </div>
    <div class="" style="padding: 10px;">
    <table>
        <tr>
            <td width="200"><p><b>Tìm kiếm theo khóa học</b></p></td>
            <td width="400"><v-select @input="search_item" v-model="search.course" :options="courses" label="name"></v-select></td>
        </tr>
    </table>
      
    </div>
   
   
    <table class="admintablelist table table-bordered table-hover">
        <thead>
            <tr>
                <th><input type="checkbox" v-on:click="selectAll" v-model="allchecked"></th>
                <th><a href="" v-on:click.prevent="sortBy('id')">Id</a></th>
                <th><a href="" v-on:click.prevent="sortBy('username')">Tài khoản</a></th>
                <th><a href="" v-on:click.prevent="sortBy('full_name')">Tên</a></th>
                <th><a href="" v-on:click.prevent="sortBy('email')">Email</a></th>
                <th><a href="" v-on:click.prevent="sortBy('mobile')">SĐT</a></th>
                <th><a href="" v-on:click.prevent="sortBy('status')">Trạng thái</a></th>
                <th></th>
            </tr>
            <tr>
                <td></td>
                <td>
                    <input type="text" style="width:60px;" class="form-control" v-on:keyup="search_item" name="search.id" v-model="search.id" /></td>
                <td>
                    <input type="text" placeholder="Tìm kiếm tài khoản" class="form-control" v-on:keyup="search_item" name="search.username" v-model="search.username" />
                </td>
                <td>
                    <input type="text" placeholder="Tìm kiếm tên" class="form-control" v-on:keyup="search_item" name="search.full_name" v-model="search.full_name" />
                </td>
                <td>
                    <input type="text" placeholder="Tìm kiếm email" class="form-control" v-on:keyup="search_item" name="search.email" v-model="search.email" />
                </td>
                <td>
                    <input type="text" placeholder="Tìm kiếm SĐT" class="form-control" v-on:keyup="search_item" name="search.mobile" v-model="search.mobile" />
                </td>
                <td>
                    <select v-on:change="search_item" name="search.status" class="form-control" v-model="search.status">
                        <option value="">--Lọc trạng thái--</option>
                        <option value="10">Hoạt động</option>
                        <option value="0">Khóa</option>
                    </select>
                </td>
                <td></td>
            </tr>
        </thead>
        <tbody>

            <tr v-for="item in items">
                <td><input type="checkbox" v-model="checked" name="checked" :value="item.id"></td>
                <td>{{item.id}}</td>
                <td><a v-on:click.prevent="update_item(item)">{{item.username}}</a></td>
                <td>{{item.full_name}}</td>
                <td>{{item.email}}</td>
                <td>{{item.mobile}}</td>
                <td>{{item.status == 10 ? "Hoạt động" : "Khóa"}}</td>
                <td>
                    <a title="Cập nhật" v-on:click.prevent="update_item(item)"><span class="text-blue"><i class="glyphicon glyphicon-eye-open "></i></span></a>
                    <a title="Xóa" v-on:click.prevent="delete_item(item.id)"><span class="text-black"><i class="glyphicon glyphicon-trash"></i></span></a>
                    <a title="Lịch sử học tập" :href="'/acp/elearning/student/history?id='+item.id"><i class="fa fa-book" aria-hidden="true"></i></a>
					<a title="Xuất dữ liệu" :href="'/api/elearning/student/downloaddetail?id='+item.id"><i class="glyphicon glyphicon-download"></i></a>
                </td>
            </tr>

        </tbody>

    </table>
    <!-- Pagination -->
    <nav>
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
    </nav>
	<div class="modal fade" id="create-item-excel" tabindex="-1" role="dialog" aria-labelledby="myModalLabelExcel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button v-on:click="clearinputItem" type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>

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
								<th v-if="account_created">Kết quả tạo tài khoản</th>
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
								<span v-if="item[6] == 1" class="text-warning">Email đã tồn tại</span>
								<span v-if="item[6] == 2" class="text-warning">Email không được bỏ trống</span>
								<span v-if="item[6] == 0" class="text-success">Thành công</span>
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
                                    <input :readonly="inputItem.id" type="email" name="email" class="form-control" v-model="inputItem.email" />
                                    <span v-if="showError.email" class="error text-danger">{{showError.email[0]}}</span>
                                </div>
                                <div class="form-group">
                                    <label for="title">Tên đăng nhập:</label>
                                    <input  :readonly="inputItem.id" type="text" name="username" class="form-control" v-model="inputItem.username" />
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
    </div>
    <div style="  position: fixed;bottom: 0;background: #FFF;z-index: 9999;padding: 5px 10px; border-top: 1px solid #eee; width: 100%;" v-if="checked[0]">
        <span v-on:click="bulk_delete" class="btn btn-danger"><span class="glyphicon glyphicon-trash"></span>Xóa lựa chọn</span>
    </div>
</div>

<script>
   Vue.component('v-select', VueSelect.VueSelect);
    var app = new Vue({
        el: '#app',
        data: {
			list_upload: [],
			list_upload_name: '',
			account_created: 0,
			file: '',
            items: [],
            role_id : <?php echo Yii::$app->user->identity->role_id;?>,
            user_id : <?php echo Yii::$app->user->identity->user_id;?>,
            checked: [],
            allchecked: false,
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
                'status': 10,
                'academic': '',
                'degree': '',
                'company_address':''
               
            },
            search: {
                username: "",
                email: "",
                full_name: "",
                mobile: "",
                id: "",
                course:"",
				status: 10
            },
            sortColumn: "id",
            sortType: "DESC",
          
            pagination: {
                total: 0,
                per_page: 2,
                from: 1,
                to: 0,
                current_page: 1
            },
            offset: 4,
            showError:{},
            loading : false,
            courses:[]

          
        },

        mounted() {
            this.getVueItems();


        },
        components: {
           
        },
        methods: {
           
			download: function(){
				axios.post('/api/elearning/student/download')
                    .then(response => {
						console.log(response)
                    });
			},
            getVueItems: function() {

                axios.post('/api/elearning/student/list?page=' + this.pagination.current_page + '&sortBy=' + this.sortColumn + '&sortType=' + this.sortType+'&user_id=<?php echo Yii::$app->user->identity->user_id;?>&role_id=<?php echo Yii::$app->user->identity->role_id;?>', this.search)
                    .then(response => {
                        this.pagination = response.data.pagination;
                        this.items = response.data.data;
                        this.courses = response.data.courses;
                       
                    });
            },
            storeItem: function() {
                this.loading = true;
                axios.post('/api/elearning/student/store', this.inputItem).then((response) => {
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

                        this.truncateItem();
                        this.getVueItems();
                        this.loading = false;
                        $("#create-item").modal('hide');
                    }

                }).catch(e => {
                    console.log(e);
                });;
            },
            update_item: function(item) {
                console.log(item);
                this.inputItem = item;
                $("#create-item").modal('show');
            },
            delete_item: function(itemId) {
                if (confirm("Bạn chắc chắn muốn xóa mục này?")) {
                    axios.post('/api/elearning/student/delete?id=' + itemId)
                        .then(response => {
                            this.getVueItems();
                            toastr.warning('Học viên đã được khóa.', 'Thông báo', {
                                timeOut: 5000
                            });
                        });
                }

            },
            bulk_delete: function() {
                console.log(this.checked);
                if (confirm("Bạn chắc chắn muốn xóa những mục này?")) {
                    axios.post('/api/elearning/student/bulkdelete', this.checked)
                        .then(response => {
                            this.checked = [];
                            this.getVueItems();
                            console.log(response);
                            toastr.warning('Các học viên đã được khóa.', 'Thông báo', {
                                timeOut: 5000
                            });
                        });
                }
            },
            clearinputItem: function() {
                if (this.inputItem.id) {
                    this.truncateItem();
                }
            },
            truncateItem: function() {
                this.inputItem = {
                    'username': '',
                    'email': '',
                    'full_name': '',
                    'address': '',
                    'mobile': '',
                    'dob': '',
                    'gender': '',
                    'company': '',
                    'position': '',
                    'status': 0,
                    'academic': '',
                    'degree': '',
                    'company_address':''
                };
            },
            changePage: function(page) {
                this.pagination.current_page = page;
                this.getVueItems();
            },
            search_item: function() {
                this.getVueItems();
            },
            sortBy: function(column) {
                this.sortColumn = column;
                if (this.sortType == "ASC") {
                    this.sortType = "DESC";
                } else {
                    this.sortType = "ASC";
                }
                this.getVueItems();
            },

            
            selectAll: function() {


                this.checked = [];
                if (!this.allchecked) {
                    for (item in this.items) {
                        this.checked.push(this.items[item].course_id);
                    }
                }
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
				axios.post('/api/elearning/student/storebulk', this.list_upload_name).then((response) => {
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




        },
        computed: {
            isActived: function() {
                return this.pagination.current_page;
            },
            pagesNumber: function() {
                if (!this.pagination.to) {
                    return [];
                }
                var from = this.pagination.current_page - this.offset;
                if (from < 1) {
                    from = 1;
                }
                var to = from + (this.offset * 2);
                if (to >= this.pagination.last_page) {
                    to = this.pagination.last_page;
                }
                var pagesArray = [];
                while (from <= to) {
                    pagesArray.push(from);
                    from++;
                }
                return pagesArray;
            },
       
        }
    })
</script>