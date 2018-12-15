$(function () {
    'use strict';


    var tab_cong_doan = $('#tab7default');

    $('.them_cong_doan').on('click', function () {
        var cong_doan_cloned = $('.cong-doan-clone').clone();
        var item_key = randomNumberFromRange(200,20000);
        var ten = cong_doan_cloned.find('.ten-cong-doan');
        var ty_le = cong_doan_cloned.find('.ty-le-cong-doan');
        cong_doan_cloned.attr('data-key', item_key);
        ten.attr('name', 'Settings[cong_doan_'+item_key+'_ten]');
        ty_le.attr('name', 'Settings[cong_doan_'+item_key+']');
        cong_doan_cloned.removeClass('hide cong-doan-clone');
        tab_cong_doan.find('.list-group').append(cong_doan_cloned);
        tab_cong_doan.find('.sortable').sortable('reload');

        updateListCongDoan();

    });

    $(document).on('click', '.remove_cong_doan_item', function () {
        var $this = $(this);
        $this.closest('.list-group-item').remove();
        updateListCongDoan();
    });

    function updateListCongDoan() {

        var list = tab_cong_doan.find('.list-group-item:not(.cong-doan-clone)').map(function (i, n) {
            return $(n).data('key');
        }).get().join(',');

        $('input[name*="Settings[list_cong_doan]"]').val(list);
    }

    $('.them_trang_thai').on('click', function () {

       var clone_item = $('#tab3default .trang-thai-item').find('li').clone();
       var item_key = randomNumberFromRange(100,10000);
        clone_item.attr('data-key', item_key);
        var spectrum_group = clone_item.find('.spectrum-group');
        var input_source = clone_item.find('.spectrum-source');
        var input_value = clone_item.find('.spectrum-input');
        var select_cong_doan = clone_item.find('select');
        clone_item.find('.ten-input').attr('name', 'Settings[order_status_'+item_key+'_ten]');
        clone_item.find('.ten-input').val('Tên ' + item_key);
        input_source.attr('name', 'ws'+item_key+'-source');
        input_source.attr('id', 'ws'+item_key+'-source');

        select_cong_doan.attr('name', 'Settings[order_status_'+item_key+'_congdoan]');
        input_value.attr('name', 'Settings[order_status_'+item_key+']');
        input_value.attr('id', 'ws' + item_key);
        clone_item.find('input[type="radio"]').attr("name",'Settings[order_status_'+item_key+'_menu]');
        $('#tab3default .list-group').append(clone_item);
        $('#tab3default .sortable').sortable('reload');

        changeOrderStatusPosition();

        input_source.on('change', function () {
            input_value.val($(this).val());
        });
        input_source.spectrum({
            'showInput' : true,
            'showInitial' : true,
            'showPalette' : true,
            'showSelectionPalette' : true,
            'showAlpha' : true,
            'preferredFormat' : 'hex',
            'theme' : 'sp-krajee',
        });
        initRemoveOrderStatusItem();
    });

    initRemoveOrderStatusItem();

    function initRemoveOrderStatusItem() {
        $('.remove_trang_thai_item').on('click', function () {
            var $this = $(this);
            $this.closest('.list-group-item').remove();
            changeOrderStatusPosition();
        })
    }
    function changeOrderStatusPosition() {

        var pos = $('#tab3default .sortable').find('li').map(function (i, n) {
            return $(n).data('key');
        }).get().join(',');

        $('input[name*="Settings[order_status_pos]"]').val(pos);
    }
    function randomNumberFromRange(min,max)
    {
        return Math.floor(Math.random()*(max-min+1)+min);
    }

});