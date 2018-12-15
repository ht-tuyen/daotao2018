<?php 
    $this->title = "Quản lý ngân hàng câu hỏi";
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
                    <a class="btn btn-default" href="/acp/elearning/question" title="Reset Grid" data-pjax="0"><i class="glyphicon glyphicon-repeat"></i> Tải lại &amp; Xóa lọc trang</a>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
    </div>



    <table class="admintablelist table table-bordered table-hover">
        <thead>
            <tr>
                <th><input type="checkbox" v-on:click="selectAll" v-model="allchecked"></th>
                <th><a href="" v-on:click.prevent="sortBy('question_id')">Id</a></th>
                <th><a href="" v-on:click.prevent="sortBy('name')">Câu hỏi</a></th>
                <th><a href="" v-on:click.prevent="sortBy('category_id')">Lĩnh vực</a></th>
                <th><a href="" v-on:click.prevent="sortBy('course_id')">Khoá học</a></th>
                <th><a href="" v-on:click.prevent="sortBy('lesson_id')">Bài học</a></th>
                <th><a href="" v-on:click.prevent="sortBy('state')">Trạng thái</a></th>
                <th></th>
            </tr>
            <tr>
                <td></td>
                <td><input type="text" style="width:60px;" class="form-control" v-on:keyup="search_item" name="search.id" v-model="search.id" /></td>
                <td><input type="text" placeholder="Tìm kiếm câu hỏi" class="form-control" v-on:keyup="search_item" name="search.name" v-model="search.name" /></td>
                <td>
                <v-select @input="search_item" v-model="search.category" :options="categories" label="name"></v-select>
                </td>
                <td>
                <v-select @input="search_item" v-model="search.course" :options="courses" label="name"></v-select>

                </td>
                <td>
                <v-select @input="search_item" v-model="search.lesson" :options="lessons" label="name"></v-select>

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
                <td><input type="checkbox" v-model="checked" name="checked" :value="item.question_id"></td>
                <td>{{item.question_id}}</td>
                <td><a v-on:click.prevent="update_item(item)">{{item.name}}</a></td>
                <td>{{item.category.name}}</td>
                <td>{{item.course  ? item.course.name : ''}}</td>
                <td>{{item.lesson  ? item.lesson.name : ''}}</td>
                <td>{{item.state == 1 ? "Xuất bản" : "Bản nháp"}}</td>
                <td>
                    <a title="Cập nhật" v-on:click.prevent="update_item(item)"><span class="text-blue"><i class="glyphicon glyphicon-pencil "></i></span></a>
                    <a title="Xóa" v-on:click.prevent="delete_item(item.question_id)"><span class="text-black"><i class="glyphicon glyphicon-trash"></i></span></a>
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
    <!-- Create Item Modal -->
    <div class="modal fade" id="create-item" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button v-on:click="clearinputItem" type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>

                    <h4 class="modal-title" id="myModalLabel">{{inputItem.question_id ? "Cập nhật câu hỏi" : "Tạo câu hỏi"}}</h4>
                </div>
                <div class="modal-body">
                    <!-- Form-->
                    <form method="POST" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <label for="title">Câu hỏi <span class="text-danger">(*)</span>:</label>
                                    <input type="text" name="title" class="form-control" v-model="inputItem.name" />
                                    <span v-if="showError.name" class="error text-danger">{{showError.name[0]}}</span>
                                </div>
                                <div class="form-group">
                                    <label for="points">Điểm <span class="text-danger">(*)</span>:</label>
                                    <input type="text" name="points" class="form-control" v-model="inputItem.points" />
                                    <span v-if="showError.points" class="error text-danger">{{showError.points[0]}}</span>
                                </div>
                                <div class="form-group">
                                    <label for="category_id">Lĩnh vực <span class="text-danger">(*)</span>:</label>
                                    
                                    <select name="category_id" v-model="inputItem.category_id" id="category_id" class="form-control">
                                        <option v-bind:value="category.category_id" v-text="category.name" v-for="category in categories"></option>
                                    </select>
                                    <span v-if="showError.category_id" class="error text-danger">{{showError.category_id[0]}}</span>
                                </div>
                                <div class="form-group">
                                    <label for="question_type">Loại câu hỏi <span class="text-danger">(*)</span>:</label>
                                    <select name="question_type" class="form-control" v-model="inputItem.question_type">
                                        <option value="">--Chọn loại câu hỏi--</option>
                                        <option value="1">Đúng sai</option>
                                        <option value="2">Trắc nghiệm</option>
                                        <option value="3">Trắc nghiệm nhiều phương án</option>
                                        <option value="4">Trả lời câu hỏi tự luận</option>
                                    </select>
                                    <span v-if="showError.question_type" class="error text-danger">{{showError.question_type[0]}}</span>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6">
                                   
                                    
                                    <div class="form-group" v-if="inputItem.question_type == 2 || inputItem.question_type == 3">
                                       <label>Phương án trắc nghiệm</label>
                                        <div v-if="inputItem.answers">
                                        <table class="table table-bordered">
                                            <tr>
                                                <th>Số</th>
                                                <th>Phương án</th>
                                                <th></th>
                                            </tr>
                                            <template v-for="(answer, index) in inputItem.answers" >
                                            <tr>
                                                <td>{{index+1}}</td>   
                                                <td>{{answer}}</td> 
                                                <td><a href="" @click.prevent="removeAnswer(index)">Xóa</a>
                                                </td>
                                            </tr>
                                            </template>
                                        </table>    
                                        </div>
                                        <input style="width: 70%; display:inline-block" type="text" name="answerInput" class="form-control" v-model="answerInput" />
                                        <button @click.prevent="addAnswer" class="btn btn-info">Tạo phương án</button>
                                    </div>
                                    <div class="form-group">
                                        <label for="correct_answer">Đáp án đúng <span class="text-danger">(*)</span></label>
                                            <template v-if="inputItem.question_type == 1">
                                               <select name="" v-model="inputItem.correct_answer" id="" required  class="form-control">
                                                   <option value="">--Chọn--</option>
                                                   <option value="1">Đúng</option>
                                                   <option value="2">Sai</option>
                                               </select>
                                                
                                            </template>
                                            <template v-if="inputItem.question_type == 2 && inputItem.answers">
                                                <select name="" v-model="inputItem.correct_answer" id="" required class="form-control">
                                                    <option value="">--Chọn--</option>
                                                    <option v-for="(option, index) in inputItem.answers" :value="index+1" v-text="option"> </option>
                                                </select>
                                            </template>
                                            <template v-if="inputItem.question_type == 3">
                                                <label ><i>(Chọn theo số thứ tự của các phương án trên. Các đáp án cách nhau bằng dấu phẩy, ví dụ: 1,3)</i></label>
                                                <input type="text" name="correct_answer" class="form-control" v-model="inputItem.correct_answer" />
                                            </template>
                                            

                                            <template v-if="inputItem.question_type == 4"><i>(Lưu ý viết đúng chính tả, không thừa khoảng trắng.)</i>
                                                <input type="text" name="correct_answer" class="form-control" v-model="inputItem.correct_answer" />
                                            </template>
                                            
                                        
                                       
                                        <span v-if="showError.correct_answer" class="error text-danger">{{showError.correct_answer[0]}}</span>
                                    </div>
                                    <div class="form-group">
                                        <label for="state">Trạng thái:</label>
                                        <br>
                                        <input type="radio" name="state" id="one" value="1" v-model="inputItem.state">
                                        <label for="one">Xuất bản</label>
                                        <input type="radio" name="state" id="two" value="-1" v-model="inputItem.state">
                                        <label for="two">Bản nháp</label>

                                    </div>
                                </div>
                            </div>
                        <div class="row">
                            <div class="col-xs-12">
                            <div class="form-group">
                            <label for="description">Thông tin {{module}}:</label>

                            <quill-editor v-model="inputItem.description" ref="quillEditorA" :options="editorOption" @blur="onEditorBlur($event)" @focus="onEditorFocus($event)" @ready="onEditorReady($event)">
                            </quill-editor>

                        </div>



                        <div class="form-group">
                            <a v-on:click.prevent="storeItem" class="btn btn-success">Lưu</a>
                        </div>
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
    Vue.use(VueQuillEditor)
    Vue.component('v-select', VueSelect.VueSelect);
    var app = new Vue({
        el: '#app',
        data: {
            role_id : <?php echo Yii::$app->user->identity->role_id;?>,
            user_id : <?php echo Yii::$app->user->identity->user_id;?>,
            items: [],
            module: "câu hỏi",
            checked: [],
            allchecked: false,
            answerInput:'',
            inputItem: {
                'name': '',
                'description': '',
                'category_id': '',
                'question_id': '',
                'question_type':'',
                'answers':[],
                'correct_answer':'',
                'state':1,
                'points':1,
                'course_id': 0,
                'lesson_id': 0,
                'quiz_id':0,
                
                
            },
            search: {
                name: "",
                category: "",
                course: "",
                lesson: "",
                state: 1,
                id: ""
            },
            sortColumn: "question_id",
            sortType: "ASC",
            categories: [],
            courses:[],
            lessons:[],
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
                placeholder: 'Nhập nội dung tại đây'
            },
            showError:{}
        },

        mounted() {
            this.getVueItems();


        },
        components: {
            LocalQuillEditor: VueQuillEditor.quillEditor
        },
        methods: {
            
            onEditorBlur(quill) {
                console.log('editor blur!', quill)
            },
            onEditorFocus(quill) {
                console.log('editor focus!', quill)
            },
            onEditorReady(quill) {
                console.log('editor ready!', quill)
            },
            addAnswer: function(){
                if (this.answerInput !="")
                this.inputItem.answers.push(this.answerInput);
                this.answerInput = "";
            },
            removeAnswer: function(item){
              
                this.inputItem.answers.splice(item, 1);
            },
            getVueItems: function() {

                axios.post('/api/elearning/question/list?page=' + this.pagination.current_page + '&sortBy=' + this.sortColumn + '&sortType=' + this.sortType+'&user_id=<?php echo Yii::$app->user->identity->user_id;?>&role_id=<?php echo Yii::$app->user->identity->role_id;?>', this.search)
                    .then(response => {
                        this.pagination = response.data.pagination;
                        this.items = response.data.data;
                        this.categories = response.data.categories;
                        this.courses = response.data.courses;
                        this.lessons = response.data.lessons;
                    });
            },
            storeItem: function() {
                this.inputItem.user_id = <?php echo Yii::$app->user->identity->user_id;?>;
                this.inputItem.role_id = <?php echo Yii::$app->user->identity->role_id;?>;
                axios.post('/api/elearning/question/store', this.inputItem).then((response) => {
                    if (response.data.error) {
                        //console.log(response.data.error);
                        this.showError = response.data.error;
                    }else {
                        if (this.inputItem.lesson_id) {
                            toastr.success('Câu hỏi đã được cập nhật.', 'Thông báo', {
                                timeOut: 5000
                            });
                        } else {
                            toastr.success('Câu hỏi đã được tạo.', 'Thông báo', {
                                timeOut: 5000
                            });
                        }

                        this.truncateItem();
                        this.getVueItems();
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
                    axios.post('/api/elearning/question/delete?id=' + itemId)
                        .then(response => {
                            this.getVueItems();
                            toastr.warning('Câu hỏi đã được xóa.', 'Thông báo', {
                                timeOut: 5000
                            });
                        });
                }

            },
            bulk_delete: function() {
                console.log(this.checked);
                if (confirm("Bạn chắc chắn muốn xóa những mục này?")) {
                    axios.post('/api/elearning/question/bulkdelete', this.checked)
                        .then(response => {
                            this.checked = [];
                            this.getVueItems();
                            console.log(response);
                            toastr.warning('Câu hỏi đã được xóa.', 'Thông báo', {
                                timeOut: 5000
                            });
                        });
                }
            },
            clearinputItem: function() {
                if (this.inputItem.question_id) {
                    this.truncateItem();
                }
                this.showError = {};
            },
            truncateItem: function() {
                this.inputItem = {
                    'name': '',
                    'description': '',
                    'category_id': '',
                    'question_id': '',
                    'question_type':'',
                    'answers':[],
                    'correct_answer':'',
                    'state':1,
                    'course_id': 0,
                    'lesson_id': 0,
                    'quiz_id':0,
                    'points':1
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

         
       
            selectAll: function() {


                this.checked = [];
                if (!this.allchecked) {
                    for (item in this.items) {
                        this.checked.push(this.items[item].question_id);
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