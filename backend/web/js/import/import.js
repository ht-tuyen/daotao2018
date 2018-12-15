

(function ($) {

    $(document).ajaxStart(function () {
        $(".loading-indicator-wrapper").addClass('loader-visible').removeClass('loader-hidden');
    }).ajaxStop(function () {
        $(".loading-indicator-wrapper").removeClass('loader-visible').addClass('loader-hidden');
    });
    var import_type = $('#import-import_type');
    var import_thanh_tien = $('.wrapper-import-thanh-tien');
    var import_tien_thue = $('.wrapper-import-tien-thue');
    var import_tong_tien = $('.wrapper-import-tongtien');
    // define object import/export
    var ImportExport = function () {

        // remove format thousand
        this.removeFormat = function (string) {
            if (string && Object.prototype.toString.call(string) === '[object String]')
                string = string.replace(/,/g, '');
            string = parseFloat(string);
            return string;
        };

        this.numberInput = function () {

            $('.numberOnly').toArray().forEach(function (field) {
                new Cleave(field, {
                    numeral: true,
                    numeralThousandsGroupStyle: 'thousand',
                    numeralDecimalScale: 2
                });
            });

            $('.numberOnlyFull').toArray().forEach(function (field) {
                new Cleave(field, {
                    numeral: true,
                    numeralThousandsGroupStyle: 'thousand',
                    numeralDecimalScale: 12
                });
            });

            $('.integerOnly').toArray().forEach(function (field) {
                new Cleave(field, {
                    numeral: true,
                    numeralThousandsGroupStyle: 'thousand',
                    numeralDecimalScale: 0
                });
            });

        };


        this.hideNVLColumnsForRow = function (wrapper) {

            var unit_column = wrapper.find('input[name*="[unit]"]').closest('td');
            var unit_price_column = wrapper.find('input[name*="[unit_price]"]').closest('td');
            var tien_column = wrapper.find('input[name*="[tien]"]').closest('td');
            var table_nguyen_vat_lieu = $('.table_nguyen_vat_lieu');

            if (import_type.val() == 1) {
                unit_column.addClass('hide');
                unit_price_column.addClass('hide');
                tien_column.addClass('hide');

                table_nguyen_vat_lieu.find('th:eq('+unit_column.index()+')').addClass('hide');
                table_nguyen_vat_lieu.find('th:eq('+unit_price_column.index()+')').addClass('hide');
                table_nguyen_vat_lieu.find('th:eq('+tien_column.index()+')').addClass('hide');
                import_thanh_tien.addClass('hide');
                import_tien_thue.addClass('hide');
                import_tong_tien.addClass('hide');
            } else {
                unit_column.removeClass('hide');
                unit_price_column.removeClass('hide');
                tien_column.removeClass('hide');
                table_nguyen_vat_lieu.find('th:eq('+unit_column.index()+')').removeClass('hide');
                table_nguyen_vat_lieu.find('th:eq('+unit_price_column.index()+')').removeClass('hide');
                table_nguyen_vat_lieu.find('th:eq('+tien_column.index()+')').removeClass('hide');
                import_thanh_tien.removeClass('hide');
                import_tien_thue.removeClass('hide');
                import_tong_tien.removeClass('hide');
            }

        };
        this.initSelectVNLchanged = function (el) {
            var $parent = el.closest('tr');
            var $_this = this;
            $.ajax({
                'url': link_getTonKho,
                'type': 'get',
                'dataType': 'json',
                'data': {'id': el.val()},
                'success': function (data) {
                    if (!$.isEmptyObject(data)) {
                        $parent.find('input[name*="[unit]"]').val(data.donvi);
                        $parent.find('input[name*="[unit_price]"]').val(data.dongia);
                        $parent.find('input[name*="[title]"]').val(data.title);
                        $parent.find('input[name*="[type]"]').val(data.type);
                        $parent.find('input[name*="[tonkho_id]"]').val(data.id);
                    }
                    if ($parent.find('input[name*="[title]"]').val().trim() === '')
                        $parent.find('input[name*="[title]"]').val(el.val());
                    $_this.initNVLRow($parent, true);
                    $_this.numberInput();
                }
            });

        };

        this.initNVLRow = function (wrapper, $changed) {
            var $_this = this, quantity = $('input[name*="quantity"]', wrapper),
                unit_price = $('input[name*="unit_price"]', wrapper),
            price = $('input[name*="tien"]', wrapper);

            if (typeof $changed == 'undefined') $changed == false;

            quantity.add(unit_price).bind('change', function () {
                var tien = $_this.removeFormat(quantity.val()) * $_this.removeFormat(unit_price.val());
                price.val(tien).formatCurrency();
                $_this.updateThanhTien();
            });

            if ($changed == true) {
                var tien = $_this.removeFormat(quantity.val()) * $_this.removeFormat(unit_price.val());
                price.val(tien).formatCurrency();
                $_this.updateThanhTien();
            }
            // $_this.hideNVLColumnsForRow(wrapper);
        };

        this.updateThanhTien = function () {
            var $_this = this, tongTien = 0,
                thanhtien = $('input[name*="import_thanhtien"]'),
                tienthue = $('input[name*="import_tienthue"]'),
                finallyTien = $('input[name*="import_tongtien"]')

            ;
            $('.nguyen_vat_lieu_row').each(function () {
                var $this = $(this);
                var price = $('input[name*="tien"]', $this);
                var tien = 0;
                if (price.val() != '') {
                    tien = $_this.removeFormat(price.val());
                }
                tongTien += parseInt(tien);
            });

            thanhtien.val(tongTien).formatCurrency();
            tienthue.val(tongTien*10/100).formatCurrency();
            finallyTien.val(tongTien + (tongTien*10/100)).formatCurrency();

            $('#import-import_has_paid_price').val(tongTien).formatCurrency();
        }
    };

    var imex = new ImportExport();

    // init loading
    imex.numberInput();
    $('.nguyen_vat_lieu_row').each(function () {
        var $this = $(this);
        imex.initNVLRow($this);
        imex.hideNVLColumnsForRow($this);
    });

    // init select import or export
    import_type.on('change', function () {
        $('.nguyen_vat_lieu_row').each(function () {
            var $this = $(this);
            imex.hideNVLColumnsForRow($this);
        });
    });

    // init multi select nguyen vat lieu
    $(document).on('change', '.nguyen_vat_lieu_row select', function () {
        imex.initSelectVNLchanged($(this));
    });

    //them nguyen vat lieu
    $(document).on('click', '.btn-them-nguyen-vat-lieu', function () {
        var table = $('.table_nguyen_vat_lieu');
        var count = table.find('.nguyen_vat_lieu_row').length;
        var html = table.find('.nguyen_vat_lieu_row:first-child').clone();
        html.find('td:eq(0)').html(count + 1);

        // reinit select
        var select = html.find('select');
        select.parent().find('span').remove();
        var select_attributes = $.map(select.get(0).attributes, function(item) {
            return item.name.toString().toLowerCase();
        });
        $.each(select_attributes, function(index, item) {
            select.removeAttr(item);
        });

        select.attr('id', 'nguyenvatlieu' + count);
        select.val('');

        select.select2({
            allowClear: true,
            language: "vi",
            placeholder: "Nhập nguyên vật liệu",
            theme: "krajee",
            width: "100%"
        });

        html.attr('data-vatlieurow', count);

        html.find("input").each(function () {
            this.name = this.name.replace('[0]', '[' + (count) + ']');
            this.id = this.id.replace('-0-', '-' + (count) + '-');
            this.value = ''
        });
        imex.hideNVLColumnsForRow(html);
        table.find('> tbody').append(html);

    });

    //xoa nguyen vat lieu
    $(document).on('click', '.remove_nguyen_vat_lieu_row', function () {
        var _this = this, table = $('.table_nguyen_vat_lieu');
        krajeeDialog.confirm("Bạn có chắc muốn xóa mục này?", function (result) {
            if (result) {
                if (table.find('.nguyen_vat_lieu_row').length == 1) {
                    krajeeDialog.alert('Không thể xóa vì xuất nhập phải có ít nhất một nguyên vật liệu');
                } else {

                    if ($(_this).closest('tr').data('vatlieurow') == 0) {
                        krajeeDialog.alert('Không thể xóa dòng này');
                    } else {
                        $(_this).closest('tr').remove();
                        table.find('.nguyen_vat_lieu_row').each(function (i) {
                            $(this).find('td:first-child').text(i+1);
                        });
                        imex.updateThanhTien();
                    }

                }

            }
        });
    });

})(jQuery);
