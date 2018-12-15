(function ($) {

    var export_date = $('input[name*="export_date"]');

    $('.export-nhap-phe-lieu').on('click', function () {
        if (export_date.val() == '') {
            alert('Nhập ngày để xuất dữ liệu'); return false;
        }
        window.location.href = link_phe_lieu_export + "?date=" + export_date.val();
    });

})(jQuery);