<?php 
    $this->title = "Quản lý khóa học";
    $this->params['breadcrumbs'][] = $this->title;

    /*
        Role id 
        1: Admin
        963 : Teacher
    */
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
                    <button v-if="role_id != 963" type="button" class="btn btn-success" data-toggle="modal" data-target="#create-item">
                        <i class="glyphicon glyphicon-plus"></i> Tạo khóa học
                    </button>
					<a class="btn btn-success" href="/api/elearning/course/download" title="Reset Grid" ><i class="glyphicon glyphicon-download"></i> Xuất dữ liệu</a>

                    <a class="btn btn-default" href="/acp/elearning/course" title="Reset Grid" data-pjax="0"><i class="glyphicon glyphicon-repeat"></i> Tải lại &amp; Xóa lọc trang</a>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
    </div>


    <div style="padding: 20px;">
    <table class="admintablelist table table-bordered table-hover">
        <thead>
            <tr>
                <th><input type="checkbox" v-on:click="selectAll" v-model="allchecked"></th>
                <th><a href="" v-on:click.prevent="sortBy('course_id')">Id</a></th>
                <th><a href="" v-on:click.prevent="sortBy('name')">Khóa học</a></th>
                <th><a href="" v-on:click.prevent="sortBy('category_id')">Lĩnh vực</a></th>
                <th>Học viên</th>
                <th><a href="" v-on:click.prevent="sortBy('state')">Trạng thái</a></th>
                <th></th>
            </tr>
            <tr>
                <td></td>
                <td><input type="text" style="width:60px;" class="form-control" v-on:keyup="search_item" name="search.id" v-model="search.id" /></td>
                <td><input type="text" placeholder="Tìm kiếm khóa học" class="form-control" v-on:keyup="search_item" name="search.name" v-model="search.name" /></td>
                <td>
                <v-select @input="search_item" v-model="search.category" :options="categories" label="name"></v-select>
                </td>
                <td>
                    
                </td>
                <td>
                    <select v-on:change="search_item" name="search.state" class="form-control" v-model="search.state">
                        <option value="">--Lọc trạng thái--</option>
                        <option value="1">Xuất bản</option>
                        <option value="-1">Bản nháp</option>
                    </select>
                </td>
                <td></td>
            </tr>
        </thead>
        <tbody>

            <tr v-for="item in items">
                <td><input type="checkbox" v-model="checked" name="checked" :value="item.course_id"></td>
                <td>{{item.course_id}}</td>
                <td><a v-on:click.prevent="update_item(item)">{{item.name}}</a></td>
                <td>{{item.category}}</td>
                <td>
                   <a title="Tổng số học viên đăng ký" class="btn btn-primary" v-bind:href="'/acp/elearning/course/student?id='+item.course_id">{{item.course_student.length}}</a>
                    <a title="Học viên đã được kích hoạt" class="btn btn-success" v-bind:href="'/acp/elearning/course/student?id='+item.course_id">{{item.active_users}}</a>
                   <a title="Học viên chưa được kích hoạt" class="btn btn-danger" v-bind:href="'/acp/elearning/course/student?id='+item.course_id">{{item.inactive_users}}</a>
                   <a title="Học viên đã làm bài kiểm tra cuối khoá" class="btn btn-warning" v-bind:href="'/acp/elearning/course/student?id='+item.course_id">{{item.completed_quizes}}</a>
                </td>
                <td>{{item.state == 1 ? "Xuất bản" : "Bản nháp"}}</td>
                <td>
                    <a title="Cập nhật" v-on:click.prevent="update_item(item)"><span class="text-blue"><i class="glyphicon glyphicon-pencil "></i></span></a>
                    <a title="Xóa" v-if="role_id != 963" v-on:click.prevent="delete_item(item.course_id)"><span class="text-black"><i class="glyphicon glyphicon-trash"></i></span></a>
                    <!-- <a v-bind:href="'/acp/elearning/course/student?id='+item.course_id"><i class="glyphicon glyphicon-user"></i></a> -->
                   
                    <template v-if="item.quiz">
                        <template v-if="item.completed_quizes > 0">
                            <!-- Xem danh sách học viên thi -->
                            <a title="Xem danh sách học viên" v-bind:href="'/acp/elearning/course/student?id='+item.course_id"><i class="glyphicon glyphicon-file"></i></a>
                        </template>
                        <template v-else>
                            <!-- Sửa đề thi -->
                        <a title="Cập nhật đề thi" v-if="item.main_teacher == user_id || role_id != 963" v-bind:href="'/acp/elearning/quiz/update?type=2&id='+item.course_id+'&name='+item.name+'&category_id='+item.category_id+'&quiz_id='+item.quiz.quiz_id"><i class="glyphicon glyphicon-file"></i></a>
                        </template>
                       
                    </template>
                    <template v-else>
                        <!-- Tạo đề thi -->
                        <a title="Tạo đề thi" v-if="item.main_teacher == user_id || role_id != 963" v-bind:href="'/acp/elearning/quiz/create?type=2&id='+item.course_id+'&name='+item.name+'&category_id='+item.category_id"><i class="glyphicon glyphicon-file"></i></a>
                    </template>
                    <a title="Xuất dữ liệu" :href="'/api/elearning/course/downloaddetail?id='+item.course_id"><i class="glyphicon glyphicon-download"></i></a>
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
    
    <div class="note">
        <h4><i>* Chú thích học viên:</i></h4>
        <button class="btn btn-primary">1</button> Tổng số học viên đăng ký <br>
        <button class="btn btn-success">1</button> Học viên đã được kích hoạt <br>
        <button class="btn btn-danger">1</button> Học viên chưa được kích hoạt <br>
        <button class="btn btn-warning">1</button> Học viên đã làm bài kiểm tra cuối khoá <br>
    </div>
    </div>


    
    <!-- Create Item Modal -->
    <div class="modal fade" id="create-item" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button v-on:click="clearinputItem" type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>

                    <h4 class="modal-title" id="myModalLabel">{{inputItem.course_id ? "Cập nhật khóa học" : "Tạo khóa học"}}</h4>
                </div>
                <div class="modal-body">
                    <!-- Form-->
                    <form method="POST" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <label for="title">Tên khóa học:</label>
                                    <input type="text" name="title" class="form-control" v-model="inputItem.name" />
                                    <span v-if="showError.name" class="error text-danger">{{showError.name[0]}}</span>   
                                </div>
                                <div class="form-group">
                                    <label for="parent_id">Lĩnh vực :</label>
                                    <select name="category_id" v-model="inputItem.category_id" id="category_id" class="form-control">
                                        <option v-bind:value="category.value" v-text="category.name" v-for="category in categories"></option>
                                    </select>
                                    <span v-if="showError.category_id" class="error text-danger">{{showError.category_id[0]}}</span>   
                                </div>
                                <div class="row">
                                    <div class="col-xs-12 col-sm-4">
                                        <div class="form-group">
                                            <label for="state">Trạng thái:</label>
                                            <br>
                                            <input type="radio" name="state" id="one" value="1" v-model="inputItem.state">
                                            <label for="one">Xuất bản</label>
                                            <input type="radio" name="state" id="two" value="-1" v-model="inputItem.state">
                                            <label for="two">Bản nháp</label>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-4">
                                        <div class="form-group">
                                            <label for="state">Khóa học nổi bật:</label>
                                            <br>
                                            <input type="radio" name="featured" id="one_featured" value="1" v-model="inputItem.featured">
                                            <label for="one_featured">Có</label>
                                            <input type="radio" name="featured" id="two_featured" value="0" v-model="inputItem.featured">
                                            <label for="two_featured">Không</label>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-4">
                                        <div class="form-group">
                                            <label for="ready">Cho phép đăng ký khóa học:</label>
                                            <br>
                                            <input type="radio" name="ready" id="one_ready" value="1" v-model="inputItem.ready">
                                            <label for="one_ready">Có</label>
                                            <input type="radio" name="ready" id="two_ready" value="-1" v-model="inputItem.ready">
                                            <label for="two_ready">Không</label>
                                        </div>
                                    </div>
                                </div>


                                
                                <div class="form-group">
                                    <label for="title">Thứ tự:</label>
                                    <input type="number" name="ordering" class="form-control" v-model="inputItem.ordering" />
                                    <span v-if="showError.ordering" class="error text-danger">{{showError.ordering[0]}}</span>   
                                </div>
                                <div class="form-group">
                                    <label for="">Giảng viên</label>
                                    <table class="table table-bordered">
                                        <tr>
                                            
                                            <th>Tên</th>
                                            <th>Quyền hạn</th>
                                            <th></th>
                                        </tr>
                                        <template v-for="(teacher, index) in inputItem.teachers" >
                                        <tr>
                                            <td>{{teacher.fullname}} - {{teacher.username}}</td> 
                                            <td>
                                                <div v-if="teacher.user_id == mainTeacher">
                                                    <button @click.prevent="" class="btn btn-success">Chấm điểm</button>
                                                </div>   
                                                <div v-else>
                                                    <button @click.prevent="changeMainTeacher(teacher.user_id)" class="btn btn-warning">Không chấm điểm</button>
                                                </div> 
                                           </td>
                                            <td><a href="" @click.prevent="removeTeacher(index)">Xóa</a>
                                            </td>
                                        </tr>
                                        </template>
                                    </table>    
                                    
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-10">
                                        <v-select  v-model="teacherInput" :options="teachers" label="fullname"></v-select>

                                        </div>
                                        <div class="col-xs-12 col-sm-2">
                                        <button @click.prevent="addTeacher" class="btn btn-info">Thêm giảng viên</button>

                                        </div>

                                    </div>
                                </div>

                            </div>
                            <div class="col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <label for="thumbnail">Ảnh đại diện:</label>
                                    <input id="thumbnail" type="file" ref="thumbnail" v-on:change="previewImage" name="thumbnail" class="form-control" />
                                    <div class="image-preview" v-if="inputItem.thumbnail" style="border:1px solid #ccc; padding: 5px; margin: 5px;">
                                    <img v-if="inputItem.course_id && !inputItem.thumbnail_is_changed" class="preview" :src="'/uploads/elearning/course/'+inputItem.thumbnail" style="width: 300px;">
                                    <img v-else :src="inputItem.thumbnail" style="width: 300px;" alt="">                                    
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="full_image">Ảnh chi tiết:</label>
                                    <input id="full_image" type="file" ref="fullImage" v-on:change="previewfullImage" name="full_image" class="form-control" />
                                    <div class="image-preview" v-if="inputItem.full_image" style="border:1px solid #ccc; padding: 5px; margin: 5px;">
                                        <img v-if="inputItem.course_id && !inputItem.full_image_is_changed" class="preview" :src="'/uploads/elearning/course/'+inputItem.full_image" style="width: 300px;">
                                        <img v-else :src="inputItem.full_image" style="width: 300px;" alt="">
                                       
                                    </div>
                                
                                
                                </div>
                            </div>
                        </div>





                        <div class="form-group">
                            <label for="full_desc">Thông tin khóa học:</label>
                            
                           
                            <div class="editor">
                                <vue-mce
                                @init="handleInit"
                                @destroy="handleDestroy"
                                @change="handleChange"
                                @input="handleInput"
                                @error="handleError"
                                v-model="inputItem.full_desc"
                                :config="config"
                                name="qwerty"
                                ref="ref"
                                />
                            </div>
                            <span v-if="showError.full_desc" class="error text-danger">{{showError.full_desc[0]}}</span>
                            
                        </div>



                        <div class="form-group">
                            
                            <a v-if="role_id != 963" v-on:click.prevent="storeItem" class="btn btn-success">Lưu</a>
                            <div class="alert alert-warning" v-else>Bạn không có quyền sửa thông tin khóa học</div>
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

<style>
    button a {
        color:#fff !important;
    }
</style>
<script src="https://cloud.tinymce.com/stable/tinymce.min.js?apiKey=lxzv6h8kur0syil9hllrjlm94wtumcz3fy6ea2jc0inlsmnb"></script>
<script src="https://cdn.jsdelivr.net/npm/vue-mce@1.5.0/dist/vue-mce.web.js"></script>

<script>
    const config = {
        height: 500,
        inline: false,
        theme: 'modern',
        fontsize_formats: "8px 10px 12px 14px 16px 18px 20px 22px 24px 26px 28px 30px 34px 38px 42px 48px 54px 60px",
        plugins: 'print preview fullpage powerpaste searchreplace autolink directionality advcode visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists textcolor wordcount  imagetools mediaembed  linkchecker contextmenu colorpicker textpattern help',
        toolbar1: 'formatselect fontsizeselect | bold italic strikethrough forecolor backcolor | link | alignleft aligncenter alignright alignjustify  | numlist bullist outdent indent  | removeformat',
        image_advtab: true,
        templates: [
            { title: 'Test template 1', content: 'Test 1' },
            { title: 'Test template 2', content: 'Test 2' }
        ],
        content_css: [
            '//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
            '//www.tinymce.com/css/codepen.min.css'
        ],
        };
    
    Vue.use(VueQuillEditor)
    Vue.component('v-select', VueSelect.VueSelect);
    var app = new Vue({
        el: '#app',
        data: {
            config: config,
            
            items: [],
            role_id : <?php echo Yii::$app->user->identity->role_id;?>,
            user_id : <?php echo Yii::$app->user->identity->user_id;?>,
            checked: [],
            allchecked: false,
            inputItem: {
                'name': '',
                'thumbnail': '',
                'full_desc': '',
                'ready':-1,
                'full_image': '',
                'featured': 0,
                'short_desc': '',
                'course_id': '',
                'category_id': '',
                'state': 1,
                'ordering': '',
                'full_image_is_changed': 0,
                'thumbnail_is_changed': 0,
                'student':[],
                'course_student':[],
                'teachers':[],
            },
            teacherInput:'',
            search: {
                name: "",
                category: "",
                state: 1,
                id: ""
            },
            mainTeacher: 0,
            sortColumn: "course_id",
            sortType: "DESC",
            categories: [],
            pagination: {
                total: 0,
                per_page: 2,
                from: 1,
                to: 0,
                current_page: 1
            },
            offset: 4,

            editorOption: {
                theme: 'snow',
                placeholder: 'Nhập nội dung khóa học tại đây'
            },
            showError:{},
            teachers: [],
        },

        mounted() {
            this.getVueItems();


        },
        components: {
            LocalQuillEditor: VueQuillEditor.quillEditor,
            'vue-ckeditor': VueCkeditor.component
        },
        methods: {
            handleInit (editor) {
                console.log('Initialized');
                },
                
                handleDestroy (editor) {
                console.log('Destroyed');
                },
                
                handleChange (value) {
                console.log('Changed');
                },
                
                handleInput (value) {
                console.log('Input');
                },
                
                handleError (err) {
                console.log('An error occurred');
                },
            changeMainTeacher: function (id){
                this.mainTeacher = id;
            },
            addTeacher: function (){
                if (this.teacherInput !="")
                this.inputItem.teachers.push(this.teacherInput);
                this.teacherInput = "";
            },
            removeTeacher: function(item){
                this.inputItem.teachers.splice(item, 1);
            },
           
            onEditorBlur(quill) {
                console.log('editor blur!', quill)
            },
            onEditorFocus(quill) {
                console.log('editor focus!', quill)
            },
            onEditorReady(quill) {
                console.log('editor ready!', quill)
            },
            getVueItems: function() {

                axios.post('/api/elearning/course/list?page=' + this.pagination.current_page + '&sortBy=' + this.sortColumn + '&sortType=' + this.sortType+'&user_id=<?php echo Yii::$app->user->identity->user_id;?>&role_id=<?php echo Yii::$app->user->identity->role_id;?>', this.search)
                    .then(response => {
                        this.pagination = response.data.pagination;
                        this.items = response.data.data;
                        this.categories = response.data.categories;
                        this.teachers = response.data.teachers;
                    });
            },
            storeItem: function() {
                this.inputItem.mainTeacher = this.mainTeacher;
                this.inputItem.user_id = <?php echo Yii::$app->user->identity->user_id;?>;
                this.inputItem.role_id = <?php echo Yii::$app->user->identity->role_id;?>;
                axios.post('/api/elearning/course/store', this.inputItem).then((response) => {
                    if (response.data.error) {
                        //console.log(response.data.error);
                        this.showError = response.data.error;
                    }else {
                        if (this.inputItem.course_id) {
                            toastr.success('Khóa học đã được cập nhật.', 'Thông báo', {
                                timeOut: 5000
                            });
                        } else {
                            toastr.success('Khóa học đã được tạo.', 'Thông báo', {
                                timeOut: 5000
                            });
                        }

                        this.truncateItem();
                        this.getVueItems();
                        $('#thumbnail').val('');
                        $('#full_image').val('');
                        $("#create-item").modal('hide');
                        //location.reload();
                    }

                }).catch(e => {
                    console.log(e);
                });;
            },
            update_item: function(item) {
                
                this.inputItem = item;
                this.mainTeacher = item.main_teacher;
                $("#create-item").modal('show');
            },
            approve_user: function (item){
                this.inputItem = item;
                $("#approve-user").modal('show');
            },
            delete_item: function(itemId) {
                if (confirm("Bạn chắc chắn muốn xóa mục này?")) {
                    axios.post('/api/elearning/course/delete?id=' + itemId)
                        .then(response => {
                            this.getVueItems();
                            toastr.warning('Khóa học đã được xóa.', 'Thông báo', {
                                timeOut: 5000
                            });
                        });
                }

            },
            bulk_delete: function() {
                console.log(this.checked);
                if (confirm("Bạn chắc chắn muốn xóa những mục này?")) {
                    axios.post('/api/elearning/course/bulkdelete', this.checked)
                        .then(response => {
                            this.checked = [];
                            this.getVueItems();
                            console.log(response);
                            toastr.warning('Các khóa học đã được xóa.', 'Thông báo', {
                                timeOut: 5000
                            });
                        });
                }
            },
            clearinputItem: function() {
                if (this.inputItem.course_id) {
                    this.truncateItem();
                }
                this.showError = {};
            },
            truncateItem: function() {
                this.inputItem = {
                    'name': '',
                    'thumbnail': '',
                    'full_desc': '',
                    'featured': 0,
                    'short_desc': '',
                    'state': 1,
                    'full_image': '',
                    'course_id': '',
                    'category_id': '',
                    'ordering': '',
                    'ready':-1,
                    'full_image_is_changed': 0,
                    'thumbnail_is_changed': 0,
                    'student':[],
                    'course_student':[]
                };
                this.showError = {};
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

            previewImage: function(event) {
                var input = event.target;
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = (e) => {
                        this.inputItem.thumbnail = e.target.result;
                        this.inputItem.thumbnail_is_changed = 1;
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            },
            previewfullImage: function(event) {
                var input = event.target;
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = (e) => {
                        this.inputItem.full_image = e.target.result;
                        this.inputItem.full_image_is_changed = 1;
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            },
            selectAll: function() {


                this.checked = [];
                if (!this.allchecked) {
                    for (item in this.items) {
                        this.checked.push(this.items[item].course_id);
                    }
                }
            },

        },
        filters: {
            active_user: function (users) {
                if (!users) return 0
                users.filter(function(value){
                    return value.state == 1
                })
            }
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
            editorA() {
                return this.$refs.quillEditorA.quill
            },
            editorB() {
                return this.$refs.quillEditorB.quill
            }
        },
        

    })
</script>