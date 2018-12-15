(function ($) {

    var PheLieu = function () {

    }

    //them phế liệu
    $(document).on('click', '.btn-them-phe-lieu', function () {
        var table = $('.table_phe_lieu');
        var count = table.find('.phe_lieu_row').length;
        var html = table.find('.phe_lieu_row:first-child').clone();
        html.find('td:eq(0)').html(count + 1);

        // reinit select
        var select = html.find('select');
        select.parent().find('span').remove();
        var select_attributes = $.map(select.get(0).attributes, function(item) {
            return item.name.toString().toLowerCase();
        });
        $.each(select_attributes, function(index, item) {
            if (item == 'name' || item == 'id') {} else {
                select.removeAttr(item);
            }

        });

        var select_clone_name = select.attr('name');
        var select_clone_id = select.attr('id');
        console.log(select_clone_name);
        select.attr('name', select_clone_name.replace('[0]', '[' + (count) + ']'));
        select.attr('id', select_clone_id.replace('-0-', '-' + (count) + '-'));
        select.val('');

        html.attr('data-phelieurow', count);

        html.find("input").each(function () {
            this.name = this.name.replace('[0]', '[' + (count) + ']');
            this.id = this.id.replace('-0-', '-' + (count) + '-');
            this.value = '';
        });
        select.select2({
            allowClear: true,
            language: "vi",
            placeholder: "Chọn phế liệu",
            theme: "krajee",
            width: "100%"
        });


        table.find('> tbody').append(html);

    });

    //xoa phe lieu
    $(document).on('click', '.remove_phe_lieu_row', function () {
        var _this = this, table = $('.table_phe_lieu');
        krajeeDialog.confirm("Bạn có chắc muốn xóa mục này?", function (result) {
            if (result) {
                if (table.find('.phe_lieu_row').length == 1) {
                    krajeeDialog.alert('Không thể xóa vì nhập phải có ít nhất một phế liệu');
                } else {
                    if ($(_this).closest('tr').data('phelieurow') == 0) {
                        krajeeDialog.alert('Không thể xóa dòng này');
                    } else {
                        $(_this).closest('tr').remove();
                        table.find('.phe_lieu_row').each(function (i) {
                            $(this).find('td:first-child').text(i+1);
                        });
                    }


                }

            }
        });
    });

})(jQuery);