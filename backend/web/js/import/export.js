(function ($) {

    var import_type = $('select[name*="import_type"]');
    var export_date = $('input[name*="export_date"]');

    $('.export-nhap-xuat').on('click', function () {
        if (export_date.val() == '') {
            alert('Nhập ngày để xuất dữ liệu'); return false;
        }
        window.location.href = link_get_nhap_xuat + "?import_type="+import_type.val()+"&date=" + export_date.val() +"&embedded=false";
    });

    $('.preview-nhap-xuat').on('click', function () {

        if (export_date.val() == '') {
            alert('Nhập ngày để xuất dữ liệu'); return false;
        }
        var url = $(this).data('src') + "?" + encodeURIComponent("import_type="+import_type.val()+"&date=" + export_date.val());
        new_win = $.fancybox.open({
            src: url +"&embedded=true",
            type: 'iframe',
            width: '100%',
            height: "100%",
            margin: [10, 10, 10, 10],
            iframe: {
                css: {
                    width: '100%',
                    height: "100%",
                    margin: [10, 10, 10, 10],
                }
            },
            opts: {
                width: '100%',
                height: "100%",
                margin: [10, 10, 10, 10],
            }
        });

    });

})(jQuery);