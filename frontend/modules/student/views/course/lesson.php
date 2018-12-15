<?php 
use yii\helpers\Url;
use common\components\CourseSideBar;
use common\components\CourseBottom;

$this->title = $item->name;
$this->params['breadcrumbs'][] = $item->course->name;
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
                <?= CourseSideBar::widget(['course_id' => $item->course_id,'active_type'=>1,'active_id'=>$item->lesson_id]) ?>

                </div>
                <div role="tabpanel" class="tab-pane" id="profile">
                    <div id="app" class="course-sidebar">
                        <h3>Thảo luận</h3>
                        <div style="padding: 10px;">
                        <div class="list-message">
                            <template v-for="item in items">
                                <div class="message" @click="replyTo(item)">
                                    <a href="#replybox"><i title="Trả lời" class="fa fa-comment-o" aria-hidden="true"></i></a>
                                    <div class="from-user" :class="{is_admin: item.is_admin}">
                                        <div class="user-name"  > <span  style="font-weight: bold;" v-text="item.user.full_name ? item.user.full_name : item.user.fullname"></span> <span class="date-send" ><i>{{item.created_date | formatDate}}</i></span></div>
                                        
                                    </div>
                                    <div class="message-content" v-text="item.message"></div>
                                    <div class="replied" v-if="item.replies.length > 0">
                                        <template v-for="reply in item.replies">
                                            <div class="reply">
                                                <div class="from-user" :class="{is_admin: reply.is_admin}">
                                                    <div class="user-name"  > <span  style="font-weight: bold;" v-text="reply.user.full_name ? item.user.full_name : item.user.fullname"></span> <span class="date-send" ><i>{{reply.created_date | formatDate}}</i></span></div>
                                                
                                                </div>
                                                <div class="message-content" v-text="reply.message"></div>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </template>
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
                        </div>
                        <div class="send-message" id="replybox">
                            <div class="reply-content" v-if="inputItem.parent_id">
                                <blockquote>
                                    {{replyMessage}}
                                </blockquote>
                                <a @click.prevent="cancelReply" style="color:#e28326" href=""><i title="Hủy" class="fa fa-close" aria-hidden="true"></i></a>
                            </div>
                            <textarea name="message" v-model="inputItem.message" id="" cols="30" rows="10" class="form-control">
                            </textarea>
                            <button @click="storeItem" class="btn btn-info" style=" padding: 5px 20px;font-size: 14px;">Gửi</button>
                        </div> 
                        </div>
                    </div>
                </div>
                <div role="tabpanel" class="tab-pane" id="messages">
                    <div class="course-sidebar">
                        <h3>Tài liệu tham khảo</h3>
                        <ul>
                        <?php foreach ($item->attachments as $attach ) {?>
                            <li><i class="fa fa-file" aria-hidden="true"></i>
                                <a target="_blank" href="/uploads/elearning/attachment/<?php echo $attach->source?>"><?php echo $attach->name?></a></li>
                        <?php }?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-sm-8">
        <div class="content-course-right">
        <?php if ($item->lesson_format == 1) {?>
            <video controls >
                <source src="/uploads/elearning/lesson/resource/<?php echo $item->lesson_resource?>" type="video/mp4">
            </video>
        <?php }else{ ?>
            <iframe src="/uploads/elearning/lesson/resource/<?php echo $item->lesson_resource?>" frameborder="0"></iframe>
        <?php }?>
        <p></p>
        <h3>Nội dung bài học</h3>
        <?php echo $item->full_desc?>
        </div>
    </div>
</div>

<div class="fixed-bottom">
    
    <div class="course-bottom ">
        <div class="row">
            <div class="col-xs-12 col-sm-4">
                <?php echo CourseBottom::widget(['course_id' => $item->course_id]) ?>
                
            </div>
            <div class="col-xs-12 col-sm-8 text-center">
                <div class="lesson-nav pre">
                    <div id="pre_url"></div>
                </div>
                    <?php echo '<a href="'. Url::to(['/student/course/completelesson', 'id' => $item->lesson_id]).'">
                        <i class="fa fa-check-circle-o" aria-hidden="true"></i>
                        Làm bài kiểm tra</a>';
                    ?>
                <div class="lesson-nav next">
                    <div id="next_url"></div>
                </div>
               
            </div>
        </div>
    </div>
</div>
<script src="https://momentjs.com/downloads/moment.min.js"></script>
<script>
    var pre_url;
    var next_url;
    var x = document.getElementById("lesson_<?php echo $item->lesson_id?>").previousSibling; 
    if (x.previousElementSibling) {
        console.log(x)
        pre_url = x.previousElementSibling.childNodes[3].href;
    }else {
        pre_url = '';
    }
    var y = document.getElementById("lesson_<?php echo $item->lesson_id?>").nextSibling; 
    if (y.nextElementSibling) {
        next_url = y.nextElementSibling.childNodes[3].href;
    }else {
        next_url = '';
    }
    if (pre_url)
        document.getElementById('pre_url').innerHTML='<a href="'+pre_url+'"><i class="fa fa-arrow-circle-left" aria-hidden="true"></i> Bài trước</a>';
    if (next_url)
    document.getElementById('next_url').innerHTML='<a href="'+next_url+'">Bài sau <i class="fa fa-arrow-circle-right" aria-hidden="true"></i></a>';
    console.log(pre_url);
    console.log(next_url);
</script>
<script>

  var app = new Vue({
      el: '#app',
      data: {
          
          
          items: [],
          inputItem: {
              'message': '',
              'lesson_id' : <?php echo $item->lesson_id?>,
              'parent_id': 0,
              'is_admin':0,
              'user_id': <?php echo Yii::$app->user->id?>
          },
          replyMessage: '',
          pagination: {
              total: 0,
              per_page: 2,
              from: 1,
              to: 0,
              current_page: 1
          },
          offset: 4,

         
      },

      mounted() {
          this.getVueItems();


      },
     
      methods: {
          replyTo: function(message) {
            this.inputItem.parent_id = message.message_id;
            this.replyMessage = message.message;
          },
          cancelReply(){
            this.inputItem.parent_id = 0;
            this.replyMessage = '';
          },
          getVueItems: function() {
              console.log(this.pagination.current_page);
              axios.post('/api/elearning/message/list?id='+this.inputItem.lesson_id+'&page=' + this.pagination.current_page)
                  .then(response => {
                      this.pagination = response.data.pagination;
                      this.items = response.data.data;
                      console.log(response);
                  });
          },
         
          storeItem: function() {
              axios.post('/api/elearning/message/store', this.inputItem).then((response) => {
                  console.log(response);
                  this.getVueItems();
                  this.inputItem.message="";
                  this.inputItem.parent_id = 0;
                  this.replyMessage = '';

              }).catch(e => {
                  console.log(e);
              });
          },
         
          delete_item: function(itemId) {
              if (confirm("Bạn chắc chắn muốn xóa mục này?")) {
                  axios.post('/api/elearning/lesson/delete?id=' + itemId)
                      .then(response => {
                          this.getVueItems();
                          toastr.warning('Bài học đã được xóa.', 'Thông báo', {
                              timeOut: 2000
                          });
                      });
              }

          },
         
          changePage: function(page) {
              this.pagination.current_page = page;
              this.getVueItems();
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
      },
     
    filters: {
        formatDate: function (value) {
            if (!value) return ''
            
            return moment(String(value)).format('hh:mm DD/MM/YYYY')
        }
    }
  })
</script>