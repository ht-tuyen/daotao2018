<div id="app">
    <h3>Kết quả thi: <?php echo($course->quiz->name)?></h3>
<table class="admintablelist table table-bordered table-hover <?php echo $course->quiz->quiz_id?>">
    <thead>
        <tr>
            
            <th>Học viên</th>
            <th>Kết quả</th>
            <th>Thời gian làm bài</th>
            <th>Thời gian nộp bài</th>
            
        </tr>
        
    </thead>
    <tbody>

        <tr v-for="result in results">
            
            
            <td>{{result.student.full_name}} - {{result.student.mobile}} - {{result.student.email}}</td>
            <td>{{result.result}}/{{result.total}}</td>
            <td>{{result.started_time}}</td>
            <td>{{result.submitted_time}}</td>
            
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
</div>


<script>
  
    var app = new Vue({
        el: '#app',
        data: {
            results: [],
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
           
            getVueItems: function() {

                axios.post('/api/elearning/course/result?quiz_id=<?php echo $course->quiz->quiz_id?>&page=' + this.pagination.current_page )
                    .then(response => {
                        console.log(response);
                        this.pagination = response.data.pagination;
                        this.results = response.data.data;
                       
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
    })
</script>
                