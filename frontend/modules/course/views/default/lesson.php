<?php 
use yii\widgets\LinkPager;
$this->title = $item->name;
$this->params['breadcrumbs'][] = $this->title;
use yii\helpers\Url;
$this->params['class'] = "lession-view";
?>
<div id="app">

<div class="resource text-center">
    <?php if ($item->lesson_format == 1) {?>
        <video controls >
            <source src="/uploads/elearning/lesson/resource/<?php echo $item->lesson_resource?>" type="video/mp4">
        </video>
    <?php }else{ ?>
        <iframe src="/uploads/elearning/lesson/resource/<?php echo $item->lesson_resource?>" frameborder="0"></iframe>
    <?php }?>
</div>
<div class="lessone-info">
    <h3>Thông tin bài học</h3>
    <?php echo $item->full_desc?>
  
<?php if (count($item->attachments)) {?>
    <h3>Tài liệu tham khảo </h3>
    <ul>
    <?php foreach ($item->attachments as $attach) {?>
        <li><a href="/uploads/elearning/attachment/<?php echo $attach->source?>"><?php echo $attach->name?></a></li>
    <?php }?>
    </ul>
<?php }?>

</div> 
<h3>Thảo luận</h3>
<div class="list-message">
    <template v-for="item in items">
        <div class="message">
            <div class="from-user">
                <div class="user-name" style="font-weight: bold;" v-text="item.user.full_name"></div>
                <span class="date-send" v-text="item.created_date"></span>
            </div>
            <div class="message-content" v-text="item.message"></div>
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
<div class="send-message">
    <textarea name="message" v-model="inputItem.message" id="" cols="30" rows="10" class="form-control">
    </textarea>
    <button @click="storeItem" class="btn btn-info">Gửi</button>
</div> 
</div>
<script>
  
    var app = new Vue({
        el: '#app',
        data: {
            
            
            items: [],
            inputItem: {
                'message': '',
                'lesson_id' : <?php echo $item->lesson_id?>
            }
            ,
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
        }
    })
</script>