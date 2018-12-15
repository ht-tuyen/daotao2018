<?php 
    $this->title = "Quản lý module cảm nhận học viên";
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
                        <i class="glyphicon glyphicon-plus"></i> Tạo
                    </button>
                    <a class="btn btn-default" href="/acp/elearning/feature" title="Reset Grid" data-pjax="0"><i class="glyphicon glyphicon-repeat"></i> Tải lại &amp; Xóa lọc trang</a>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
    </div>



    <table class="admintablelist table table-bordered table-hover">
        <thead>
            <tr>
                <th><input type="checkbox" v-on:click="selectAll" v-model="allchecked"></th>
                <th><a href="" v-on:click.prevent="sortBy('id')">Id</a></th>
                <th><a href="" v-on:click.prevent="sortBy('name')">Tên</a></th>
                <th><a href="" v-on:click.prevent="sortBy('position')">Chức vụ</a></th>
                <th><a href="" v-on:click.prevent="sortBy('state')">Trạng thái</a></th>
                <th></th>
            </tr>
            <tr>
                <td></td>
                <td><input type="text" style="width:60px;" class="form-control" v-on:keyup="search_item" name="search.id" v-model="search.id" /></td>
                <td><input type="text" placeholder="Tìm kiếm " class="form-control" v-on:keyup="search_item" name="search.name" v-model="search.name" /></td>
                <td><input type="text" placeholder="Tìm kiếm " class="form-control" v-on:keyup="search_item" name="search.position" v-model="search.position" /></td>

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
                <td><input type="checkbox" v-model="checked" name="checked" :value="item.id"></td>
                <td>{{item.id}}</td>
                <td><a v-on:click.prevent="update_item(item)">{{item.name}}</a></td>
                <td>{{item.position}}</td>

                <td>{{item.state == 1 ? "Xuất bản" : "Bản nháp"}}</td>
                <td>
                    <a v-on:click.prevent="update_item(item)"><span class="text-blue"><i class="glyphicon glyphicon-pencil "></i></span></a>
                    <a v-on:click.prevent="delete_item(item.id)"><span class="text-black"><i class="glyphicon glyphicon-trash"></i></span></a>
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

                    <h4 class="modal-title" id="myModalLabel">{{inputItem.id ? "Cập nhật " : "Thêm mới"}}</h4>
                </div>
                <div class="modal-body">
                    <!-- Form-->
                    <form method="POST" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <label for="title">Tên:</label>
                                    <input type="text" name="title" class="form-control" v-model="inputItem.name" />
                                    <span v-if="showError.name" class="error text-danger">{{showError.name[0]}}</span>
                                </div>
                                <div class="form-group">
                                    <label for="position">Chức vụ:</label>
                                    <input type="text" name="position" class="form-control" v-model="inputItem.position" />
                                    <span v-if="showError.position" class="error text-danger">{{showError.position[0]}}</span>
                                </div>
                              
                                <div class="form-group">
                                    <label for="description">Giới thiệu:</label>
                                    <textarea name="description" class="form-control" v-model="inputItem.description"></textarea>
                                </div>

                            </div>
                            <div class="col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <label for="source">Ảnh:</label>
                                    <input id="source" type="file" ref="source" v-on:change="previewImage" name="source" class="form-control" />
                                   

                                    <div class="image-preview" v-if="inputItem.source" style="border:1px solid #ccc; padding: 5px; margin: 5px;">
                                        <img v-if="inputItem.id && !inputItem.source_is_changed" class="preview" :src="'/uploads/elearning/feedback/'+inputItem.source" style="width: 300px;">
                                        <img v-else :src="inputItem.source" style="width: 300px;" alt="">                                    
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="title">Thứ tự:</label>
                                    <input type="text" name="title" class="form-control" v-model="inputItem.ordering" />
                                    <span v-if="showError.ordering" class="error text-danger">{{showError.ordering[0]}}</span>
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
                'id': '',
                'position': '',
                'state': 1,
                'ordering': 0,
                'source': 0,
                'description':'',
                'source_is_changed': 0
            },
            search: {
                name: "",
                position: "",
                state: "",
                id: ""
            },
            sortColumn: "id",
            sortType: "DESC",
            
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
                placeholder: 'Nhập nội dung'
            },
            showError:{}
        },

        mounted() {
            this.getVueItems();


        },
        
        methods: {
          
            getVueItems: function() {

                axios.post('/api/elearning/feedback/list?page=' + this.pagination.current_page + '&sortBy=' + this.sortColumn + '&sortType=' + this.sortType+'&user_id=<?php echo Yii::$app->user->identity->user_id;?>&role_id=<?php echo Yii::$app->user->identity->role_id;?>', this.search)
                    .then(response => {
                        this.pagination = response.data.pagination;
                        this.items = response.data.data;
                      
                });
            },
            storeItem: function() {
                this.inputItem.user_id = <?php echo Yii::$app->user->identity->user_id;?>;
                this.inputItem.role_id = <?php echo Yii::$app->user->identity->role_id;?>;
                axios.post('/api/elearning/feedback/store', this.inputItem).then((response) => {
                    if (response.data.error) {
                        //console.log(response.data.error);
                        this.showError = response.data.error;
                    }else {
                        if (this.inputItem.id) {
                            toastr.success('Cảm nhận đã được cập nhật.', 'Thông báo', {
                                timeOut: 5000
                            });
                        } else {
                            toastr.success('Cảm nhận đã được tạo.', 'Thông báo', {
                                timeOut: 5000
                            });
                        }
                        $('#source').val('');
                        this.truncateItem();
                        this.getVueItems();
                        $("#create-item").modal('hide');
                       
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
                    axios.post('/api/elearning/feedback/delete?id=' + itemId)
                        .then(response => {
                            this.getVueItems();
                            toastr.warning('Cảm nhận đã được xóa.', 'Thông báo', {
                                timeOut: 5000
                            });
                        });
                }

            },
            bulk_delete: function() {
                console.log(this.checked);
                if (confirm("Bạn chắc chắn muốn xóa những mục này?")) {
                    axios.post('/api/elearning/feedback/bulkdelete', this.checked)
                        .then(response => {
                            this.checked = [];
                            this.getVueItems();
                            console.log(response);
                            toastr.warning('Cảm nhận đã được xóa.', 'Thông báo', {
                                timeOut: 5000
                            });
                        });
                }
            },
            clearinputItem: function() {
                if (this.inputItem.id) {
                    this.truncateItem();
                }
                this.showError = {};
            },
            truncateItem: function() {
                this.inputItem = {
                    'name': '',
                    'id': '',
                    'position': '',
                    'state': 1,
                    'ordering': 0,
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
                        this.checked.push(this.items[item].id);
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