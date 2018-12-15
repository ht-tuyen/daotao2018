function isValidEmailAddress(emailAddress) {
    var pattern = new RegExp(/^[+a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/i);
    // alert( pattern.test(emailAddress) );
    return pattern.test(emailAddress);
}

var OrderView = function () {
    this.getTongChiPhi = function (elm) {
        var rootFrame = this.getRootCongNoFrame(elm);
        var ip = $('input[name*="TongChiPhi"]', rootFrame);
        return parseFloat(ip.val());
    };

//        this.getTongChiPhi_PhiVanChuyen = function(elm) {
//            var rootFrame = this.getRootCongNoFrame(elm);
//            var ip = $('input[name*="tongChiPhi_PhiVanChuyenPlus"]', rootFrame);
//            return parseFloat(ip.val());
//        };

    this.getTongChiPhi_PhiVanChuyen = function (elm) {
        var rootFrame = this.getRootCongNoFrame(elm);
        var ip = $('input[name*="tongChiPhi_PhiVanChuyenPlus"]', rootFrame);
//            console.log(ip);
        var val = parseFloat(ip.val());
        if (isNaN(val))
            return 0;
        return val;
    };

    this.getTongChiPhi_DaTinhPhanTram = function (elm) {
        var rootFrame = this.getRootCongNoFrame(elm);
        var ip = $('input[name="tongChiPhi_DaTinhPhanTram"]', rootFrame);
        return parseFloat(ip.val());
    };

    this.getRootCongNoFrame = function (elm) {
        if (!elm || elm == undefined || elm == 'undefined')
            return false;

        if ($(elm).hasClass('order_info_congno')) {
            return $(elm);
        }
        return $(elm).closest('.order_info_congno');
    };

    this.getVanChuyenFrame = function (elm) {
        if ($(elm).hasClass('table_vanchuyen'))
            return $(elm);

        return $(elm).closest('.table_vanchuyen');
    };

    this.vanChuyenChange = function (elm) {
        var rootFrame = this.getRootCongNoFrame(elm);
        var vchuyenFrame = this.getVanChuyenFrame(elm);

        if (elm.name.indexOf('vanChuyenTan_Chuyen') != -1) {
            if (elm.value == Number('<?php echo Orderdata::VCHUYEN_CHUYEN ?>')) {
                $('.Orderdata_chuyen', vchuyenFrame).show();
                $('.Orderdata_weight', vchuyenFrame).hide();
            } else {
                $('.Orderdata_chuyen', vchuyenFrame).hide();
                $('.Orderdata_weight', vchuyenFrame).show();
            }
        }
        var type = $('select[name*="vanChuyenTan_Chuyen"] :selected', vchuyenFrame).prop('value');
        var soChuyen = $('input[name*="vanChuyenSoChuyen"]', vchuyenFrame).val();
        var weight = $('input[name*="vanChuyenWeight"]', vchuyenFrame).val();
        var donGia = $('input[name*="vanChuyenDonGia"]', vchuyenFrame).val();

        var chiPhiVanChuyen = 0;
        if (type == Number('<?php echo Orderdata::VCHUYEN_CHUYEN ?>')) {
            chiPhiVanChuyen = soChuyen * donGia;
        } else if (type == Number('<?php echo Orderdata::VCHUYEN_TRONG_LUONG ?>')) {
            chiPhiVanChuyen = weight * donGia;
        }

        $('.vanChuyenChiPhiTxt', vchuyenFrame).html(chiPhiVanChuyen).formatCurrency();
        $('input[name*="vanChuyenChiPhi"]', vchuyenFrame).val(chiPhiVanChuyen);

        console.log(this.getTongChiPhi(elm), chiPhiVanChuyen);
        var tongTriGia1 = this.getTongChiPhi(elm) + chiPhiVanChuyen;
        $('input[name*="tongChiPhi_PhiVanChuyenPlus"]', rootFrame).val(tongTriGia1);
        $('.tongChiPhi_PhiVanChuyenPlusTxt', rootFrame).html(tongTriGia1).formatCurrency();
//            $('.giaTriThuc', rootFrame).html(tongTriGia1).formatCurrency();

        this.tinhChiPhi1SanPham(rootFrame);
    };

    this.tinhChiPhi1SanPham = function (rootFrame) {
        var tongGiaTri = this.getTongChiPhi_PhiVanChuyen(rootFrame);
        var soLuong = numberOnly($('input[name*="amount"]', rootFrame).val());

        if (isNaN(soLuong)) {
            console.error('isNaN so_luong - line <?php echo __LINE__; ?>');
            return false;

        } else if (isNaN(tongGiaTri)) {
            console.error('isNaN tongGiaTri - line <?php echo __LINE__; ?>');
            return false;
        }
        var donGia = tongGiaTri / soLuong;
        $('input.tongChiPhiDonGiaSPView', rootFrame).val(donGia).formatCurrency();
        $('input[name*="tongChiPhiDonGiaSP"][type="hidden"]', rootFrame).val(donGia);

        var phanTramFrame = this.getPhanTramFrame(rootFrame);
        this.tinhLoiNhuan_(phanTramFrame, true);
        return false;
    };

    this.getPhanTramFrame = function (elm) {
        var rootFrame = this.getRootCongNoFrame(elm);
        return $('tbody.tbody_tinh_phan_tram', rootFrame);
    };

    this.tinhLoiNhuan = function (elm) {
        if ($("#check_is_photo").val() == 1)
            var phanTramFrame = elm.closest('tr.tbody_tinh_phan_tram');
        else
            var phanTramFrame = this.getPhanTramFrame(elm);

        var phanTramIp = $('input[name*="tongChiPhiPhanTram"]', phanTramFrame);
        var tongChiPhiDonGiaSPIp = $('input[name*="tongChiPhiDonGiaSP"][type="hidden"]', phanTramFrame);
        var tongChiPhiGiaBanSPIp = $('input[name*="tongChiPhiGiaBanSP"][type="hidden"]', phanTramFrame);
        var tongChiPhiGiaKhachGuiSPIp = $('input[name*="tongChiPhiGiaKhachGuiSP"][type="hidden"]', phanTramFrame);
        var giaKhachGui = parseFloat($('input[name*="tongChiPhiGiaKhachGuiSP"][type="hidden"]', phanTramFrame).val());
        var tongChiPhi = parseFloat($('input[name*="TongChiPhi"][type="hidden"]', rootFrame).val());

        var phanTram = parseFloat(phanTramIp.val());
        var tongChiPhiDonGiaSP = parseFloat(tongChiPhiDonGiaSPIp.val());
        var tongChiPhiGiaBanSP = parseFloat(tongChiPhiGiaBanSPIp.val());
        if (elm.name.indexOf('tongChiPhiPhanTram') != -1 || elm.name.indexOf('phanTramQLDN') != -1) {
            tongChiPhiGiaBanSP = tongChiPhiDonGiaSP + (tongChiPhiDonGiaSP * (phanTram / 100));
            tongChiPhiGiaBanSPIp.val(tongChiPhiGiaBanSP.toFixed(2));
            $('.tongChiPhiGiaBanSPView', phanTramFrame).val(tongChiPhiGiaBanSP.toFixed(2)).formatCurrency();

            tongChiPhiGiaKhachGuiSPIp.val(tongChiPhiGiaBanSP.toFixed(2));
            $('.tongChiPhiGiaKhachGuiSPView', phanTramFrame).val(tongChiPhiGiaBanSP.toFixed(2)).formatCurrency();
            tongChiPhiGiaKhachGuiSPIp.trigger('change');
            if ($("#check_is_photo").val() == 1) {
                tongChiPhiPhaiThu = 0;
                $('tr.tbody_tinh_phan_tram').each(function () {
                    soLuong = $(this).find($('input[name*="[so_luong]"][type="hidden"]')).val();
                    soLuong = soLuong.replace(/,/g, '');
                    donGia = $(this).find($('input[name*="tongChiPhiGiaBanSP"][type="hidden"]')).val();
                    tongChiPhiPhaiThu += parseFloat(soLuong) * parseFloat(donGia);
                });
                if (tongChiPhiPhaiThu > 0) {
                    tongChiPhiLoiNhuan = tongChiPhiPhaiThu - tongChiPhi;
                    $(".isPhotoTongChiPhiPhaiThuTxt", rootFrame).html(tongChiPhiPhaiThu).formatCurrency();
                    $(".hoaHong_GiaTriThuc", rootFrame).html(tongChiPhiPhaiThu).formatCurrency();
                    $('input[name*="tongChiPhiPhaiThu"][type="hidden"]', rootFrame).val(tongChiPhiPhaiThu);
                    $(".isPhotoTongChiPhiLoiNhuanTxt", rootFrame).html(tongChiPhiLoiNhuan).formatCurrency();
                    $(".hoaHongGiaKhachGuiTxt ", rootFrame).val(tongChiPhiPhaiThu).formatCurrency();
                    $('input[name*="hoaHongGiaKhachGui"][type="hidden"]', rootFrame).val(tongChiPhiPhaiThu);
                    $('input[name*="tongChiPhiLoiNhuan"][type="hidden"]', rootFrame).val(tongChiPhiLoiNhuan);
                }
            }
        } else if ($(elm).hasClass('tongChiPhiGiaBanSPView')) {
            tongChiPhiGiaBanSP = elm.value;
            tongChiPhiGiaBanSP = parseFloat(tongChiPhiGiaBanSP.replace(/,/g, ''));

            console.log(tongChiPhiGiaBanSP);
            tongChiPhiGiaKhachGuiSPIp.val(tongChiPhiGiaBanSP.toFixed(2));
            $('.tongChiPhiGiaKhachGuiSPView', phanTramFrame).val(tongChiPhiGiaBanSP.toFixed(2)).formatCurrency();
            $('input[name*="tongChiPhiGiaBanSP"][type="hidden"]', phanTramFrame).val(tongChiPhiGiaBanSP.toFixed(2));
            //                $('input[name*="tongChiPhiGiaBanSP"][type="hidden"]', phanTramFrame).val(elm.value).toNumber();
            $(this).next().val(elm.value).toNumber();

            phanTram = (tongChiPhiGiaBanSP - tongChiPhiDonGiaSP) / tongChiPhiDonGiaSP * 100;
            phanTram = precise_round(phanTram, 2);
            phanTramIp.val(phanTram);
            $(elm).formatCurrency();
            tongChiPhiGiaKhachGuiSPIp.trigger('change');
            if ($("#check_is_photo").val() == 1) {
                tongChiPhiPhaiThu = 0;
                $('tr.tbody_tinh_phan_tram').each(function () {
                    soLuong = $(this).find($('input[name*="[so_luong]"][type="hidden"]')).val();
                    soLuong = soLuong.replace(/,/g, '');
                    donGia = $(this).find($('input[name*="tongChiPhiGiaBanSP"][type="hidden"]')).val();
                    tongChiPhiPhaiThu += parseFloat(soLuong) * parseFloat(donGia);
                });
                if (tongChiPhiPhaiThu > 0) {
                    tongChiPhiLoiNhuan = tongChiPhiPhaiThu - tongChiPhi;
                    $(".isPhotoTongChiPhiPhaiThuTxt", rootFrame).html(tongChiPhiPhaiThu).formatCurrency();
                    $(".hoaHong_GiaTriThuc", rootFrame).html(tongChiPhiPhaiThu).formatCurrency();
                    $('input[name*="tongChiPhiPhaiThu"][type="hidden"]', rootFrame).val(tongChiPhiPhaiThu);
                    $(".isPhotoTongChiPhiLoiNhuanTxt", rootFrame).html(tongChiPhiLoiNhuan).formatCurrency();
                    $(".hoaHong_GiaTriThuc", rootFrame).html(tongChiPhiPhaiThu).formatCurrency();
                    $(".hoaHongGiaKhachGuiTxt", rootFrame).html(tongChiPhiPhaiThu).formatCurrency();
                    $('input[name*="hoaHongGiaKhachGui"][type="hidden"]', rootFrame).val(tongChiPhiPhaiThu);
                    $('input[name*="tongChiPhiLoiNhuan"][type="hidden"]', rootFrame).val(tongChiPhiLoiNhuan);
                }
            }
        } else if (elm.name.indexOf('tongChiPhiGiaKhachGuiSP') != -1) {
            $(elm).formatCurrency();
            var tongChiPhiGiaKhachGuiSP_hidden_elm = $('input[name*="tongChiPhiGiaKhachGuiSP"][type="hidden"]', phanTramFrame);
            tongChiPhiGiaKhachGuiSP_hidden_elm.val(elm.value).toNumber();

            var rootFrame = this.getRootCongNoFrame(phanTramFrame);
            var soLuong = parseFloat($('input[name*="amount"]', rootFrame).val());
            var hoaHong_GiaKhachGui = Math.round(tongChiPhiGiaKhachGuiSP_hidden_elm.val() * soLuong);
            var tongChiPhiPhaiThu = parseFloat($('input[name*="tongChiPhiPhaiThu"]', rootFrame).val());
            var daThanhToan = parseFloat($('input[name*="has_paid"][type="hidden"]', rootFrame).val());
            var vat = parseFloat($('input[name*="vat"]', rootFrame).val());
            var chuaThanhToan_t = Math.round(tongChiPhiPhaiThu + tongChiPhiPhaiThu * vat / 100) - daThanhToan;
            console.log('hhkg:' + hoaHong_GiaKhachGui);
            $('input[name*="hoaHongGiaKhachGui"][type="text"]', rootFrame).val(hoaHong_GiaKhachGui).formatCurrency();
            $('input[name*="hoaHongGiaKhachGui"][type="hidden"]', rootFrame).val(hoaHong_GiaKhachGui).toNumber();
            if (elm.value.replace(/[^0-9]+/g, '') > 0) {
                $(".tr_unpaid").show();
            } else {
                $('input[name*="unpaid_tt"][type="text"]', rootFrame).val(chuaThanhToan_t > 0 ? chuaThanhToan_t : 0).formatCurrency();
                $('input[name*="unpaid_tt"][type="hidden"]', rootFrame).val(chuaThanhToan_t > 0 ? chuaThanhToan_t : 0);
                $(".tr_unpaid").hide();
                $("#tr_congNo_unpaid_tt").show();
            }
        }
        //            console.log(phanTram);

        //            } else if (elm.name.indexOf('tongChiPhiGiaBanSP') != -1) {
        //                phanTram = (tongChiPhiGiaBanSP - tongChiPhiDonGiaSP) / tongChiPhiDonGiaSP * 100;
        //                phanTram = precise_round(phanTram, 2);
        //                phanTramIp.val(phanTram);
        //
        //            }
        this.tinhLoiNhuan_(phanTramFrame, true);
    }
    ;

    this.tinhLoiNhuan_ = function (phanTramFrame, tinhLaiGiaBan) {
        var rootFrame = this.getRootCongNoFrame(phanTramFrame);
//            console.log(phanTramFrame);
//            console.log(rootFrame);

        var phanTram = parseFloat($('input[name*="tongChiPhiPhanTram"]', phanTramFrame).val());
        var tongChiPhiDonGiaSP = parseFloat($('input[name*="tongChiPhiDonGiaSP"][type="hidden"]', phanTramFrame).val());
        var tongChiPhiGiaBanSP = parseFloat($('input[name*="tongChiPhiGiaBanSP"][type="hidden"]', phanTramFrame).val());
        var giaKhachGui = parseFloat($('input[name*="tongChiPhiGiaKhachGuiSP"][type="hidden"]', phanTramFrame).val());
        if ($("#check_is_photo", rootFrame).val() == 1)
            var soLuong = parseFloat($('input[name*="amount_photo"]', rootFrame).val());
        else
            var soLuong = parseFloat($('input[name*="amount"]', rootFrame).val());
        var tongChiPhi_PhiVanChuyenPlus = parseFloat($('input[name*="tongChiPhi_PhiVanChuyenPlus"]', rootFrame).val());

        if (tinhLaiGiaBan) {
//cuong disabled vi tinh gia bi lech so vơi khi luu                tongChiPhiDonGiaSP = tongChiPhi_PhiVanChuyenPlus / soLuong;
//                tongChiPhiGiaBanSP = tongChiPhiDonGiaSP + (tongChiPhiDonGiaSP * (phanTram / 100));
//                tongChiPhiGiaBanSP = tongChiPhiGiaBanSP.toFixed(2);
//                $('input[name*="tongChiPhiGiaBanSP"][type="hidden"]', phanTramFrame).val(tongChiPhiGiaBanSP);
//                $('.tongChiPhiGiaBanSPView').val(tongChiPhiGiaBanSP).formatCurrency();
        }
        if (parseFloat($('input[name*="amount_photo"][type="hidden"]', phanTramFrame).val()) > 0)
            soLuong = parseFloat($('input[name*="amount_photo"][type="hidden"]', phanTramFrame).val());

        if (isNaN(soLuong)) {
            console.log('isNaN soLuong - line <?php echo __LINE__; ?>');
            return false;

        } else if (isNaN(tongChiPhiGiaBanSP)) {
            console.log('isNaN tongChiPhiGiaBanSP - line <?php echo __LINE__; ?>');
            return false;
        }
        var tongThu = Math.round(tongChiPhiGiaBanSP * soLuong);
        var tongLoiNhuan = tongThu - tongChiPhi_PhiVanChuyenPlus;

        if (isNaN(tongThu)) {
            console.error('isNaN tongThu - line <?php echo __LINE__; ?>');
            return false;

        } else if (isNaN(tongLoiNhuan)) {
            console.error('isNaN tongLoiNhuan - line <?php echo __LINE__; ?>');
            return false;
        }
        $('.tongChiPhiLoiNhuanTxt', phanTramFrame).html(Math.round(tongLoiNhuan)).formatCurrency();
        $('input[name*="tongChiPhiLoiNhuan"]', phanTramFrame).val(tongLoiNhuan);

        $('.tongChiPhiPhaiThuTxt', phanTramFrame).html(Math.round(tongThu)).formatCurrency();
        $('input[name*="tongChiPhiPhaiThu"]', phanTramFrame).val(tongThu);

//            $('.hoaHong_GiaTriThuc', rootFrame).html(tongThu).formatCurrency();
        percent_vat = $('input[name*="vat"]').val();
        tongthuVAT = tongThu + (tongThu * percent_vat / 100);
        $(".tongChiPhiPhaiThuVATTxt", phanTramFrame).val(tongthuVAT).formatCurrency();
        $('input[name*="tongChiPhiPhaiThuVAT"]', phanTramFrame).val(tongthuVAT);
        this.updateHoahongNv(rootFrame);
        this.tinhHoaHongKhachHang(rootFrame);
//            ODR.core.CongNo(fratongChiPhiPhaiThuTxtme);
//            ODR.core.DoanhThuNV(frame);
        this.updateTienCongDoan(rootFrame);
        return false;
    };

    // Tính tiền thưởng cho nhân viên tham gia các công đoạn
    this.updateTienCongDoan = function (phanTramFrame) {
        // finally tong chi phi phai thu
        var tong_phai_thu = $('input[name*="tongChiPhiPhaiThu"]', phanTramFrame).val();
        var percent = parseFloat($('input[name*="percent"]', $('.table_hoahong_nhanvien')).val());
        var tien_nhan_vien = tong_phai_thu * (percent/100);
        var tong_tien_thuc = 0;
        $('.table_hoahong_nhanvien_theo_cong_doan .cong_doan_item').each(function () {
            var $el_cong_doan = $(this);
            var $cong_doan_tien = Math.round(tien_nhan_vien * ($el_cong_doan.data('phantram')/100));
            $('#cong_doan_' + $el_cong_doan.data('id')).text($cong_doan_tien).formatCurrency();
            $('#cong_doan_tien_' + $el_cong_doan.data('id')).val($cong_doan_tien);
            tong_tien_thuc += $cong_doan_tien;
        });

        $('.table_hoahong_nhanvien_theo_cong_doan input[name*="tienthuc"]').val(tong_tien_thuc);
        $('.table_hoahong_nhanvien_theo_cong_doan #tong_tien_thuc').text(tong_tien_thuc).formatCurrency();
    };

    this.getTableHoaHong = function (elm) {
        var rootFrame = this.getRootCongNoFrame(elm);
        return $('.table_hoa_hong_kh', rootFrame);
    };

    this.tinhChiPhiPhu = function () {
        var tongChiPhi = parseFloat($('input[name*="TongChiPhi"][type="hidden"]').val()),
            phanTramQLDN = parseFloat($('input[name*="phanTramQLDN"]').val()),
            chiPhiQLDN = Math.round(tongChiPhi * phanTramQLDN / 100);

        if (chiPhiQLDN != undefined) {
            $('.chiPhiQLDNtext').html(chiPhiQLDN).formatCurrency();
            $('input[name*="chiPhiQLDN"][type="hidden"]').val(chiPhiQLDN);
            var giaToanBo = tongChiPhi + chiPhiQLDN;
            $('.giaToanBotext').html(giaToanBo).formatCurrency();
            $('input[name*="giaThanhToanBo"][type="hidden"]').val(giaToanBo);
            var soLuong = $('input[name*="[amount]"][type="hidden"]').val();
            soLuong = soLuong.replace(/,/g, '');
            var tongChiPhiDonGiaSP = giaToanBo / soLuong;
            $('input[name*="tongChiPhiDonGiaSP"][type="hidden"]').val(tongChiPhiDonGiaSP.toFixed(2));
            $('input[name*="tongChiPhiDonGiaSP"][type="text"]').val(tongChiPhiDonGiaSP.toFixed(2)).formatCurrency();
        }
    };

    this.tinhHoaHongKhachHang = function (frame) {
        if (frame == undefined || frame == "undefined") {
            console.error('tinhHoaHongKhachHang() - frame is undefined - line <?php echo __LINE__; ?>');
            return false;
        }
        var frame = this.getTableHoaHong(frame);
        var rootFrame = this.getRootCongNoFrame(frame);


        var tongChiPhiPhaiThu = parseFloat($('input[name*="tongChiPhiPhaiThu"]', rootFrame).val());
        var type = parseFloat($('select[name*="hoaHongType"] :selected', frame).val());
        $('.hoaHongKhachHangNote', frame).hide();

//            console.log(type);

        if (type == Number('<?php echo Orderdata::HOA_HONG_KHACH_GUI_GIA ?>')) {
            var hoaHongGiaKhachGui = Math.round(parseFloat($('input[name*="hoaHongGiaKhachGui"][type="hidden"]', frame).val()));
            var hoaHongPhanTramDpThu = Math.round(parseFloat($('input[name*="hoaHongPhanTramDpThu"]', frame).val()));

            if (isNaN(hoaHongGiaKhachGui)) {
                console.warn('hoaHongGiaKhachGui isNaN - line <?php echo __LINE__; ?>');
                return false;

            }
            if (isNaN(tongChiPhiPhaiThu)) {
                console.warn('tongChiPhiPhaiThu isNaN - line <?php echo __LINE__; ?>');
                return false;

            }
            if (isNaN(hoaHongPhanTramDpThu)) {
                console.warn('hoaHongPhanTramDpThu isNaN - line <?php echo __LINE__; ?>');
                return false;

            }
            if (hoaHongGiaKhachGui < tongChiPhiPhaiThu) {
                $('.hoaHongKhachHangNote', frame).show().html('Giá khách gửi phải lớn hơn hoặc bằng giá trị thực.');
//                    $('input[name*="hoaHongTongPhaiThu"][type="hidden"]', frame).val(0);
//                    $('input.hoaHongTongPhaiThuTxt', frame).val(0);
                $('input[name*="hoaHongTongPhaiThu"]', frame).val(0);
                return false;
            }
            $('.hoaHongKhachHangNote', frame).empty();
            var anRa = 0;
            if ((anRa = hoaHongGiaKhachGui - tongChiPhiPhaiThu) < 0) {
//                    $('input[name*="hoaHongTongPhaiThu"][type="hidden"]', frame).val(0);
//                    $('input.hoaHongTongPhaiThuTxt', frame).val(0);
                $('input[name*="hoaHongTongPhaiThu"]', frame).val(0);
                $('.hoaHongKhachHangNote', frame).show().html('Giá khách gửi phải lớn hơn hoặc bằng giá trị thực.');
                return false;
            }
            var tongPhaiThu = Math.round(tongChiPhiPhaiThu + (anRa * (hoaHongPhanTramDpThu / 100)));

        } else {
            var hoaHongPhanTramChoKhach = parseFloat($('input[name*="hoaHongPhanTramChoKhach"]', frame).val());
            var tongPhaiThu = Math.round(tongChiPhiPhaiThu - (tongChiPhiPhaiThu * (hoaHongPhanTramChoKhach / 100)));

            if (isNaN(hoaHongPhanTramChoKhach)) {
                console.warn('isNaN hoaHongPhanTramChoKhach - line <?php echo __LINE__; ?>');
                return false;

            } else if (isNaN(tongChiPhiPhaiThu)) {
                console.warn('isNaN tongChiPhiPhaiThu - line <?php echo __LINE__; ?>');
                return false;
            }
//                $('.frm_hoahong1 input[name*="tongPhaiThu"]', frame).val(tongPhaiThu).formatCurrency();
        }
        $('input[name*="hoaHongTongPhaiThu"][type="text"]', frame).val(tongPhaiThu).formatCurrency();
        $('input[name*="hoaHongTongPhaiThu"][type="hidden"]', frame).val(tongPhaiThu);

        this.tinhCongNoKhacHang(frame);

        return true;
    };

    this.getCongNoFrame = function (frame) {
        var rootFrame = this.getRootCongNoFrame(frame);
        return $('.table_cong_no', rootFrame);
    };

    this.tinhCongNoKhacHang = function (frame) {
        var frame = this.getCongNoFrame(frame);
        var rootFrame = this.getRootCongNoFrame(frame);
//            console.log(frame);
//            $('select.ordersExport', frame).attr('disabled', true);

        var daThanhToan = parseFloat($('input[name*="has_paid"][type="hidden"]', frame).val());
        if (isNaN(daThanhToan))
            daThanhToan = 0;
        var tongChiPhiPhaiThu = parseFloat($('input[name*="tongChiPhiPhaiThu"]', rootFrame).val());
        var tongChiPhiPhaiThuVAT = parseFloat($('input[name*="tongChiPhiPhaiThuVAT"][type="hidden"]', rootFrame).val());
        var hoaHongPhanTram = parseFloat($('input[name*="hoaHongPhanTramChoKhach"]', rootFrame).val());
        var tongChiPhiPhaiThu1 = tongChiPhiPhaiThu;

        var type = $('select[name*="hoaHongType"] :selected', rootFrame).val();
        var hoaHongGiaKhachGui = parseFloat($('input[name*="hoaHongGiaKhachGui"][type="hidden"]', rootFrame).val());
        var hoaHongTongPhaiThu = parseFloat($('input[name*="hoaHongTongPhaiThu"][type="hidden"]', rootFrame).val());
        var hoaHongPhanTramDpThu = parseFloat($('input[name*="hoaHongPhanTramDpThu"]', rootFrame).val());
        var vat = parseFloat($('input[name*="vat"]', frame).val());

        if (hoaHongGiaKhachGui >= tongChiPhiPhaiThu && type == 1) {
            tongChiPhiPhaiThu = Math.round(((hoaHongGiaKhachGui - tongChiPhiPhaiThu) * hoaHongPhanTramDpThu / 100) + tongChiPhiPhaiThu + (hoaHongGiaKhachGui * (vat / 100)));
            $('.tr_congNo_unpaid_tt', frame).show();

            if (tongChiPhiPhaiThuVAT == daThanhToan) {
                var cong_no_con = 0;
            } else {
                var cong_no_con = Math.round(hoaHongGiaKhachGui + (hoaHongGiaKhachGui * vat / 100)) - Math.round(daThanhToan);
            }
            $('input[name*="unpaid"][type="text"]', frame).val(cong_no_con > 0 ? cong_no_con : 0).formatCurrency();
            $('input[name*="unpaid"][type="hidden"]', frame).val(cong_no_con > 0 ? cong_no_con : 0);

            var chuaThanhToan_t = Math.round(tongChiPhiPhaiThu) - Math.round(daThanhToan);
            $('input[name*="unpaid_tt"][type="text"]', frame).val(chuaThanhToan_t > 0 ? chuaThanhToan_t : 0).formatCurrency();
            $('input[name*="unpaid_tt"][type="hidden"]', frame).val(chuaThanhToan_t > 0 ? chuaThanhToan_t : 0);

        } else if (type == 2) {
            var TongPhaiThuTruocVAT = tongChiPhiPhaiThu;
            var tongChiPhiPhaiThu = Math.round(tongChiPhiPhaiThu + (tongChiPhiPhaiThu * (vat / 100)) - (tongChiPhiPhaiThu * (hoaHongPhanTram / 100)));
            $('.tr_congNo_unpaid_tt', frame).show();
            if (tongChiPhiPhaiThuVAT == daThanhToan) {
                var cong_no_con = 0;
                var chuaThanhToan_t = 0;
            } else {
                var cong_no_con = hoaHongGiaKhachGui + (hoaHongGiaKhachGui * vat / 100) - daThanhToan;
                var chuaThanhToan_t = tongChiPhiPhaiThu - daThanhToan;
            }
            $('input[name*="unpaid"][type="text"]', frame).val(cong_no_con > 0 ? cong_no_con : 0).formatCurrency();
            $('input[name*="unpaid"][type="hidden"]', frame).val(cong_no_con > 0 ? cong_no_con : 0);

            $('input[name*="unpaid_tt"][type="text"]', frame).val(chuaThanhToan_t > 0 ? chuaThanhToan_t : 0).formatCurrency();
            $('input[name*="unpaid_tt"][type="hidden"]', frame).val(chuaThanhToan_t > 0 ? chuaThanhToan_t : 0);

        } else {
            tongChiPhiPhaiThu = Math.round(tongChiPhiPhaiThu + (tongChiPhiPhaiThu * (vat / 100)));
            if (tongChiPhiPhaiThuVAT == daThanhToan) {
                var cong_no_con = 0;
                var chuaThanhToan_t = 0;
            } else {
                var cong_no_con = Math.round(tongChiPhiPhaiThu + (tongChiPhiPhaiThu * vat / 100)) - daThanhToan;
                var chuaThanhToan_t = tongChiPhiPhaiThu - daThanhToan;
            }

            $('input[name*="unpaid"][type="text"]', frame).val(cong_no_con > 0 ? cong_no_con : 0).formatCurrency();
            $('input[name*="unpaid"][type="hidden"]', frame).val(cong_no_con > 0 ? cong_no_con : 0);

            $('input[name*="unpaid_tt"][type="text"]', frame).val(chuaThanhToan_t > 0 ? chuaThanhToan_t : 0).formatCurrency();
            $('input[name*="unpaid_tt"][type="hidden"]', frame).val(chuaThanhToan_t > 0 ? chuaThanhToan_t : 0);

        }


        if (isNaN(daThanhToan)) {
            console.error('isNaN daThanhToan - line <?php echo __LINE__; ?>');
            return false;

        }
        if (isNaN(tongChiPhiPhaiThu)) {
            console.error('isNaN tongChiPhiPhaiThu - line <?php echo __LINE__; ?>');
            return false;

        }
        if (isNaN(vat)) {
            console.error(' isNaN vat - line <?php echo __LINE__; ?>');
            return false;
        }

        $('.tongChiPhiPhaiThuVATTxt', frame).val(tongChiPhiPhaiThu).formatCurrency();
        $('input[name*="tongChiPhiPhaiThuVAT"]', frame).val(tongChiPhiPhaiThu);
//            $('input.congNo_unpaid', frame).val(tongChiPhiPhaiThu - daThanhToan).formatCurrency();


        $('input[name*="total_cost"]', frame).val(tongChiPhiPhaiThu);

    };

    this.getHoahongnvElm = function (elm) {
        var rootFrame = this.getRootCongNoFrame(elm);
        return $('.table_hoahong_nhanvien', rootFrame);
    };

    this.updateHoahongNv = function (elm) {
        var frame = this.getHoahongnvElm(elm), _this = this;
        console.log(frame);

        var cost = parseFloat($('input[name*="cost"][type="hidden"]', frame).val());
        var percent = parseFloat($('input[name*="percent"]', frame).val());
        if (percent > 0) {
            _this.tinhSoTienNv(frame);
        } else {
            _this.tinhPhanTramNv(frame);
        }
    };

    this.tinhSoTienNv = function (hhnv_item) {
        console.log('tinhSoTienNv');

        if (hhnv_item == undefined || hhnv_item == "undefined") {
            console.error('hhnv_item is undefined - line <?php echo __LINE__; ?>');
            return false;
        }
        var percent = parseFloat($('input[name*="percent"]', hhnv_item).val());
        var tongThu = parseFloat($('input[name*="tongChiPhiPhaiThu"]', this.getRootCongNoFrame(hhnv_item)).val());

        if (isNaN(percent)) {
            console.error('isNaN percent - line <?php echo __LINE__; ?>');
            return false;

        } else if (isNaN(tongThu)) {
            console.error('isNaN tongThu - line <?php echo __LINE__; ?>');
            return false;
        }

        var m = Math.round(tongThu * (percent / 100));
        $('.costTxt', hhnv_item).val(m).formatCurrency();
        $('input[name*="cost"][type="hidden"]', hhnv_item).val(m);
//            console.log('m:'+m, hhnv_item);
        return true;
    };

    this.tinhPhanTramNv = function (hhnv_item) {
        console.log('tinhPhanTramNv');

        if (hhnv_item == undefined || hhnv_item == "undefined") {
            console.warn('hhnv_item is undefined - line <?php echo __LINE__; ?>');
            return false;
        }
        var cost = parseFloat($('input[name*="cost"][type="hidden"]', hhnv_item).val());
        var tongThu = parseFloat($('input[name*="tongChiPhiPhaiThu"]', this.getRootCongNoFrame(hhnv_item)).val());

        if (isNaN(cost)) {
            console.warn('isNaN cost - line <?php echo __LINE__; ?>');
            return false;

        } else if (isNaN(tongThu)) {
            console.warn('isNaN tongThu - line <?php echo __LINE__; ?>');
            return false;
        }
        var p = parseInt(100 / (tongThu / cost));
        $('input[name*="percent"]', hhnv_item).val(p);
//            console.log('p:'+p, hhnv_item);
        return true;
    };
}

function precise_round(num, decimals) {
    return Math.round(num * Math.pow(10, decimals)) / Math.pow(10, decimals);
}

function numberOnly(num) {
    return num.toString().replace(/[^0-9]+/g, '');
}

var order_view = new OrderView();
$(function () {
    var doc = $(document);

    doc.ajaxStart(function () {
        $(".loading-indicator-wrapper").addClass('loader-visible').removeClass('loader-hidden');
    }).ajaxStop(function () {
        $(".loading-indicator-wrapper").removeClass('loader-visible').addClass('loader-hidden');
    });


    doc.on('change', 'table.table_vanchuyen select, table.table_vanchuyen input', function () {
        var parent = $(this).closest('table.table_vanchuyen');
        if ($(this).hasClass('vanChuyenDonGiaTxt')) {
            $(this).formatCurrency();
            $('input[name*="vanChuyenDonGia"]', parent).val(this.value).toNumber();
            order_view.vanChuyenChange(this);
        } else {
            order_view.vanChuyenChange(this);
        }
    });

    doc.on('change', 'tbody.tbody_tinh_phan_tram input', function () {
        order_view.tinhLoiNhuan(this);
    });

    doc.on('change', 'tr.tbody_tinh_phan_tram input', function () {
        order_view.tinhLoiNhuan(this);
    });


//        doc.on('change', '.table_hoa_hong_kh input.hoaHongGiaKhachGuiTxt', function(e) {
    doc.on('change', '.table_hoa_hong_kh input[name*="hoaHongGiaKhachGui"][type="text"]', function (e) {
        var frame = $(this).closest('.table_hoa_hong_kh');
        $(this).formatCurrency();
        $('input[name*="hoaHongGiaKhachGui"][type="hidden"]', frame).val(this.value).toNumber();
        order_view.tinhHoaHongKhachHang(frame);
    });


    doc.on('change', '.table_hoa_hong_kh input', function (e) {
        var frame = $(this).closest('.table_hoa_hong_kh');
        order_view.tinhHoaHongKhachHang(frame);
    });

    doc.on('change', '.table_hoa_hong_kh select[name*="hoaHongType"]', function (e) {
        var val = this.value;
        var frame = $(this).closest('.table_hoa_hong_kh');
        var rootFrame = $(this).closest('.order_info_congno');
        var status = rootFrame.find('select[name*="status"]').val();

        if (val == Number('<?php echo Orderdata::HOA_HONG_KHACH_GUI_GIA; ?>')) {
            $('.td_kh_gui_gia', rootFrame).show();
            $('.td_kh_ycau_cat_hhong', rootFrame).hide();
            $('.don_gia_khach_gui_txt, .don_gia_khach_gui_ip', rootFrame).show();
            $(".tr_unpaid").show();
        } else if (val == Number('<?php echo Orderdata::HOA_HONG_KHACH_YEU_CAU_CAT_HOA_HONG; ?>')) {
            $('.td_kh_gui_gia', rootFrame).hide();
            $('.td_kh_ycau_cat_hhong', rootFrame).show();
            $('.don_gia_khach_gui_txt, .don_gia_khach_gui_ip', rootFrame).hide();
            $(".tr_unpaid").hide();
        } else {
            $('.td_kh_gui_gia', rootFrame).hide();
            $('.td_kh_ycau_cat_hhong', rootFrame).hide();
            $(".tr_unpaid").hide();
            return false;
        }

        order_view.tinhHoaHongKhachHang(frame);
    });

    doc.on('change', '#Payment_amount', function () {
        $(this).formatCurrency();
    });

    doc.on('click', '#add_paid', function () {
        var $this = $(this),
            order_info_id = $this.data('id');
        $.ajax({
            'type': 'get',
            'data': {'order_info_id': order_info_id},
            'url': link_addPay + '?order_info_id=' + info_id,
            'success': function (data) {
                var dialog_elm = $('<div>').html(data).css('padding', '1em').dialog({
                        'title': 'Thêm mới thanh toán',
                        'width': 900,
                        'minHeight': "auto",
                        'buttons': [
                            {
                                'text': 'Lưu',
                                'click': function () {
                                    var button = this,
                                        action = $('form', $(this).dialog()).attr('action');
                                    $.ajax({
                                        'type': 'post',
                                        'url': action,
                                        'data': $('form', $(this).dialog()).serialize(),
                                        'dataType': 'json',
                                        'success': function (data) {
                                            if (data.success) {
                                                $('.congNo_has_paid').val(data.has_paid).trigger('change');
                                                $(button).dialog('close');
                                                location.reload();
                                            } else {
                                                var html = '';
                                                $.each(data, function (k, v) {
                                                    html += '<li style="text-align: left">' + v[0] + '</li>'
                                                });
                                                if (html != "") {
                                                    html = '<div class="errorSummary"><p>Xin hãy sửa lại những lỗi nhập liệu sau:</p><ul>' + html + '</ul></div>'
                                                    jcore.alert(html);
                                                }
                                            }
                                        }
                                    });
                                }
                            },
                            {
                                'text': 'Đóng',
                                'click': function () {
                                    $(this).dialog('close');
                                }
                            }
                        ],
                        'close': function () {
                            $(this).remove();
                        }
                    }),
                    $datePayment = $('#Payment_pay_date');
                $datePayment.datepicker({
                    'changeMonth': true,
                    'changeYear': true,
                    'dateFormat': 'yy-mm-dd'
                });

                //phu them 21/11/2016
                var $tong_can_thanh_toan = $('#tong_can_thanh_toan'),
                    $Payment_amount = $('#Payment_amount'),
                    $Payment_overbalance = $('#Payment_overbalance');

                if ($('#Payment_amount_tt').length > 0) {
                    var $Payment_amount_tt = $('#Payment_amount_tt');
                    $Payment_amount_tt.formatCurrency();


                }

                $Payment_amount.formatCurrency();

                //su dung thanh toan du
                $Payment_amount.change(function () {
                    var can_thanh_toan = $Payment_amount.val(),
                        tong_thanh_toan = parseFloat($tong_can_thanh_toan.val());
                    can_thanh_toan = parseFloat(can_thanh_toan.replace(/,/g, ""));
                    if (can_thanh_toan > tong_thanh_toan) {
                        var message = 'Bạn đã nhập qúa số tiền cần thanh toán, bạn có chắc chắn muốn sử dụng số này?';
                        $('<div>').addClass('alertBox').html(message).dialog({
                            'title': 'Xác nhận',
                            'resizable': false,
                            'modal': true,
                            'minHeight': 0,
                            'minWidth': 0,
                            'width': 'auto',
                            'buttons': [
                                {
                                    'text': 'Đồng ý',
                                    'click': function () {
                                        $(this).dialog('close');
                                        $Payment_overbalance.val(can_thanh_toan - tong_thanh_toan);
                                    }
                                }
                                , {
                                    'text': 'Hủy bỏ',
                                    'click': function () {
                                        $(this).dialog('close');
                                        $Payment_amount.val(tong_thanh_toan).formatCurrency();
                                    }
                                }
                            ]
                        });
                    }
                });
                //het phu them 21/11/2016

            }
        });
    });

    function update_overbalance() {
        var $Payment_amount = $('#Payment_amount'),
            $Payment_balance_total = $('#Payment_balance_total'),
            $tong_can_thanh_toan = $('#tong_can_thanh_toan'),
            $Payment_amount_tt = $('#Payment_amount_tt'),
            overbalance_total_ = 0, overbalance_total_after = 0,
            can_thanh_toan = parseFloat($Payment_amount_tt.val().replace(/,/g, ""));
        $("input[name='ids\[\]']").each(function () {
            var $this = $(this),
                $parent = $this.closest('.overbalance_line'),
                overbalance_ = parseFloat($parent.find('input[name*="overbalance_total"]').val());
            if (this.checked) {
                overbalance_total_ += overbalance_;
                if (can_thanh_toan > 0) {
                    can_thanh_toan -= overbalance_;
                    if (can_thanh_toan < 0) {
                        $parent.find('input[name*="overbalance_total_after"]').val(Math.abs(can_thanh_toan));
                        $parent.find('.con_lai').html(Math.abs(can_thanh_toan)).formatCurrency();
                    } else {
                        $parent.find('input[name*="overbalance_total_after"]').val(0);
                        $parent.find('.con_lai').html(0).formatCurrency();
                    }
                } else {
                    $parent.find('input[name*="overbalance_total_after"]').val(overbalance_);
                    $parent.find('.con_lai').html(overbalance_).formatCurrency();
                }
            } else {
                $parent.find('input[name*="overbalance_total"]').val(overbalance_);
                $parent.find('.con_lai').html(overbalance_).formatCurrency();
            }
        });
        $Payment_amount.val(can_thanh_toan <= 0 ? 0 : can_thanh_toan).formatCurrency();
        $Payment_balance_total.val(overbalance_total_).formatCurrency();
        $tong_can_thanh_toan.val(can_thanh_toan <= 0 ? 0 : can_thanh_toan);
    }

    doc.on('click', '#ids_all', function () {
        var checked = this.checked;
        $("input[name='ids\[\]']:enabled").each(function () {
            this.checked = checked;
        });
        update_overbalance();
    });
    doc.on('click', "input[name='ids\[\]']", function () {
        $('#ids_all').prop('checked', $("input[name='ids\[\]']").length == $("input[name='ids\[\]']:checked").length);
        update_overbalance();
    });

    doc.on('change', '.table_cong_no select, .table_cong_no input', function (e) {
        var frame = $(this).closest('.table_cong_no');

        if ($(this).hasClass('congNo_has_paid')) {
            $(this).formatCurrency();
            $('input[name*="has_paid"][type="hidden"]', frame).val(this.value).toNumber();

        } else if ($(this).hasClass('congNo_unpaid')) {
            $(this).formatCurrency();
            $('input[name*="unpaid"][type="hidden"]', frame).val(this.value).toNumber();

        } else if ($(this).hasClass('congNo_unpaid_tt')) {
            $(this).formatCurrency();
            $('input[name*="unpaid_tt"][type="hidden"]', frame).val(this.value).toNumber();
        }
        order_view.tinhCongNoKhacHang(this);
    });


    //doanh thu nhan vien
    var changeHoahongNvName = function (frame) {
        $('.table_hoahong_nhanvien tbody tr').each(function (k, rs) {
            var data_id, data_name
            $('input, select', this).each(function () {
                var $this = $(this);
                if (!$this.data('id')) {
                    $this.data('id', this.id);
                }
                if (!$this.data('name')) {
                    $this.data('name', this.name);
                }
                this.id = $this.data('id').replace('_len_', k);
                this.name = $this.data('name').replace('_len_', k);
//                    console.log($this.data(), this);
            });
        });
    };

    doc.on('click', '.table_hoahong_nhanvien tfoot .add', function (e) {
        var tbody = $(this).closest('.table_hoahong_nhanvien').find('tbody');
        var tr_first = $(this).closest('tfoot').find('tr.templ').clone();
        tbody.append(
            tr_first.show().removeClass('templ')
        );
        $('input[name*="percent"]', tr_first).trigger('change');
        changeHoahongNvName();
        return false;
    });

    doc.on('click', '.table_hoahong_nhanvien tbody .delete', function (e) {
        var $this = $(this);
        jcore.confirm('Bạn có chắc là muốn xóa?', function () {
            $this.closest('tr').remove();
            changeHoahongNvName();
        });
    });

    doc.on('change', '.table_hoahong_nhanvien tbody select', function (e) {
        var hhnv_item = $(this).closest('tbody.hhnv_item');
        order_view.tinhSoTienNv(hhnv_item);
        return false;


        var table_hoahong_nhanvien = $(this).closest('.table_hoahong_nhanvien');
        var $this = $(this), val = Number(this.value), $option_selected = $(':selected', this);
        if (val > 0) {
            $('tbody select', table_hoahong_nhanvien).not(this).each(function () {
                var val_ = $(this).prop('value');
                if (val_ == val) {
                    jcore.alert('Đã có nhân viên <strong>' + $option_selected.prop('text') + '</strong> trong danh sách. Hãy chọn nhân viên khác');
                    $option_selected.prop('selected', false);
                    return false;
                }
            });
        }
    });

    doc.on('change', '.table_hoahong_nhanvien tbody input', function (e) {
        var hhnv_item = $(this).closest('tbody.hhnv_item');
        if (this.name.indexOf('cost') != -1) {
            if ($(this).hasClass('costTxt')) {
                $(this).formatCurrency();
                $(this).next().val(this.value).toNumber();
            }
            order_view.tinhPhanTramNv(hhnv_item);
        } else if (this.name.indexOf('percent') != -1) {
            order_view.tinhSoTienNv(hhnv_item);
        }
//            order_view.updateHoahongNv(this);
        order_view.updateTienCongDoan($('.tbody_tinh_phan_tram'));
    });

    doc.on('click', '.a_xuat_excel', function () {
        if (full_access == 'true') {
            var id = $(this).data('id');
            $.ajax({
                url: link_checkexport,
                dataType: 'json',
                type: 'post',
                data: {
                    recordid: id
                },
                success: function (data) {
                    if (data.success == "success") {
                        if ($(".list_export_" + id).val() != "") {
                            var val = Number($(".list_export_" + id).val()),
                                info_id = Number($(".list_export_" + id).data('info_id'));
                            if ($("#other_send_ncc_" + info_id).is(":visible")) {
                                if ($("#other_send_ncc_" + info_id).val() == '') {
                                    jcore.alert('Bạn phải chọn nhà cung cấp để xuất exel');
                                } else {
                                    if (val > 0 && info_id > 0) {
                                        $('iframe#export_frame').attr('src', link_export + '?type=' + val + '&info_id=' + info_id + '&ncc_id=' + $("#other_send_ncc_" + info_id).val());
                                    }
                                }
                            } else {
                                if (val > 0 && info_id > 0) {
                                    $('iframe#export_frame').attr('src', link_export + '?type=' + val + '&info_id=' + info_id);
                                }
                            }
                        } else {
                            jcore.alert('Bạn hãy chọn mục cần xuất excel');
                        }
                    } else {
                        jcore.alert("Đơn hàng chưa được lưu đầy đủ thông tin.<br>Vui lòng lưu đơn hàng trước khi sử dụng tính năng này");
                    }
                }
            })
            ;
        } else {
            jcore.alert('<div style="text-align:left"><?= $notice ?></div>');
        }

    });

    function loadEmailNCC(type, order_info) {
        $.ajax({
            url: link_loadNCC,
            type: 'post',
            data: {type: type, order_info: order_info},
            success: function (data) {
                if (!data)
                    data = '<div class="email_list_div" style="margin-top: 5px"><input type="text" class="other_send_mail_email_' + order_info + '" style="width: 450px; margin-bottom: 3px;" name="other_send_mail_email[]" placeholder="Nhập email"/> <a href="javascript:;" class="addLineEmail">[Thêm]</a></div>';
                $('#email_list').html(data);
                $('input[name*="other_send_mail_email"]').click(function () {
                    $(this).select();
                });
            }
        });
    }

    doc.on('click', '.removeLineEmail', function () {
        var result = confirm("Bạn có chắc muốn xóa?");
        if (result) {
            var tr = $(this).closest('.email_list_tr'),
                class_ = tr.find('input[name*="other_send_mail_email"]').attr('class');
            if (tr.length <= 0) {
                tr = $(this).closest('.email_list_div');
                class_ = tr.find('input[name*="other_send_mail_email"]').attr('class');
            }
            tr.remove();

            if ($('input[name*="other_send_mail_email"]').length <= 0) {
                $('#email_list').html('<div class="email_list_div" style="margin-top: 5px"><input type="text" class="' + class_ + '" style="width: 450px; margin-bottom: 3px;" name="other_send_mail_email[]" placeholder="Nhập email"/> <a href="javascript:;" class="addLineEmail">[Thêm]</a></div>');
            }
        }
    });

    doc.on('click', '.addLineEmail', function () {
        var class_ = $(this).closest('.email_list_div').find('input[name*="other_send_mail_email"]').attr('class');
        $('#email_list').append('<div class="email_list_div" style="margin-top: 5px"><input type="text" class="' + class_ + '" style="width: 450px; margin-bottom: 3px;" name="other_send_mail_email[]" placeholder="Nhập email"/> <a href="javascript:;" class="removeLineEmail">[Xóa]</a></div>');
    });

    doc.on('change', 'select[name*="[export_id]"]', function () {
        var type = $(this).val(),
            order_info = $(this).data('info_id');
        if ($("#checkbox_sendmail_" + order_info).is(':checked'))
            loadEmailNCC(type, order_info);
    });

    doc.on('click', '.checkbox_sendmail', function () {
        var id = $(this).data('id'),
            type = $(".list_export_" + id).val();
        if ($(this).is(':checked')) {
            $(".a_xuat_excel_" + id).hide();
            $(".a_gui_mail_" + id).show();
            $('.other_send_mail_title_' + id).show();
            $('.other_send_mail_email_' + id).show();
            $('.other_send_mail_content_' + id).show();
            $('.p_other_send_mail_signature_' + id).show();
            $('.email_list_' + id).show();
            loadEmailNCC(type, id);
        } else {
            $(".a_xuat_excel_" + id).show();
            $(".a_gui_mail_" + id).hide();
            $('.other_send_mail_title_' + id).hide();
            $('.other_send_mail_email_' + id).hide();
            $('.other_send_mail_content_' + id).hide();
            $('.p_other_send_mail_signature_' + id).hide();
            $('.email_list_' + id).hide();
        }
    });
    doc.on('click', '.a_gui_mail', function () {
        var id = $(this).data('id');
        if ($(".list_export_" + id).val() != "") {
            var val = Number($(".list_export_" + id).val()),
                info_id = Number($(".list_export_" + id).data('info_id')),
                other_send_mail_title = $.trim($(".other_send_mail_title_" + id).val()),
                other_send_mail_content = $.trim($(".other_send_mail_content_" + id).val()),
                other_send_mail_email = $(".other_send_mail_email_" + id).serialize(),
                email_validate = true, signature;
            $('input[name*="other_send_mail_email"]').each(function () {
                var email = $.trim($(this).val());
                if (email == null || email == undefined || !isValidEmailAddress(email)) {
                    email_validate = false;
                    return false;
                }
            });
            if (!email_validate) {
                jcore.alert('Vui lòng kiểm tra lại email cần gửi');
                return false;
            }
            else if (!other_send_mail_title || other_send_mail_title == undefined) {
                jcore.alert('Vui lòng kiểm tra tiêu đề gửi mail');
                return false;
            } else if (!other_send_mail_content || other_send_mail_content == undefined) {
                jcore.alert('Vui lòng kiểm tra nội dung gửi mail');
                return false;
            }
            if ($("#other_send_mail_signature_" + id).is(':checked')) {
                signature = 1;
            } else {
                signature = 0;
            }
            if (val > 0 && info_id > 0) {
                $(".a_gui_mail_" + id).addClass('button_disabled');
                $(".a_gui_mail_" + id).prop("disabled", true);
                $(".image_loading_" + id).show();
                $.ajax({
                    'data': {
                        'type': val,
                        'info_id': info_id,
                        'other_send_mail_title': other_send_mail_title,
                        'other_send_mail_email': other_send_mail_email,
                        'other_send_mail_content': other_send_mail_content,
                        'other_send_mail_signature': signature
                    },
                    'type': 'post',
                    'url': link_export_sendmail,
                    'success': function (data) {
                        if (data == 'success') {
                            jcore.alert('Gửi mail thành công');
                            setTimeout(function () {
                                location.reload();
                            }, 2000);
                        } else if (data = 'error') {
                            jcore.alert('error');
                        }
                    }
                });
            }
        } else {
            jcore.alert('Bạn hãy chọn mục cần gửi mail');
        }
    });

    doc.on('click', 'a.add_schedule', function () {

        var parent_schedule = $(this).closest('.ul_add_schedule');
        if (full_access == 'true') {
            var id = $(this).data('id');
            if ($('select[name*="schedule_title"]', parent_schedule).val() == '') {
                krajeeDialog.alert('Vui lòng chọn công đoạn sản xuất');
                return false;
            } else if ($('input[name*="schedule_time"]', parent_schedule).val() == '') {
                krajeeDialog.alert('Vui lòng chọn ngày');
                return false;
            } else {
                if ($(".input_title_schedule_" + id).val() != '') {
                    var title = $(".input_title_schedule_" + id).val();
                } else {
                    var title = $(".schedule_title_" + id).val();
                }
                var order_info_id = id;
                var user = $('select[name*="schedule_user"]', parent_schedule).val();
                var status = $('select[name*="schedule_status"]', parent_schedule).val();
                var approval = 0;
                var time = $('input[name*="schedule_time"]', parent_schedule).val();
                var time_done = $('input[name*="schedule_time_done"]', parent_schedule).val();

                if (user == null || user == '') {
                    krajeeDialog.alert('Vui lòng chọn ít nhất một nhân viên');
                    return false;
                }

                if (status == null || status == '') {
                    krajeeDialog.alert('Vui lòng chọn ít nhất một trạng thái');
                    return false;
                }

                if (time_done == null || time_done == '') {
                    krajeeDialog.alert('Vui lòng chọn ngày hoàn thành');
                    return false;
                }
                $.ajax({
                    'data': {
                        'title': title,
                        'time': time,
                        'time_done': time_done,
                        'user': user,
                        'status': status,
                        'approval': approval,
                        'order_info_id': order_info_id
                    },
                    'type': 'post',
                    'url': link_addSchedule,
                    'success': function (response) {
//                        console.log(response);
                        var data = $.parseJSON(response);
                        if (data.status == 'confirm') {
                            newWin = window.open(data.message, '_blank');
                            if (!newWin || newWin.closed || typeof newWin.closed == 'undefined') {
                                krajeeDialog.alert('Không thể mở cửa sổ vì trình duyệt của bạn đã chặn pop-up window.<br>Bạn hãy vô hiệu hóa chức năng chặn pop-up của trình duyệt sau đó hãy thử lại.');
                            } else {
                                newWin.focus();
                            }
                        } else {

                            var percent = $('.table_hoahong_nhanvien input[name*="percent"]').val();

                            $('select[name*="schedule_title"]', parent_schedule).val('');
                            $('select[name*="schedule_status"]', parent_schedule).val('');
                            $('select[name*="schedule_user"]', parent_schedule).val('');
                            $('input[name*="schedule_time"]', parent_schedule).val('');
                            $(".input_title_schedule_" + id).val('');
                            $(".input_title_schedule_" + id).slideUp();
                            $.ajax({
                                'data': {'id': id, 'percent' : percent},
                                'type': 'post',
                                'url': link_loadSchedule,
                                'success': function (data) {
                                    if (data != '') {
                                        $("#list_schedule_" + id).html(data);
                                        var table = $($(data)[0]);
                                        var newrow_id = table.find('#newest_item').val();
                                        var newrow = table.find('.remove_schedule_'+newrow_id);
                                        var rowid = newrow.find(".edit_schedule").data('id');
                                        var phantram = newrow.data('phantram');
                                        var tien = newrow.data('tien');
                                        var table_cong_doan = $('.table_hoahong_nhanvien_theo_cong_doan');
                                        var rowhtml = '<tr class="cong_doan_item" id="cong_doan_item'+rowid+'" data-phantram="'+phantram+'" data-id="'+rowid+'">';
                                        rowhtml += '<td>'+newrow.find('.schedule_title_'+rowid).find('a').text()+'</td>';
                                        rowhtml += '<td>'+newrow.find('.schedule_ten_'+rowid).text()+'</td>';
                                        rowhtml += '<td><span id="cong_doan_'+rowid+'"></span> đ <input type="hidden" name="cong_doan_tien_'+rowid+' id="cong_doan_tien_'+rowid+'" value="'+tien+'"></td>';
                                        rowhtml += '</tr>';
                                        table_cong_doan.find('.hhnv_cong_doan_item table').append(rowhtml);
                                        order_view.updateTienCongDoan($('#tab2default'));
                                    }
                                }
                            });
                        }
                    }
                });
            }
        } else {
            krajeeDialog.alert('<div style="text-align:left">' + notice + '</div>');
        }

    });
    doc.on('click', '.cancel_schedule', function () {
        order_info_id = $(this).data('info_id');
        var percent = $('.table_hoahong_nhanvien input[name*="percent"]').val();
        $.ajax({
            'data': {'id': order_info_id, 'percent' : percent},
            'type': 'post',
            'url': link_loadSchedule,
            'success': function (data) {
                if (data != '') {
                    $("#list_schedule_" + order_info_id).html(data);
                }
            }
        });
    });

    doc.on('click', '.save_schedule', function () {
        var $this = $(this),
            id = $this.data('id'),
            info_id = $this.data('info_id'),
            $contain = $this.closest('tr.remove_schedule_' + id),
            index = $contain.index();
        if ($(".schedule_title_edit", $contain).val() == '') {
            krajeeDialog.alert('Vui lòng chọn công đoạn sản xuất');
            return false;
        } else if ($(".schedule_time_edit", $contain).val() == '') {
            krajeeDialog.alert('Vui lòng chọn ngày giao');
            return false;
        } else if ($(".schedule_time_done_edit", $contain).val() == '') {
            krajeeDialog.alert('Vui lòng chọn ngày hoàn thành');
            return false;
        } else {
            var title = $(".schedule_title_edit", $contain).val();
            var time = $(".schedule_time_edit", $contain).val();
            var time_done = $(".schedule_time_done_edit", $contain).val();
            var status = $(".schedule_status_edit", $contain).val();
            var user = $(".schedule_user_edit", $contain).val();
            var approval = $(".schedule_approval_edit", $contain).val();
            var feedback = $("#addcomment").val();
            $.ajax({
                'data': {
                    feedback: feedback,
                    'id': id,
                    'title': title,
                    'status': status,
                    'approval': approval,
                    'user': user,
                    'time': time,
                    'time_done': time_done
                },
                'type': 'post',
                'url': link_saveSchedule,
                'success': function (data) {
                    if (data == 'success') {

                        var percent = $('.table_hoahong_nhanvien input[name*="percent"]').val();

                        $.ajax({
                            'data': {'id': id, 'index': index, 'percent' : percent, 'info_id' : info_id},
                            'type': 'post',
                            'url': link_loadSchedule,
                            'success': function (data) {
                                if (data != '') {
                                    if (feedback != '')
                                        krajeeDialog.alert('Phản hồi của bạn đã gửi đi thành công');

                                    $contain.after(data);
                                    $contain.remove();

                                    var savedrow = $(data);

                                    var rowid = savedrow.find(".edit_schedule").data('id');
                                    var phantram = savedrow.data('phantram');
                                    var tien = savedrow.data('tien');
                                    var table_cong_doan = $('.table_hoahong_nhanvien_theo_cong_doan');
                                    var rowhtml = '<tr class="cong_doan_item" id="cong_doan_item'+rowid+'" data-phantram="'+phantram+'" data-id="'+rowid+'">';
                                    rowhtml += '<td>'+savedrow.find('.schedule_title_'+rowid).find('a').text()+'</td>';
                                    rowhtml += '<td>'+savedrow.find('.schedule_ten_'+rowid).text()+'</td>';
                                    rowhtml += '<td><span id="cong_doan_'+rowid+'"></span> đ <input type="hidden" name="cong_doan_tien_'+rowid+' id="cong_doan_tien_'+rowid+'" value="'+tien+'"></td>';
                                    rowhtml += '</tr>';
                                    table_cong_doan.find('.hhnv_cong_doan_item table').find('#cong_doan_item' + rowid).remove();

                                    table_cong_doan.find('.hhnv_cong_doan_item table').append(rowhtml);

                                    order_view.updateTienCongDoan($('#tab2default'));

                                }
                            }
                        });
                    } else if (data = 'error') {
                        krajeeDialog.alert('error');
                    }
                }
            });
        }
    });
    doc.on('click', '.delete_schedule', function () {
        var id = $(this).data('id');
        krajeeDialog.confirm('Bạn có chắc muốn xóa mục này?', function (result) {
            if (result) {
                if (id > 0) {
                    $.ajax({
                        'data': {'id': id},
                        'type': 'post',
                        'url': link_deleteSchedule,
                        'success': function (data) {
                            if (data == 'success') {
                                $('.remove_schedule_' + id).remove();
                                $('.hhnv_cong_doan_item #cong_doan_item' + id).remove();
                                order_view.updateTienCongDoan($('#tab2default'));
                            } else if (data = 'error') {
                                krajeeDialog.alert('error');
                            }
                        }
                    });
                }
            }
        });
    });
    doc.on('dblclick', '.edit_schedule', function () {
        var id = $(this).data('id');
        var stt = $(this).data('stt');
        $.ajax({
            'data': {'id': id, 'stt': stt},
            'type': 'post',
            'url': link_editSchedule,
            'success': function (data) {
                if (data != '') {
                    $('.remove_schedule_' + id).html(data);
                    $('.schedule_time_edit').removeClass('hasDatepicker');
                    var dateToday = new Date();
                    $('.schedule_time_edit').datepicker({
                        'changeMonth': true,
                        'changeYear': true,
                        'dateFormat': 'yy-mm-dd',
                        'minDate': dateToday
                    });
                }
            }
        });
    });
    doc.on('change', '.ul_add_schedule .schedule_title', function () {
        var id = $(this).data('info_id');
        if ($(this).val() === 'other') {
            $(".input_title_schedule_" + id).slideDown();
        }else{
            $(".input_title_schedule_" + id).slideUp();
        }
    });
    doc.on('change', '.check_cong_no', function () {
        var order_info_id = $(this).data('id');

        if ($(this).is(':checked')) {
            if ($(".check_tongchiphiphaithu_" + order_info_id).val() != $(".check_has_paid_" + order_info_id).val()) {
                krajeeDialog.alert('Bạn phải hoàn thành công nợ thì mới được chọn');
                $(this).prop('checked', false);
            } else {
                $(this).prop('checked', true);
            }
        }
    });
    doc.on('change', '.schedule_approval_edit', function () {
        console.log($(this).val());
        var parent_schedule = $(this).closest('tr');
        if ($(this).val() == 2) {
            $('#addcomment', parent_schedule).val('');
            $('#addcomment', parent_schedule).slideDown();
        } else {
            $('#addcomment', parent_schedule).val('');
            $('#addcomment', parent_schedule).slideUp();
        }
    });

    $('input[id$="tongChiPhiPhanTram"], select[id$="hoaHongType"]').trigger('change');

    $(".don_hang_da_xoa input[type='text'], .don_hang_da_xoa input[type='checkbox'], .don_hang_da_xoa textarea").css({
        'pointer-events': 'none',
        'background': '#e9e9e9',
        'border-color': '#ccc'
    });
    $(".don_hang_da_xoa select, .don_hang_da_xoa a").css({
        'pointer-events': 'none',
        'background': '#e9e9e9',
        'border-color': '#ccc'
    });
    $('.don_hang_da_xoa select[name*="[status]"]').removeAttr("style");
    $('.don_hang_da_xoa .checkbox_sendmail').attr("disabled", 'disabled');

    $('select[name*="status"]').change(function () {
        var _this = $(this), status = this.value, id = $(this).data('id'), current_val = $(this).data('current_val'),
            has_payment = $(this).data('has_payment'), unpaid_tt = $(this).data('unpaid');
        if (status == STATUS_DA_HOAN_THANH_CONG_NO || status == STATUS_FINISH) {
            if (unpaid_tt > 0 || has_payment == false) {
                krajeeDialog.alert('Bạn phải hoàn thành xong công nợ mới chuyển được trạng thái này');
                $(this).val(current_val);
                return false;
            } else {
                krajeeDialog.confirm('Bạn có chắc chắn muốn chuyển trạng thái này. Sau khi chuyển sẽ không thể hoàn tác', function (result) {
                    if (result) {
                        return true;
                    } else {
                        _this.val(current_val);
                    }
                });
            }
        } else if (status == STATUS_PENDING || status == STATUS_DA_BAO_GIA) {
            if (has_payment == true) {
                krajeeDialog.confirm('Bạn có chắc chắn muốn chuyển trạng thái này. Sau khi chuyển sẽ xóa toàn bộ thanh toán của đơn hàng và không thể hoàn tác?', function (result) {
                    if (result) {
                        $.ajax({
                            'type': 'post',
                            data: {has_payment: has_payment, status: status, id: id},
                            'url': '{$link_update_status}',
                            'success': function (data) {
                                location.reload();
                            }
                        });
                    } else {
                        _this.val(current_val);
                    }
                });
            } else {

            }
        }
    });
    $('.btn-save-order-view').click(function () {
        var formData = $("form#order-views-form").serialize();
        $.ajax({
            'url': link_save_view,
            'data': formData,
            dataType: 'json',
            'type': 'POST',
            'success': function (data) {
                console.log(data);
            }
        });
    });

    $('input[id$="tongChiPhiPhanTram"], select[id$="hoaHongType"]').trigger('change');
});
