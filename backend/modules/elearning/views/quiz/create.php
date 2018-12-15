<?php 
    $this->title = "Quản lý đề thi";
    $this->params['breadcrumbs'][] = $this->title;
?>

<div id="app" class="panel panel-info ">
    <div class="" style="padding:20px;">
   <h2>Tạo  {{inputItem.quiz_type == 1 ? 'bài kiểm tra ' : 'đề thi'}} cho {{inputItem.quiz_type == 1 ? 'bài học ' : 'khoá học'}} {{inputItem.item_name}}</h2>
    <form method="POST" enctype="multipart/form-data">
        <div class="row">
            <div class="col-xs-12 col-sm-6">
                <div class="form-group">
                    <label for="title">Tên {{module}}:</label>
                    <input type="text" name="title" class="form-control" v-model="inputItem.name" />
                    <span v-if="showError.name" class="error text-danger">{{showError.name[0]}}</span>
                </div>
                <div class="form-group">
                    <label for="title">Tổng điểm:</label>
                    <input type="number" name="total_points" class="form-control" v-model="inputItem.total_points" />
                    <span v-if="showError.name" class="error text-danger">{{showError.total_points[0]}}</span>
                </div>
                <div class="form-group">
                    <label for="title">Điểm đạt:</label>
                    <input type="number" name="minimum_points" class="form-control" v-model="inputItem.minimum_points" />
                    <span v-if="showError.name" class="error text-danger">{{showError.minimum_points[0]}}</span>
                </div>
                <div class="form-group">
                    <label for="number_questions">Số lượng câu hỏi:</label>
                    <input type="number" name="number_questions" class="form-control" v-model="inputItem.number_questions" />
                    <span v-if="showError.number_questions" class="error text-danger">{{showError.number_questions[0]}}</span>
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
                    
                    
                </div>

            </div>

        <div class="row">
            <div class="col-xs-12">
            <div class="form-group">
            <label for="short_desc">Thông tin:</label>

            <quill-editor v-model="inputItem.short_desc" ref="quillEditorA" :options="editorOption" @blur="onEditorBlur($event)" @focus="onEditorFocus($event)" @ready="onEditorReady($event)">
            </quill-editor>

        </div>
        <div class="form-group">
            <a v-on:click.prevent="storeItem" class="btn btn-success">Lưu và tạo câu hỏi</a>
        </div>
            </div>
        </div>
        
    </form>
   </div>
</div>

<script>
    Vue.use(VueQuillEditor)
    Vue.component('v-select', VueSelect.VueSelect);
    var app = new Vue({
        el: '#app',
        data: {
          
            module: "đề thi",
            inputItem: {
                'name': '',
                'number_questions': '',
                'short_desc': '',
                'quiz_id': '',
                'total_point':0,
                'quiz_type': <?php echo $type?>,
                'item_id': <?php echo $id?>,
                'item_name': '<?php echo $name?>',
                'category_id': <?php echo $category_id?>,
                'published_start':'',
                'published_end':'',
                'time':'',
                'state': 1,
                'minimum_points':1,
                'course_id': <?php echo $type == 2 ? $id : 0?>,
                'lesson_id': <?php echo $type == 1 ? $id : 0?>,
                'ordering': '',
                
            },
            search: {
                name: "",
                course: "",
                state: 1,
                id: ""
            },
            sortColumn: "quiz_id",
            sortType: "DESC",
            editorOption: {
                theme: 'snow',
                placeholder: 'Nhập nội dung tại đây'
            },
            showError:{}
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
            
            storeItem: function() {
                axios.post('/api/elearning/quiz/store', this.inputItem).then((response) => {
                    console.log(response);
                    if (response.data.error) {
                        //console.log(response.data.error);
                        this.showError = response.data.error;
                    }else {
                        toastr.success('Đề thi đã được tạo.', 'Thông báo', {
                            timeOut: 5000
                        });
                        window.location.href = '/acp/elearning/quiz/update?type=<?php echo $type?>&id='+this.inputItem.item_id+'&name='+this.inputItem.item_name+'&quiz_id='+response.data.quiz_id+'&category_id=<?php echo $category_id?>';
                       
                    }

                }).catch(e => {
                    console.log(e);
                });;
            },

        },
        computed: {
            
            editorA() {
                return this.$refs.quillEditorA.quill
            },
           
        }
    })
</script>