<?php
    $this->title = "Quản lý đề thi";
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
                    
                    <a class="btn btn-default" href="/acp/elearning/quiz" title="Reset Grid" data-pjax="0"><i class="glyphicon glyphicon-repeat"></i> Tải lại &amp; Xóa lọc trang</a>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
    </div>



    <table class="admintablelist table table-bordered table-hover">
        <thead>
            <tr>
                <th><input type="checkbox" v-on:click="selectAll" v-model="allchecked"></th>
                <th><a href="" v-on:click.prevent="sortBy('quiz_id')">Id</a></th>
                <th><a href="" v-on:click.prevent="sortBy('quiz_type')">Loại</a></th>
                <th><a href="" v-on:click.prevent="sortBy('name')">Tên</a></th>
                <th><a href="" v-on:click.prevent="sortBy('category')">Lĩnh vực</a></th>
                <th><a href="" v-on:click.prevent="sortBy('course')">Khoá học</a></th>
                <th><a href="" v-on:click.prevent="sortBy('lesson')">Bài học</a></th>

                <th>Số câu hỏi/điểm</th>
                <th>Số câu hỏi đã tạo/điểm</th>
                <th><a href="" v-on:click.prevent="sortBy('state')">Trạng thái</a></th>
                <th></th>
            </tr>
            <tr>
                <td></td>
                <td><input type="text" style="width:60px;" class="form-control" v-on:keyup="search_item" name="search.id" v-model="search.id" /></td>
                <td>                
                    <v-select @input="search_item" v-model="search.type" :options="options" label="name"></v-select>
                </td>
                <td><input type="text" placeholder="Tìm kiếm đề thi" class="form-control" v-on:keyup="search_item" name="search.name" v-model="search.name" /></td>
                <td>                
                    <v-select @input="search_item" v-model="search.category" :options="categories" label="name"></v-select>
                </td>
                <td>                
                    <v-select @input="search_item" v-model="search.course" :options="courses" label="name"></v-select>
                </td>
                <td>
                    <v-select @input="search_item" v-model="search.lesson" :options="lessons" label="name"></v-select>
                </td>
                <td></td>
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

            <tr v-for="item in items" :class="item.available_questions < item.number_questions || item.total_points < item.available_points ? 'danger' : ''">
                <td><input type="checkbox" v-model="checked" name="checked" :value="item.quiz_id"></td>
                <td>{{item.quiz_id}}</td>
                <td>{{item.quiz_type == 1 ? 'Bài kiểm tra' : 'Đề thi'}}</td>
                <td><a v-on:click.prevent="update_item(item)">{{item.name}}</a></td>
                <td>{{item.category}}</td>
                <td>{{item.course}}</td>
                <td>{{item.lesson}}</td>

                <td>{{item.number_questions}}/{{item.total_points}}</td>
                <td>{{item.available_questions}}/{{item.available_points}}</td>
                <td>{{item.state == 1 ? "Xuất bản" : "Bản nháp"}}</td>
                <td>
                    <a title="Cập nhật" v-on:click.prevent="update_item(item)"><span class="text-blue"><i class="glyphicon glyphicon-pencil "></i></span></a>
                    <a title="Xóa" v-on:click.prevent="delete_item(item.quiz_id)"><span class="text-black"><i class="glyphicon glyphicon-trash"></i></span></a>
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

                    <h4 class="modal-title" id="myModalLabel">{{inputItem.quiz_id ? "Cập nhật đề thi" : "Tạo đề thi"}}</h4>
                </div>
                <div class="modal-body">
                    <!-- Form-->
                    <form method="POST" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <label for="title">Tên {{module}}:</label>
                                    <input type="text" name="title" class="form-control" v-model="inputItem.name" />
                                    <span v-if="showError.name" class="error text-danger">{{showError.name[0]}}</span>
                                </div>
                                <div class="form-group">
                                    <label for="parent_id">Khóa học :</label>
                                    <select name="course_id" v-model="inputItem.course_id" id="course_id" class="form-control">
                                        <option v-bind:value="cousre.course_id" v-text="cousre.name" v-for="cousre  in courses"></option>
                                    </select>
                                    <span v-if="showError.course_id" class="error text-danger">{{showError.course_id[0]}}</span>
                                </div>
                                <div class="form-group">
                                        <label for="number_questions">Số lượng câu hỏi:</label>
                                        <input type="number" name="number_questions" class="form-control" v-model="inputItem.number_questions" />
                                        <span v-if="showError.number_questions" class="error text-danger">{{showError.number_questions[0]}}</span>
                                    </div>



                            </div>
                            <div class="col-xs-12 col-sm-6">

                                    <div class="form-group">
                                        <label for="published_start">Thời gian bắt đầu:</label>
                                        <input type="date" name="published_start" class="form-control" v-model="inputItem.published_start" />
                                        <span v-if="showError.published_start" class="error text-danger">{{showError.published_start[0]}}</span>
                                    </div>
                                    <div class="form-group">
                                        <label for="published_end">Thời gian kết thúc:</label>
                                        <input type="date" name="published_end" class="form-control" v-model="inputItem.published_end" />
                                        <span v-if="showError.published_end" class="error text-danger">{{showError.published_end[0]}}</span>
                                    </div>
                                    <div class="form-group">
                                        <label for="time">Thời gian làm bài (phút):</label>
                                        <input type="number" name="time" class="form-control" v-model="inputItem.time" />
                                        <span v-if="showError.time" class="error text-danger">{{showError.time[0]}}</span>

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
                            <label for="short_desc">Thông tin {{module}}:</label>

                            <quill-editor v-model="inputItem.short_desc" ref="quillEditorA" :options="editorOption" @blur="onEditorBlur($event)" @focus="onEditorFocus($event)" @ready="onEditorReady($event)">
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
            module: "đề thi",
            checked: [],
            allchecked: false,
            inputItem: {
                'name': '',
                'number_questions': '',
                'short_desc': '',
                'quiz_id': '',
                'course_id': '',
                'published_start':'',
                'published_end':'',
                'time':'',
                'state': 1,
                'ordering': '',
                'quiz_type':0,
                'item_id':0,
                'category_id':0

            },
            search: {
                name: "",
                course: "",
                state: 1,
                id: ""
            },
            sortColumn: "quiz_id",
            sortType: "DESC",
            courses: [],
            lessons: [],
            categories: [],
            options:[
                { value: "1", name: "Bài kiểm tra" },
                { value: "2", name: "Đề thi" },
               
            ],
            courses: [],
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
            getVueItems: function() {

                axios.post('/api/elearning/quiz/list?page=' + this.pagination.current_page + '&sortBy=' + this.sortColumn + '&sortType=' + this.sortType+'&user_id=<?php echo Yii::$app->user->identity->user_id;?>&role_id=<?php echo Yii::$app->user->identity->role_id;?>', this.search)
                    .then(response => {
                        this.pagination = response.data.pagination;
                        this.items = response.data.data;
                        this.courses = response.data.courses;
                        this.lessons = response.data.lessons;
                        this.categories = response.data.categories;
                    });
            },
            storeItem: function() {
                this.inputItem.user_id = <?php echo Yii::$app->user->identity->user_id;?>;
                this.inputItem.role_id = <?php echo Yii::$app->user->identity->role_id;?>;
                axios.post('/api/elearning/quiz/store', this.inputItem).then((response) => {
                    if (response.data.error) {
                        //console.log(response.data.error);
                        this.showError = response.data.error;
                    }else {
                        if (this.inputItem.lesson_id) {
                            toastr.success('Đề thi đã được cập nhật.', 'Thông báo', {
                                timeOut: 5000
                            });
                        } else {
                            toastr.success('Đề thi đã được tạo.', 'Thông báo', {
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
                window.location.href = '/acp/elearning/quiz/update?type='+item.quiz_type+'&id='+item.item_id+'&name=&quiz_id='+item.quiz_id+'&category_id='+item.category_id;

                
                // console.log(item);
                // this.inputItem = item;
                // $("#create-item").modal('show');
            },
            delete_item: function(itemId) {
                if (confirm("Bạn chắc chắn muốn xóa mục này?")) {
                    axios.post('/api/elearning/quiz/delete?id=' + itemId)
                        .then(response => {
                            this.getVueItems();
                            toastr.warning('Đề thi đã được xóa.', 'Thông báo', {
                                timeOut: 5000
                            });
                        });
                }

            },
            bulk_delete: function() {
                console.log(this.checked);
                if (confirm("Bạn chắc chắn muốn xóa những mục này?")) {
                    axios.post('/api/elearning/quiz/bulkdelete', this.checked)
                        .then(response => {
                            this.checked = [];
                            this.getVueItems();
                            console.log(response);
                            toastr.warning('Đề thi đã được xóa.', 'Thông báo', {
                                timeOut: 5000
                            });
                        });
                }
            },
            clearinputItem: function() {
                if (this.inputItem.quiz_id) {
                    this.truncateItem();
                }
                this.showError = {};
            },
            truncateItem: function() {
                this.inputItem = {
                    'name': '',
                    'number_questions': '',
                    'short_desc': '',
                    'quiz_id': '',
                    'course_id': '',
                    'published_start':'',
                    'published_end':'',
                    'time':'',
                    'state': 1,
                    'ordering': ''
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
                        this.checked.push(this.items[item].quiz_id);
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