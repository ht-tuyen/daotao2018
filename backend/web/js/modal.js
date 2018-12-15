//itemcheck
$(document).on('click','#delete-all-btn', function(e){
    var parent = $('div.grid-view')
    var select = parent.yiiGridView("getSelectedRows")
    if(confirm('Bạn chắc chắn muốn xóa '+select.length+' lựa chọn?')){    
        var url = '/acp/'+$(this).attr('data-url')
        loadimg();                  
        $.ajax({
            cache: false,
            url: url,
            type: 'POST',
            data: {
                pk: select,
            },
            success: function (data) {
                if(data == 1){                    
                    popthanhcong();
                    rload()
                }else{
                    popthatbai();
                }                                               
            },
            error: function () {
                popthatbai();                       
            }
        });
    }   
})

$(document).on('click','.select-on-check-all', function(e){
    if($(this)[0].checked){
        $('#delete-all').removeClass('hide')
    }else{
        $('#delete-all').addClass('hide')
    }
})
$(document).on('click','.itemcheck', function(e){
    var parents = $(this).parents('table');
    var countcheck = parents.find('.itemcheck:checked').length;
    if(countcheck > 0){                         
        $('#delete-all').removeClass('hide')
    }else{
        $('#delete-all').addClass('hide')   
    }
})


 //order
$(document).on('change','.change-order', function(e){
    var value = $(this).val()
    var parent = $(this).parents('tr')
    var dongia = parent.find('.order-dongia').val()
    var soluong = parent.find('.order-soluong').val()

    var _thanhtien = parent.find('.order-thanhtien')
    _thanhtien.html( (parseInt(dongia)*parseInt(soluong)).toLocaleString() +' đ')
    var table = $(this).parents('table')
    reload_gia(table)
})
$(document).on('click','.order-remove', function(e){
    if(confirm('Bạn muốn xóa?')){ 
        var table = $(this).parents('table')
        $(this).parents('tr').remove()
        reload_gia(table)
    }
})
//End order


$(document).on('click','#change_captcha',function (e) {
    $(this).parent().find('>img').click()    
})

$(document).on('keyup','.select2-search__field',function (e) {
    var s = $(this).val()    
    
    var first_duan_sohieu = $('#select2-duan-sohieu-results>li:first-child')
    if(typeof(first_duan_sohieu.attr('id')) === 'undefined'){
        first_duan_sohieu.html(s + ' <i> - Ấn ENTER để thêm mới</i>')
    }    

    var first_duan_vitri = $('#select2-duan-vitri-results>li:first-child')
    if(typeof(first_duan_vitri.attr('id')) === 'undefined'){
        first_duan_vitri.html(s + ' <i> - Ấn ENTER để thêm mới</i>')
    } 
    
})


function opentab(gda = ''){    
    $('a[href="#tab_trienkhai"]').click()
    setTimeout(function(){        
        $('a[href="#tab_trienkhai_2_'+gda+'"]').click()    
    },50)    
}

function isEmpty(value){
    return (typeof value === "undefined" || value === null || value === '');
}

function match_ics (term, text) {
    // console.log(term.term)
    // console.log(text.text)
    if(term.term == text.text){
        console.log('------------------------')
        return text.text
    }   
  return false;
}


//Delete tieu chuan quoc te
function del_tcqt(url = '',idduan = '') {
    if (confirm('Bạn chắc chắn muốn xóa?')) { 
        loadimg();                  
        $.ajax({
            cache: false,
            url: url,
            type: 'POST', 
            success: function (data) {
                if(data == 1){   
                    if(!isEmpty(idduan)){
                        reduan(idduan,'0','','/acp/tieuchuanquocte/updatem?id=')
                    }
                    popthanhcong();
                    rload('tieuchuan-index','/acp/tieuchuanquocte','','noclose');
                }else{
                    popthatbai();
                }                                               
            },
            error: function () {
                unloadimg();                        
            }
        });
    }
}



//Submit form settings
//  $('form#settings-form').on('beforeSubmit', function(e) {             
//     loadimg();
//     var form = $(this);                                    
//     var formData = new FormData(document.querySelector('form#settings-form'));
//     $.ajax({
//         url: form.attr("action"),
//         type: form.attr("method"),
//         data: formData,
//         processData: false,
//         contentType: false,
//         success: function (data){
//             $('section.content').html(data)
//             popthanhcong();
//             e.preventDefault();          
//         },
//         error: function () {
//             popthatbai();
//             e.preventDefault();              
//         }
//     });
// }).on('submit', function(e){        
//     e.preventDefault();
// });



//Export check all
$(document).on('click','.export_checkall',function (e) {
    var current = $(this)
    var parent = current.parents('fieldset')
    if(current[0].checked){        
        parent.find('input').prop('checked', true)        
    }else{
        parent.find('input').prop('checked', false)
    } 
})



//Form ket qua tim kiem TC dang xay dung
$(document).on('click','#ketquatimkiem ul.pagination a',function (e) {
        var url = $(this).attr('href');   

        var search_tukhoa = $('#kehoachnam-search_tukhoa').val();
        var search_nam = $('#kehoachnam-search_nam').val();
        var search_bonganh = $('#kehoachnam-search_bonganh').val();
        var search_linhvuc = $('#kehoachnam-search_linhvuc').val();
        var search_coquanbiensoan = $('#kehoachnam-search_coquanbiensoan').val();
        var search_duan = $('#kehoachnam-search_duan').val();
        var search_giaidoan = $('#kehoachnam-search_giaidoan').val();
     
        loadimg();
           $.ajax({
            cache: false,            
            url: url,
            type: 'POST',   
            data:{
                'tukhoa': search_tukhoa,
                'nam': search_nam,                  
                'bonganh':search_bonganh,
                'linhvuc':search_linhvuc,
                'coquanbiensoan':search_coquanbiensoan,
                'duan': search_duan,
                'giaidoan':search_giaidoan,
            },         
            success: function (data) { 
                $('.searchtieuchuan-index').html(data);
                unloadimg();
                },
                error: function () {
                    unloadimg();
                }
            });   
        e.preventDefault();
    })




$(document).on('click','.change-all-roler', function () {   
    current = $(this)
    var choice = current.attr('data-value')    
    parent = current.parents('tr')
    parent.find('table .mini-slider-control').attr('data-value',choice);
    parent.find('table input.field_permission').attr('value',choice);
    parent.find('table .mini-slider-control a').css('left',(parseInt(choice) * 50)+'%');
})




//Tuy chon update khi import
$(document).on('click','.ds_update_button', function () {
    if(!confirm('Bạn muốn cập nhật các theo các lựa chọn ở trên?')){
        return false
    }
    loadimg()
    var list = $('.select_update:checked').map(function () { return $(this).val(); }).get()
    // console.log(list)
    $.ajax({
        url: '/acp/importtieuchuan/updatem',
        type: 'POST',
        cache: false,
        data: {
            'list': list,
        },
        success: function (data) {
            if(data.status == 1){                
                $.each(data.success, function(i, item) {
                    $('input[value="'+item+'"]').parents('tr').removeClass('bg-gray')
                    $('input[value="'+item+'"]').parents('td').addClass('bg-green')
                    $('input[value="'+item+'"]').parent().find('>input').remove()
                });
                popthanhcong()
                unloadimg()
            }else{
                $('input[value="'+data.false+'"]').parents('td').addClass('bg-red')                
                $s_errors = ''
                $.each(data.content,function(i, item){
                    $s_errors += '<p>'+item+'</p>'
                })
                $('input[value="'+data.false+'"]').parents('tr').find('.clearfix').append('<br/><div class="box-footer text-red">'+$s_errors+'</div>')
                alert('Có lỗi, vui lòng xem danh sách trên dòng được đánh dấu đỏ.') 
                unloadimg()
            }
        },
        error: function () {
             alert('Lỗi kết nối, vui lòng tải lại trang') 
             unloadimg()
        }
    })
})


//Tuy chon update khi import
$(document).on('change','.select_update', function () {      
    var current = $(this)
    var cha_tr = current.parents('tr')
    var cha_table = current.parents('table')
    if(current[0].checked){        
        cha_tr.addClass('bg-gray')
        $('.ds_update_button').removeClass('hide')
    }else{
        cha_tr.removeClass('bg-gray')
    }

    var all_input = cha_table.find('input.select_update:checkbox').length
    var count_check = cha_table.find('input.select_update:checkbox:checked').length
    if(count_check == 0){
        $('.ds_update_button').addClass('hide')
    }

    if(all_input == count_check){
        $('.select_update_all').prop('checked', true)
    }else{
        $('.select_update_all').prop('checked', false)
    }

})
//Tuy chon tat ca update khi import
$(document).on('change','.select_update_all', function () {      
    var current = $(this)
    var cha_table = current.parents('table')
    if(current[0].checked){        
        $('.select_update').prop('checked', true)
        cha_table.find('>tbody>tr').addClass('bg-gray')
        $('.ds_update_button').removeClass('hide')
    }else{
        $('.select_update').prop('checked', false)
        cha_table.find('>tbody>tr').removeClass('bg-gray')        
        $('.ds_update_button').addClass('hide')
    }
})


$(document).on('change','.c_hop', function () {      
    var current = $(this)
    var idmoihop = current.attr('data-idmh')
    
    if(current[0].checked){
        if (confirm('Xác nhập sẽ tham dự cuộc họp?') == false) {
            current.prop('checked', false);
            return false            
        }else{
            current.prop('checked', true)
        }
    }else{
        if (confirm('Hủy tham dự cuộc họp?') == false) {
            current.prop('checked', true)
            return false
        }
    }

    $.ajax({
        url: '/acp/danhsachlayykien/xntd',
        type: 'POST',
        cache: false,
        data: {
            'idmail': idmoihop,
        },
        success: function (data) {
            // body...
        },
        error: function () {
            
        }
    })
    
})






//Xóa phương án trả lời child góp ý TC QT
$(document).on('click','.del-cauhoi-child-pa', function () {     
    current =  $(this)
    gdk = current.attr('data-key')    
    // return false
    // $.ajax({
    //     cache: false,
    //     url: '/acp/tailieu/check',
    //     data:{
    //         gdk: gdk,
    //     },
    //     type: 'POST',
    //     success: function (data){
    //         if(data ==  1){
    //             alert('Bạn không thể xóa vì mã tài liệu này đã được sử dụng trong cơ sở dữ liệu.')
    //         }else{
                if (confirm('Bạn chắc chắn muốn xóa phương án trả lời này?')) { 
                    current.parent().remove()
                }
    //         }
    //     },
    //     error: function () {
    //         alert('Lỗi kết nối, vui lòng tải lại trang') 
    //     }
    // })  
})


//Xóa câu hỏi child góp ý TC QT
$(document).on('click','.del-cauhoi-name-pa', function () {     
    current =  $(this)
    gdk = current.attr('data-key') 
    if (confirm('Bạn chắc chắn muốn xóa câu hỏi này?')) { 
        current.parents('li.seting-cauhoi').remove()
    }
})


//Xóa trả lời chính góp ý TC QT
$(document).on('click','.del-cauhoi-pa', function () {     
    current =  $(this)
    gdk = current.attr('data-key') 
    if (confirm('Bạn chắc chắn muốn xóa phương án này?')) { 
        current.parent().remove()
    }
})

//Xóa Câu hỏi chính góp ý TC QT
$(document).on('click','.del-cauhoi', function () {     
    current =  $(this)
    gdk = current.attr('data-key') 
    if (confirm('Bạn chắc chắn muốn xóa câu hỏi này?')) { 
        current.parents('li.seting-cauhoi').remove()
    }
})


//Remove tên tài liệu mới tại cấu hình 
$(document).on('click','.del-config-gd', function () {     
    current =  $(this)
    gdk = current.attr('data-key')    
    $.ajax({
        cache: false,
        url: '/acp/tailieu/check',
        data:{
            gdk: gdk,
        },
        type: 'POST',
        success: function (data){
            if(data ==  1){
                alert('Bạn không thể xóa vì mã tài liệu này đã được sử dụng trong cơ sở dữ liệu.')
            }else{
                if (confirm('Bạn chắc chắn muốn xóa?')) { 
                    current.parents('.seting-gd').remove()
                }
            }
        },
        error: function () {
            alert('Lỗi kết nối, vui lòng tải lại trang') 
        }
    })  
})


//Lựa chọn phương án TC QT level 1
$(document).on('change','.choice_pa',function (){
    current = $(this)
    choice_child = current.find('option:selected').attr('data-c');
    choice_parent = current.find('option:selected').attr('data-p');
    $('.'+choice_parent).addClass('hide');

    if(choice_child != ''){
        $('#'+choice_child).removeClass('hide');
    }

    $('.'+choice_parent +' .required textarea').each(function(){
        current = $(this)
        var noidung = current.val()
        if(current.is(":visible") == true){
            if(noidung == '-'){
                current.text('')
            }
        }else{
            if(noidung == ''){
                current.text('-')
            }
        }
    })

     $('.'+choice_parent +' select.choice_child_pa').each(function(){
        current = $(this)
        var noidung = current.val()
        if(current.is(":visible") == false){           
            if(noidung == ''){
                current.val('1')
            }
        }else{
            if(noidung == '1'){
                current.val('')
            }
        }
    })
    // $("div.id_100 select").val("val2");
    
})


//Lựa chọn phương án câu hỏi phụ TC QT level 1
$(document).on('change','.choice_child_pa',function (){
    current = $(this)
    choice_child = current.find('option:selected').attr('data-c');
    choice_parent = current.find('option:selected').attr('data-p');

    $('.'+choice_parent).addClass('hide');
    if(choice_child != '') {
        $('.'+choice_parent).addClass('hide');
        $('#'+choice_child).removeClass('hide');
    }


    $('.'+choice_parent +' .required textarea').each(function(){
        current = $(this)
        var noidung = current.val()
        if(current.is(":visible") == true){
            if(noidung == '-'){
                current.text('')
            }
        }else{
            if(noidung == ''){
                current.text('-')
            }
        }
    })

})



//Lựa chọn
$(document).on('change','.choice_type',function (){
    current = $(this)
    parent = current.parent().parent()
    if(current[0].checked){                
        parent.find('.choice_required').prop('disabled',false)        
    }else{        
        parent.find('.choice_required').prop('disabled',true)
        parent.find('.choice_required').prop('checked',false)
    }
})







//Thêm câu hỏi Chính TC QT
$(document).on('click','.add-cauhoi', function () { 
    var parent_fieldset = $(this).parents('fieldset')    
    gd = parent_fieldset.attr('data-gd')
    var idchild = parent_fieldset.find('li.seting-cauhoi').length + 1
    while($('input[name="Settings[list_cau_hoi]['+gd+']['+gd+'-'+idchild+'][name]"]').length > 0){
        idchild += 1
    }
    var shtml = '';    
    shtml += '<li class="seting-cauhoi">'
    shtml += 'Câu hỏi: <div class="seting-cauhoi-name col-md-12 no-padding"><input type="text" placeholder="Nhập câu hỏi..." class="form-control" name="Settings[list_cau_hoi]['+gd+']['+gd+'-'+idchild+'][name]" value=""><span data-key="" class="del-cauhoi"><i class="glyphicon glyphicon-trash"></i></span></div>'

    shtml += '<div class="seting-cauhoi-pa" data-k="Settings[list_cau_hoi]['+gd+']['+gd+'-'+idchild+'][pa]">'  

    shtml += '<div class="seting-cauhoi-pa-one">' //Start 1

    shtml += '<div class="pull-left margin">Trả lời:</div><input type="text" placeholder="Phương án trả lời..." class="form-control" name="Settings[list_cau_hoi]['+gd+']['+gd+'-'+idchild+'][pa][1][tieude]" value="">'
    
    shtml += '<span data-key="" class="del-cauhoi-pa"><i class="glyphicon glyphicon-trash"></i></span>';

    shtml += '<div class="seting-cauhoi-pa-comment"><label><input type="checkbox"  name="Settings[list_cau_hoi]['+gd+']['+gd+'-'+idchild+'][pa][1][comment]"  value="1">Comment</label>'
    shtml += ' (<i><label><input type="checkbox"  name="Settings[list_cau_hoi]['+gd+']['+gd+'-'+idchild+'][pa][1][comment_batbuoc]"  value="1">Bắt buộc</label>) </i> </div>'
    shtml += '<div class="seting-cauhoi-pa-file"><label><input type="checkbox" name="Settings[list_cau_hoi]['+gd+']['+gd+'-'+idchild+'][pa][1][file]"  value="1">File đính kèm</label>'
    shtml += ' (<i><label><input type="checkbox"  name="Settings[list_cau_hoi]['+gd+']['+gd+'-'+idchild+'][pa][1][file_batbuoc]"  value="1">Bắt buộc</label>) </i> </div>'
    
    shtml += '<fieldset class="seting-cauhoi-pa-form">'
    shtml += '<legend>Câu hỏi phụ</legend>'  
    shtml += '<ul class="no-padding list-unstyled" data-k="Settings[list_cau_hoi]['+gd+']['+gd+'-'+idchild+'][pa][1][child]">';
    shtml += '</ul>'  
    shtml += '<span class="add-cauhoi-pa btn btn-default">Thêm câu hỏi cho phụ</span>'
    shtml += '</fieldset>'
    
    shtml += '</div>' // End 1
    shtml += '<span class="add-traloi btn btn-default">Thêm câu trả lời</span>'
    shtml += '</div>'
    shtml += '</li>'
    parent_fieldset.find('>ul').append(shtml);              
})

//Thêm câu trả lời chính
$(document).on('click','.add-traloi', function () { 
    var parent_ = $(this).parent()   
    var current = $(this) 
    var skey = parent_.attr('data-k')
    var idchild = parent_.find('.seting-cauhoi-pa-one').length + 1
    while(parent_.find('input[name="'+skey+'['+idchild+'][tieude]"]').length > 0){
        idchild += 1
    }

    // alert(skey)
    // alert(idchild)
    // return false
    var shtml = '';    
    shtml += '<div class="seting-cauhoi-pa-one">' //Start 1

    shtml += '<div class="pull-left margin">Trả lời:</div><input type="text" placeholder="Phương án trả lời..." class="form-control" name="'+skey+'['+idchild+'][tieude]" value="">'

    shtml += '<span data-key="" class="del-cauhoi-pa"><i class="glyphicon glyphicon-trash"></i></span>';

    shtml += '<div class="seting-cauhoi-pa-comment"><label><input type="checkbox"  name="'+skey+'['+idchild+'][comment]"  value="1">Comment</label>'
    shtml += ' (<i><label><input type="checkbox"  name="'+skey+'['+idchild+'][comment_batbuoc]"  value="1">Bắt buộc</label>) </i> </div>'
    shtml += '<div class="seting-cauhoi-pa-file"><label><input type="checkbox" name="'+skey+'['+idchild+'][file]"  value="1">File đính kèm</label>'
    shtml += ' (<i><label><input type="checkbox"  name="'+skey+'['+idchild+'][file_batbuoc]"  value="1">Bắt buộc</label>) </i> </div>'
    
    shtml += '<fieldset class="seting-cauhoi-pa-form">'
    shtml += '<legend>Câu hỏi phụ</legend>'  
    shtml += '<ul class="no-padding list-unstyled" data-k="'+skey+'['+idchild+'][child]">';
    shtml += '</ul>'  
    shtml += '<span class="add-cauhoi-pa btn btn-default">Thêm câu hỏi cho phụ</span>'
    shtml += '</fieldset>'
    
    shtml += '</div>' // End 1
    $(shtml).insertBefore(current)
    // parent_.append(shtml);              
})



//Thêm câu hỏi Đáp án TC QT
$(document).on('click','.add-cauhoi-pa', function () { 
    var parent_fieldset = $(this).parents('.seting-cauhoi-pa-form') 
    var skey = parent_fieldset.find('>ul').attr('data-k')
    var idchild = parent_fieldset.find('li.seting-cauhoi-pa').length + 1
    while($('#'+skey+'['+idchild+']').length > 0){
        idchild += 1
    }  

    var shtml = '';        
    shtml += '<li class="seting-cauhoi-pa" id="'+skey+'['+idchild+']">';
    shtml += '<div class="col-md-12 no-padding">Câu hỏi: </div>';
   
    shtml += '<div class="seting-cauhoi-name col-md-12 no-padding"><input type="text" class="form-control"  placeholder="Nhập câu hỏi..." name="'+skey+'['+idchild+'][name]" value=""><span data-key="" class="del-cauhoi-name-pa"><i class="glyphicon glyphicon-trash"></i></span></div>';

    shtml += '<div class="seting-cauhoi-child-pa" data-k="'+skey+'['+idchild+'][pa]">';  //111111

    shtml += '<div class="seting-cauhoi-pa-one"  id="'+skey+'['+idchild+'][pa][1]">';    
    shtml += '<div class="pull-left margin">Trả lời:</div><input type="text" class="form-control" placeholder="Phương án trả lời..."  name="'+skey+'['+idchild+'][pa][1][tieude]" value="">';

    shtml += '<span data-key="" class="del-cauhoi-child-pa"><i class="glyphicon glyphicon-trash"></i></span>';

    shtml += '<div class="seting-cauhoi-pa-comment"><label><input type="checkbox"  name="'+skey+'['+idchild+'][pa][1][comment]"  value="1">Comment</label>';
    shtml += ' (<i><label><input type="checkbox"  name="'+skey+'['+idchild+'][pa][1][comment_batbuoc]"  value="1">Bắt buộc</label>) </i> </div>';
    shtml += '<div class="seting-cauhoi-pa-file"><label><input type="checkbox" name="'+skey+'['+idchild+'][pa][1][file]"   value="1">File đính kèm</label>';
    shtml += ' (<i><label><input type="checkbox"  name="'+skey+'['+idchild+'][pa][1][file_batbuoc]"    value="1">Bắt buộc</label>) </i> </div>'; 
    shtml += '<div class="clearfix"></div>'; 
    shtml += '</div>';
    shtml += '</div>'; ///End 11111
    shtml += '<span class="add-traloi-pa btn btn-default">Thêm câu trả lời</span>';
    shtml += '</li>';

    parent_fieldset.find('>ul').append(shtml);              
})













//Thêm câu trả lời cho câu hỏi phụ
$(document).on('click','.add-traloi-pa', function () { 
    var parent_ul = $(this).parents('ul')
    var parent_li = $(this).parents('li.seting-cauhoi-pa')
    var skey = parent_li.find('div.seting-cauhoi-child-pa').attr('data-k')      
    var idchild = parent_li.find('>.seting-cauhoi-pa-one').length + 1
  
    while($('input[name="'+skey+'['+idchild+'][tieude]"]').length > 0){       
        idchild += 1
    }
    


    var shtml = '';        
   
    shtml += '<div class="seting-cauhoi-pa-one" id="'+skey+'['+idchild+']">';    
    shtml += '<div class="pull-left margin">Trả lời:</div><input type="text" class="form-control" placeholder="Phương án trả lời..."  name="'+skey+'['+idchild+'][tieude]" value="">';

    shtml += '<span data-key="" class="del-cauhoi-child-pa"><i class="glyphicon glyphicon-trash"></i></span>';

    shtml += '<div class="seting-cauhoi-pa-comment"><label><input type="checkbox"  name="'+skey+'['+idchild+'][comment]"  value="1">Comment</label>';
    shtml += ' (<i><label><input type="checkbox"  name="'+skey+'['+idchild+'][comment_batbuoc]"  value="1">Bắt buộc</label>) </i> </div>';
    shtml += '<div class="seting-cauhoi-pa-file"><label><input type="checkbox" name="'+skey+'['+idchild+'][file]"   value="1">File đính kèm</label>';
    shtml += ' (<i><label><input type="checkbox"  name="'+skey+'['+idchild+'][file_batbuoc]"    value="1">Bắt buộc</label>) </i> </div>'; 
    shtml += '<div class="clearfix"></div>';  
    shtml += '</div>';
   
    parent_li.find('div.seting-cauhoi-child-pa').append(shtml);                
})



//Thêm tên lien ket website
$(document).on('click','.add-lkw', function () { 
    var parent_ = $('ul#lkw')
    var idchild = parent_.find('.seting-gd').length + 1
    while($('input[name="Settings[lien_ket_website]['+idchild+'][url]"]').length > 0){
        idchild += 1
    }
    var shtml = '';    
    shtml += '<li class="ui-state-default seting-gd">'
    shtml += '<div class="seting-gd-child  tailieucongkhai lkw col-md-12">'
    shtml += '<span class="gd-move"><i class="glyphicon glyphicon-move"></i></span>'
    shtml += '<input  placeholder="Đường dẫn"  type="text" class="form-control lkw_url" name="Settings[lien_ket_website]['+idchild+'][url]" value="">'
    shtml += '<input placeholder="Chữ hiển thị" type="text" class="form-control lkw_label" name="Settings[lien_ket_website]['+idchild+'][label]" value="">'
    shtml += '<span data-key="'+idchild+'" class="del-config-gd"><i class="glyphicon glyphicon-trash"></i></span>' 
    shtml += '</div>'
    shtml += '</li>'
    parent_.append(shtml);
    // parent_.remove();
})




//Thêm tên tài liệu công khai
$(document).on('click','.add-config-tl-congkhai', function () { 
    var parent_fieldset = $(this).parents('fieldset')    
    gd = parent_fieldset.attr('data-gd')
    var idchild = parent_fieldset.find('.seting-gd').length + 1
    while($('input[name="Settings[tai_lieu_cong_khai]['+gd+']['+gd+'-'+idchild+'][name]"]').length > 0){
        idchild += 1
    }
    var shtml = '';    
    shtml += '<li class="ui-state-default seting-gd">'
    shtml += '<div class="seting-gd-child tailieucongkhai">'
    shtml += '<span class="gd-move"><i class="glyphicon glyphicon-move"></i></span>'
    shtml += '<input type="text" class="form-control" name="Settings[tai_lieu_cong_khai]['+gd+']['+gd+'-'+idchild+'][name]" value="">'
    
    shtml += '<span data-key="'+gd+'-'+idchild+'" class="del-config-gd"><i class="glyphicon glyphicon-trash"></i></span>' 
    shtml += '</div>'
    shtml += '</li>'
    parent_fieldset.find('>ul').append(shtml);              
})





//Thêm tên tài liệu mới tại cấu hình 
$(document).on('click','.add-config-gd', function () { 
    var parent_fieldset = $(this).parents('fieldset')    
    var setting_key = parent_fieldset.attr('data-key')
    gd = parent_fieldset.attr('data-gd')
    var idchild = parent_fieldset.find('.seting-gd').length + 1
    while($('input[name="Settings['+setting_key+']['+gd+']['+gd+'-'+idchild+'][name]"]').length > 0){        
        idchild += 1
    }    
    var shtml = '';    
    shtml += '<li class="ui-state-default seting-gd">'
    shtml += '<div class="seting-gd-child">'
    shtml += '<span class="gd-move"><i class="glyphicon glyphicon-move"></i></span>'
    shtml += '<input type="text" class="f165 form-control" name="Settings['+setting_key+']['+gd+']['+gd+'-'+idchild+'][name]" value="">'
    shtml += '<input type="text" class="st-stt form-control" name="Settings['+setting_key+']['+gd+']['+gd+'-'+idchild+'][stt]" value="">'
    shtml += '<div class="chungrieng"><label><input type="radio" checked value="1" name="Settings['+setting_key+']['+gd+']['+gd+'-'+idchild+'][type]" />Chung</label><label><input type="radio" value="2" name="Settings['+setting_key+']['+gd+']['+gd+'-'+idchild+'][type]" />Riêng</label></div>';

    shtml += '<input type="checkbox" class="" name="Settings['+setting_key+']['+gd+']['+gd+'-'+idchild+'][status]" value="1" checked style="width: auto;margin: 10px 0 0 0;">';

    shtml += '<span data-key="'+gd+'-'+idchild+'" class="del-config-gd"><i class="glyphicon glyphicon-trash"></i></span>' 
    shtml += '</div>'
    shtml += '</li>'
    parent_fieldset.find('>ul').append(shtml);              
})



//Thay đổi url, title
function titleurl(title = '', url = '') {         
    var obj = { Title: title, Url:  url};
        if (window.history.replaceState) {
           window.history.replaceState(obj, obj.Title, obj.Url);
        }
}

//Thay doi so ban ghi trong tung trang
$(document).on('change','.page-i', function () {     
    var count = $(this).val()
    var url = updateparameter(window.location.href,'t',count)
    window.location.href = url;
})

//Thay doi paramater
function updateparameter(url, key, value) {
  var re = new RegExp("([?|&])" + key + "=.*?(&|$)", "i"),
      separator = url.indexOf('?') !== -1 ? "&" : "?";

  if (url.match(re)) return url.replace(re, '$1' + key + "=" + value + '$2');
  else return url + separator + key + "=" + value;
}


//Ẩn hiện lĩnh vực trong list bộ ngành
$(document).on('click','.hs_bn', function () {     
    var id = $(this).attr('data-bn');
    if(typeof id !== 'undefined'){
        $('.hs_bn'+id).toggleClass('hide')
    }    
})


$('.form-search').bind('heightChange', function(){
        current = $(this)
    alert(current.html())
});


//Ẩn hiện box tìm kiếm nâng cao
function opensearch(a = '') {        
    // $('.'+a+'-search').clone().insertBefore('.'+a+'-index table.admintablelist')

    // $('.tieuchuan-search').clone().insertBefore('.tieuchuan-index table.admintablelist')
    
    $('.'+a+'-index .'+a+'-search .item-search').toggleClass('hide')
}


var ctrlDown = false;
var ctrlKey = 17, f5Key = 116, rKey = 82;

$(document).keydown(function( e ) {
    if( e.keyCode == f5Key ){
        if (confirm('Bạn muốn tải lại trang?')) {         
        }else{                        
            e.preventDefault( );        
        }
    } 
    if( e.keyCode == ctrlKey )
        ctrlDown = true;

    if( ctrlDown && ( e.keyCode == rKey ) )
        if (confirm('Bạn muốn tải lại trang?')) {         
        }else{                        
            e.preventDefault( );        
        }
}).keyup(function(e) {
    if (e.keyCode == ctrlKey)
        ctrlDown = false;
});


//View chuyen sang edit
function viewedit(url = '',idmodal = '', current = '') {
    var parent = $(current).parents('.modal')
    idparent = parent.attr('id')
    if(typeof idparent !== 'undefined'){
        modal = idparent
    }else{
        modal = 'modal'+idmodal
    }        

    loadimg()
    $.ajax({
        cache: false,
        url: url,
        type: 'POST',
        success: function (data){
                $('#'+modal)
                .find('.clcontent')
                .html("");

                $('#'+modal).modal('show')                      
                .find('.clcontent')
                .html(data);
                unloadimg()
            },
        error: function () {
            unloadimg()
            alert('Lỗi kết nối, vui lòng tải lại trang')            
        },
    });  
}


//Reload form bkt
function rebkt(id = ''){    
    par = $('.thanhvien-form').parents('.modal')
    if(typeof par !== 'undefined'){
        modal = $(par).attr('id')
        idmodal = modal.replace('modal','')        
        url = '/acp/thanhvien/updatem?id='+id    
        loadimg()
        $.ajax({
            cache: false,
            url: url,
            type: 'POST',
            success: function (data){
                    $('#modal'+idmodal)
                    .find('#modalContent')
                    .html("");

                    $('#modal'+idmodal).modal('show')                      
                    .find('#modalContent')
                    .html(data)
                },
            error: function () {
                unloadimg()
                alert('Lỗi kết nối, vui lòng tải lại trang')            
            },
        });  
    }    
}


//Reload form du an hien tai
function retieuchuan(id = '',modal = ''){
    url = '/acp/tieuchuan/updatem?id='+id
    // alert(url)

    loadimg()
    $.ajax({
        cache: false,
        url: url,
        type: 'POST',
        success: function (data){
                $('#modal'+modal)
                .find('#modalContent'+modal)
                .html("");

                $('#modal'+modal).modal('show')                      
                .find('#modalContent'+modal)
                .html(data)
                unloadimg()
            },
        error: function () {
            unloadimg()
            alert('Lỗi kết nối, vui lòng tải lại trang')            
        },
    });  
}



//Reload form du an hien tai
function reduan(idduan = '',giaidoan = '',groupduan = '',url = ''){
    if(giaidoan == 0 || giaidoan == 1){
        giaidoan = ''
        tab = ''
    }else{
        if(giaidoan == ''){
            giaidoan = '2'
        }
        tab = '2'
    }
    if(url  == ''){
        url = '/acp/duan/updatem?id='+idduan
    }else{
        url = url+idduan
    }
    

    if(tab != '') url += '&showtab='+tab
    if(giaidoan != '') url += '&tabgiaidoan='+giaidoan
    if(groupduan != '') url += '&tabgda='+groupduan

    
    loadimg()
    $.ajax({
        cache: false,
        url: url,
        type: 'POST',
        success: function (data){
                $('#modal')
                .find('#modalContent')
                .html("");

                $('#modal').modal('show')                      
                .find('#modalContent')
                .html(data)

                unloadimg()
            },
        error: function () {
            unloadimg()
            alert('Lỗi kết nối, vui lòng tải lại trang')            
        },
    });  
}


//Add tab groupduan
$(document).on('click','.addmulti', function () {      
    if (confirm('Bạn tạo thêm nhóm cho dự án?')) {    
        loadimg()
        current = $(this);
        parent = $(this).parent();
        p_parent = $(this).parent().parent();

        groupduan = current.attr('data-gda');
        idduan = current.attr('data-da');
       
        $.ajax({
            cache: false,
            url: '/acp/duan/addgroupduan?da='+idduan+'&gda='+groupduan,
            type: 'POST',
            success: function (data){                    
                    popthanhcong()                    
                    reduan(idduan,'2',groupduan)
                },
            error: function () {
                popthatbai();              
            },
        });
    }else{
        return false
    }
});



//Add các giai đoạn quốc tế
$(document).on('click','.addmultiqt', function () {      
    if (confirm('Bạn tạo thêm giai đoạn góp ý TC QT?')) {    
        loadimg()
        current = $(this);
        parent = $(this).parent();
        p_parent = $(this).parent().parent();

        groupduan = current.attr('data-gda');
        idduan = current.attr('data-da');
       
        $.ajax({
            cache: false,
            url: '/acp/duan/addgroupduan?da='+idduan+'&gda='+groupduan,
            type: 'POST',
            success: function (data){                    
                    popthanhcong()                    
                    reduan(idduan,'2',groupduan)
                },
            error: function () {
                popthatbai();              
            },
        });
    }else{
        return false
    }
});



//Xoa tab groupduan
$(document).on('click','.r_tab', function () {   
    if (confirm('Tất cả dữ liệu của Nhóm Dự án này sẽ bị xóa. \n Bạn chắc chắn muốn xóa?')) { 
        loadimg()
        current = $(this);
        parent = $(this).parent();
        p_parent = $(this).parent().parent();

        groupduan = current.attr('data-gda');
        idduan = current.attr('data-da');
       
        $.ajax({
            cache: false,
            url: '/acp/duan/removegroupduan?da='+idduan+'&gda='+groupduan,
            type: 'POST',
            success: function (data){            
                    popthanhcong()
                    groupduan = parseInt(groupduan) - 1
                    reduan(idduan,'2',groupduan)
                },
            error: function () {
                popthatbai();          
            },
        });
    }
});

 
//Xu ly fix head trien khai
function headscroll() {
    if($('#duan-form').length ){
        if($("#duan-form>.nav.nav-tabs" ).position() !== 'undefined'){
            if($("#duan-form>.nav.nav-tabs" ).position().top < -10){
                $(".tab>.nav.nav-tabs").addClass('fix-title-1')
                $(".tab>.tab-content .nav.nav-tabs").addClass('fix-title-2')
                $(".tab>.tab-content .tab-content").addClass('fix-content-1')
                
            }else{
                $(".tab>.nav.nav-tabs").removeClass('fix-title-1')
                $(".tab>.tab-content .nav.nav-tabs").removeClass('fix-title-2')
                $(".tab>.tab-content .tab-content").removeClass('fix-content-1')
            }
        }
    }
}


//Add thêm lịch gop y, lich hop
function addmore(url = '',giaidoan = '',groupduan = '') { 
    var id = parseInt($('.addmore-'+giaidoan+'-'+groupduan).find('.addmorechild').length)
    $.ajax({
        cache: false,
        url: url+'&gduan='+groupduan+'&id='+id,
        type: 'POST',               
        success: function (data) {
            $('.addmore-'+giaidoan+'-'+groupduan).append('<div class="addmorechild child-'+giaidoan+'-'+groupduan+'-'+id+'">'+data+'</div>');
            setTimeout( function(){ 
                $('.addmore-'+giaidoan+'-'+groupduan+' form').remove();
                $('.addmore-'+giaidoan+'-'+groupduan+' script').remove();
            }, 300);  
        },
        error: function () {             
        }
    });
}


//Xoa add more
$(document).on('click','.daddmore', function () {  
    if (confirm('Bạn chắc chắn muốn xóa?')) { 
        $(this).parents('.addmorechild').remove();
    }
});


// Xu ly giờ họp
$(document).on('change','.hour_', function(){
    var gio = parseInt($(this).val().split(':')['0'])        
    var phut = parseInt($(this).val().split(':')['1']) 
    if($(this).parents('.bootstrap-timepicker.input-group').find('>.bootstrap-timepicker-widget.dropdown-menu.open').length > 0 ){                                    
        if(gio < 8){
            alert('Giờ họp quá sớm, vui lòng chọn lại')
            $(this).val('08:'+phut)
        }

        if(11 <= gio && gio < 13){
            alert('Giờ nghỉ trưa, vui lòng chọn lại')
            $(this).val('10:'+phut)
        }

        if(gio >= 16){
            alert('Giờ họp quá muộn, vui lòng chọn lại')
            $(this).val('15:'+phut)
        }
    }
})




//Change ngay
$(document).on('change','.time_', function () {  
    idstring = $(this).attr('id');
    type =  idstring.split('-')['0']; //batdau, ketthuc
    giaidoan = idstring.split('-')['1'];
    groupduan = idstring.split('-')['2'];    
    id = idstring.split('-')['3']
    timeketthuc = $('.time_gd_'+giaidoan+'>span.ketthuc').text();
    time = $(this).val();
    if(type == 'lichhopdukien'){
        s_input = '<input type="text" class="hide" name="Duan['+type+']['+giaidoan+']['+groupduan+']['+id+'][td]" value="1"/></span>'
    }else{
        s_input = '<input type="text" class="hide" name="Duan['+type+']['+giaidoan+']['+groupduan+'][td]" value="1"/></span>'
    }
    sosanhlich(this,time,timeketthuc,'Trễ so với kế hoạch',giaidoan+groupduan+'trekh',s_input); 
})

//Chon Ngay batdau lay y kien
// $(document).on('change','.time_ykien_bd', function () {  
//     idstring = $(this).attr('id');
//     giaidoan = idstring.split('-')['1']; 
//     groupduan = idstring.split('-')['2'];
//     // timeketthuc = $('#duan-ketthuclayykien-'+giaidoan+'-'+groupduan+'-time').val() 
//     timeketthuc = $('#ketthuclayykien-'+giaidoan+'-'+groupduan).val()
//     time = $(this).val(); 
//     s_input = '<input type="text" class="hide" name="Duan['+type+']['+giaidoan+']['+groupduan+'][td]" value="2"/></span>'   
//     console.log('.time_ykien_bd')
//     sosanhlich(this,time,timeketthuc,'Phải trước ngày kết thúc',giaidoan+groupduan+'somhonketthuc',s_input,giaidoan+groupduan+'muonhonbatdau');
// })



//Chon Ngay ket thuc lay y kien
// $(document).on('change','.time_ykien_kt', function () {  
//     idstring = $(this).attr('id');
//     giaidoan = idstring.split('-')['1']; 
//     groupduan = idstring.split('-')['2'];
//     // timebatdau = $('#duan-batdaulayykien-'+giaidoan+'-'+groupduan+'-time').val();   
//     timebatdau = $('#batdaulayykien-'+giaidoan+'-'+groupduan).val();   
//     time = $(this).val(); 
//     s_input = '<input type="text" class="hide" name="Duan['+type+']['+giaidoan+']['+groupduan+'][td]" value="2"/></span>'   
//      console.log('.time_ykien_kt')
//     sosanhlich(this,timebatdau,time,'Phải sau ngày bắt đầu',giaidoan+groupduan+'muonhonbatdau',s_input,giaidoan+groupduan+'somhonketthuc');
// })

//Chon ngay hop
$(document).on('change','.time_hop', function () {  
    idstring = $(this).attr('id')
    giaidoan = idstring.split('-')['1']
    groupduan = idstring.split('-')['2']
    id = idstring.split('-')['3']
    // timegopyketthuc = $('#duan-ketthuclayykien-'+giaidoan+'-'+groupduan+'-time').val()
    timegopyketthuc = $('#ketthuclayykien-'+giaidoan+'-'+groupduan).val()
    time = $(this).val();
    s_input = '<input type="text" class="hide" name="Duan['+type+']['+giaidoan+']['+groupduan+']['+id+'][td]" value="2"/></span>'  
    sosanhlich(this,timegopyketthuc,time,'Phải sau thời gian lấy ý kiến',giaidoan+groupduan+id+'saulayykien',s_input);
})


//Function thông báo lỗi ngày
function sosanhlich(curent,time,timesosanh,noidung = '',clas = '',s_input = '', clas_thamchieu = '',days = 1){
    if(isEmpty(time)){
        return false;
    }
    if(isEmpty(timesosanh)){
        return false;
    }
    parent =  $(curent).parent().parent();
    

    if(time != ''){
        date = time.split('-');
        time = date['1'] + '/' + date['0'] + '/' +  date['2']; //CHuyen ve thang/ngay/nam

        date = timesosanh.split('-');
        timesosanh = date['1'] + '/' + date['0'] + '/' +  date['2'];

        //days: 0 tức mặc định
        //days: 1 tức giảm 1 ngày
        //days: 2 tức giảm 2 ngày       
        days = (86400000 * days)
        startDate = Date.parse(timesosanh)  + days
        endDate = Date.parse(time)    


        if(clas_thamchieu != ''){
            $('span.'+clas_thamchieu).parent().parent().find('input.bg-red-gradient').removeClass('bg-red-gradient')
            $('span.'+clas_thamchieu).remove()
        }

        if (startDate > endDate) {
            parent.find('.'+clas).remove(); 
            
        } else { 
            if(parent.find('.trekehoach').length > 0){
                if(parent.find('.'+clas).length > 0){
                    parent.find('.'+clas).html(noidung+s_input);
                }else{                    
                    parent.find('.trekehoach').html('<span class="'+clas+'">'+noidung+s_input+'</span>');
                }                
            }else{
                parent.append('<div class="trekehoach"><span class="'+clas+'">'+noidung+s_input+'</span></div>');    
            }            
        }      
    }else{
        parent.find('.'+clas).remove();
    }  

    if(parent.find('.trekehoach>span').length > 0){                               
        $(curent).addClass('bg-red-gradient');
    }else{
        $(curent).removeClass('bg-red-gradient');
    }
    
}



// Chuyển chuỗi kí tự (string) sang đối tượng Date()
function parseDate(str) {
  var mdy = str.split('-');
  return new Date(mdy[2], mdy[1], mdy[0]);
}



//Dinh kem file sendmail 
$(document).on('change','.sendmailfile', function () {  
    var file = $(this).val();
    if(file == ''){
        if($('.sendmailfile').length > 1){
            $(this).remove();
        }
        return false;
    }else{
        $(this).parent().append('<input type="file" name="sendfileduthao[]" class="sendmailfile" value="" multiple accept="">');            
    }
})


/////////////Upfile du thao trong giai doan
$(document).on('click','.upfileduthao', function () {  
    var idtc = $(this).attr('data-id');
    var giaidoan = $(this).attr('data-gd');
    var groupduan = $(this).attr('data-gda');
    var keyword = $(this).attr('data-keyword');

    $('#input_fileduthao_idtieuchuan').val(idtc);
    $('#input_fileduthao_giaidoan').val(giaidoan);
    $('#input_fileduthao_groupduan').val(groupduan);
    $('#input_fileduthao_keyword').val(keyword);
    
    $('#input_fileduthao').click();
    return false;
});

$(document).on('change','#input_fileduthao', function () { 
    if($(this).val() == '') return false;    
    loadimg();
    setTimeout( function(){         
        if($('#input_fileduthao').parent().hasClass('has-error')){
            alert($('.has-error>.help-block').text());
            unloadimg();
            return false;
        }else{                
            var formData = new FormData(document.querySelector('form#fileduthao-form'));
            $.ajax({
                url: '/acp/fileduthao/createm',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function (data) {
                    if(data.status == 1){                                        
                        var idduan = $('span#duan-da_id').text(); 
                        var gd = $('#input_fileduthao_giaidoan').val(); 
                        var gda = $('#input_fileduthao_groupduan').val();
                        var idtieuchuan = $('#input_fileduthao_idtieuchuan').val();

                        var tcqt = $('#input_tcqt').val();
                        
                        if(tcqt == '1'){
                            re_tcqt = '/acp/tieuchuanquocte/updatem?id='
                        }else{
                            re_tcqt = ''
                        }

                        if($('.tieuchuan-update').length > 0){                            
                            retieuchuan(idtieuchuan,'13')
                            if(isEmpty(idtieuchuan)){
                                reduan(idduan,gd,gda,re_tcqt)
                            }else{
                                reduan(idduan,'0',gda,re_tcqt)
                            }
                        }else{                            
                            reduan(idduan,gd,gda,re_tcqt)
                        }

                        popthanhcong();
                    }else{
                        popthatbai();       
                    }  
                    $('#input_fileduthao').val('');                           
                },
                error: function () {
                    popthatbai(); 
                    $('#input_fileduthao').val('');                   
                }
            });
        }
    }, 1000);
    
    return false;
});



//Update tai lieu cho cả bộ hồ sơ
$(document).on('click','.upfilebohoso', function () { 
    var idduan = $('span#duan-da_id').text();   
    $('#bohoso_da_id').val(idduan);    
    $('#bohoso_uploadfile').click();
    return false;    
});

$(document).on('change','#bohoso_uploadfile', function () { 
    if($(this).val() == '') return false; 

    if(!confirm('Các file tài liệu sẽ được thêm mới, hoặc sẽ được cập nhật đè lên các phiên bản cũ hơn. Xác nhận?')){
        return false
    }

    loadimg();
    setTimeout( function(){         
        if($('#bohoso_uploadfile').parent().hasClass('has-error')){
            alert($('.has-error>.help-block').text());
            unloadimg();
            $('#bohoso_uploadfile').val('');                     
            return false;
        }else{                
            var formData = new FormData(document.querySelector('form#form_bohoso-form'));
            $.ajax({
                url: '/acp/duan/uploadbohoso',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function (data) {
                    if(data.status == 1){                                        
                        var idduan = $('span#duan-da_id').text();
                        reduan(idduan)                        
                        popthanhcong();
                        // if(data.errors != ''){
                            // alert('Các file định dạng tên không phù hợp: \n'+data.errors)
                            if(data.success != ''){
                                msg_thanhcong = '<div><b>+ Cập nhật thành công: '+data.success+' file.</b></div>'
                            }else{
                                msg_thanhcong = ''
                            }

                            if(data.errors != ''){
                                msg_thatbai = '<div></p><b>+ Các file định dạng tên không phù hợp:</b></p>'+data.errors+'</div>'
                            }else{                                
                                msg_thatbai = ''
                            }

                            $('#modal2')                  
                            .find('#modalContent2')
                            .html("");

                            $('#modal2').modal('show')                      
                            .find('#modalContent2')
                            .html('<div><h1>Thông báo</h1></div><br/>'+msg_thanhcong +'<br/>'+ msg_thatbai);
                            unloadimg();  
                        // }
                    }else{
                        popthatbai();       
                    }      
                    $('#bohoso_uploadfile').val('');                       
                },
                error: function () {
                    popthatbai(); 
                    $('#bohoso_uploadfile').val('');                     
                }
            });
        }
    }, 1000);
    
    return false;
});









//Update tai lieu trong giai doan
$(document).on('click','.upfiletailieu', function () { 
    var idduan = $(this).attr('data-id');
    var giaidoan = $(this).attr('data-gd');
    var groupduan = $(this).attr('data-gda');
    var keyword = $(this).attr('data-type');
    $('#input_tailieu_idduan').val(idduan);
    $('#input_tailieu_giaidoan').val(giaidoan);
    $('#input_tailieu_groupduan').val(groupduan);
    $('#input_tailieu_keyword').val(keyword);
    $('#input_tailieu').click();
    return false;    
});


$(document).on('change','#input_tailieu', function () { 
    if($(this).val() == '') return false;    
    loadimg();
    setTimeout( function(){         
        if($('#input_tailieu').parent().hasClass('has-error')){
            alert($('.has-error>.help-block').text());
            unloadimg();
            return false;
        }else{                
            var formData = new FormData(document.querySelector('form#tailieu-form'));
            $.ajax({
                url: '/acp/tailieu/createm',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function (data) {
                    if(data == 1){                                        
                        var idduan = $('span#duan-da_id').text(); 
                        var gd = $('#input_tailieu_giaidoan').val(); 
                        var gda = $('#input_tailieu_groupduan').val();

                        var tailieucongkhai = $('span#tailieucongkhai').text();
                        if(tailieucongkhai == 1){
                            url = 'congkhai';
                        }else{
                            url = 'index';
                        }

                        $.ajax({
                            url: '/acp/tailieu/'+url+'?da='+idduan+'&gd='+gd+'&gda='+gda,
                            type: 'POST',                           
                            success: function (data) {
                                $('.tailieu-'+gd+'-'+gda+'-index').html(data);     
                            },                            
                        });
                        popthanhcong();
                    }else{
                        popthatbai();       
                    }      
                    $('#input_tailieu').val('');                       
                },
                error: function () {
                    popthatbai(); 
                    $('#input_tailieu').val('');                     
                }
            });
        }
    }, 1000);
    
    return false;
});





$(document).on('click','.opentab', function () { 
    var child = $(this).attr('data-key');
    span = $(this).find('>span');        
    if(span.hasClass('glyphicon-chevron-down')){
        $('.'+child).addClass('hide');
        span.removeClass('glyphicon-chevron-down').addClass('glyphicon-chevron-up');
    }else{                        
        $('.'+child).removeClass('hide');
        span.removeClass('glyphicon-chevron-up').addClass('glyphicon-chevron-down');
    }
})


$(document).on('click','.open-search', function () { 
    span = $(this).find('>span');        
    if(span.hasClass('glyphicon-chevron-down')){
        $(this).parent().find('.content-search').removeClass('hide');
        span.removeClass('glyphicon-chevron-down').addClass('glyphicon-chevron-up');
    }else{
        $(this).parent().find('.content-search').addClass('hide');
        span.removeClass('glyphicon-chevron-up').addClass('glyphicon-chevron-down');
    }
})

$(document).on('click','.loadtieuchuan', function () { 
    current = $(this);
    idelement = current.attr('id');
    idkehoach = current.attr('data-key');
    url = '/acp/tieuchuan/loadtieuchuan?id='+ idkehoach;        
    if(current.hasClass('glyphicon-chevron-down')){        
        if(url !== '' && $('.tc-chitiet-'+idkehoach+' table').length == 0){    
            loadimg();    
            $.ajax({
                cache: false,
                url: url,
                type: 'POST',
                success: function (data) { 
                    $('.tc-chitiet-'+idkehoach).html(data);
                    unloadimg();  
                    
                },
                error: function () {
                    popthatbai();              
                }
            });
        }
        $('.'+idelement).removeClass('hide');
        current.removeClass('glyphicon-chevron-down').addClass('glyphicon-chevron-up');
    }else{
        $('.'+idelement).addClass('hide');
        current.removeClass('glyphicon-chevron-up').addClass('glyphicon-chevron-down');
    }

});


function openkehoach(url = '') {     
    if(url !== ''){    
        loadimg();    
        $.ajax({
            cache: false,
            url: url,
            type: 'POST',
            success: function (data) { 
                $('section.content').html(data);
                unloadimg();  
            },
            error: function () {
                popthatbai();              
            }
        });
    }
}; 


$(document).on('click','.backkehoach', function () {   
    loadimg();    
    $.ajax({
        cache: false,
        url: '/acp/kehoachnam',
        type: 'POST',
        success: function (data) { 
            $('section.content').html(data);
            $('.content-header h1').html('Danh sách kế hoạch');
            
            document.title = 'Danh sách kế hoạch';
            window.history.pushState("acp", "title", "/acp/kehoachnam");            

            unloadimg();  
        },
        error: function () {
            popthatbai();              
        }
    });
}); 
$(document).on('click','.backduan', function () {   
    loadimg();    
    $.ajax({
        cache: false,
        url: '/acp/duan',
        type: 'POST',
        success: function (data) { 
            $('section.content').html(data);
            $('.content-header h1').html('Danh sách dự án');
            
            document.title = 'Danh sách dự án';
            window.history.pushState("acp", "title", "/acp/duan");

            unloadimg();  
        },
        error: function () {
            popthatbai();              
        }
    });
}); 


$(document).on('click','.backsearch', function () {   
    loadimg();    
    $.ajax({
        cache: false,
        url: '/acp/kehoachnam/searchtieuchuan',
        type: 'GET',
        success: function (data) { 
            $('section.content').html(data);
            $('.content-header h1').html('Tìm kiếm');
            unloadimg();  
        },
        error: function () {
            popthatbai();              
        }
    });
}); 




$(document).on('fileuploaded', '#import-tieuchuan-form', function(event, data, previewId, index) {  
    var response = data.response;  

    if(response.status == 0){
        $('#kq_create').html('<br/><b class="btn-sm alert-danger">Import không thành công. Lỗi tại dòng: ' + response.line +'</b>');
    }else{
        $('#kq_create').html('<br/><b class="btn-sm alert-success">Kết quả: </b> (Thêm mới: <b>' + response.count_create +'</b> tiêu chuẩn, Tùy chọn cập nhật: <b>' + response.count_update +'</b> tiêu chuẩn)');
        // $('#kq_update').html('<b>Cập nhật: ' + response.count_update +'</b>');
        if(response.table_create){
            $('#ds_create').html('<b>Danh sách thêm mới</b>' + response.table_create);
        }

        if(response.table_update){
            $('#ds_update').html('<b>Danh sách các Tiêu chuẩn có dữ liệu thay đổi</b><p>Bạn có thể chọn 1 hoặc tích chọn tất cả, sau đó ấn "Cập nhật theo lựa chọn" để cập nhật dữ liệu mới.' + response.table_update);
        }
    }
});


$.fn.modal.prototype.constructor.Constructor.DEFAULTS.backdrop = 'static';


$(document).on('hidden.bs.modal','.modal', function () {       
    if($(this).hasClass('mcl')) $(this).remove();

    if($('.modal.fade.in').length > 0){
        $('body').addClass('modal-open');        
    }
});


function maddquyetdinh(current,url = '',id = '') {  
    loadimg();
    var tk = $(current).parents('form').find('input[name=_csrf-backend]').val();
    $.ajax({
        cache: false,
        url: url,
        type: 'POST',
        data: {'tk': tk},
        success: function (data) {             
            $('#modal'+id)                  
            .find('#modalContent'+id)
            .html("");

            $('#modal'+id).modal('show')                      
            .find('#modalContent'+id)
            .html(data);
            unloadimg();  
        },
        error: function () {
            popthatbai();              
        }
    });
}

function maddtieuchuan(current,url = '',id = '') {  
    loadimg();
    var tk = $(current).parents('form').find('input[name=_csrf-backend]').val();
    data = {
        'tk' : tk,
    }
    openmodal(url,id,data);
}



function openmodal(url = '',id = '', data = '', new_modal = '') {  

    var zindex = $('body').attr('data-z')
    if(typeof zindex === 'undefined') {
        zindex = 1050
    }    
    zindex = parseInt(zindex) + 1
    $('body').attr('data-z',zindex)


    loadimg();
     $.ajax({
        cache: false,
        url: url,
        data: data,
        type: 'POST',
        success: function (data){            
            if($('#modal'+id).css('display') === 'block' && new_modal != 'not_new_modal'){
                var rd = Math.floor(Math.random() * 10000) + 1;

                $('#modal'+id).clone().appendTo('body').attr('id','modal'+id+'-cl-'+rd).addClass('mcl');

                $('#modal'+id+'-cl-'+rd)
                .find('#modalContent'+id)
                .html("");

                $('#modal'+id+'-cl-'+rd).modal('show')                      
                .find('#modalContent'+id)
                .html(data);

                $('#modal'+id+'-cl-'+rd).css('z-index', zindex);
            }else{
                $('#modal'+id)
                .find('#modalContent'+id)
                .html("");

                $('#modal'+id).modal('show')                      
                .find('#modalContent'+id)
                .html(data);

                $('#modal'+id).css('z-index', zindex);
            }
            unloadimg();  
        },
        error: function () {
            popthatbai();              
        }
    });
}

function findclosemodal(current) {
    par = $(current).parents('.modal')
    if(typeof par !== 'undefined'){
        modal = $(par).attr('id')
        idmodal = modal.replace('modal','')
        closemodal(idmodal)
    }
}


function closemodal(idmodal) {    
    setTimeout( function(){ 
        $('#modal'+idmodal).modal('hide');
    }, 500);
    
    setTimeout( function(){             
         $('#modal'+idmodal+' .modal-body div').html('');
    }, 1000);
    
}


function rload(element = '', url = window.location.href, idmodal = '', close = '') { 
    id = '';
    loadimg(); 
    if(close !== 'noclose'){
        closemodal(idmodal);
    }

     $.ajax({
        cache: false,
        url: url,
        type: 'POST',               
        success: function (data) {            
            if(element == ''){
                $('section.content').html(data);
            }else{
                $('.'+element).parent().html(data);
            }
            unloadimg();
        },
        error: function () {
            popthatbai();                       
        }
    });
}

function del(url = '',idmodal = '',urlreload = ''){
    if (confirm('Bạn chắc chắn muốn xóa?')) { 
        loadimg();                
        $.ajax({
            cache: false,
            url: url,
            type: 'POST',               
            success: function (data) {
                if(data == 1){                                        
                    rload('',urlreload);
                    popthanhcong();   
                }else{
                    if(data.status == 1){
                        rload('',urlreload);
                        popthanhcong();  
                    }else{
                        if(isEmpty(data.data)){
                        }else{
                            alert(data.data)
                        }
                        popthatbai();
                    }                    
                }                                                  
            },
            error: function () {
                popthatbai();       
            }
        });
    }
}








function loadimg(){
    $('.loading-indicator-wrapper').removeClass('loader-hidden');
    // setTimeout( function(){
    //     unloadimg();
    // }, 60000);  
}
function unloadimg(){
    setTimeout( function(){ 
        $('.loading-indicator-wrapper').animate({
            opacity: 0,    
        }, 300,function () {
            $('.loading-indicator-wrapper').addClass('loader-hidden');    
            $('.loading-indicator-wrapper').css('opacity',1);
        });
    }, 300);        
}



function popthanhcong() {
    var sop = $('.popupalert .thanhcong').length;               
    $('.popupalert').append('<div class=thanhcong'+sop+'><div class="thanhcong">Thao tác thành công!</div></div>');                               
    $('.thanhcong'+sop+'').fadeTo(2000, 500).slideUp(500, function(){
        $('.thanhcong'+sop+'').remove();        
    });
    unloadimg();
}


function popthatbai() {
    var sop = $('.popupalert .thatbai').length;                         
    $('.popupalert').append('<div class=thatbai'+sop+'><div class="thatbai">Thao tác thất bại!</div></div>');   
    $('.thatbai'+sop+'').fadeTo(1000, 500).slideUp(500, function(){
        $('.thatbai'+sop+'').remove();        
    });
    unloadimg();
}