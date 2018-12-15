<?php 
use yii\widgets\LinkPager;
use yii\helpers\Json;
$this->title = $item->name;
$this->params['breadcrumbs'][] = $this->title;
use yii\helpers\Url;
$this->params['class'] = "quiz-view";
?>

<div id="app">
<div class="row">
    <div class="col-sm-4 col-md-3">
        <div class="time-info">Thời gian còn lại: <span id="demo"></span></div>
        
        <div class="qustion-list">
            <template v-for="question in questions">
                <div v-bind:class="{ 'qustion-slide': question.question_id, 'fill': question.filled }">
                    <div class="qustion-number ">{{question.name}}</div>
                </div>
            </template>
        </div>
    </div>
    <div class="col-sm-8 col-md-9">
        <div class="result alert alert-success" v-if="showresult==1">
            Bạn đã làm đúng {{correct}} trên {{total}} câu hỏi. <a href="/">Trở về trang chủ</a>
        </div>
        <div class="qustion-main" v-if="showresult==0">
            
            
                <div class="qustion-box">
               
                <template v-for="(question, index) in questions">
                
                    <div class="qustion">{{index+1}}. {{question.name}}</div>
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
                            <input @input="addEvent" @change="addEvent"  v-bind:name="'question-'+question.question_id" v-bind:id="'question-'+question.question_id+'-'+index2" v-bind:value="index2 + 1" type="checkbox"  v-bind:question_id="question.question_id">
                            {{answer}}
                            </label>
                        </div>
                    </template>
                    <template v-else>
                        <input @input="addEvent" @change="addEvent" v-on:change="filled(index)" v-bind:name="'question-'+question.question_id" v-bind:question_id="question.question_id"  class="form-control" value="" type="text">
                    </template>
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


<script>
// Set the date we're counting down to
var countDownDate = new Date("<?php echo date("Y-m-d H:i:s",strtotime('+'.$item->time.' mins'))?>").getTime();

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
var app = new Vue({
        el: '#app',

    data: function () {
        return {
            item:{ 
            }, 
            questions:[],
            quiz_result_id : 0,
            showresult: 0,
            correct: 0,
            total: <?php echo $item->number_questions?>,
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
                axios.get('/api/elearning/quiz/showresult?id=' + this.quiz_result_id)
                .then(response => {
                   this.showresult = 1;
                   this.correct = response.data.correct;
                    console.log(response);     
                });
        },
        filled: function (index){
            this.questions[index].filled = 1;
           
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