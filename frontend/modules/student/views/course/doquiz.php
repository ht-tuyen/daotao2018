<?php 
use yii\helpers\Url;
use common\components\CourseSideBar;
use common\components\CourseBottom;

$this->title = $item->name;
$this->params['breadcrumbs'][] = $item->lesson->course->name;

$this->params['breadcrumbs'][] = $item->lesson->name;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-xs-12 col-sm-4">
        <div class="content-course-left">
            <!-- Nav tabs -->
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active">
                    <a href="#home" aria-controls="home" role="tab" data-toggle="tab">
                    <i class="fa fa-bars" aria-hidden="true"></i>
                    </a>
                </li>
                <li role="presentation">
                    <a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">
                    <i class="fa fa-comment-o" aria-hidden="true"></i>
                    </a>
                </li>
                <li class="last_tab" role="presentation"><a href="#messages" aria-controls="messages" role="tab" data-toggle="tab">
                    <i class="fa fa-plus" aria-hidden="true"></i>
                    </a>
                </li>
            </ul>

            <!-- Tab panes -->
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="home">
                <?= CourseSideBar::widget(['course_id' => $item->lesson->course_id,'active_type'=>2,'active_id'=>$item->quiz_id]) ?>

                </div>
                <div role="tabpanel" class="tab-pane" id="profile">
                    
                </div>
                <div role="tabpanel" class="tab-pane" id="messages">
                    
                </div>
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-sm-8">
        <div class="content-course-right">
            <div id="appquiz">
                <h3><?php echo $item->name?></h3>
                <div class="qustion-list">
                    <p><b>Danh sách câu hỏi</b></p>
                    <template v-for="(question, index) in questions">
                        <span v-bind:class="{ 'qustion-slide': question.question_id, 'fill': question.filled }">
                            <span class="question-box" v-text="index+1"></span>
                        </span>
                    </template>
                </div>
        
                <div class="result alert alert-success" v-if="showresult==1">
                    Bạn đã làm đúng {{correct}} trên {{total}} câu hỏi.</a>
                    Số điểm: {{correct_point}} / {{total_points}}
                </div>
                <div class="qustion-main" v-if="showresult==0">
                    
                    
                        <div class="qustion-box">
                    
                        <template v-for="(question, index) in questions">
                            <div class="row">
                                <div class="col-xs-12 col-sm-2">
                                    <div class="box-number">
                                        <p>Câu hỏi {{index+1}}</p>
                                        
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-10">
                                    <div class="box-text">
                                        <div class="qustion"><b>{{question.name}}</b></div>
                                        <div class="question-desc" v-html="question.description"></div>
                                        <div class="ans">
                                            <template v-if="question.question_type == 1">
                                                <div class="ans-slide">
                                                    <label v-on:click="filled(index)" class="" v-bind:for="'question-'+question.question_id+'-1'">
                                                    <input @input="addEvent" @change="addEvent" v-bind:name="'question-'+question.question_id" newattr="3" v-bind:id="'question-'+question.question_id+'-1'" value="1" type="radio"  v-bind:question_id="question.question_id">Đúng</label>
                                                </div>
                                                <div class="ans-slide">
                                                    <label v-on:click="filled(index)" class="" v-bind:for="'question-'+question.question_id+'-2'">
                                                    <input  @input="addEvent" @change="addEvent" v-bind:name="'question-'+question.question_id"  v-bind:question_id="question.question_id" v-bind:id="'question-'+question.question_id+'-2'" value="2" type="radio">Sai</label>
                                                </div>
                                            </template>
                                            <template v-else-if="question.question_type == 2">
                                                <div v-for="(answer, index2) in question.answers" class="ans-slide">
                                                    <label v-on:click="filled(index)" class="" v-bind:for="'question-'+question.question_id+'-'+index2">
                                                    <input @input="addEvent" @change="addEvent"  v-bind:name="'question-'+question.question_id" v-bind:id="'question-'+question.question_id+'-'+index2" v-bind:value="index2 + 1" type="radio"  v-bind:question_id="question.question_id">
                                                    {{answer}}
                                                    </label>
                                                </div>
                                            </template>
                                            <template v-else-if="question.question_type == 3">
                                                <div v-for="(answer, index2) in question.answers" class="ans-slide">
                                                    <label v-on:click="filled(index)" class="" v-bind:for="'question-'+question.question_id+'-'+index2">
                                                    <input @input="addMultiEvent" @change="addMultiEvent"  v-bind:name="'question-'+question.question_id" v-bind:id="'question-'+question.question_id+'-'+index2" v-bind:value="index2 + 1" type="checkbox"  v-bind:question_id="question.question_id">
                                                    {{answer}}
                                                    </label>
                                                </div>
                                            </template>
                                            <template v-else>
                                                <input @input="addEvent" @change="addEvent" v-on:change="filled(index)" v-bind:name="'question-'+question.question_id" v-bind:question_id="question.question_id"  class="form-control" value="" type="text">
                                            </template>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                        </template>   
                    </div> 
                    <div class="start-btn">
                        <a href="#" id="submit_quiz" @click.prevent="showResult" class="btn">Nộp bài</a>
                    </div>
                </div>
            </div>
        </div> 
    </div>
</div>



<div class="fixed-bottom">
    
    <div class="course-bottom container">
        <div class="row">
            <div class="col-xs-12 col-sm-4">
                <?php echo CourseBottom::widget(['course_id' => $item->lesson->course_id]) ?>
                
            </div>
            <div class="col-xs-12 col-sm-8 text-center">
                <div class="lesson-nav pre">
                    <div id="pre_url"></div>
                </div>
                <span class="time-info">Thời gian còn lại: <span id="demo"></span></span>
                <div class="lesson-nav next">
                    <div id="next_url"></div>
                </div>
               
            </div>
        </div>
    </div>
</div>
<script>
// Set the date we're counting down to
var d1 = new Date (),
countDownDate  = new Date ( d1 );
countDownDate.setMinutes ( d1.getMinutes() + <?php echo $item->time?> );

//var countDownDate = new Date(date.getTime() + minutes*60000);

// Update the count down every 1 second
var x = setInterval(function() {

    // Get todays date and time
    var now = new Date().getTime();
    
    // Find the distance between now an the count down date
    var distance = countDownDate - now;
    
    // Time calculations for days, hours, minutes and seconds
    
    var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
    var seconds = Math.floor((distance % (1000 * 60)) / 1000);
    
    // Output the result in an element with id="demo"
    document.getElementById("demo").innerHTML =  minutes + "m " + seconds + "s ";
    
    // If the count down is over, write some text 
    if (distance < 0) {
        clearInterval(x);
        document.getElementById("demo").innerHTML = "Hết giờ làm bài";
        document.getElementById('submit_quiz').click();
    }
}, 1000);


</script>
<script>
var appquiz = new Vue({
        el: '#appquiz',

    data: function () {
        return {
            item:{ 
            }, 
            questions:[],
            quiz_result_id : 0,
            showresult: 0,
            correct: 0,
            total: <?php echo $item->number_questions?>,
            total_points: <?php echo $item->total_points?>,
            correct_point: 0,
        }
    },
    mounted() {
        this.getVueItem();
    },
    methods:{
        getVueItem: function() {
                axios.get('/api/elearning/quiz/view?id=' + <?php echo $item->quiz_id?>)
                .then(response => {
                    this.item = response.data.item;
                    this.quiz_result_id = response.data.quiz_result_id;
                    this.questions = response.data.questions;
                    console.log(response);     
                });
        },
        showResult: function() {
                axios.get('/api/elearning/quiz/showresult?id=' + this.quiz_result_id+'&quiz_id=<?php echo $item->quiz_id?>&quiz_type=1&item=<?php echo $item->lesson_id?>&quiz_name=<?php echo $item->name?>&itemname=<?php echo $item->lesson->name?>')
                .then(response => {
                   this.showresult = 1;
                   this.correct = response.data.correct;
                   this.correct_point = response.data.total_points;
                    console.log(response);     
					window.location.replace("<?php echo Url::to(['/student/course/completelesson', 'id' => $item->lesson_id])?>");

                });
        },
        filled: function (index){
            this.questions[index].filled = 1;
           
        },
        addMultiEvent({ type, target }) {
            var favorite = [];
            var question_id = $(target).attr('question_id');
            $.each($("input[name='question-"+question_id+"']:checked"), function(){            
                favorite.push($(this).val());
            });
            
            console.log("My favourite sports are: " + favorite.join(", "));
            let submitanswer= {
                'quiz_result_id': this.quiz_result_id,
                'question_id': $(target).attr('question_id'),
                'answer': favorite.join(", ")
           }
           //console.log(submitanswer);
           this.submitAnswer(submitanswer);
        },
        addEvent ({ type, target }) {
           
           var att = $(target).attr('newattr');
          
           let submitanswer= {
                'quiz_result_id': this.quiz_result_id,
                'question_id': $(target).attr('question_id'),
                'answer': target.value
           }
           //console.log(submitanswer);
           this.submitAnswer(submitanswer);
        },
        submitAnswer(submitanswer) {
            //console.log(submitanswer);
            axios.post('/api/elearning/quiz/saveanswer', submitanswer)
                .then(response => {
                    console.log(response);     
                });
        },
    }
});
</script>

