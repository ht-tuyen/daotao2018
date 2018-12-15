<?php
$this->title = 'Khóa học';
$this->params['breadcrumbs'][] = $this->title;
use yii\helpers\Url;
$this->params['class'] = "courses-view course-details lession-view";
?>
<div id="app">

    <router-view></router-view>
   
</div>
<script>
// COURSES LIST COMPONENT

const Index = { 
    template:`
        <div class="list-courses ">
            <div class="container">
                <div class="row">
                    <div v-for="item in items" class="col-sm-6 col-md-3">
                        <div class="course-post">
                            <div class="img">
                                <img src="http://115.160.244.10:8084/themeforest/academy/html/images/courses/courses-img6.jpg" alt=""/>
                            
                            
                            </div>
                            <div class="info">
                                <div class="name">{{item.name}}</div>
                                <div class="expert"><span>Giảng viên </span>{{item.teacher.fullname}}</div>
                            </div>
                            <div class="product-footer">
                                <div class="comment-box">	
                                    <div class="box"><i class="fa fa-users"></i>35 Học viên</div>
                                </div>
                                <div class="rating">
                                    <div class="fill" style="width:45%"></div>
                                </div>
                                <div class="view-btn">
                                <router-link :to="{ name: 'detail', params: { id: item.slug }}" class="btn">Xem khóa học</router-link>
                                
                                </div>
                            </div>
                        </div>
                    </div>
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
            </div>   
            
            
        </div>
    `,
    data: function () {
        return {
            items: [],
            pagination: {
                total: 0,
                per_page: 2,
                from: 1,
                to: 0,
                current_page: 1
            },
            sortColumn:"course_id",
            sortType: "desc",
            search: {},
            offset: 4,
        }
    },
    mounted() {
        this.getVueItems();
    },
    methods:{
        getVueItems: function() {
            axios.post('/api/elearning/course/list?page=' + this.pagination.current_page + '&sortBy=' + this.sortColumn + '&sortType=' + this.sortType, this.search)
                .then(response => {
                    this.pagination = response.data.pagination;
                    this.items = response.data.data;
                    
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
    }
  
}


// COURSE DETAIL

const Detail = { 
   
    template: `
    <div>
    <div class="course-details-main">
        <div class="course-img">
            <img src="http://115.160.244.10:8084/themeforest/academy/html/images/courses/courses-img8.jpg" alt="">
        </div>
        <div class="course-info">
            <div class="course-box">
                <div class="icon"><i class="fa fa-file"></i></div>
                <p>{{item.lessons ? item.lessons.length : 0}} Bài học</p>
            </div>
            <div class="course-box">
                <div class="icon"><i class="fa fa-exclamation"></i></div>
                <p>1 Bài kiểm tra</p>
            </div>
            <div class="course-box">
                <div class="icon"><i class="fa fa-file-text-o"></i></div>
                <p>13 Tài liệu</p>
            </div>
            <div class="course-box">
                <div class="icon"><i class="fa fa-video-camera"></i></div>
                <p>9 Video</p>
            </div>
            <div class="course-box">
                <div class="icon"><i class="fa fa-mortar-board"></i></div>
                <p>{{item.students ? item.students.length : 0}} Học viên</p>
            </div>
        </div>
        
        
    </div>
    
    <div class="info">
        <h4>Thông tin khóa học</h4>
        <div class="description" v-html="item.full_desc">
         
        </div>
        
    </div>
    
    <div class="syllabus">
        <h4>Bài học</h4>
        
       
        <div v-for="lesson in item.lessons" class="syllabus-box">
            
            <div class="syllabus-view first">
                <div class="main-point">
                <router-link :to="{ name: 'lesson', params: { id: lesson.lesson_id }}">
                   {{lesson.name}}
                   </router-link>
                </div>
                <div class="point-list" style="display: none;">
                    
                        {{lesson.short_desc}} 
                        <ul>                            	
                            <li><a href="<?php echo $url ?>">Học ngay</a></li>
                        </ul>
                </div>
                
            </div>
        </div>
     
        </div>   
    </div>
    `,
    data: function () {
        return {
            item:{
               
            },
           
        }
    },
    mounted() {
        this.getVueItem();
    },
    methods:{
        getVueItem: function() {
            let app = this;
            let id = app.$route.params.id;
            axios.get('/api/elearning/course/view?id=' + id)
                .then(response => {
                    this.item = response.data;
                    console.log(this.item);
                    
                });
        },
      
    }
    
};


// LESON DETAIL

const Lesson = { 
   
   template: `
   <div>
   <div class="vedio-box">
    <img src="http://115.160.244.10:8084/themeforest/academy/html/images/courses/courses-img8.jpg" alt="">
    <div class="play-icon"><i class="fa fa-play"></i></div>
    </div>
    <div class="lessone-info">
        <h3>Thông tin bài học</h3>
        <div class="description" v-html="item.full_desc">
    
    <template v-if="item.attachments">
        <h3>Tài liệu tham khảo </h3>
        <ul>
        
            <li v-for="attach in item.attachments"><a v-bind:href="attach.source">{{attach.name}}</a></li>
       
        </ul>
    </template>
    </div> 
    </div> 
   `,
   data: function () {
       return {
           item:{   
           },
       }
   },
   mounted() {
       this.getVueItem();
   },
   methods:{
       getVueItem: function() {
           let app = this;
           let id = app.$route.params.id;
           axios.get('/api/elearning/lesson/view?id=' + id)
               .then(response => {
                   this.item = response.data;
                   console.log(this.item);
                   
               });
       },
     
   }
   
};


// 2. Define some routes
// Each route should map to a component. The "component" can
// either be an actual component constructor created via
// `Vue.extend()`, or just a component options object.
// We'll talk about nested routes later.
const routes = [
    { path: '/', component: Index },
    { path: '/:id',name: 'detail', component: Detail },
    { path: 'lesson/:id',name: 'lesson', component: Lesson }

]

// 3. Create the router instance and pass the `routes` option
// You can pass in additional options here, but let's
// keep it simple for now.
const router = new VueRouter({
  routes // short for `routes: routes`
})
// 4. Create and mount the root instance.
// Make sure to inject the router with the router option to make the
// whole app router-aware.
const app = new Vue({
  router
}).$mount('#app')

</script>