(function ($) {
    var order = new Order, price_Keypress = false, doc = $(document), select_current, unsaved = false;

    doc.on('change', ':input, select', function () {
        unsaved = true;
    });

    $(window).bind('beforeunload', function() {
        if(unsaved){
            return "Các thay đổi bạn đã thực hiện có thể không được lưu. Bạn có chắc muốn tải lại trang hoặc rời trang này?";
        }
    });

    doc.on('click', '.panel-heading-item span.clickable, .panel-heading-item h3.panel-title', function () {
        var $this = $(this).closest('.panel-heading-item').find('span.clickable');
        if (!$this.hasClass('panel-collapsed')) {
            $this.parents('.panel-item').find('.panel-body-item').slideUp();
            $this.addClass('panel-collapsed');
            $this.find('i').removeClass('glyphicon-chevron-up').addClass('glyphicon-chevron-down');
        } else {
            $this.parents('.panel-item').find('.panel-body-item').slideDown();
            $this.removeClass('panel-collapsed');
            $this.find('i').removeClass('glyphicon-chevron-down').addClass('glyphicon-chevron-up');
        }
    });

    //chon khach hang
    doc.on('change', customer_id, function () {
        var _this = this, customer_id = $(this).val();
        if (customer_id > 0) {
            $.ajaxQueue({
                'data': {'cus_id': customer_id},
                'type': 'get',
                'url': getCustomerInfo,
                'success': function (data) {
                    $('#customer_information').html(data);
                    order.getFileDesignDropList(_this, {'customer_id': customer_id});
                }
            });
        }
        return false;
    });

    //thay doi thong tin san pham
    doc.on('focusin', 'input[name*="[amount]"], input[name*="[inner_page_amount]"], input[name*="ext_inner_page_amount"]', function () {
        $(this).data('val', $(this).val());
    }).on('change', 'input[name*="[amount]"], input[name*="[inner_page_amount]"], input[name*="ext_inner_page_amount"]', function () {
        order.alertValidation(this);
        order.updateProductSelect(this);
        var rootFrame = order.getRootFrame(this),
            product_id = order.getProductId(this),
            product = order.getProductById(product_id);
        // if (product && Number(product.has_inner_page) === 1)
            order.getProductSize(rootFrame, product);
        // order.timKhoGiayBia(this);
    });

    doc.on('change', '#san-pham select, #san-pham input', function (e) {
        var _this = this, rootFrame = order.getRootFrame(_this), frame = $(_this).closest('#san-pham'),
            val = $(_this).val();
        order.showHideByProduct(_this);
        if (e.hasOwnProperty('originalEvent'))
        $(_this).addClass('userChanged');
        if ((_this.nodeName === 'SELECT' && _this.name.indexOf('product_id') !== -1) || _this.name.indexOf('[amount]') !== -1) {
            order.updateProductSelect(_this);
            if (_this.name.indexOf('product_id') !== -1) {
                $.ajaxQueue({
                    'cache': false,
                    'url': link_getProductList,
                    'data': {id: val},
                    'success': function (data) {
                        $('.table_list_product > tbody.list').html(data);
                        order.searchProduct();
                    }
                });
            }
        } else if (_this.nodeName === 'INPUT' && _this.name.indexOf('ChiPhiThietKe') !== -1) {
            order.updateChiPhiTab(_this);
        } else if (_this.nodeName === 'INPUT' && _this.name.indexOf('bia_cung') !== -1) {
            if (rootFrame.find('input[name*="[bia_cung]"]').is(':checked')) {
                rootFrame.find('select[name*="ToGacKhoGiayId"]').val(rootFrame.find('select[name*="[GiayRuotKhoGiayId]"]').val());
                order.tinhChiPhiNVL(_this);
                rootFrame.find('.tbody_to_gac_order').show();
                rootFrame.find('.tbody_bia_carton_order').show();
            } else {
                rootFrame.find('input[name*="[ToGacChiPhi]"]').val(0);
                rootFrame.find('input[name*="[ToGacChiPhiVat]"]').val(0);
                rootFrame.find('.tbody_to_gac_order').hide();
                rootFrame.find('.tbody_bia_carton_order').hide();
            }
        } else if (_this.nodeName === 'INPUT' && (_this.name.indexOf('[nape]') !== -1 || this.name.indexOf('length') !== -1 || this.name.indexOf('width') !== -1 || this.name.indexOf('thick') !== -1)) {
            var product_id = order.getProductId(frame),
                product = order.getProductById(product_id);
            order.getProductSize(frame, product);
        } else if (_this.name.indexOf('ext_color_inner_page_amount') !== -1) {
            var count = $(_this).closest('li.trang-ruot-item').data('ruot');
            if(count === undefined || count === 0)
                count = '';
            rootFrame.find('input[name*="[QuyCachInRuot' + count + ']"]').val(val).trigger('change');
            rootFrame.find('input[name*="[QuyCachInRuotTayCuoi' + count + ']"]').val(val).trigger('change');
        } else if (_this.nodeName === 'INPUT' && (_this.name.indexOf('MauPhaBia') !== -1 || _this.name.indexOf('MauPhaRuot') !== -1 || _this.name.indexOf('ext_mau_pha_ruot') !== -1)) {
            if ($(_this).is(":checked")) {
                $(_this).closest('.col-sm-8').find('.he_so_mau_pha').show();
                $(_this).closest('.col-sm-3').find('.he_so_mau_pha').show();
            } else {
                $(_this).closest('.col-sm-8').find('.he_so_mau_pha').hide();
                $(_this).closest('.col-sm-3').find('.he_so_mau_pha').hide();
            }
        } else if (_this.nodeName === 'SELECT' && _this.name.indexOf('SoMauInBiaMax') !== -1) {
            rootFrame.find('input[name*="[QuyCachIn]"]').val(val).trigger('change');
        } else if (_this.nodeName === 'SELECT' && _this.name.indexOf('SoMauInRuotMax') !== -1) {
            rootFrame.find('input[name*="[QuyCachInRuot]"]').val(val).trigger('change');
            rootFrame.find('input[name*="[QuyCachInRuotTayCuoi]"]').val(val).trigger('change');
        } else if (_this.name.indexOf('KieuHop') !== -1) {
            if (Number(val) === hopMocDay) {
                rootFrame.find('.cai_day').show().css({'display': 'inline-block'});
            } else {
                rootFrame.find('.cai_day').hide();
            }
            if (Number(val) === hopCungDinhHinh) {
                rootFrame.find('.nap_hop').show().css({'display': 'inline-block'});
                rootFrame.find('.tbody_giay_ruot_order').show();
                rootFrame.find('.in_cung_kho_giay').hide();
            } else {
                rootFrame.find('.nap_hop').hide();
                rootFrame.find('input[name*="ChiPhiInRuot"][type="hidden"]').val(0);
                rootFrame.find('input[name*="GiayRuotChiPhi"][type="hidden"]').val(0);
                rootFrame.find('.tbody_giay_ruot_order').hide();
            }
            order.updateProductSelect(_this);
        } else if (_this.name.indexOf('bia_ghep_ruot') !== -1) {
            var $tbody_bia = $('.tbody_giay_bia_order'),
                $li_kieuin = $('li.inbia_elm');

            if ($(this).is(':checked')) {
                $('input[name*="GiayBiaThanhPham"], input[name*="GiayBiaSoTo"], input[name*="GiayBiaSoToBuHao"], input[name*="TongChiPhiInBia"], input[name*="TongChiPhiGiayInBia"]', $tbody_bia).val(0);
                $("select.ncc_select", $tbody_bia).prop("readonly", true);
                $('input, select', $tbody_bia).css({'pointer-events': 'none'});
                $('input, select', $li_kieuin).css({'pointer-events': 'none'});
            } else {
                $("select.ncc_select", $tbody_bia).prop("readonly", false);
                $('input, select', $tbody_bia).css({'pointer-events': 'auto'});
                $('input, select', $li_kieuin).css({'pointer-events': 'auto'});
            }
            order.updateProductSelect(this);
        }else if (this.nodeName === 'SELECT' && this.name.indexOf('he_so_muc') !== -1) {
            order.tinhChiPhiNVL(this);
        }else if (this.nodeName === 'SELECT' && this.name.indexOf('OrdersPaper') !== -1) {
            rootFrame.find('#giay-in select[name="'+this.name+'"]').val(val).trigger('change');
        }else{
            order.updateProductSelect(_this);
        }

        setTimeout(function () {
            $('.product_scroll').css({'height': ($('.control-sidebar-bg').height() - 105)});
        }, 500);

        if (
            this.name.indexOf('product_id') !== -1 || this.name.indexOf('KieuDong') !== -1 || this.name.indexOf('KieuHop') !== -1
            || this.name.indexOf('[amount]') !== -1 || this.name.indexOf('MauPhaBia') !== -1
            || this.name.indexOf('vt_nhip_bia') !== -1 || this.name.indexOf('MauPhaRuot') !== -1
            || this.name.indexOf('calcu_type') !== -1 || this.name.indexOf('thick') !== -1
            || this.name.indexOf('length') !== -1 || this.name.indexOf('width') !== -1 || this.name.indexOf('nape') !== -1
            || this.name.indexOf('tai_dai') !== -1 || this.name.indexOf('tai_rong') !== -1
            || this.name.indexOf('inner_page_amount') !== -1
            || this.name.indexOf('vi_tri_tai') !== -1 || this.name.indexOf('bia_ghep_ruot') !== -1
            || this.name.indexOf('kich_thuoc_tai') !== -1 || this.name.indexOf('tai_nap') !== -1
            || this.name.indexOf('boi_song_e') !== -1 || this.name.indexOf('kich_thuoc_cai_day') !== -1
            || this.name.indexOf('chua_kep_nhip') !== -1 || this.name.indexOf('kich_thuoc_nap') !== -1
        ) {
            var reset = 3;
            if (this.name.indexOf('MauPhaBia') !== -1 || this.name.indexOf('MauPhaRuot') !== -1)
                reset = 0;
            order.timKhoGiayBia(rootFrame, reset);
            var kieuHop = order.getProductKieuHop(rootFrame);
            if (Number(kieuHop) === hopCungDinhHinh) {
                order.timKhoGiayRuot(rootFrame, reset, '');
            }
        }
        var index = $(_this).closest('li.trang-ruot-item').data('ruot'),
            hasInnerPage = order.hasInnerPage(rootFrame);

        if(index === undefined || index === 0)
            index = '';

        if (_this.name.indexOf('[product_name]') === -1 && hasInnerPage) {
            if (
                _this.name.indexOf('[inner_page_amount]') !== -1
                || _this.name.indexOf('[MauPhaRuot]') !== -1
                || _this.name.indexOf('[vt_nhip_ruot]') !== -1
            ) {
                order.timKhoGiayRuot(rootFrame, 3, '');
            }
            else if (index >= 0 &&
                (
                    _this.name.indexOf('ext_inner_page_amount') !== -1
                    || _this.name.indexOf('[ext_mau_pha_ruot]') !== -1 || _this.name.indexOf('vt_nhip_ruot') !== -1
                )
            ) {
                order.timKhoGiayRuot(rootFrame, 3, index > 0 ? index : '');
            } else if (
                _this.name.indexOf('product_id') !== -1 || _this.name.indexOf('KieuDong') !== -1 || _this.name.indexOf('KieuHop') !== -1
                || _this.name.indexOf('[amount]') !== -1
                || _this.name.indexOf('length') !== -1 || _this.name.indexOf('width') !== -1 || _this.name.indexOf('chua_kep_nhip') !== -1
            ) {
                $('.trang-ruot-item', rootFrame).each(function (i) {
                    order.timKhoGiayRuot(rootFrame, 3, i > 0 ? i + 1 : '');
                });
            }
        }

        order.updateChiPhiTab(_this);
    });

    //them ruot moi
    doc.on('click', '.add-new-inner-page', function () {
        //them thong tin ruot moi
        var rootFrame = order.getRootFrame(this), new_inner_page = rootFrame.find('.trang-ruot-item:first').clone(),
            count_inner_page = rootFrame.find('.trang-ruot-item').length,
            product_id = order.getProductId(this);
        new_inner_page.find('.button-line').empty().html('<a href="javascript:;" class="remove-inner-page" title="Xóa ruột"><i class="fa fa-minus fa-lg"></i></a>');
        $('<div class="title-line"><span>Thông tin ruột ' + (count_inner_page + 1) + '</span></div>').insertBefore(new_inner_page.find('.has_inner_page:first'));
        var amount_input = new_inner_page.find('input[name*="[inner_page_amount]"]'),
            color_input = new_inner_page.find('select[name*="[SoMauInRuotMax]"]'),
            color_mix_input = new_inner_page.find('input[name*="[MauPhaRuot]"][type="checkbox"]'),
            color_mix_hidden = new_inner_page.find('input[name*="[MauPhaRuot]"][type="hidden"]'),
            kep_input = new_inner_page.find('input[name*="vt_nhip_ruot"]');
        amount_input.attr('id', amount_input.attr('id').replace('inner_page_amount', 'ext_inner_page_amount_' + (count_inner_page - 1)));
        amount_input.attr('name', amount_input.attr('name').replace('[inner_page_amount]', '[ext_inner_page_amount][]'));
        amount_input.val(0);
        color_input.attr('id', color_input.attr('id').replace('somauinruotmax', 'ext_color_inner_page_amount_' + (count_inner_page - 1)));
        color_input.attr('name', color_input.attr('name').replace('[SoMauInRuotMax]', '[ext_color_inner_page_amount][]'));
        color_input.val(rootFrame.find('select[name*="[SoMauInRuotMax]"]').val());
        color_mix_input.attr('id', color_mix_input.attr('id').replace('maupharuot', 'ext_mau_pha_ruot_' + (count_inner_page - 1)));
        color_mix_input.attr('name', color_mix_input.attr('name').replace('[MauPhaRuot]', '[ext_mau_pha_ruot][]'));
        color_mix_hidden.attr('name', color_mix_hidden.attr('name').replace('[MauPhaRuot]', '[ext_mau_pha_ruot][]'));
        kep_input.each(function () {
            this.name = this.name.replace('[vt_nhip_ruot]', '[vt_nhip_ruot' + (count_inner_page + 1) + ']');
        });

        new_inner_page.find('select[name*="GiayRuotChatLieu"], select[name*="GiayRuotDinhLuong"], select[name*="GiayRuotKhoGiayId"]').each(function () {
            var value = rootFrame.find('#san-pham select[name="'+this.name+'"]').val();
            this.name = this.name.replace(/\[([a-zA-Z]+)\]/g, "[$1" + (count_inner_page + 1) + "]");
            if(this.name.indexOf('GiayRuotKhoGiayId') === -1)
                this.value = value;
        });
        new_inner_page.find('.userChanged').removeClass('userChanged');
        $(new_inner_page).attr('data-ruot', count_inner_page+1);
        $(new_inner_page).insertAfter('.trang-ruot-item:last');
        //them thong tin kieu in
        order.fillGiayRuotThem(this);
        order.fillKieuInThem(this);
        if (product_id > 0) {
            order.fillOutput(this, true);
            order.fillGiaCong(this, true);
        }
        setTimeout(function() {
            rootFrame.find('.gia-cong-item:last').find('select[name*="Bia_Ruot"]').val(count_inner_page + 1);
        }, 1000);
        // order.updateProductSelect(this);
    });

    //xoa ruot
    doc.on('click', '.remove-inner-page', function () {
        var data_ruot = $(this).closest('li.trang-ruot-item').data('ruot');
        $(this).closest('.trang-ruot-item').remove();
        $('.trang-ruot-item:not(:first-child)').each(function (i) {
            var _this = $(this);
            _this.find('.title-line span').html('Thông tin ruột ' + (i + 2));
            var amount_input = _this.find('input[name*="ext_inner_page_amount"]'),
                color_input = _this.find('select[name*="ext_color_inner_page_amount"]'),
                color_mix_input = _this.find('input[name*="ext_mau_pha_ruot"][type="checkbox"]'),
                kep_input = _this.find('input[name*="vt_nhip_ruot"]');
            amount_input.attr('id', amount_input.attr('id').replace(/ext_inner_page_amount_[0-9]/g, 'ext_inner_page_amount_' + (i + 1)));
            color_input.attr('id', color_input.attr('id').replace(/ext_color_inner_page_amount_[0-9]/g, 'ext_color_inner_page_amount_' + (i + 1)));
            color_mix_input.attr('id', color_mix_input.attr('id').replace(/ext_mau_pha_ruot_[0-9]/g, 'ext_mau_pha_ruot_' + (i + 1)));
            kep_input.each(function () {
                this.name = this.name.replace(/vt_nhip_ruot[0-9]/g, 'vt_nhip_ruot' + (i + 2));
            });
        });
        $('.kieuin-item.inruot_elm[data-ruot="'+data_ruot+'"]').remove();
        $('.kieuin-item.inruot_elm').each(function (i) {
            if(i > 0){
                $(this).find('th:eq(0)').html('In ruột loại ' + (i + 1));
                $(this).attr('data-ruot', i + 1);
                if($(this).hasClass('taycuoi_elm'))
                    $(this).attr('data-tay_cuoi', i + 1);
                $(this).find('input, select').each(function () {
                    this.name = this.name.replace(/[0-9]+(?!.*[0-9])/g, (i + 1));
                });
            }
        });
        $('tbody.tbody_giay_ruot_order[data-ruot="'+data_ruot+'"]').remove();
        $('tbody.tbody_giay_ruot_order').each(function (i) {
            if(i > 0){
                $(this).find('.ten-loai-giay').html('Giấy ruột '+(i + 1));
                $(this).attr('data-ruot', i + 1);
                $(this).find('input, select').each(function () {
                    this.name = this.name.replace(/[0-9]+(?!.*[0-9])/g, (i + 1));
                });
            }
        });
        $('select[name*="ExportBiaRuot"] option[value="ruot'+data_ruot+'"]:selected').closest('.output-item').remove();
        $('select[name*="ExportBiaRuot"] option[value="ruot'+data_ruot+'"]').remove();
        $('select[name*="Bia_Ruot"] option[value="ruot'+data_ruot+'"]:selected').closest('.gia-cong-item').remove();
        $('select[name*="Bia_Ruot"] option[value="ruot'+data_ruot+'"]').remove();
    });

    //them xuat ra
    doc.on('click', '.them_loai_xuat_ra', function () {
        order.fillOutput(this);
    });

    //them gia cong
    doc.on('click', '.them_loai_gia_cong', function () {
        var _this = this, rootFrame = order.getRootFrame(_this),
            count_giacong = rootFrame.find('.gia-cong-item').length,
            count_inner_page = $('.trang-ruot-item').length,
            product_id = order.getProductId(_this),
            product = order.getProductById(product_id);
        if (!product)
            return false;
        $.ajaxQueue({
            'type': 'post',
            'url': link_fillGiaCong,
            'data': {
                count_order: 0,
                count_giacong: count_giacong,
                count_inner_page: count_inner_page,
                product: product,
                is_photo: is_photo,
                is_ruot: ''
            },
            'success': function (html) {
                rootFrame.find('.gia-cong-lists').append(html);
                rootFrame.find('.gia-cong-lists .gia-cong-item:last select[name*="NhaCungCapId"]').select2({
                    allowClear: true,
                    language: "vi",
                    placeholder: "Chọn nhà cung cấp",
                    theme: "krajee",
                    width: "100%"
                });
                if (is_photo === 1)
                    order.updateGiaCongPhotoList();
                order.tinhChiPhiGiaCong(_this);
                order.updateChiPhiTab(_this);
            }
        });
    });

    //xoa chi phi khac
    doc.on('click', '.remove_other_cost_row', function () {
        var _this = this, rootFrame = order.getRootFrame(_this);
        krajeeDialog.confirm("Bạn có chắc muốn xóa mục này?", function (result) {
            if (result) {
                $(_this).closest('tr').remove();
                order.tinhChiPhiNVL(rootFrame);
            }
        });
    });

    //xoa xuat ra
    doc.on('click', '.xoa-xuat-ra', function () {
        var _this = this, rootFrame = order.getRootFrame(_this);
        krajeeDialog.confirm("Bạn có chắc muốn xóa mục này?", function (result) {
            if (result) {
                $(_this).closest('.output-item').remove();
                order.updateOutputList(rootFrame);
                order.updateChiPhiTab(rootFrame);
            }
        });
    });

    //xoa gia cong
    doc.on('click', '.xoa-gia-cong', function () {
        var _this = this, rootFrame = order.getRootFrame(_this);
        krajeeDialog.confirm("Bạn có chắc muốn xóa mục này?", function (result) {
            if (result) {
                $(_this).closest('.gia-cong-item').remove();
                order.updateGiaCongList(rootFrame);
                order.updateChiPhiTab(rootFrame);
            }
        });

    });

    //them chi phi khac
    doc.on('click', '.them_chi_phi_khac', function () {
        var rootFrame = order.getRootFrame(this),
            list = rootFrame.find('.table_chi_phi_khac tbody tr:last'),
            count = rootFrame.find('.table_chi_phi_khac tbody tr').length,
            html = rootFrame.find('.table_chi_phi_khac tfoot tr:first-child').clone().removeAttr('style', '');
        html.find('td:eq(0)').html(count);
        html.find('select, input').each(function () {
            if ($(this).is("select")) {
                var el = 'otherCostSelect_' + (count + 1);
                this.id = this.id.replace('othercost-othercostselect', el);
                this.value = '';
                $(this).select2({
                    allowClear: true,
                    language: "vi",
                    placeholder: "Nhập tiêu đề",
                    theme: "krajee",
                    width: "100%",
                    tags: true
                });
            } else if (this.name.indexOf('time') !== -1) {
                this.name = this.name.replace('[1000]', '[' + (count + 1) + ']');
                this.id = this.id.replace('-1000-', '-' + (count + 1) + '-');
                $(this).kvDatepicker({
                    autoclose: true,
                    format: 'dd-mm-yyyy'
                });
            } else {
                this.name = this.name.replace('[1000]', '[' + (count + 1) + ']');
                this.id = this.id.replace('-1000-', '-' + (count + 1) + '-');
            }
        });
        $(html).insertAfter(list);
        order.numberInput();
    });

    //thay doi thong tin chi phi khac
    doc.on('change', '[id*="otherCostSelect"]', function () {
        var _this = this, $this = $(this),
            banTinh = $this.data('bantinh'),
            $parent = $this.closest('tr');
        $.ajaxQueue({
            'url': link_getTonKho,
            'type': 'get',
            'dataType': 'json',
            'data': {'id': $this.val()},
            'success': function (data) {
                if (!$.isEmptyObject(data)) {
                    $parent.find('input[name*="[unit]"]').val(data.donvi);
                    $parent.find('input[name*="[unit_price]"]').val(data.dongia);
                    $parent.find('input[name*="[title]"]').val(data.title);
                    $parent.find('input[name*="[type]"]').val(data.type);
                    $parent.find('input[name*="[tonkho_id]"]').val(data.id);
                }
                if ($parent.find('input[name*="[title]"]').val().trim() === '')
                    $parent.find('input[name*="[title]"]').val($this.val());
                if (banTinh === undefined || (banTinh !== undefined && banTinh !== 'thuongmai'))
                    order.tinhChiPhiNVL(_this);
            }
        });
    });

    doc.on('click', '.btn.tinh-chi-phi, .btn.copy-don-hang', function () {
        if (has_error === 1) {
            krajeeDialog.alert('Vui lòng xem lại các cảnh báo về dữ liệu đầu vào được đóng khung màu đỏ');
            return false;
        } else {
            $('div').removeClass('has-error');
            $('.help-block').html('');
            var formData = new FormData($('#form-create-order')[0]);
            if(this.className.indexOf('copy-don-hang') !== -1)
                formData.delete('id');

            $.ajaxQueue({
                type: 'POST',
                url: link_create,
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function (data) {
                    // console.log(data);
                    if (data) {
                        if (data.status === 'success') {
                            unsaved = false;
                            window.location.href = data.link;
                        } else {
                            var count = -1, top = 0;
                            $.each(data, function (k, v) {
                                count++;
                                $.each(v, function (k1, v1) {
                                    var field = k1.split('-'),
                                        form_group,
                                        arr = jQuery.grep(field, function (n, i) {
                                            return ( n !== 'orderinfo');
                                        });
                                    arr = arr.join();
                                    if (arr === 'customer_id')
                                        top = 1;
                                    else if (top === 0)
                                        top = 2;
                                    form_group = $('.field-orderinfo-' + count + '-' + arr);
                                    form_group.addClass('has-error');
                                    form_group.find('.help-block').html(v1[0]);
                                });
                            });
                            if (top === 1)
                                $('html, body').animate({scrollTop: $("#khach-hang").offset().top}, 500);
                            else
                                $('html, body').animate({scrollTop: $("#san-pham").offset().top}, 500);
                        }
                    }
                }
            });
        }
    });

    //tinh chi phi khac
    doc.on('change', 'input[name*="[quantity]"], input[name*="[unit_price]"]', function (e) {
        var rootFrame = order.getRootFrame(this), tr = $(this).closest('tr'),
            quantity = tr.find('input[name*="[quantity]"]').val(),
            price = tr.find('input[name*="[unit_price]"]').val(),
            total = 0, total_vat;
        quantity = order.removeFormat(quantity);
        price = order.removeFormat(price);
        if (!isNaN(quantity) && !isNaN(price))
            total = quantity * price;
        if (!isNaN(total))
            tr.find('input[name*="[price]"]').val(total);

        total = 0;
        rootFrame.find('.table_chi_phi_khac input[name*="[price]"]').each(function () {
            price = $(this).val();
            price = parseFloat(order.removeFormat(price));
            if (!isNaN(price))
                total += price;
        });

        total_vat = Math.round(total + total * 0.1);
        rootFrame.find('.TongChiPhiKhacTxt').html(total).formatCurrency();
        rootFrame.find('input[name*="[tongChiPhiKhac]"]').val(total);
        rootFrame.find('.TongChiPhiKhacVATTxt').html(total_vat).formatCurrency();
        rootFrame.find('input[name*="[tongChiPhiKhacVAT]"]').val(total_vat);
        if (e.hasOwnProperty('originalEvent'))
        $(this).addClass('userChanged');
        order.updateChiPhiTab(this);
        order.numberInput();
    });

    doc.on('click', 'input[name*="[is_photo]"]', function () {
        var rootFrame = order.getRootFrame(this),
            $not_photo = rootFrame.find('.is_not_photo'),
            $is_photo = rootFrame.find('.is_photo'),
            $a_photo = $('a.is_photo'),
            $a_not_photo = $('a.is_not_photo');
        if ($(this).is(':checked')) {
            is_photo = 1;
            $not_photo.slideUp();
            $is_photo.slideDown();
            $a_photo.show();
            $a_not_photo.hide();
            $('.gia-cong-lists').empty();
            $('.output-lists').empty();
            $('.price_elm').val(0);
            $('.table_photo input[name*="so_luong"]:first').trigger('change');
        } else {
            is_photo = 0;
            $not_photo.slideDown();
            $is_photo.slideUp();
            $a_photo.hide();
            $a_not_photo.show();
            order.updateGiaCongPhotoList();
            $('input[name*="TongChiPhiPhoto"]').val(0);
            rootFrame.find('select[name*="product_id"]').trigger('change');
        }
        setTimeout(function () {
            $('.product_scroll').css({'height': ($('.control-sidebar-bg').outerHeight() - 105)});
            order.updateChiPhiTab(rootFrame);
        }, 500);
    });

    //thay doi chi phi thiet ke
    doc.on('change', 'input[name*="[ChiPhiThietKe]"]', function () {
        order.updateChiPhiTab(this);
    });

    //thay doi thong tin kieu in
    doc.on('change', '.kieuin-item input, .kieuin-item select', function (e, wasTriggered) {
        var rootFrame = order.getRootFrame(this),
            elm = $(this).closest('.kieuin-item'),
            kem_dai, kem_rong, quycach, val = $(this).val(), kem_gia, gia_kem_ruot, gia_kem_ruot_cm;
        val = order.removeFormat(val);
        if (e.hasOwnProperty('originalEvent'))
        $(this).addClass('userChanged');
        if (this.nodeName === 'SELECT' && this.name.indexOf('KhoMayIn') !== -1) {
            order.selectKhoMayIn(this, $(this).closest('.kieuin-item'));
        } else if (this.nodeName === 'INPUT' && (this.name.indexOf('DaiKemBia') !== -1 || this.name.indexOf('RongKemBia') !== -1)) {
            kem_dai = elm.find('input[name*="DaiKemBia"]').val();
            kem_rong = elm.find('input[name*="RongKemBia"]').val();
            kem_gia = elm.find('input[name*="DonGiaKemCmBia"]').val();
            kem_gia = order.removeFormat(kem_gia);
            kem_gia = parseFloat(kem_dai) * parseFloat(kem_rong) * parseFloat(kem_gia);
            if (isNaN(kem_gia))
                kem_gia = 0;
            elm.find('input[name*="DonGiaKemBia"]').val(kem_gia);
        } else if (this.nodeName === 'INPUT' && this.name.indexOf('DonGiaKemBia') !== -1) {
            kem_dai = elm.find('input[name*="DaiKemBia"]').val();
            kem_rong = elm.find('input[name*="RongKemBia"]').val();
            kem_gia = Math.round(parseFloat(val) / parseFloat(kem_dai) / parseFloat(kem_rong));
            if (isNaN(kem_gia))
                kem_gia = 0;
            elm.find('input[name*="DonGiaKemCmBia"]').val(kem_gia);

        } else if (this.nodeName === 'INPUT' && this.name.indexOf('DonGiaKemCmBia') !== -1) {
            kem_dai = elm.find('input[name*="DaiKemBia"]').val();
            kem_rong = elm.find('input[name*="RongKemBia"]').val();
            kem_gia = Math.round(parseFloat(val) * parseFloat(kem_dai) * parseFloat(kem_rong));
            if (isNaN(kem_gia))
                kem_gia = 0;
            elm.find('input[name*="DonGiaKemBia"]').val(kem_gia);
        } else if (this.nodeName === 'INPUT' && (this.name.indexOf('DaiKemRuot') !== -1 || this.name.indexOf('RongKemRuot') !== -1)) {
            kem_dai = elm.find('input[name*="DaiKemRuot"]').val();
            kem_rong = elm.find('input[name*="RongKemRuot"]').val();
            kem_gia = elm.find('input[name*="DonGiaKemCmRuot"]').val();
            gia_kem_ruot = parseFloat(kem_dai) * parseFloat(kem_rong) * parseFloat(kem_gia);
            if (isNaN(gia_kem_ruot))
                gia_kem_ruot = 0;
            $('input[name*="DonGiaKemRuot"]').val(gia_kem_ruot);
        } else if (this.nodeName === 'INPUT' && this.name.indexOf('DonGiaKemRuot') !== -1) {
            kem_dai = elm.find('input[name*="DaiKemRuot"]').val();
            kem_rong = elm.find('input[name*="RongKemRuot"]').val();
            gia_kem_ruot_cm = Math.round(parseFloat(val) / parseFloat(kem_dai) / parseFloat(kem_rong));
            if (isNaN(gia_kem_ruot_cm))
                gia_kem_ruot_cm = 0;
            elm.find('input[name*="DonGiaKemCmRuot"]').val(gia_kem_ruot_cm);
        } else if (this.nodeName === 'INPUT' && this.name.indexOf('DonGiaKemCmRuot') !== -1) {
            kem_dai = elm.find('input[name*="DaiKemRuot"]').val();
            kem_rong = elm.find('input[name*="RongKemRuot"]').val();

            gia_kem_ruot = Math.round(parseFloat(val) * parseFloat(kem_dai) * parseFloat(kem_rong));
            if (isNaN(gia_kem_ruot))
                gia_kem_ruot = 0;
            elm.find('input[name*="DonGiaKemRuot"]').val(gia_kem_ruot);
        } else if (this.name.indexOf('QuyCachIn') !== -1) {
            this.value = $.trim(this.value);
            if (this.value === '0/0') {
                $(this).css('outline', '2px solid #c00').attr('title', 'Quy cách in không hợp lệ.');
                elm.find('.QuyCachInTxt').empty();

            } else if (this.value.match(/^(\d+)\/(\d+)$/)) {
                $(this).css('outline', 'none').attr('title', '');
                order.tinhQuyCachIn(this.value, rootFrame);
                order.tinhChiPhiXuatRa(this, false);
                order.tinhChiPhiGiaCong(this);
            } else {
                $(this).css('outline', '2px solid #c00').attr('title', 'Quy cách in không hợp lệ.');
            }
        } else if (this.name.indexOf('[kieu_in_tro]') !== -1) {
            $(elm).find('.fa-unlock-alt').addClass('fa-lock').removeClass('fa-unlock-alt');
            order.tinhQuyCachIn(null, rootFrame);
        } else if (this.name.indexOf('kieu_in_tro_ruot') !== -1) {
            $(elm).find('.fa-unlock-alt').addClass('fa-lock').removeClass('fa-unlock-alt');
            quycach = $.trim(elm.find('input[name*="QuyCachInRuot"]').val());
            if (quycach === '0/0') {
                $(this).css('outline', '2px solid #c00').attr('title', 'Quy cách in không hợp lệ.');
                elm.find('.QuyCachInTxt').empty();

            } else if (quycach.match(/^(\d+)\/(\d+)$/)) {
                $(this).css('outline', 'none').attr('title', '');
                order.tinhQuyCachIn(quycach, rootFrame, elm);

            } else {
                $(this).css('outline', '2px solid #c00').attr('title', 'Quy cách in không hợp lệ.');
            }
        }

        var hasInnerPage = order.hasInnerPage(rootFrame),
            count_ = $(this).closest('li.inruot_elm').data('ruot'),
            count_taycuoi = $(this).closest('li.taycuoi_elm').data('tay_cuoi'),
            reset = 0, kieuHop = order.getProductKieuHop(rootFrame);
        if (this.name.indexOf('QuyCachIn') !== -1)
            reset = 3;
        if (wasTriggered === undefined || wasTriggered === true) {

            if ($(this).closest('.kieuin-item').hasClass('inbia_elm')) {
                order.timKhoGiayBia(rootFrame, reset);
                if (Number(kieuHop) === hopCungDinhHinh) {
                    order.timKhoGiayRuot(rootFrame, reset, '');
                }
            }
            else if (((count_ !== undefined || count_taycuoi !== undefined) && hasInnerPage) || Number(kieuHop) === hopCungDinhHinh) {
                if (count_ === 0) {
                    rootFrame.find('select[name*="[GiayRuotKhoGiayId]"]').val('');
                    order.timKhoGiayRuot(rootFrame, reset, '');
                } else if (count_taycuoi === 0 && parseInt(rootFrame.find('input[name*="[TrangDu]"]').val()) > 0) {
                    rootFrame.find('select[name*="[TayCuoiKhoGiayId]"]').val('');
                    if (
                        (parseFloat(rootFrame.find('input[name*="[GiayRuotTayCuoiLength]"]').val()) > parseFloat(rootFrame.find('input[name*="[DaiKemRuotTayCuoi]"]').val())
                            ||
                            parseFloat(rootFrame.find('input[name*="[GiayRuotTayCuoiWidth]"]').val()) > parseFloat(rootFrame.find('input[name*="[RongKemRuotTayCuoi]"]').val()))
                        && rootFrame.find('input[name*="[InCungKhoGiay]"]').is(':checked')
                    ) {
                        rootFrame.find('input[name*="[InCungKhoGiay]"]').prop('checked', false);
                    }

                    order.timKhoGiayRuotTayCuoi(rootFrame, reset, '');
                } else if (count_ > 0) {
                    rootFrame.find('select[name*="[GiayRuotKhoGiayId' + count_ + ']"]').val('');
                    order.timKhoGiayRuot(rootFrame, reset, count_);
                } else if (count_taycuoi > 0 && parseInt(rootFrame.find('input[name*="[TrangDu' + count_taycuoi + ']"]').val()) > 0) {
                    rootFrame.find('select[name*="[TayCuoiKhoGiayId' + count_taycuoi + ']"]').val('');
                    if (
                        (parseFloat(rootFrame.find('input[name*="[GiayRuotTayCuoiLength' + count_taycuoi + ']"]').val()) > parseFloat(rootFrame.find('input[name*="[DaiKemRuotTayCuoi' + count_taycuoi + ']"]').val())
                            ||
                            parseFloat(rootFrame.find('input[name*="[GiayRuotTayCuoiWidth' + count_taycuoi + ']"]').val()) > parseFloat(rootFrame.find('input[name*="[RongKemRuotTayCuoi' + count_taycuoi + ']"]').val()))
                        && rootFrame.find('input[name*="[InCungKhoGiay' + count_taycuoi + ']"]').is(':checked')
                    ) {
                        rootFrame.find('input[name*="[InCungKhoGiay' + count_taycuoi + ']"]').prop('checked', false);
                    }

                    order.timKhoGiayRuotTayCuoi(rootFrame, reset, count_taycuoi);
                }
            }
        }

        order.numberInput();
    });

    //thay doi thong tin kieu in chung
    doc.on('change', '.printTypeTable input, .printTypeTable select', function () {
        var rootFrame = order.getRootFrame(this),
            val = $(this).val();
        if (this.nodeName === 'SELECT' && this.name.indexOf('NhaCungCapIn') !== -1) {
            if (supplier_json[val]) {
                rootFrame.find('input[name*="calcu_type"]').val(supplier_json[val].calcu_type);
            }
            $.ajaxQueue({
                'url': link_getKhoMayIn,
                'type': 'get',
                'data': {'supplier': $(this).val()},
                'success': function (data) {
                    rootFrame.find(khoMayInBia).html(data);
                    rootFrame.find(khoMayInRuot).html(data);
                }
            });
            // order.setPrintTypeData();
            order.selectNhaCungCapIn($(this).closest('.printTypeTable'));
            $('.tim_kho_giay_in').trigger('click');
        } else if (this.name.indexOf('calcu_type') !== -1) {
            rootFrame.find(".tim_kho_giay_in").trigger('click');
        } else if (this.nodeName === 'INPUT' && (this.name.indexOf('DonGiaInHasntFormula') || this.name.indexOf('SoLuongInHasntFormula'))) {
            order.updateChiPhiTab(this);
        }
    });

    doc.on('change', 'input[name*="[checkbox_hasnt_formula_kieu_in]"]', function () {
        var rootFrame = order.getRootFrame(this), printTypeTable = $(this).closest('.printTypeTable');
        if ($(this).is(":checked")) {
            printTypeTable.find(".hasnt_formula_print_type").hide();
            printTypeTable.find(".show_don_gia").show();
            printTypeTable.find('input[name*="SoLuongInHasntFormula"]').val(rootFrame.find('input[name*="amount"]').val());
            rootFrame.find('#kieu-in .panel-title .chiPhiText').show();
        } else {
            printTypeTable.find(".hasnt_formula_print_type").show();
            printTypeTable.find(".show_don_gia").hide();
            printTypeTable.find('input[name*="SoLuongInHasntFormula"]').val(0);
            rootFrame.find('#kieu-in .panel-title .chiPhiText').hide();
        }
    });

    //thay doi thong tin in test
    doc.on('change', '.print_test_frm input, .print_test_frm select', function () {

        var rootFrame = order.getRootFrame(this),
            frame = $(this).closest('.print_test_frm');
        if ($(this).hasClass('DonGiaInTestTxt')) {
            frame.find('input[name*="DonGiaInTest"][type="hidden"]').val(this.value).toNumber();

        } else if (this.name.indexOf('NhaCungCapInTest') !== -1) {
            var supplier = supplier_json[this.value];
            if (supplier.unit_price_it > 0) {
                frame.find('input[name*="[DonGiaInTest]"]').val(supplier.unit_price_it);
                frame.find('input[name*="[DonGiaInTest]"][type="hidden"]').val(supplier.unit_price_it);
                order.numberInput();
            }
        } else if (this.name.indexOf('[InTest]') !== -1) {
            if (Number($(this).val()) === 1) {
                frame.find('input, select, button').not(this).prop('disabled', false);
            } else {
                frame.find('input, select, button').not(this).prop('disabled', true);
                frame.find('select').not(this).val('');
                frame.find('input').not(this).val(0);
            }
        }
        order.tinhChiPhiInTest(rootFrame);
    });

    //thay doi thong tin van chuyen
    doc.on('change', '.table_chi_phi_van_chuyen input, .table_chi_phi_van_chuyen select', function () {
        var rootFrame = order.getRootFrame(this);
        if (this.name.indexOf('vanChuyenTan_Chuyen') !== -1) {
            if (Number(this.value) === Number(vChuyen_Chuyen)) {
                rootFrame.find('.Orderdata_chuyen').show();
                rootFrame.find('.Orderdata_weight').hide();
                rootFrame.find('[class*="vanchuyendongia"]').find('.input-group-addon').html('đ/chuyến');
            } else {
                rootFrame.find('.Orderdata_chuyen').hide();
                rootFrame.find('.Orderdata_weight').show();
                rootFrame.find('[class*="vanchuyendongia"]').find('.input-group-addon').html('đ/kg');
                order.tinhTrongLuongDonHang(this);
            }
        }
        var type = parseInt(rootFrame.find('select[name*="vanChuyenTan_Chuyen"]').val()),
            soChuyen = rootFrame.find('input[name*="vanChuyenSoChuyen"]').val(),
            weight = rootFrame.find('input[name*="vanChuyenWeight"]').val(),
            donGia = rootFrame.find('input[name*="vanChuyenDonGia"]').val(),
            chiPhiVanChuyen = 0;

        soChuyen = order.removeFormat(soChuyen);
        weight = order.removeFormat(weight);
        donGia = order.removeFormat(donGia);

        if (type === Number(vChuyen_Chuyen)) {
            chiPhiVanChuyen = soChuyen * donGia;
        } else if (type === Number(vChuyen_tLuong)) {
            chiPhiVanChuyen = weight * donGia;
        }
        chiPhiVanChuyen = Math.round(chiPhiVanChuyen);
        rootFrame.find('.vanChuyenChiPhiTxt').html(chiPhiVanChuyen).formatCurrency();
        rootFrame.find('input[name*="[vanChuyenChiPhi]"]').val(chiPhiVanChuyen);
        order.updateChiPhiTab(this);
    });

    //thay doi thong tin xuat ra
    doc.on('change', '.output-item select, .output-item input', function (e) {
        var _this = this, rootFrame = order.getRootFrame(_this),
            frame = $(_this).closest('.output-item'), bia_ghep_ruot = 0,
            bia_ruot = frame.find('select[name*="ExportBiaRuot"]').val(),
            loai_xuat = Number(frame.find('select[name*="ExportType"]').val()),
            supplier_id = Number(frame.find('select[name*="ExportNhaCungCap"]').val()),
            supplier = supplier_json[supplier_id];

        if (rootFrame.find('input[name*="bia_ghep_ruot"]').is(':checked'))
            bia_ghep_ruot = 1;

        if (_this.name.indexOf('ExportType') !== -1 || _this.name.indexOf('ExportNhaCungCap') !== -1) {
            if (supplier) {
                if (loai_xuat === kieuRaPhim) {
                    frame.find('input[name*="ExportDonGia"]').val(supplier.unit_price);
                    frame.find('.output_kichthuoc').show();
                } else {
                    frame.find('input[name*="ExportDonGia"]').val(supplier.don_gia_ra_kem);
                    frame.find('.output_kichthuoc').hide();
                }
            }
        } else if (_this.name.indexOf('ExportBiaRuot') !== -1) {
            var giayruot_number, soTay;
            giayruot_number = bia_ruot.replace(/ruot/g, '');
            soTay = parseFloat(rootFrame.find('input[name*="[SoTay' + giayruot_number + ']"]').val());
            if (bia_ruot === 'bia') {
                frame.find('.output_soluong').hide();
                frame.find('.output_taycuoi').hide();
            } else if (!bia_ruot) {
                frame.find('.output_soluong').show();
                frame.find('.output_taycuoi').hide();
                if (soTay % 1 !== 0)
                    frame.find('.output_taycuoi').show();
            }
        } else if ((_this.name.indexOf('ExportLength') !== -1 || _this.name.indexOf('ExportWidth') !== -1) && e.hasOwnProperty('originalEvent')) {
            $(_this).addClass('userChanged');
        } else if (_this.name.indexOf('ExportSoLuong') !== -1 && e.hasOwnProperty('originalEvent')) {
            $(_this).addClass('userChanged');
        }
        order.tinhChiPhiXuatRa(_this);
        order.updateChiPhiTab(_this);
    });

    //thay doi thong tin gia cong
    doc.on('change', '.gia-cong-item select, .gia-cong-item input', function (e) {
        var _this = this, frame = $(_this).closest('.gia-cong-item'),
            bia_ghep_ruot = 0, giayruot_number, val = $(_this).val(),
            rootFrame = order.getRootFrame(_this), nodeName = _this.nodeName, name = _this.name;
        if (e.hasOwnProperty('originalEvent'))
        $(_this).addClass('userChanged');
        if (rootFrame.find('input[name*="bia_ghep_ruot"]').is(':checked'))
            bia_ghep_ruot = 1;

        if (nodeName === 'SELECT' && name.indexOf('Bia_Ruot') !== -1) {
            giayruot_number = val.replace(/ruot/g, '');
            if (giayruot_number > 0 && bia_ghep_ruot === 1) {

            }
        } else if (nodeName === 'SELECT' && name.indexOf('LoaiGiaCongId') !== -1) {
            var gia_cong = order.getContentById(val),
                cong_thuc_tinh = gia_cong.formula, NhaCungCapId = frame.find('select[name*="NhaCungCapId"]');
            if (cong_thuc_tinh) {

                frame.find('.formula_gia_cong').hide();
                if (cong_thuc_tinh.indexOf('dai') !== -1) {
                    frame.find('.formula_gia_cong.gia_cong_kich_thuoc, .formula_gia_cong.gia_cong_kich_thuoc_dai').show();
                }

                if (cong_thuc_tinh.indexOf('rong') !== -1) {
                    frame.find('.formula_gia_cong.gia_cong_kich_thuoc, .formula_gia_cong.gia_cong_kich_thuoc_rong').show();
                }

                if (cong_thuc_tinh.indexOf('soLuongSanPham') !== -1 || cong_thuc_tinh.indexOf('soLuong') !== -1) {
                    frame.find('.formula_gia_cong.gia_cong_so_luong').show();
                }

                if (cong_thuc_tinh.indexOf('donGia') !== -1) {
                    frame.find('.formula_gia_cong.gia_cong_don_gia').show();
                }

                if (cong_thuc_tinh.indexOf('soMatCan') !== -1) {
                    frame.find('.formula_gia_cong.gia_cong_so_mat').show();
                }

                if (cong_thuc_tinh.indexOf('soToIn') !== -1) {
                    frame.find('.formula_gia_cong.gia_cong_so_to_in').show();
                }

                if (cong_thuc_tinh.indexOf('soTrang') !== -1) {
                    frame.find('.formula_gia_cong.gia_cong_so_trang').show();
                }

                var product_id = order.getProductId(_this), gia_cong_list,
                    productJson = order.getProductById(product_id);
                if (productJson && productJson.ncc_gia_cong_default) {
                    frame.find('select[name*="Bia_Ruot"]').closest('tr').show();
                    frame.find('select[name*="Bia_Ruot"] option').each(function () {
                        $(this).attr("disabled", false);
                    });

                    gia_cong_list = productJson.gia_cong_mac_dinh;
                    $.each(gia_cong_list, function (i) {
                        if (parseFloat(gia_cong_list[i].LoaiGiaCongId) === parseFloat(val)) {
                            if (gia_cong_list[i].Bia_Ruot === 'bia') {
                                frame.find('select[name*="Bia_Ruot"] option[value="ruot"]').attr("disabled", true);
                                frame.find('select[name*="Bia_Ruot"]').val('bia');
                            } else if (gia_cong_list[i].Bia_Ruot === 'ruot') {
                                frame.find('select[name*="Bia_Ruot"] option[value="bia"]', frame).attr("disabled", true);
                                frame.find('select[name*="Bia_Ruot"]').val('ruot');
                            } else if (gia_cong_list[i].Bia_Ruot === 'chung') {
                                frame.find('select[name*="Bia_Ruot"]').closest('tr').hide();
                            }
                            return false;
                        }
                    });
                }
            }
            if (val > 0) {
                $.ajaxQueue({
                    'url': link_nccGiaCong,
                    'data': {'gia_cong_id': val},
                    'type': 'get',
                    'success': function (options) {
                        $('option', NhaCungCapId).not($('option:first-child', NhaCungCapId)).remove();
                        NhaCungCapId.append(options);
                    }
                });
            }
        } else if (nodeName === 'SELECT' && name.indexOf('NhaCungCapId') !== -1) {
            var gia_cong_id = frame.find('select[name*="LoaiGiaCongId"]').val();
            if (supplier_json && val !== 'add_new' && supplier_json[val].loai_gia_cong_su_dung) {
                var loai_gia_cong_su_dung = $.parseJSON(supplier_json[val].loai_gia_cong_su_dung),
                    don_gia = loai_gia_cong_su_dung[gia_cong_id];
                frame.find('input[name*="DonGia"]').val(don_gia);
                order.numberInput();
            }
        } else if (name.indexOf('Length') !== -1 || name.indexOf('Width') !== -1 || name.indexOf('SoMat') !== -1 || name.indexOf('[soLuong]') !== -1 || name.indexOf('[soToIn]') !== -1 || name.indexOf('[soTrang]') !== -1 || name.indexOf('[soToInTayCuoi]') !== -1 || name.indexOf('[soTrangTayCuoi]') !== -1) {

        }

        order.tinhChiPhiGiaCong(_this);
        order.updateChiPhiTab(_this);
    });

    doc.on('change', 'input[name*="[checkbox_hasnt_formula_gia_cong]"]', function () {
        order.tinhChiPhiGiaCong(this);
    });

    $('input[name*="amount"]').on('focusin', function () {
        $(this).data('val', $(this).val());
    }).on('change', function () {
        var val = $(this).data('val');
        val = order.removeFormat(val);
        if (val > 0) {
            krajeeDialog.alert('Thay đổi số lượng sẽ làm sai lệch thông tin đơn hàng. <br /> Vui lòng cập nhật các tab: <strong>Giấy in</strong>, <strong>Kiểu in</strong>, <strong>Ra phim/kẽm</strong>, <strong>Gia công/Thành phẩm</strong>');
        }
    });

    $(document).on('focus', '#giay-in table select[name*="KhoGiayId"]', function () {
        previous_val = this.value;
    });
    //thay doi thong tin giay in
    doc.on('change', '#giay-in table input, #giay-in table select', function (e) {
        var _this = this, rootFrame = order.getRootFrame(_this), tbody = $(_this).closest('tbody'), stt;
        if (e.hasOwnProperty('originalEvent'))
        $(_this).addClass('userChanged');
        if (_this.name.indexOf('GiayBiaPriceRam') !== -1) {
            var GiayBiaPriceRam = $(this).val();
            GiayBiaPriceRam = order.removeFormat(GiayBiaPriceRam);
            if (GiayBiaPriceRam !== '' && GiayBiaPriceRam > 0) {
                var GiayBiaLength = tbody.find('input[name*="GiayBiaLength"]').val(),
                    GiayBiaWidth = tbody.find('input[name*="GiayBiaWidth"]').val(),
                    GiayBiaDinhLuong = tbody.find('select[name*="GiayBiaDinhLuong"]').val();
                if (GiayBiaDinhLuong > 0 && GiayBiaLength > 0 && GiayBiaWidth > 0) {
                    tbody.find('input[name*="[GiayBiaPriceSheet]"]').val('');
                    var GiayBiaPriceSheet = GiayBiaPriceRam / 500,
                        GiayBiaPriceTon = GiayBiaPriceSheet / (GiayBiaLength * GiayBiaWidth / 10000 * GiayBiaDinhLuong / 1000000);
                    GiayBiaPriceTon = GiayBiaPriceTon / 1000000;
                    GiayBiaPriceTon = GiayBiaPriceTon.toFixed(2);
                    tbody.find('input[name*="[GiayBiaPrice]"]').val(GiayBiaPriceTon);
                    order.updatePaper($('input[name*="[GiayBiaPrice]"]'));
                    if (rootFrame.find('input[name*="[bia_ghep_ruot]"]').is(":checked")) {
                        tbody.find('input[name*="[GiayRuotPrice]"]').val(GiayBiaPriceTon);
                        order.updatePaper($('input[name*="[GiayRuotPrice]"]'));
                    }
                }
            }
        } else if (_this.name.indexOf('GiayBiaPriceSheet') !== -1) {
            GiayBiaPriceSheet = $(this).val();
            GiayBiaPriceSheet = order.removeFormat(GiayBiaPriceSheet);
            if (GiayBiaPriceSheet !== "" && GiayBiaPriceSheet > 0) {
                GiayBiaLength = tbody.find('input[name*="GiayBiaLength"]').val();
                GiayBiaWidth = tbody.find('input[name*="GiayBiaWidth"]').val();
                GiayBiaDinhLuong = tbody.find('select[name*="GiayBiaDinhLuong"]').val();
                if (GiayBiaDinhLuong > 0 && GiayBiaLength > 0 && GiayBiaWidth > 0) {
                    tbody.find('input[name*="[GiayBiaPriceRam]"]').val('');
                    GiayBiaPriceTon = GiayBiaPriceSheet / (GiayBiaLength * GiayBiaWidth / 10000 * GiayBiaDinhLuong / 1000000);
                    GiayBiaPriceTon = GiayBiaPriceTon / 1000000;
                    GiayBiaPriceTon = GiayBiaPriceTon.toFixed(2);
                    tbody.find('input[name*="[GiayBiaPrice]"]').val(GiayBiaPriceTon);
                    tbody.find('input[name*="[GiayBiaPrice]"]').trigger('change');
                    if (rootFrame.find('input[name*="[bia_ghep_ruot]"]').is(":checked")) {
                        tbody.find('input[name*="[GiayRuotPrice]"]').val(GiayBiaPriceTon);
                        order.updatePaper($('input[name*="[GiayRuotPrice]"]'));
                    }
                }
            }
        } else if (_this.name.indexOf('GiayRuotPriceRam') !== -1) {
            var GiayRuotPriceRam = $(this).val();
            GiayRuotPriceRam = order.removeFormat(GiayRuotPriceRam);
            if (GiayRuotPriceRam !== '' && GiayRuotPriceRam > 0) {
                var GiayRuotLength = tbody.find('input[name*="GiayRuotLength"]').val(),
                    GiayRuotWidth = tbody.find('input[name*="GiayRuotWidth"]').val(),
                    GiayRuotDinhLuong = tbody.find('select[name*="GiayRuotDinhLuong"]').val(),
                    count_page = tbody.find('input[name*="GiayRuotPrice"]').data('count_page');
                if (count_page === undefined || count_page === 'undefined')
                    count_page = '';
                var GiayRuotPrice = tbody.find('input[name*="[GiayRuotPrice' + count_page + ']"]');
                if (GiayRuotDinhLuong > 0 && GiayRuotLength > 0 && GiayRuotWidth > 0) {
                    tbody.find('input[name*="GiayRuotPriceSheet"]').val('');
                    var GiayRuotPriceSheet = GiayRuotPriceRam / 500,
                        GiayRuotPriceTon = GiayRuotPriceSheet / (GiayRuotLength * GiayRuotWidth / 10000 * GiayRuotDinhLuong / 1000000);
                    GiayRuotPriceTon = GiayRuotPriceTon / 1000000;
                    GiayRuotPriceTon = GiayRuotPriceTon.toFixed(2);
                    $(GiayRuotPrice).val(GiayRuotPriceTon);
                    order.updatePaper($(GiayRuotPrice));
                }
            }
        } else if (_this.name.indexOf('GiayRuotPriceSheet') !== -1) {
            GiayRuotPriceSheet = $(this).val();
            GiayRuotPriceSheet = order.removeFormat(GiayRuotPriceSheet);
            if (GiayRuotPriceSheet !== '' && GiayRuotPriceSheet > 0) {
                GiayRuotLength = tbody.find('input[name*="GiayRuotLength"]').val();
                GiayRuotWidth = tbody.find('input[name*="GiayRuotWidth"]').val();
                GiayRuotDinhLuong = tbody.find('select[name*="GiayRuotDinhLuong"]').val();
                count_page = tbody.find('input[name*="GiayRuotPrice"]', rootFrame).data('count_page');
                if (count_page === undefined || count_page === 'undefined')
                    count_page = '';
                GiayRuotPrice = tbody.find('input[name*="[GiayRuotPrice' + count_page + ']"]');
                if (GiayRuotDinhLuong > 0 && GiayRuotLength > 0 && GiayRuotWidth > 0) {
                    tbody.find('input[name*="GiayRuotPriceRam"]').val('');
                    GiayRuotPriceTon = GiayRuotPriceSheet / (GiayRuotLength * GiayRuotWidth / 10000 * GiayRuotDinhLuong / 1000000);
                    GiayRuotPriceTon = GiayRuotPriceTon / 1000000;
                    GiayRuotPriceTon = GiayRuotPriceTon.toFixed(2);
                    $(GiayRuotPrice).val(GiayRuotPriceTon);
                }
            }
        } else {
            if(_this.name.indexOf('ChatLieu') !== -1 || _this.name.indexOf('DinhLuong') !== -1 || _this.name.indexOf('KhoGiayId') !== -1){
                rootFrame.find('#san-pham select[name="'+_this.name+'"]').val($(this).val());
            }

            if (_this.name.indexOf('GiayBiaPrice') !== -1) {
                if (window.price_Keypress === true) {
                    tbody.find('input[name*="[GiayBiaPriceRam]"]').val('');
                    tbody.find('input[name*="[GiayBiaPriceSheet]"]').val('');
                    window.price_Keypress = false;
                }
            } else if (_this.name.indexOf('GiayRuotPrice') !== -1) {
                if (window.price_Keypress === true) {
                    tbody.find('input[name*="[GiayRuotPriceRam]"]').val('');
                    tbody.find('input[name*="[GiayRuotPriceSheet]"]').val('');
                    window.price_Keypress = false;
                }
            }
            order.updatePaper(this);
        }

        var kieuInGiaTrucTiep = parseInt(rootFrame.find('input[name*="[checkbox_hasnt_formula_kieu_in]"]:checked').val());

        stt = $(this).closest('tbody').data('ruot');
        if (stt === undefined || stt === 0)
            stt = '';

        var real = 0;
        if(_this.name.indexOf('DinhLuong') !== -1 || _this.name.indexOf('Price') !== -1)
          real = 3;

        if ((_this.name.indexOf('InCungKhoGiay') !== -1 || _this.name.indexOf('TayCuoi') !== -1 || _this.name.indexOf('ToBuHaoThem') !== -1) && parseInt(tbody.find('input[name*="[TrangDu' + stt + ']"]').val()) > 0) {
            if ((_this.name.indexOf('TayCuoi') !== -1 && parseInt(tbody.find('select[name*="[TayCuoiKhoGiayId' + stt + ']"]').val()) === parseInt(tbody.find('select[name*="[GiayRuotKhoGiayId' + stt + ']"]').val()))) {

                if (kieuInGiaTrucTiep !== 1) {
                    tbody.find('input[name*="[InCungKhoGiay' + stt + ']"]').prop('checked', true);
                    tbody.find('select[name*="[TayCuoiKhoGiayId' + stt + ']"]').val(tbody.find('select[name*="[GiayRuotKhoGiayId' + stt + ']"]', tbody).val());
                    tbody.find('select[name*="[KhoMayInRuotTayCuoi' + stt + ']"]').val(tbody.find('select[name*="[KhoMayInRuot' + stt + ']"]').val()).trigger('change');
                    return;
                }
            }

            if (this.name.indexOf('InCungKhoGiay') !== -1) {
                tbody.find('select[name*="[TayCuoiKhoGiayId' + stt + ']"]').removeClass('userChanged');
                rootFrame.find('select[name*="[KhoMayInRuotTayCuoi' + stt + ']"]').val(rootFrame.find('select[name*="[KhoMayInRuot' + stt + ']"]').val()).trigger('change');
            }

            order.timKhoGiayRuotTayCuoi(tbody, real, stt);
        }
        else if ($(_this).closest('tbody').hasClass('tbody_giay_bia_order')) {
            order.timKhoGiayBia(tbody, real);
        } else if ($(_this).closest('tbody').hasClass('tbody_giay_ruot_order')) {
            order.timKhoGiayRuot(tbody, real, stt);
        }
        order.tinhChiPhiNVL(_this);
        order.updateChiPhiTab(_this);
    });

    doc.on('change', '#form-create-order input, #form-create-order select', function () {
        var parent = $(this).parent('.form-group');
        if (parent.hasClass('has-error')) {
            parent.removeClass('has-error');
            parent.find('.help-block').html('');
        }
    });

    doc.on('change', 'input[name*="[checkbox_hasnt_formula_giay_in]"]', function () {
        var rootFrame = order.getRootFrame(this),
            _this = this;
        rootFrame.find('.chiPhiGiayInBia').html('0');
        rootFrame.find('.chiPhiGiayInBiaVat').html('0');
        rootFrame.find('input[name*="GiayBiaChiPhi"]').val(0);
        rootFrame.find('input[name*="GiayBiaChiPhiVat"]').val(0);
        rootFrame.find('input[name*="GiayBiaPrice"]').val(0);

        rootFrame.find('.chiPhiGiayInRuot').html(0);
        rootFrame.find('.chiPhiGiayInRuotVat').html(0);
        rootFrame.find('input[name*="GiayRuotChiPhi"]').val(0);
        rootFrame.find('input[name*="GiayRuotChiPhiVat"]').val(0);
        rootFrame.find('input[name*="GiayRuotPrice"]').val(0);

        rootFrame.find('input[name*="[TongChiPhiGiayIn]"]').val(0);
        if ($(_this).is(":checked")) {
            rootFrame.find(".hasnt_formula").hide();
            rootFrame.find(".show_hasnt_formula_giay_in").show();
            rootFrame.find('input[name*="GiayBiaSoLuong"]').val(rootFrame.find('input[name*="amount"]').val());
            rootFrame.find('input[name*="GiayRuotSoLuong"]').val(rootFrame.find('input[name*="amount"]').val());
            rootFrame.find(".title_th_don_gia").html('Đơn giá');
            if (rootFrame.find('.tay_cuoi_ruot').is(':visible')) {
                rootFrame.find('.tay_cuoi_ruot').hide();
            }
            rootFrame.find('.in_cung_kho_giay').hide();
        } else {
            rootFrame.find(".hasnt_formula").show();
            rootFrame.find(".show_hasnt_formula_giay_in").hide();
            rootFrame.find('input[name*="GiayBiaSoLuong"]').val(0);
            rootFrame.find('input[name*="GiayRuotSoLuong"]').val(0);
            rootFrame.find(".title_th_don_gia").html('Đơn giá (triệu đồng / tấn)');
            rootFrame.find('.in_cung_kho_giay').show();
            rootFrame.find('select[name*="GiayBiaDinhLuong"]').trigger('change');
            rootFrame.find('select[name*="GiayRuotDinhLuong"]').trigger('change');
        }
        order.updateChiPhiTab(_this);
    });

    doc.on('click', '.upload_file_design_btn', function () {
        var rootFrame = order.getRootFrame(this),
            customer_id = rootFrame.find('select[id$="customer_id"] :selected').val();
        customer_id = Number(customer_id);
        if (isNaN(customer_id) || customer_id <= 0) {
            krajeeDialog.alert('Xin hãy chọn Khách hàng ở tab Thông tin khách hàng trước khi tải lên file thiết kế.');
            return false;
        }
        $.fancybox.open({
            src: '#file_design_upload',
            type: 'inline',
            width: '100%',
            opts: {
                closeClickOutside: false,
                clickOutside: '',
                clickSlide: '',
                beforeClose: function () {
                    $('#file_design_upload').find('.has-error').removeClass('has-error');
                    $('#file_design_upload').find('.help-block').empty();
                }
            }

        });
    });

    doc.on('keypress', '#attach-link', function () {
        $(this).closest('#file_design_upload').find('.field-attach-file').removeClass('has-error');
        $(this).closest('#file_design_upload').find('.field-attach-file .help-block').empty();
    });

    doc.on('click', '.file_design_upload_button', function () {
        var rootFrame = order.getRootFrame(this), formData = new FormData($('#file_design_upload_form')[0]);
        formData.append('Attach[customer_id]', $('select[name*="customer_id"]').val());

        $.ajaxQueue({
            type: 'POST',
            url: link_upload,
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: function (data) {
                if (!$.isEmptyObject(data) && data.status === 'true') {
                    $('#file_design_upload_form').find('.has-error').removeClass('has-error');
                    $('#file_design_upload_form').find('.has-success').removeClass('has-success');
                    $('#file_design_upload_form').find('.help-block').empty();
                    $('#file_design_upload_form').find("input").val("");
                    $('#attach-file').fileinput('clear');

                    order.appendFileDesignToDroplist(rootFrame, data.json);
                    $.fancybox.close();
                } else if (!$.isEmptyObject(data)) {
                    $.each(data, function (k, v) {
                        $('.field-' + k).addClass('has-error');
                        $('.field-' + k).find('.help-block').html(v[0]);
                    });
                }
            }
        });
    });

    //xem tat ca file thiet ke
    doc.on('change', 'select[id$="file_design"]', function () {
        var val = $(':selected', this).val(),
            rootFrame = order.getRootFrame(this),
            viewBtn = rootFrame.find('.prev_selected_file_design'),
            deleteBtn = rootFrame.find('.delete_selected_file_design');

        if (val === 'view_all') {
            var customer_id = $('select[id$="customer_id"] :selected', rootFrame).val(),
                keyword = $('input.search_file_design_txt', rootFrame).val(),
                data = {'customer_id': customer_id, 'keyword': keyword, 'limit': 0};
            order.getFileDesignDropList(this, data, 0);
            viewBtn.hide();
            deleteBtn.hide();
        } else {
            viewBtn.show();
            deleteBtn.show();
        }
    });

    //preview file thiet ke
    doc.on('click', '.prev_selected_file_design', function () {
        var rootFrame = order.getRootFrame(this),
            file_id = Number(rootFrame.find('select[id$="file_design"] :selected').val());
        if (isNaN(file_id) || file_id <= 0) {
            krajeeDialog.alert('Hãy chọn một tập tin để xem trước.');
            return false;
        }

        $.ajaxQueue({
            'dataType': 'json',
            'type': 'get',
            'url': link_prevFileDesign,
            'data': {'fileId': file_id},
            'success': function (json) {
                // console.log(json);
                if (json.success) {
                    var dialog_html = '', scale;
                    if (json.type === 'image') {
                        scale = order.scaleImage(json.width, json.height, 800, 500, true);
                        dialog_html = $('<img>').attr({
                            'src': json.file_url,
                            'width': scale.width,
                            'height': scale.height
                        });
                    } else if (json.type === 'link') {
                        scale = order.scaleImage(json.width, json.height, 800, 500, true);
                        dialog_html = $('<img>').attr({
                            'src': json.file_url,
                            'width': scale.width,
                            'height': scale.height
                        });
                    } else {
                        dialog_html = 'File không thể xem trước, hãy tải về máy tính của bạn để xem bằng phần mềm có hỗ trợ định dạng file này.<br><a href="' + json.file_url + '" style="color:#c00"><strong>Tải về ngay</strong></a>';
                    }

                    $.fancybox.open({
                        content: $('<div>').append(dialog_html)
                    });

                } else {
                    krajeeDialog.alert(json.msg);
                }
            }
        });
    });

    //delete file thiet ke
    doc.on('click', '.delete_selected_file_design', function () {
        var rootFrame = order.getRootFrame(this),
            file_id = Number(rootFrame.find('select[id$="file_design"] :selected').val());
        if (isNaN(file_id) || file_id <= 0) {
            krajeeDialog.alert('Hãy chọn một tập tin cần xóa.');
            return false;
        }
        krajeeDialog.confirm("Bạn có chắc muốn xóa mục này?", function (result) {
            if (result) {
                $.ajaxQueue({
                    'dataType': 'json',
                    'type': 'get',
                    'url': link_delFileDesign,
                    'data': {'fileId': file_id},
                    'success': function (json) {
                        if (json.success) {
                            $('select[id$="file_design"] option[value="' + file_id + '"]').remove();
                        }
                        krajeeDialog.alert(json.msg);
                    }
                });
            }
        });

    });

    //tim file thiet ke - search file design
    var searchFile = function () {
        var rootFrame = order.getRootFrame(this),
            customer_id = rootFrame.find('select[id$="customer_id"] :selected').val(),
            keyword;
        if ($(this).hasClass('search_file_design_txt')) {
            keyword = this.value;
        } else {
            keyword = rootFrame.find('input.search_file_design_txt').val();
        }
        var data = {'customer_id': customer_id, 'keyword': keyword};

        order.getFileDesignDropList(this, data);
    };

    doc.on('keypress', 'input.search_file_design_txt', function (e) {
        var key = e.keyCode;
        if (key === 13) {
            searchFile.call(this);
        }
    });

    doc.on('click', '.search_file_design_btn', function () {
        searchFile.call(this);
    });

    doc.on('click', '#sapXepThanhPhamGiayBia, #sapXepThanhPhamGiayRuot, #sapXepThanhPhamTayCuoi', function () {
        var _this = this, rootFrame = order.getRootFrame(_this),
            SoLuong = rootFrame.find('input[name*="[amount]"]').val(),
            tbody = $(_this).closest('tbody.tbody_giay_ruot_order'),
            formData = new FormData($('#form-create-order')[0]);
        if (SoLuong === '' || Number(SoLuong) === 0) {
            krajeeDialog.alert('Vui lòng nhập số lượng sản phẩm trước khi sắp xếp');
            return false;
        }
        var SoMatInBia = $('input[name*="SoMatInBia"]').val(),
            SoMatInRuot = $('input[name*="SoMatInRuot"]').val(),
            tayCuoi = 0,
            selectElm = order.getRootFrame(_this),
            kieuHop = order.getProductKieuHop(selectElm),
            data_rel = $(_this).closest('tbody').data('rel');
        if ($('.tay_cuoi_ruot', tbody).is(':visible'))
            tayCuoi = 1;
        if (data_rel === undefined)
            data_rel = '';
        else {
            var arrL = data_rel.split('_');
            data_rel = '&tbody=' + data_rel;
        }
        $.ajaxQueue({
            type: 'POST',
            url: link_finishedProductSort,
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            'success': function (data) {
                var new_win = '';
                if (_this.id === 'sapXepThanhPhamGiayBia') {
                    if ($("#OrdersPaper_0_GiayBiaKhoGiayId").val() !== "") {
                        new_win = $.fancybox.open({
                            src: link_finishedProductSort + '?type=gb&order_id=' + order_id + '&SoMatInBia=' + SoMatInBia,
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
                    } else {
                        new_win = '';
                        krajeeDialog.alert("Bạn phải chọn khổ giấy trước khi sắp xếp thành phẩm");
                    }

                } else if (_this.id === 'sapXepThanhPhamGiayRuot') {
                    if ($("[id^='OrdersPaper_0_GiayRuotKhoGiayId']", tbody).val() !== '') {
                        new_win = $.fancybox.open({
                            src: link_finishedProductSort + '?type=gr&order_id=' + order_id + '&SoMatInRuot=' + SoMatInRuot + (Number(kieuHop) === hopCungDinhHinh ? '&nap_hop=1' : '') + data_rel,
                            type: 'iframe',
                            width: "100%",
                            height: "100%",
                            margin: [10, 10, 10, 10]
                        });
                    } else {
                        new_win = '';
                        krajeeDialog.alert("Bạn phải chọn khổ giấy trước khi sắp xếp thành phẩm");
                    }
                } else if (_this.id === 'sapXepThanhPhamTayCuoi') {
                    if ($("[id^='OrdersPaper_0_TayCuoiKhoGiayId']", tbody).val() !== "") {
                        new_win = $.fancybox.open({
                            src: link_finishedProductSort + '?type=gr&order_id=' + order_id + '&tayCuoi=' + tayCuoi + '&SoMatInRuot=' + SoMatInRuot + data_rel,
                            type: 'iframe',
                            width: "100%",
                            height: "100%",
                            margin: [10, 10, 10, 10]
                        });
                    } else {
                        new_win = '';
                        krajeeDialog.alert("Bạn phải chọn khổ giấy trước khi sắp xếp thành phẩm");
                    }
                }
                if (new_win === null) {
                    krajeeDialog.alert('Không thể mở cửa sổ vì trình duyệt của bạn đã chặn pop-up window.<br>Bạn hãy vô hiệu hóa chức năng chặn pop-up của trình duyệt sau đó hãy thử lại.');
                } else {
                    if (new_win !== '')
                        new_win.focus();
                }
            }
        });
    });

    doc.on('click', '.tim_kho_giay_in', function (e, wasTriggered) {
        if (e.hasOwnProperty('originalEvent'))
            order.getKhoGiayIn(this, 1);
        else if (wasTriggered !== undefined && wasTriggered === 1)
            order.getKhoGiayIn(this, 2);
        else
            order.getKhoGiayIn(this);
    });

    //nut them don hang ghep
    $('#add_more_order').off('click').on('click', function () {
        var sub_order_length = order.getSubOrderLength();
        if (sub_order_length >= 4) {
            krajeeDialog.alert('Chỉ có thể thêm tối đa 3 đơn hàng ghép.');
            return false;
        }
        var count = $('.orders-form .nav.nav-tabs li').size();
        $(this).attr('disabled', 'disabled').val('Đang thêm đơn hàng ghép...Xin chờ');
        order.addSubOrder(count, function () {
            $('#add_more_order').removeAttr('disabled').val('Thêm đơn hàng ghép');
            // order.setPrintTypeData(true);
            // order.setPrintTestData(true);
            order.numberInput();
            $('#IntroMenu a').hide();
        });
    });

    //xoa don hang
    doc.on('click', '.remove_sub_order', function () {
        var _this = this;
        krajeeDialog.confirm("Bạn có chắc chắn muốn xóa đơn hàng ghép này?<br/><br/><p style='color: red'>Cảnh báo: Dữ liệu của đơn hàng bị xóa không thể khôi phục!</p>", function (result) {
            if (result) {
                var tab_active = $(_this).closest('li'),
                    tab_content_id = $('a', tab_active).attr('href'),
                    $tab_content_id = $(tab_content_id);
                $tab_content_id.remove();
                tab_active.remove();
                $('.nav-tabs a[href="#primary_inner"]').tab('show');
                $('#IntroMenu a').show();
            }
        });
    });

    doc.on('click', '.create_customer', function () {
        $.ajaxQueue({
            'type': 'get',
            'url': link_addCustomer,
            'success': function (data) {
                $.fancybox.open({
                    content: $('<div>').append(data)
                });
            }
        });
    });

    doc.on('click', '.add_new_customer', function () {
        var rootFrame = $('.tab-pane.active'),
            formData = new FormData($('#create_customer_form')[0]);
        $.ajaxQueue({
            type: 'POST',
            url: link_addCustomer,
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: function (data) {
                if (data.success === false && !$.isEmptyObject(data.errors)) {
                    $.each(data.errors, function (k, v) {
                        var form_group = $('.field-' + k);
                        form_group.addClass('has-error');
                        form_group.find('.help-block').html(v);
                    });
                } else {
                    $.fancybox.close();
                    order.appendCustomerToDroplist(data.result);
                    rootFrame.find('select[name*="[customer_id]"]').val(data.result.cus_id).trigger('change');
                }
            }
        });
    });

    doc.on('click', '.add_new_supplier', function () {
        var rootFrame = $('.tab-pane.active'),
            formData = new FormData($('#create_supplier_form')[0]),
            select;
        $.ajaxQueue({
            type: 'POST',
            url: $('#create_supplier_form').attr('action'),
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: function (data) {
                if (data.success === false && !$.isEmptyObject(data.errors)) {
                    $.each(data.errors, function (k, v) {
                        var form_group = $('.field-' + k);
                        form_group.addClass('has-error');
                        form_group.find('.help-block').html(v);
                    });
                } else {
                    var result = $.parseJSON(data);
                    if (Number(result.attributes.groupid) === 1) {
                        select = rootFrame.find('select[name*="[NhaCungCapIn]"]');
                    } else if (Number(result.attributes.groupid) === 3) {
                        select = rootFrame.find('select[name*="[NhaCungCapInTest]"]');
                    } else if (select_current) {
                        select = select_current;
                    }
                    if (result.supplier_json !== undefined)
                        supplier_json = result.supplier_json;

                    order.appendSupplierToDropList(select, result.attributes);
                    select.val(result.attributes.supplierid).trigger('change');
                    if (result.giakhomayin_json !== undefined)
                        giakhomayin_json = result.giakhomayin_json;

                    $.fancybox.close();
                }
            }
        });
    });

    doc.on('click', '.create_supplier', function () {
        var type = Number($(this).data('type')), rootFrame = order.getRootFrame(this), select;

        if (type === 1) {
            select = rootFrame.find('.printTypeTable input[name*="[NhaCungCapIn]"]');
        } else {
            select = rootFrame.find('.input[name*="[NhaCungCapInTest]"]');
        }
        order.addSupplier(select, type);

    });

    doc.ajaxSuccess(function () {
        if ($('input').is(":focus")) {
            $('input:focus').select();
        }
    });

    doc.on('focus', '.numberOnly, .numberOnlyFull, .integerOnly, .quy-cach-in', function () {
        $(this).select();
    });

    doc.on('change', 'select[name*="NhaCungCap"]', function () {
        var _this = this, val = _this.value,
            group;
        select_current = $(_this);
        if (_this.name.indexOf('GiayBiaNhaCungCap') !== -1 || _this.name.indexOf('GiayRuotNhaCungCap') !== -1) {
            group = 2;
        } else if (_this.name.indexOf('ExportNhaCungCap') !== -1) {
            group = 4;
        } else if (_this.name.indexOf('NhaCungCapId') !== -1) {
            group = 5;
        } else {
            return
        }
        if (val === 'add_new') {
            $(_this).val('').change();
            order.addSupplier(_this, group);
        }
    });

    doc.ajaxStart(function () {
        $(".loading-indicator-wrapper").addClass('loader-visible').removeClass('loader-hidden');
    }).ajaxStop(function () {
        $(".loading-indicator-wrapper").removeClass('loader-visible').addClass('loader-hidden');
    });

    doc.on('shown.bs.tab', 'a[data-toggle="tab"]', function (e) {
        var $li = $(e.target).closest('li'),
            id = $li.data('id');
        if (id !== undefined && id !== 'undefined' && id > 0) {
            // $('#IntroMenu a').each(function () {
            //     $(this).attr("href")
            // });
            $('#IntroMenu a').hide();
        } else {
            $('#IntroMenu a').show();
        }
    });

    //tinh gia photocopy
    doc.on('change', '.table_photo input[name*="so_luong"], .table_photo input[name*="don_gia"]', function () {
        var $tr = $(this).closest('tr'), soLuong = $tr.find('input[name*="so_luong"]').val(),
            donGia = $tr.find('input[name*="don_gia"]').val(),
            thanhTien, tongTien = 0;
        soLuong = Number(order.removeFormat(soLuong));
        donGia = Number(order.removeFormat(donGia));
        thanhTien = soLuong * donGia;
        if (!isNaN(thanhTien)) {
            $tr.find('input[name*="thanh_tien"]').val(thanhTien);
        }

        $('.table_photo input[name*="thanh_tien"]').each(function () {
            thanhTien = $(this).val();
            thanhTien = Number(order.removeFormat(thanhTien));
            if (!isNaN(thanhTien))
                tongTien += thanhTien;
        });

        if (!isNaN(tongTien)) {
            $('input[name*="TongChiPhiPhoto"]').val(tongTien);
            $('#photocopy .chiPhiText strong').html(tongTien).formatCurrency();
        }

        order.updateChiPhiTab(this);
        order.numberInput();
    });

    doc.on('change', '.table_photo input[name*="ten_san_pham"]', function () {
        order.updateGiaCongPhotoList();
    });

    //them san pham photo
    doc.on('click', '.add-photo-line', function () {
        var count = $('.tbody_photo_item tr').length,
            html = $('.tbody_photo_item tr').eq(0).clone();
        html.find('input, select').each(function () {
            this.name = this.name.replace('[0]', '[' + count + ']');
            if (this.id)
                this.id = this.id.replace('_0_', '_' + count + '_');
            if (!$(this).is('input')) {
                this.value = $("option:first", this).val();
            } else {
                this.value = '';
            }
        });
        html.find('input[name*="id_photo"]').val('san_pham_' + (count + 1));
        $(html).insertAfter($('.tbody_photo_item tr:last'));
        order.updateGiaCongPhotoList();
    });

    //xoa san pham photo
    doc.on('click', '.remove_photo', function () {
        var _this = this, rootFrame = order.getRootFrame(_this), thanhTien, tongTien = 0,
            count = $('.tbody_photo_item tr').length;
        krajeeDialog.confirm("Bạn có chắc muốn xóa mục này?", function (result) {
            if (result) {
                if (count === 1) {
                    $('.table_photo input, .table_photo select').each(function () {
                        this.value = '';
                    });
                    $('.table_photo input[name*="id_photo"]').val('san_pham_1');
                } else {
                    $(_this).closest('tr').remove();
                    $('.tbody_photo_item tr').each(function (i) {
                        if (i > 0) {
                            $(this).find('input, select').each(function () {
                                var name_ = this.name,
                                    id_ = this.id;
                                $(this).attr('name', name_.replace(/[0-9]+/g, i));
                                $(this).attr('id', id_.replace(/[0-9]+/g, i));
                            });
                            $(this).find('input[name*="id_photo"]').val('san_pham_' + (i + 1));
                        }
                    });

                    $('.table_photo input[name*="thanh_tien"]').each(function () {
                        thanhTien = $(this).val();
                        thanhTien = Number(order.removeFormat(thanhTien));
                        if (!isNaN(thanhTien))
                            tongTien += thanhTien;
                    });
                }

                if (!isNaN(tongTien)) {
                    $('input[name*="TongChiPhiPhoto"]').val(tongTien);
                    $('#photocopy .chiPhiText strong').html(tongTien).formatCurrency();
                }
                order.updateGiaCongPhotoList();
                order.updateChiPhiTab(rootFrame);
                order.numberInput();
            }
        });
    });

    //format input
    order.numberInput();
    if (is_update === 1) {
        $('select[name*="product_id"]').each(function () {
            var rootFrame = order.getRootFrame(this),
                product_id = Number($(this).val()),
                product = order.getProductById(product_id);
            rootFrame.find('.kieuin-item').each(function () {
                var $this_ = $(this), quycach = $.trim($this_.find('input[name*=QuyCachIn]').val());
                if (quycach === '0/0') {
                    $this_.find('input[name*=QuyCachIn]').css('outline', '2px solid #c00').attr('title', 'Quy cách in không hợp lệ.');
                    rootFrame.find('.inbia_elm .QuyCachInTxt').empty();

                } else if (quycach.match(/^(\d+)\/(\d+)$/)) {
                    $this_.find('input[name*=QuyCachIn]').css('outline', 'none').attr('title', '');
                    order.tinhQuyCachIn(quycach, rootFrame);

                } else {
                    $this_.find('input[name*=QuyCachIn]').css('outline', '2px solid #c00').attr('title', 'Quy cách in không hợp lệ.');
                }
            });

            $.ajaxQueue({
                'cache': false,
                'url': link_getProductList,
                'data': {id: $(this).val()},
                'success': function (data) {
                    $('.table_list_product > tbody.list').html(data);
                    $('.product_scroll').css({'height': ($('.control-sidebar-bg').height() - 105)});
                }
            });

            if($('.lock_order').length > 0) {
                $('.lock_order').find('input, select, button, .btn').each(function () {
                    $(this).attr("disabled", true);
                });
            }
            order.getProductSize(rootFrame, product);
        });
    }
    
    doc.on('click', '.quy_cach_in .fa', function () {
        var elm = $(this).closest('.kieuin-item');
        if($(this).hasClass('fa-lock')) {
            elm.find('.kieu_in_tro select').removeClass('userChanged');
            $(this).addClass('fa-unlock-alt').removeClass('fa-lock');
        }else{
            elm.find('.kieu_in_tro select').addClass('userChanged');
            $(this).addClass('fa-lock').removeClass('fa-unlock-alt');
        }
    });

})(jQuery);