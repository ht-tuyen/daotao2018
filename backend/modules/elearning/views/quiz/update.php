<?php 
    $this->title = "Quản lý đề thi";
    $this->params['breadcrumbs'][] = $this->title;
?>

<div id="app" class="panel panel-info">
    <div style="padding: 20px;">
   <h2>Đề thi cho {{inputItem.quiz_type == 1 ? 'bài học ' : 'khoá học'}} <?php echo $name?></h2>
   <div class="alert alert-warning" v-if="inputItem.quiz_result.length > 0">
     Đã có học viên làm bài, thay đổi thông tin có thể làm sai lệch kết quả
   </div>
   <h3 @click="toggleDetail" style="border: 1px solid #ccc; padding: 10px;">Thông tin đề thi</h3>
    <form v-if="show_detail == 1" method="POST" enctype="multipart/form-data">
        <div class="row">
            <div class="col-xs-12 col-sm-6">
                <div class="form-group">
                    <label for="title">Tên {{module}}:</label>
                    <input type="text" name="title" class="form-control" v-model="inputItem.name" />
                    <span v-if="showError.name" class="error text-danger">{{showError.name[0]}}</span>
                </div>
                <div class="form-group">
                    <label for="number_questions">Số lượng câu hỏi:</label>
                    <input type="number" name="number_questions" class="form-control" v-model="inputItem.number_questions" />
                    <span v-if="showError.number_questions" class="error text-danger">{{showError.number_questions[0]}}</span>
                </div>
                <div class="form-group">
                    <label for="point">Tổng điểm <span class="text-danger">(*)</span>:</label>
                    <input type="text" name="total_points" class="form-control" v-model="inputItem.total_points" />
                    <span v-if="showError.total_points" class="error text-danger">{{showError.total_points[0]}}</span>
                </div>
                <div class="form-group">
                    <label for="point">Điểm đạt <span class="text-danger">(*)</span>:</label>
                    <input type="text" name="minimum_points" class="form-control" v-model="inputItem.minimum_points" />
                    <span v-if="showError.minimum_points" class="error text-danger">{{showError.minimum_points[0]}}</span>
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
            <a v-on:click.prevent="storeItem" class="btn btn-success">Lưu đề thi</a>
        </div>
            </div>
        </div>
        
    </form>
    <h3  style="border: 1px solid #ccc; padding: 10px;">Quản lý câu hỏi</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>STT</th>
                    <th>Tên câu hỏi</th>
                    <th>Lĩnh vực</th>
                    <th>
                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#create-item">
                        <i class="glyphicon glyphicon-plus"></i> Tạo câu hỏi
                    </button>
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(question, index) in questions">
                    <td>{{index + 1}}</td>
                    <td>{{question.name}}</td>
                    <td>{{question.category.name}}</td>
                    <td>
                    <a v-on:click.prevent="update_item(question)"><span class="text-blue"><i class="glyphicon glyphicon-pencil "></i></span></a>
                    <a v-on:click.prevent="delete_item(question.question_id)"><span class="text-black"><i class="glyphicon glyphicon-trash"></i></span></a>
                    </td>
                    
                </tr>
            </tbody>
        </table>
        <h4>Thêm câu hỏi từ ngân hàng</h4>
        <div class="form-group">
            <select name="" @change="getBank" v-model="category_id" id="" class="form-control">
                <option value="">--Chọn lĩnh vực--</option>
                <option :value="category.value" v-for="(category, index) in categories" v-text="category.name"></option>
            </select>
        </div>
        <div class="form-group">
        <v-select v-if="category_id > 0" v-model="question_id" :options="bank">
            <span slot="no-options">Không tìm thấy câu hỏi trong lĩnh vực này!</div>

        </v-select>

        </div>
        <div class="form-group">    
            <button @click="addQuestion" class="btn btn-success">Thêm</button>
        </div>
    
     <!-- Create Item Modal -->
     <div class="modal fade" id="create-item" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button v-on:click="clearinputItem" type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>

                    <h4 class="modal-title" id="myModalLabel">{{inputQuestion.question_id ? "Cập nhật câu hỏi" : "Tạo câu hỏi"}}</h4>
                </div>
                <div class="modal-body">
                    <!-- Form-->
                    <form method="POST" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <label for="title">Câu hỏi <span class="text-danger">(*)</span>:</label>
                                    <input type="text" name="title" class="form-control" v-model="inputQuestion.name" />
                                    <span v-if="showError.name" class="error text-danger">{{showError.name[0]}}</span>
                                </div>
                                <div class="form-group">
                                    <label for="total_points">Điểm <span class="text-danger">(*)</span>:</label>
                                    <input type="text" name="points" class="form-control" v-model="inputQuestion.points" />
                                    <span v-if="showError.points" class="error text-danger">{{showError.points[0]}}</span>
                                </div>
                                
                                
                                <div class="form-group">
                                    <label for="question_type">Loại câu hỏi <span class="text-danger">(*)</span>:</label>
                                    <select name="question_type" class="form-control" v-model="inputQuestion.question_type">
                                        <option value="">--Chọn loại câu hỏi--</option>
                                        <option value="1">Đúng sai</option>
                                        <option value="2">Trắc nghiệm</option>
                                        <option value="3">Trắc nghiệm nhiều phương án</option>
                                        <?php if ($type == 2) {?>
                                        <option value="4">Trả lời câu hỏi tự luận</option>
                                        <?php }?>
                                    </select>
                                    <span v-if="showError.question_type" class="error text-danger">{{showError.question_type[0]}}</span>
                                </div>
                                <div class="form-group">
                                        <label for="state">Trạng thái:</label>
                                        <br>
                                        <input type="radio" name="state" id="one" value="1" v-model="inputQuestion.state">
                                        <label for="one">Xuất bản</label>
                                        <input type="radio" name="state" id="two" value="-1" v-model="inputQuestion.state">
                                        <label for="two">Bản nháp</label>

                                    </div>
                            </div>
                            <div class="col-xs-12 col-sm-6">
                                   
                                    
                                    <div class="form-group" v-if="inputQuestion.question_type == 2 || inputQuestion.question_type == 3">
                                       <label>Phương án trắc nghiệm</label>
                                        <div v-if="inputQuestion.answers">
                                        <table class="table table-bordered">
                                            <tr>
                                                <th>Số</th>
                                                <th>Phương án</th>
                                                <th></th>
                                            </tr>
                                            <template v-for="(answer, index) in inputQuestion.answers" >
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
                                            <template v-if="inputQuestion.question_type == 1">
                                               <select name="" v-model="inputQuestion.correct_answer" id="" required  class="form-control">
                                                   <option value="">--Chọn--</option>
                                                   <option value="1">Đúng</option>
                                                   <option value="2">Sai</option>
                                               </select>
                                                
                                            </template>
                                            <template v-if="inputQuestion.question_type == 2 && inputQuestion.answers.length > 0">
                                                <select name="" v-model="inputQuestion.correct_answer" id="" required class="form-control">
                                                    <option value="">--Chọn--</option>
                                                    <option v-for="(option, index) in inputQuestion.answers" :value="index+1" v-text="option"> </option>
                                                </select>
                                            </template>
                                            <template v-if="inputQuestion.question_type == 3">
                                                <label ><i>(Chọn theo số thứ tự của các phương án trên. Các đáp án cách nhau bằng dấu phẩy, ví dụ: 1,3)</i></label>
                                                <input type="text" name="correct_answer" class="form-control" v-model="inputQuestion.correct_answer" />
                                            </template>

                                            <template v-if="inputQuestion.question_type == 4"><i>(Lưu ý viết đúng chính tả, không thừa khoảng trắng.)</i>
                                                <input type="text" name="correct_answer" class="form-control" v-model="inputQuestion.correct_answer" />
                                            </template>
                                            
                                        
                                       
                                        <span v-if="showError.correct_answer" class="error text-danger">{{showError.correct_answer[0]}}</span>
                                    </div>
                                    
                                </div>
                            </div>
                        <div class="row">
                            <div class="col-xs-12">
                            <div class="form-group">
                            <label for="description">Thông tin câu hỏi:</label>

                            <quill-editor v-model="inputQuestion.description" ref="quillEditorA" :options="editorOption" @blur="onEditorBlur($event)" @focus="onEditorFocus($event)" @ready="onEditorReady($event)">
                            </quill-editor>

                        </div>
                        <div class="form-group">
                            <a v-on:click.prevent="storeQuestion" class="btn btn-success">Lưu</a>
                        </div>
                            </div>
                        </div>
                        
                    </form>
                </div>
            </div>
        </div>
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
                'total_points': 1,
                'short_desc': '',
                'quiz_id': '',
                'quiz_type': <?php echo $type?>,
                'item_id': <?php echo $id?>,
                'published_start':'',
                'published_end':'',
                'time':'',
                'state': 1,
                'minimum_points':1,
                'ordering': '',
                
            },
            category_id : <?php echo $category_id?>,
            question_id : {
                label: "--Chọn câu hỏi--",
                value: "0"
            },
            bank: [],
            categories: [],
            inputQuestion: {
                'name': '',
                'description': '',
                'category_id': <?php echo $category_id?>,
                'question_id': '',
                'course_id': <?php echo ($type == 2) ? $id : 0;?>,
                'lesson_id': <?php echo ($type == 1) ? $id : 0;?>,
                'question_type':'',
                'answers':[],
                'correct_answer':'',
                'state':1,
                'points':1,
                'quiz_id' : <?php echo $quiz_id?>
            },
            answerInput:'',
            quizzes: [],
            show_detail: 0,
            questions: [],
            search: {
                name: "",
                course: "",
                state: 1,
                id: ""
            },
            
            editorOption: {
                theme: 'snow',
                placeholder: 'Nhập nội dung tại đây'
            },
            showError:{},
            selected_question: []
        },

        mounted() {
            this.loadItem();
            this.loadCategories();
            this.getBank();
        },
        components: {
            LocalQuillEditor: VueQuillEditor.quillEditor
        },
        methods: {
            addAnswer: function(){
                if (this.answerInput !="")
                this.inputQuestion.answers.push(this.answerInput);
                this.answerInput = "";
            },
            removeAnswer: function(item){             
                this.inputQuestion.answers.splice(item, 1);
            },
            toggleDetail(){
                if (this.show_detail == 0)
                    this.show_detail = 1;
                else
                    this.show_detail = 0;

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
            
            delete_item(id){
                if (confirm('Bạn muốn xoá câu hỏi này khỏi đề thi?')){

                    axios.post('/api/elearning/quiz/removequestion?id=<?php echo $quiz_id?>&question_id='+id)
                    .then(response => {
                    
                        toastr.warning('Câu hỏi đã được xoá.', 'Thông báo', {
                                    timeOut: 5000
                                });
                        
                        this.loadItem();
                    });
                }
            },
            storeQuestion:function(){
                console.log('store question');
                this.inputQuestion.user_id = <?php echo Yii::$app->user->identity->user_id;?>;
                this.inputQuestion.role_id = <?php echo Yii::$app->user->identity->role_id;?>;
                axios.post('/api/elearning/question/store', this.inputQuestion).then((response) => {
                    console.log(this.inputQuestion);
                    console.log(response);
                    if (response.data.error) {
                        //console.log(response.data.error);
                        this.showError = response.data.error;
                    }else {
                        if (this.inputQuestion.lesson_id) {
                            toastr.success('Câu hỏi đã được cập nhật.', 'Thông báo', {
                                timeOut: 5000
                            });
                        } else {
                            toastr.success('Câu hỏi đã được tạo.', 'Thông báo', {
                                timeOut: 5000
                            });
                        }
                        this.clearinputItem();
                        this.loadItem();
                        $("#create-item").modal('hide');
                    }

                }).catch(e => {
                    console.log(e);
                });;
            },
            addQuestion(){
                axios.post('/api/elearning/quiz/addquestion?id=<?php echo $quiz_id?>&question_id='+this.question_id.value)
                .then(response => {
                   
                    toastr.success('Câu hỏi đã được thêm.', 'Thông báo', {
                                timeOut: 5000
                            });
                    
                    this.loadItem();
                    this.selected_question.push(this.question_id.value);
                    this.question_id = {
                        label: "--Chọn câu hỏi--",
                        value: "0"
                    };
                    this.getBank();
                });
            },
            update_item: function(item) {
                this.inputQuestion = item;
                $("#create-item").modal('show');
            },
            loadItem(){
                axios.post('/api/elearning/quiz/detail?id=<?php echo $quiz_id?>')
                .then(response => {
                    //console.log(response)
                    this.inputItem = response.data;  
                    this.questions = response.data.questions;  
                });
            },
            loadCategories(){
                axios.get('/api/elearning/category/list')
                .then(response => {
                    this.categories = response.data;  
                    
                });
            },
            existedQuestion(){
                for (var i = 0; i < this.questions.length; i++) {
                    this.selected_question[i] = this.questions[i].question_id;
                }
                this.selected_question.unshift(0);
                
            },
            getBank(){
                //console.log(this.questions);
                this.existedQuestion();
                //console.log(this.selected_question.toString());
                axios.get('/api/elearning/question/bank?category_id='+this.category_id+'&existed='+this.selected_question.toString())
                .then(response => {
                    
                    this.bank= [];
                    for (var i = 0; i < response.data.length; i++) {
                        this.bank[i] = {
                            label: response.data[i].name,
                            value: response.data[i].question_id
                        }
                    }
                    
                    this.bank.unshift({
                        label: "--Chọn câu hỏi--",
                        value: "0"
                    });  
                    
                });
                
            },
            storeItem: function() {
                axios.post('/api/elearning/quiz/store', this.inputItem).then((response) => {
                    console.log(response);
                    if (response.data.error) {
                        //console.log(response.data.error);
                        this.showError = response.data.error;
                    }else {
                        toastr.success('Đề thi đã được cập nhật.', 'Thông báo', {
                            timeOut: 5000
                        });
                    }

                }).catch(e => {
                    console.log(e);
                });;
            },
            clearinputItem(){
                this.inputQuestion = {
                'name': '',
                'description': '',
                'category_id': <?php echo $category_id?>,
                'question_id': '',
                'question_type':'',
                'course_id': <?php echo ($type == 2) ? $id : 0;?>,
                'lesson_id': <?php echo ($type == 1) ? $id : 0;?>,
                'answers':[],
                'correct_answer':'',
                'state':1,
                'points':1,
                'quiz_id' : <?php echo $quiz_id?>
            };
                this.showError = {};
            }

        },
        computed: {
            
            editorA() {
                return this.$refs.quillEditorA.quill
            },
            
           
        }
    })
</script>