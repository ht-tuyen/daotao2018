<?php 
    $this->title = "Quản lý tài liệu";
    $this->params['breadcrumbs'][] = $this->title;
?>

<div id="app" class="panel panel-info">
    <div class="panel-heading">
        <div class="pull-right">
            <div class="summary">Hiển thị <b>{{pagination.from}}-{{pagination.to}}</b> trong số <b>{{pagination.total}}</b> mục.</div>
        </div>
        <h3 class="panel-title">
        </h3>
        <div class="clearfix"></div>
    </div>
    <div class="kv-panel-before" style="padding: 10px;border-bottom: 1px solid #ccc;">
        <div class="pull-right">
            <div class="btn-toolbar kv-grid-toolbar" role="toolbar">
                <div class="btn-group">
                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#create-item">
                        <i class="glyphicon glyphicon-plus"></i> Tải tài liệu
                    </button>
                    <a class="btn btn-default" href="/acp/elearning/attachment" title="Reset Grid" data-pjax="0"><i class="glyphicon glyphicon-repeat"></i> Tải lại &amp; Xóa lọc trang</a>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
    </div>



    <table class="admintablelist table table-bordered table-hover">
        <thead>
            <tr>
                <th><input type="checkbox" v-on:click="selectAll" v-model="allchecked"></th>
                <th><a href="" v-on:click.prevent="sortBy('attachment_id')">Id</a></th>
                <th><a href="" v-on:click.prevent="sortBy('name')">Tài liệu</a></th>
                <th><a href="" v-on:click.prevent="sortBy('category_id')">Chuyên mục</a></th>
                <th><a href="" v-on:click.prevent="sortBy('state')">Trạng thái</a></th>
                <th></th>
            </tr>
            <tr>
                <td></td>
                <td><input type="text" style="width:60px;" class="form-control" v-on:keyup="search_item" name="search.id" v-model="search.id" /></td>
                <td><input type="text" placeholder="Tìm kiếm tài liệu" class="form-control" v-on:keyup="search_item" name="search.name" v-model="search.name" /></td>
                <td>
                    <v-select @input="search_item" v-model="search.category" :options="categories" label="name"></v-select>
                </td>
                <td>
                    <select v-on:change="search_item" name="search.state" class="form-control" v-model="search.state">
                        <option value="">--Lọc trạng thái--</option>
                        <option value="1">Xuất bản</option>
                        <option value="-1">Bản nháp</option>
                    </select>
                </td>
                <td></td>
            </tr>
        </thead>
        <tbody>

            <tr v-for="item in items">
                <td><input type="checkbox" v-model="checked" name="checked" :value="item.attachment_id"></td>
                <td>{{item.attachment_id}}</td>
                <td><a v-on:click.prevent="update_item(item)">{{item.name}}</a></td>
                <td>{{item.category}}</td>
                <td>{{item.state == 1 ? "Xuất bản" : "Bản nháp"}}</td>
                <td>
                    <a title="Cập nhật" v-on:click.prevent="update_item(item)"><span class="text-blue"><i class="glyphicon glyphicon-pencil "></i></span></a>
                    <a title="Xóa" v-on:click.prevent="delete_item(item.attachment_id)"><span class="text-black"><i class="glyphicon glyphicon-trash"></i></span></a>
                </td>
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
    <!-- Create Item Modal -->
    <div class="modal fade" id="create-item" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button v-on:click="clearinputItem" type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>

                    <h4 class="modal-title" id="myModalLabel">{{inputItem.attachment_id ? "Cập nhật tài liệu" : "Tải tài liệu"}}</h4>
                </div>
                <div class="modal-body">
                    <!-- Form-->
                    <form method="POST" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <label for="title">Tên tài liệu:</label>
                                    <input type="text" name="title" class="form-control" v-model="inputItem.name" />
                                    <span v-if="showError.name" class="error text-danger">{{showError.name[0]}}</span>
                                </div>
                                <div class="form-group">
                                    <label for="parent_id">Chuyên mục :</label>
                                   
                                    <select name="category_id" v-model="inputItem.category_id" id="category_id" class="form-control">
                                        <option v-bind:value="category.value" v-text="category.name" v-for="category in categories"></option>
                                    </select>
                                    <span v-if="showError.category_id" class="error text-danger">{{showError.category_id[0]}}</span>
                                </div>
                                <div class="form-group">
                                    <label for="description">Giới thiệu:</label>
                                    <textarea name="description" class="form-control" v-model="inputItem.description"></textarea>
                                </div>

                            </div>
                            <div class="col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <label for="source">Tải tài liệu:</label>
                                    <input type="file" ref="source" v-on:change="previewImage" name="source" class="form-control" />
                                    <template v-if="inputItem.source">
                                        <a target="_blank" :href="'/uploads/elearning/attachment/'+inputItem.source" v-text="inputItem.source"></a>
                                    </template>
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
                        </div>

                        <div class="form-group">
                            <a v-on:click.prevent="storeItem" class="btn btn-success">Lưu</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div style="  position: fixed;bottom: 0;background: #FFF;z-index: 9999;padding: 5px 10px; border-top: 1px solid #eee; width: 100%;" v-if="checked[0]">
        <span v-on:click="bulk_delete" class="btn btn-danger"><span class="glyphicon glyphicon-trash"></span>Xóa lựa chọn</span>
    </div>
</div>

<script>
    Vue.use(VueQuillEditor)
    Vue.component('v-select', VueSelect.VueSelect);

    var app = new Vue({
        el: '#app',
        data: {
            items: [],
            role_id : <?php echo Yii::$app->user->identity->role_id;?>,
            user_id : <?php echo Yii::$app->user->identity->user_id;?>,
            checked: [],
            allchecked: false,
            inputItem: {
                'name': '',
                'attachment_id': '',
                'category_id': '',
                'state': 1,
                'source': 0,
                'description':'',
                'source_is_changed': 0
            },
            search: {
                name: "",
                category: "",
                state: "",
                id: ""
            },
            sortColumn: "attachment_id",
            sortType: "DESC",
            categories: [],
            pagination: {
                total: 0,
                per_page: 2,
                from: 1,
                to: 0,
                current_page: 1
            },
            offset: 4,

            editorOption: {
                theme: 'snow',
                placeholder: 'Nhập nội dung tài liệu tại đây'
            },
            showError:{}
        },

        mounted() {
            this.getVueItems();


        },
        
        methods: {
          
            getVueItems: function() {

                axios.post('/api/elearning/attachment/list?page=' + this.pagination.current_page + '&sortBy=' + this.sortColumn + '&sortType=' + this.sortType+'&user_id=<?php echo Yii::$app->user->identity->user_id;?>&role_id=<?php echo Yii::$app->user->identity->role_id;?>', this.search)
                    .then(response => {
                        this.pagination = response.data.pagination;
                        this.items = response.data.data;
                        this.categories = response.data.categories;
                });
            },
            storeItem: function() {
                this.inputItem.user_id = <?php echo Yii::$app->user->identity->user_id;?>;
                this.inputItem.role_id = <?php echo Yii::$app->user->identity->role_id;?>;
                axios.post('/api/elearning/attachment/store', this.inputItem).then((response) => {
                    if (response.data.error) {
                        //console.log(response.data.error);
                        this.showError = response.data.error;
                    }else {
                        if (this.inputItem.attachment_id) {
                            toastr.success('Tài liệu đã được cập nhật.', 'Thông báo', {
                                timeOut: 5000
                            });
                        } else {
                            toastr.success('Tài liệu đã được tạo.', 'Thông báo', {
                                timeOut: 5000
                            });
                        }

                        this.truncateItem();
                        this.getVueItems();
                        $("#create-item").modal('hide');
                        location.reload();
                    }
                }).catch(e => {
                    console.log(e);
                });;
            },
            update_item: function(item) {
                console.log(item);
                this.inputItem = item;
                $("#create-item").modal('show');
            },
            delete_item: function(itemId) {
                if (confirm("Bạn chắc chắn muốn xóa mục này?")) {
                    axios.post('/api/elearning/attachment/delete?id=' + itemId)
                        .then(response => {
                            this.getVueItems();
                            toastr.warning('Tài liệu đã được xóa.', 'Thông báo', {
                                timeOut: 5000
                            });
                        });
                }

            },
            bulk_delete: function() {
                console.log(this.checked);
                if (confirm("Bạn chắc chắn muốn xóa những mục này?")) {
                    axios.post('/api/elearning/attachment/bulkdelete', this.checked)
                        .then(response => {
                            this.checked = [];
                            this.getVueItems();
                            console.log(response);
                            toastr.warning('Các tài liệu đã được xóa.', 'Thông báo', {
                                timeOut: 5000
                            });
                        });
                }
            },
            clearinputItem: function() {
                if (this.inputItem.attachment_id) {
                    this.truncateItem();
                }
                this.showError = {};
            },
            truncateItem: function() {
                this.inputItem = {
                    'name': '',
                    'attachment_id': '',
                    'category_id': '',
                    'state': 1,
                    'source': 0,
                    'description':'',
                    'source_is_changed': 0
                };
                this.showError = {};
            },
            changePage: function(page) {
                this.pagination.current_page = page;
                this.getVueItems();
            },
            search_item: function(obj) {
              
                this.getVueItems();
            },
            sortBy: function(column) {
                this.sortColumn = column;
                if (this.sortType == "ASC") {
                    this.sortType = "DESC";
                } else {
                    this.sortType = "ASC";
                }
                this.getVueItems();
            },

            previewImage: function(event) {
                var input = event.target;
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = (e) => {
                        this.inputItem.source = e.target.result;
                        this.inputItem.source_is_changed = 1;
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            },
           
            selectAll: function() {


                this.checked = [];
                if (!this.allchecked) {
                    for (item in this.items) {
                        this.checked.push(this.items[item].attachment_id);
                    }
                }
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