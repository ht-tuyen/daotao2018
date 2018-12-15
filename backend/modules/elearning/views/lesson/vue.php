

<?php 
    $this->title = "Quản lý bài học";
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
                        <i class="glyphicon glyphicon-plus"></i> Tạo {{module}}
                    </button>
                    <a class="btn btn-default" href="/acp/elearning/lesson" title="Reset Grid" data-pjax="0"><i class="glyphicon glyphicon-repeat"></i> Tải lại &amp; Xóa lọc trang</a>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
    </div>



    <table class="admintablelist table table-bordered table-hover">
        <thead>
            <tr>
                <th><input type="checkbox" v-on:click="selectAll" v-model="allchecked"></th>
                <th><a href="" v-on:click.prevent="sortBy('lesson_id')">Mã</a></th>
                <th><a href="" v-on:click.prevent="sortBy('name')">Bài học</a></th>
                <th><a href="" v-on:click.prevent="sortBy('course_id')">Khóa học</a></th>
                <th>Học viên</th>
                <th><a href="" v-on:click.prevent="sortBy('state')">Trạng thái</a></th>
                <th></th>
            </tr>
            <tr>
                <td></td>
                <td><input type="text" style="width:60px;" class="form-control" v-on:keyup="search_item" name="search.id" v-model="search.id" /></td>
                <td><input type="text" placeholder="Tìm kiếm bài học" class="form-control" v-on:keyup="search_item" name="search.name" v-model="search.name" /></td>
                <td>
                <v-select @input="search_item" v-model="search.course" :options="courses" label="name"></v-select>
                </td>
                <td></td>
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
                <td><input type="checkbox" v-model="checked" name="checked" :value="item.lesson_id"></td>
                <td>{{item.lesson_code}}</td>
                <td><a v-on:click.prevent="update_item(item)">{{item.name}}</a></td>
                <td>{{item.course}}</td>
                <td><a class="btn btn-primary" :href="'/acp/elearning/lesson/student?id='+item.lesson_id">{{item.students}}</a> 
                <a class="btn btn-success" :href="'/acp/elearning/lesson/student?id='+item.lesson_id">{{item.completed}}</a></td>
                <td>{{item.state == 1 ? "Xuất bản" : "Bản nháp"}}</td>
                <td>
                    <template v-if="role_id != 963 || user_id == item.created_by || item.main_teacher == user_id">
                        <a title="Cập nhật" v-on:click.prevent="update_item(item)"><span class="text-blue"><i class="glyphicon glyphicon-pencil "></i></span></a>
                        <a title="Xóa"t v-on:click.prevent="delete_item(item.lesson_id)"><span class="text-black"><i class="glyphicon glyphicon-trash"></i></span></a>
                        <a title="Bài kiểm tra" v-bind:href="'/acp/elearning/lesson/quiz?id='+item.lesson_id"><i class="glyphicon glyphicon-file"></i></a>
                        <a title="Thảo luận" v-bind:href="'/acp/elearning/lesson/message?id='+item.lesson_id"><i title="Trả lời" class="fa fa-comment-o" aria-hidden="true"></i></a>
                    </template>
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
        <button class="btn btn-primary">1</button> Tổng số học viên <br>
        <button class="btn btn-success">1</button> Học viên đã hoàn thành <br>
      
    </div>

    <!-- Create Item Modal -->
    <div class="modal fade" id="create-item" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button v-on:click="clearinputItem" type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>

                    <h4 class="modal-title" id="myModalLabel">{{inputItem.lesson_id ? "Cập nhật bài học" : "Tạo bài học"}}</h4>
                </div>
                <div class="modal-body">
                    <!-- Form-->
                    <form method="POST" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <label for="title">Tên {{module}} <span style="color:red">(*)</span>:</label>
                                    <input type="text" name="title" class="form-control" v-model="inputItem.name" />
                                    <span v-if="showError.name" class="error text-danger">{{showError.name[0]}}</span>
                                </div>
                                <div class="form-group">
                                    <label for="parent_id">Khóa học <span style="color:red">(*)</span>:</label>
                                  
                                    <select @change="getCategory" name="course_id" v-model="inputItem.course_id" id="course_id" class="form-control">
                                        <option v-bind:value="cousre.course_id" :disabled="cousre.ready == 1" v-text="cousre.name" v-for="cousre  in courses"></option>
                                    </select>
                                    <span v-if="showError.course_id" class="error text-danger">{{showError.course_id[0]}}</span>
                                </div>
                                <div class="form-group">
                                    <label for="short_desc">Giới thiệu:</label>
                                    <textarea name="short_desc" class="form-control" v-model="inputItem.short_desc"></textarea>
                                    <span v-if="showError.short_desc" class="error text-danger">{{showError.short_desc[0]}}</span>
                                </div>
                                <div class="form-group">
                                    <label for="lesson_format">Loại giáo trình <span style="color:red">(*)</span>:</label>
                                        <br>
                                    <input type="radio" name="lesson_format" id="one" value="1" v-model="inputItem.lesson_format">
                                    <label for="one">Video</label>
                                    <input type="radio" name="lesson_format" id="two" value="2" v-model="inputItem.lesson_format">
                                    <label for="two">PDF</label>
                                    <input type="radio" name="lesson_format" id="three" value="3" v-model="inputItem.lesson_format">
                                    <label for="three">Powerpoint</label>

                                </div>
                                <div class="form-group">
                                    <label for="state">Trạng thái:</label>
                                    <br>
                                    <input type="radio" name="state" id="one" value="1" v-model="inputItem.state">
                                    <label for="one">Xuất bản</label>
                                    <input type="radio" name="state" id="two" value="-1" v-model="inputItem.state">
                                    <label for="two">Bản nháp</label>

                                </div>
                                <div class="form-group">
                                    <label for="title">Thứ tự:</label>
                                    <input type="number" name="ordering" class="form-control" v-model="inputItem.ordering" />
                                    <span v-if="showError.ordering" class="error text-danger">{{showError.ordering[0]}}</span>   
                                </div>

                            </div>
                            <div class="col-xs-12 col-sm-6">
                               
                                <div class="form-group">
                                    <label for="lesson_resource">Tải giáo trình:</label>
                                    <!--<input type="file" id="lesson_resource" ref="lesson_resource" v-on:change="resourceChanged" name="lesson_resource" class="form-control" />-->
                                    <template v-if="inputItem.lesson_resource">
                                        <a target="_blank" :href="'/uploads/elearning/lesson/resource/'+inputItem.lesson_resource" v-text="inputItem.lesson_resource"></a>
                                    </template>
                                    <input type="file" class="form-control" id="file" ref="file" v-on:change="handleFileUpload()"/> 
                                    <div class="progress">
  <div class="progress-bar" role="progressbar" :aria-valuenow="uploadPercentage"
  aria-valuemin="0" aria-valuemax="100" :style="'width:'+uploadPercentage+'%'">
    <span style="position: inherit" class="sr-only">{{uploadPercentage}}% Complete</span>
  </div>
</div>

                                </div>
                               
                                <div class="form-group">
                                    <label for="attachment">Tài liệu khác:</label>
                                    <div v-if="inputItem.attachments">
                                        <table class="table table-bordered">
                                            <tr>
                                             
                                                <th>Tài liệu</th>
                                                <th></th>
                                            </tr>
                                            <template v-for="(attachment, index) in inputItem.attachments" >
                                            <tr>
                                                <td>{{attachment.name}}</td> 
                                                <td><a href="" @click.prevent="removeAttachment(index)">Xóa</a>
                                                </td>
                                            </tr>
                                            </template>
                                        </table>    
                                        </div>
                                        <div class="row">
                                            <div class="col-xs-12 col-sm-10">
                                            <v-select  v-model="attachmentInput" :options="attachments" label="name"></v-select>

                                            </div>
                                            <div class="col-xs-12 col-sm-2">
                                            <button @click.prevent="addAttachment" class="btn btn-info">Lấy tài liệu</button>

                                            </div>

                                        </div>
                                   
                                    
                                    <div class="upload-attachments"> 
                                        <div class="form-group">
                                            <label for="lesson_attachment">Tài liệu:</label>
                                            <input type="file" ref="lesson_attachment" v-on:change="attachmentChanged" name="lesson_attachment" id="lesson_attachment" class="form-control" />
                                            
                                        </div>
                                        <div class="form-group">
                                            <label for="title">Tên tài liệu <span style="color:red">(*)</span>:</label>
                                            <input type="text" name="title" required class="form-control" v-model="inputAttachmentItem.name" />
                                            <span v-if="showErrorAttach.name" class="error text-danger">{{showErrorAttach.name[0]}}</span>
                                        </div>
                                        <div class="form-group">
                                            <label for="description">Giới thiệu:</label>
                                            <textarea name="description" class="form-control" v-model="inputAttachmentItem.description"></textarea>
                                        </div>
                                        <button v-if="!isLoading" @click.prevent="uploadAttachment" class="btn btn-info">Tải tài liệu</button>
                                        <div  v-if="isLoading">
                                            <img width="24px; height: 24px;" src="http://bestanimations.com/Science/Gears/loadinggears/loading-gear-3.gif" alt=""> 
                                            Đang tải tài liệu
                                        </div>
                                    </div>

                                
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="full_desc">Thông tin {{module}}:</label>

                            
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
                            <a v-if="role_id != 963 || user_id == inputItem.created_by || inputItem.main_teacher == user_id" v-on:click.prevent="storeItem" class="btn btn-success">Lưu</a>
                            <div class="alert alert-warning" v-else>Bạn không có quyền sửa bài này</div>
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
            role_id : <?php echo Yii::$app->user->identity->role_id;?>,
            user_id : <?php echo Yii::$app->user->identity->user_id;?>,
            attachmentInput:'',
            items: [],
            module: "bài học",
            checked: [],
            allchecked: false,
            inputItem: {
                'name': '',
                'attachmentIds':[],
                'lesson_format':1,
                'lesson_resource':'',
                'full_desc': '',
                'short_desc': '',
                'lesson_id': '',
                'course_id': '',
                'state': 1,
                'ordering': '',
                'attachments':[],
                'resource_is_changed': 0,
               
                'attachment_is_changed':0
            },
            inputAttachmentItem: {
                'name':'',
                'description':'',
                'source':'',
                'source_is_changed':0,
                'state': 1,
                'category_id':0

            },
            search: {
                name: "",
                course: "",
                state: 1,
                id: ""
            },
            sortColumn: "lesson_id",
            sortType: "DESC",
            courses: [],
            attachments:[],
            pagination: {
                total: 0,
                per_page: 2,
                from: 1,
                to: 0,
                current_page: 1
            },
            offset: 4,
            attachment_category : 0,
            editorOption: {
                theme: 'snow',
                placeholder: 'Nhập nội dung tại đây'
            },
            showError:{},
			showErrorAttach: {},
            isLoading: false,
            file: '',
            uploadPercentage: 0
        },

        mounted() {
            this.getVueItems();
            

        },
        components: {
            LocalQuillEditor: VueQuillEditor.quillEditor,
            
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
            getCategory(){
                console.log(this.inputItem.course_id);
                if (!this.attachment_category) {

                }
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

                axios.post('/api/elearning/lesson/list?page=' + this.pagination.current_page + '&sortBy=' + this.sortColumn + '&sortType=' + this.sortType+'&user_id=<?php echo Yii::$app->user->identity->user_id;?>&role_id=<?php echo Yii::$app->user->identity->role_id;?>', this.search)
                    .then(response => {
                        this.pagination = response.data.pagination;
                        this.items = response.data.data;
                        this.courses = response.data.courses;
                        this.attachments = response.data.attachments;
                        //console.log(response);
                    });
            },
            addAttachment: function(){
                if (this.attachmentInput !="")
                this.inputItem.attachments.push(this.attachmentInput);
                this.attachmentInput = "";
            },
            removeAttachment: function(item){
                this.inputItem.attachments.splice(item, 1);
            },
            storeItem: function() {
                this.inputItem.user_id = <?php echo Yii::$app->user->identity->user_id;?>;
                this.inputItem.role_id = <?php echo Yii::$app->user->identity->role_id;?>;
                axios.post('/api/elearning/lesson/store', this.inputItem).then((response) => {
                    console.log(response);
                    if (response.data.error) {
                      
                        this.showError = response.data.error;
                    }else {
                     
                        console.log("ok 1");
                        if (this.inputItem.lesson_id) {
                            toastr.success('Bài học đã được cập nhật.', 'Thông báo', {
                                timeOut: 5000
                            });
                            console.log("ok 7");
                        } else {
                            toastr.success('Bài học đã được tạo.', 'Thông báo', {
                                timeOut: 5000
                            });
                            console.log("ok 8");
                            
                           
                        }
                        console.log("ok 2");
                        this.truncateItem();
                        console.log("ok 3");
                        this.getVueItems();
                        console.log("ok 4");
                        
                        console.log("ok 5");
                        $('#lesson_resource').val('');
                        console.log("ok 6");
                        $("#create-item").modal('hide');
                        console.log("ok 9");
                    }

                }).catch(e => {
                    console.log(e);
                });
            },
            update_item: function(item) {
                console.log(item);
                this.inputItem = item;
                if (this.inputItem.category_id) {
                    this.attachment_category = this.inputItem.category_id;
                }
                this.inputItem.attachmentIds = [];
                $("#create-item").modal('show');
            },
            delete_item: function(itemId) {
                if (confirm("Bạn chắc chắn muốn xóa mục này?")) {
                    axios.post('/api/elearning/lesson/delete?id=' + itemId)
                        .then(response => {
                            this.getVueItems();
                            toastr.warning('Bài học đã được xóa.', 'Thông báo', {
                                timeOut: 5000
                            });
                        });
                }

            },
            bulk_delete: function() {
                console.log(this.checked);
                if (confirm("Bạn chắc chắn muốn xóa những mục này?")) {
                    axios.post('/api/elearning/lesson/bulkdelete', this.checked)
                        .then(response => {
                            this.checked = [];
                            this.getVueItems();
                            console.log(response);
                            toastr.warning('Bài học đã được xóa.', 'Thông báo', {
                                timeOut: 5000
                            });
                        });
                }
            },
            clearinputItem: function() {
                if (this.inputItem.lesson_id) {
                    this.truncateItem();
                }
                this.showError = {};
            },
            truncateItem: function() {
                this.inputItem = {
                    'name': '',
                    'attachmentIds':[],
                    'lesson_format':1,
                    'lesson_resource':'',
                    'full_desc': '',
                    'short_desc': '',
                    'lesson_id': '',
                    'course_id': '',
                    'state': 1,
                    'ordering': '',
                    'attachments':[],
                    'resource_is_changed': 0,
                
                    'attachment_is_changed':0
                    
                };
                this.uploadPercentage = 0;
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

           
            resourceChanged: function(event) {
                // var input = event.target;
                // if (input.files && input.files[0]) {
                //     var reader = new FileReader();
                //     reader.onload = (e) => {
                //         this.inputItem.lesson_resource = e.target.result;
                //         this.inputItem.resource_is_changed = 1;
                //     }
                //     reader.readAsDataURL(input.files[0]);
                // }
            },
            handleFileUpload(){
               this.file = this.$refs.file.files[0];
               console.log (this.file);
               let formData = new FormData();

  /*
    Add the form data we need to submit
  */
  formData.append('imageFile', this.file);

  /*
    Make the request to the POST /single-file URL
  */
  let app = this;
  axios.post( '/api/elearning/lesson/uploadfile',
    formData,
    {
      headers: {
          'Content-Type': 'multipart/form-data'
      },
      onUploadProgress: function( progressEvent ) {
        this.uploadPercentage = parseInt( Math.round( ( progressEvent.loaded * 100 ) / progressEvent.total ) );
      }.bind(this)
    }
  ).then(function(e){
    console.log(e);
    app.inputItem.lesson_resource = e.data.file_name;
    app.inputItem.resource_is_changed = 1;
    toastr.success('Tài liệu đã được upload.', 'Thông báo', {
                                timeOut: 5000
                            });
  })
  .catch(function(e){
    console.log(e);
  });
            },

            attachmentChanged: function(event) {
                
                var input = event.target;
                //console.log(input.files);
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = (e) => {
                        this.inputAttachmentItem.source = e.target.result;
                        console.log(e.target.result);
                        this.inputAttachmentItem.source_is_changed = 1;
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            },
            uploadAttachment: function() {
                console.log("ok upload day");
                this.isLoading = true;
                this.inputAttachmentItem.category_id = this.attachment_category;
                this.inputAttachmentItem.user_id = <?php echo Yii::$app->user->identity->user_id;?>;
                this.inputAttachmentItem.role_id = <?php echo Yii::$app->user->identity->role_id;?>;
                axios.post('/api/elearning/attachment/store', this.inputAttachmentItem).then((response) => {
                    console.log(response);
                    if (response.data.error) {
                        //console.log(response.data.error);
                        this.showErrorAttach = response.data.error;
						this.isLoading = false;
                    }else {
                        //this.inputItem.attachmentIds.push(response.data.attachment_id);
                        this.inputItem.attachments.push(response.data);
                        toastr.success('Tài liệu đã được tải lên.', 'Thông báo', {
                            timeOut: 5000
                        });
                        this.inputAttachmentItem = {
                            'name':'',
                            'description':'',
                            'source':'',
                            'source_is_changed':0,
                            'state': 1,
                            'category_id':0
                        };
                        this.isLoading = false;
                        $('#lesson_attachment').val('');
                    }
                }).catch(e => {
                    console.log(e);
                });;
            },
           
            selectAll: function() {


                this.checked = [];
                if (!this.allchecked) {
                    for (item in this.items) {
                        this.checked.push(this.items[item].lesson_id);
                    }
                }
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
            editorA() {
                return this.$refs.quillEditorA.quill
            },
            editorB() {
                return this.$refs.quillEditorB.quill
            }
        }
    })
</script>
