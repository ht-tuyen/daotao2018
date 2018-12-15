<template>
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
                                <router-link :to="{name: 'courseDetail', params: {id: item.id}}" class="btn btn-xs btn-default">Tham gia</router-link>
                              
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
</template>
<script>
    export default {
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
        }
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
</script>