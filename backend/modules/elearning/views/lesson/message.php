<?php 

 

?>

<div id="app" class="course-sidebar">
    <h3>Thảo luận bài học <?php echo $lesson->name?></h3>
    <div style="padding: 10px;">
    <div class="list-message">
        <template v-for="item in items">
            <div class="message" @click="replyTo(item)">
                <a href="#replybox"><i title="Trả lời" class="fa fa-comment-o" aria-hidden="true"></i></a>
                <div class="from-user" :class="{is_admin: item.is_admin}">
                    <div class="user-name"  > <span  style="font-weight: bold;" v-text="item.user.full_name ? item.user.full_name : item.user.fullname "></span> <span class="date-send" ><i>{{item.created_date | formatDate}}</i></span></div>
                    
                </div>
                <div class="message-content" v-text="item.message"></div>
                <div class="replied" v-if="item.replies.length > 0">
                    <template v-for="reply in item.replies">
                        <div class="reply">
                            <div class="from-user" :class="{is_admin: reply.is_admin}">
                                <div class="user-name"  > <span  style="font-weight: bold;" v-text="reply.user.full_name ? item.user.full_name : item.user.fullname "></span> <span class="date-send" ><i>{{reply.created_date | formatDate}}</i></span></div>
                            
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
               
<script src="https://momentjs.com/downloads/moment.min.js"></script>

<script>

  var app = new Vue({
      el: '#app',
      data: {
          
          
          items: [],
          inputItem: {
              'message': '',
              'lesson_id' : <?php echo $lesson_id?>,
              'parent_id': 0,
              'is_admin': 1,
              'user_id': <?php echo Yii::$app->user->identity->user_id;?>
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