var kieuDongBox = '#kieu_dong_box',
    kieuHop = 'select[name*="KieuHop"]',
    kieuHopBox = '#kieu_hop_box',
    $hasInnerPage = '.has_inner_page',
    $kichThuocKhac = '.kich_thuoc_khac',
    $caiDayHop = '.cai_day_hop',
    $kichThuocCao = '.kich_thuoc_cao',
    $boiSongE = '.boi_song_e',
    $kichThuocNap = '.chieu_cao_nap',
    $taiNap = '.tai_nap',
    productId = 'select[name*="[product_id]"]',
    input_productLength = 'input[name*="[length]"]',
    input_productWidth = 'input[name*="[width]"]',
    input_productThick = 'input[name*="[thick]"]',
    input_kichThuocTai = 'input[name*="[kich_thuoc_tai]"]',
    input_taiNap = 'input[name*="[tai_nap]"]',
    input_caiDay = 'input[name*="kich_thuoc_cai_day"]',
    input_boiSongE = 'input[name*="gia_tri_boi_song_e"]',
    input_buHaoNoiXen = 'input[name*="bu_hao_noi_xen"]',
    chatLieuGiayBia = 'select[name*="GiayBiaChatLieu"]',
    chatLieuGiayRuot = 'select[name*="GiayRuotChatLieu"]',
    chatLieuToGac = 'select[name*="ToGacChatLieu"]',
    nccGiayBia = 'select[name*="GiayBiaNhaCungCap"]',
    nccGiayRuot = 'select[name*="GiayRuotNhaCungCap"]',
    nccToGac = 'select[name*="ToGacNhaCungCap"]',
    dinhLuongGiayBia = 'select[name*="GiayBiaDinhLuong"]',
    dinhLuongGiayRuot = 'select[name*="GiayRuotDinhLuong"]',
    dinhLuongToGac = 'select[name*="ToGacDinhLuong"]',
    donGiaGiayBia = 'input[name*="[GiayBiaPrice]"]',
    donGiaGiayRuot = 'input[name*="[GiayRuotPrice]"]',
    donGiaToGac = 'input[name*="ToGacPrice"]',
    nccIn = 'select[name*="[NhaCungCapIn]"]',
    quyCachInBia = 'input[name*="QuyCachIn"]',
    quyCachInRuot = 'input[name*="QuyCachInRuot"]',
    kieuInTroBia = 'select[name*="kieu_in_tro"]',
    kieuInTroRuot = 'select[name*="kieu_in_tro_ruot"]',
    customer_id = 'select[name*="customer_id"]',
    khoMayInBia = 'select[name*="KhoMayInBia"]',
    khoMayInRuot = 'select[name*="KhoMayInRuot"]',
    donGiaKemBia = 'input[name*="DonGiaKemBia"]',
    donGiaKemCmBia = 'input[name*="DonGiaKemCmBia"]',
    donGiaInBia = 'input[name*="DonGiaInBia"]',
    daiKemBia = 'input[name*="DaiKemBia"]',
    rongKemBia = 'input[name*="RongKemBia"]',
    congThucIn = 'input[name*="calcu_type"]',
    SoMauInBiaMax = 'select[name*="SoMauInBiaMax"]',
    soMauInBia = 'input[name*="SoMauInBia"]',
    soMatInBia = 'input[name*="SoMatInBia"]',
    GiayBiaSoTo = 'input[name*="GiayBiaSoTo"][type="hidden"]',
    GiayBiaSoToBuHao = 'input[name*="GiayBiaSoToBuHao"][type="hidden"]',
    GiayBiaLength = 'input[name*="GiayBiaLength"][type="hidden"]',
    GiayBiaWidth = 'input[name*="GiayBiaWidth"][type="hidden"]',
    GiayRuotSoTo = 'input[name*="GiayRuotoTo"][type="hidden"]',
    GiayRuotLength = 'input[name*="GiayRuotLength"][type="hidden"]',
    GiayRuotWidth = 'input[name*="GiayRuotWidth"][type="hidden"]',
    GiayBiaThanhPham = 'input[name*="GiayBiaThanhPham"][type="hidden"]',

    Order = function () {
        this.vatTax = 10;
        this.formula = {
            //tinh chi phi ra can
            'raCan': '( dai * rong * soluong * soMau * donGia)',
            //tinh chi phi ra phim
            'raPhim': '( dai * rong * soluong * soMau * donGia)',

            'raPhimTayCuoi': '( dai_tay_cuoi * rong_tay_cuoi  * soMauTayCuoi * donGia)',
            //tinh chi phi ra kem bia
            'raKemBia': '( dai * rong * soluong * soMau * donGia)',
            //tinh chi phi ra kem bia
            'raKemRuot': '( dai * rong * soluong * soMau * donGia)',
            //tinh chi phi giay in
            'giayIn': 'dai * rong * dinhLuong * (donGia / 1.1) * soLuong',
            //tinh chi phi giay in co VAT
            'giayInVAT': 'giaTruocThue + ( giaTruocThue * ( phanTram / 100 ) )',
            //in test so luong it
            'inTestS': '( soLuong * donGia )',
            //in test so luong nhieu
            'inTestL': '( ( khoGiay * soMatIn * soLuong * donGia ) / soLuong )',
            //chi phi in so luong it
            'InKem': 'soMauKem * donGiaKem', //tinh kem
            //chi phi in so luong nhieu
            'InLuot': '(soMauKem*donGiaKem ) + ( (soTo*matIn) * (soMau*donGiaIn) )', //tinh luot
            // chi phi in ca kem va luot
            'InLuotVaKem': '((soTo*matIn) * (soMau*donGiaIn) )',
            // tinh gay
            'TinhGay': 'tongSoTrangRuot*heSoGay/2'
        };

        this.getProductById = function (product_id) {
            if (!$.isEmptyObject(products_json)) {
                var data = products_json[product_id];
                return data;
            } else {
                return false;
            }
        };

        this.numberInput = function () {
            $('.numberOnly').toArray().forEach(function (field) {
                new Cleave(field, {
                    numeral: true,
                    numeralThousandsGroupStyle: 'thousand',
                    numeralDecimalScale: 2
                });
            });

            $('.integerOnly').toArray().forEach(function (field) {
                new Cleave(field, {
                    numeral: true,
                    numeralThousandsGroupStyle: 'thousand',
                    numeralDecimalScale: 0
                });
            });

            $('.dateInput').toArray().forEach(function (field) {
                new Cleave(field, {
                    date: true,
                    datePattern: ['d', 'm', 'Y'],
                    delimiter: '-'
                });
            });

            $('.quy-cach-in').toArray().forEach(function (field) {
                new Cleave(field, {
                    blocks: [1, 1],
                    delimiter: '/'
                });
            });
        };

        this.getRootFrame = function (elm) {
            if ($(elm).hasClass('.tab-pane')) {
                return $(elm);
            } else {
                return $(elm).closest('.tab-pane');
            }
        };

        this.isSubOrder = function (selectElm) {
            return $(selectElm).closest('#primary_inner').size() === 0;
        };

        this.setPrintTypeData = function () {

            var $primary_printTypeTable = $('#primary_inner .printTypeTable'),
                value, name, name_match;
            $primary_printTypeTable.find('input, select').each(function () {
                name_match = this.name.match(/(:\[|\])?(\w+)/g);
                name = name_match[name_match.length - 1];
                if (this.nodeName === 'SELECT') {
                    value = $(':selected', this).val();
                    if (name === 'NhaCungCapIn' && value > 0) {
                        $.ajax({
                            'url': link_getKhoMayIn,
                            'type': 'get',
                            'data': {'supplier': value},
                            'success': function (data) {
                                $primary_printTypeTable.find(khoMayInBia).html(data);
                                $primary_printTypeTable.find(khoMayInRuot).html(data);
                            }
                        });
                    }
                }
            });
        };

        this.quyCachIn = function (quyCach, soMatInInput, soMauInInput, QuyCachInTxt, kieuInTro) {
            var soMau, soMat, soMauMat1 = 0, soMauMat2 = 0, bia_max = 1, json,
                kieu_in_tro = kieuInTro.val(), quyCach_match;

            soMatInInput.val(0);
            soMauInInput.val(0);
            QuyCachInTxt.empty();
            if (quyCach === undefined || quyCach === "undefined") {
                console.warn('QuyCachIn() - quyCach is undefined');
                return false;
            }
            quyCach = $.trim(quyCach);

            quyCach_match = quyCach.match(/^(\d+)\/(\d+)$/);
            if (quyCach_match && quyCach_match.length > 0) {
                soMauMat1 = parseFloat(quyCach_match[1]);
                soMauMat2 = parseFloat(quyCach_match[2]);
                if (soMauMat1 > soMauMat2) {
                    bia_max = soMauMat1;
                }else {
                    bia_max = soMauMat2;
                }
                soMauInInput.val(bia_max);
            }

            if (isNaN(soMauMat1) || isNaN(soMauMat2)) {
                return false;
            }

            if (soMauMat1 === 0 && soMauMat2 === 0) {
                return false;
            } else if (soMauMat1 === 0 || soMauMat2 === 0) {
                soMat = 1;
                soMau = soMauMat1 + soMauMat2;
                QuyCachInTxt.html('In ' + soMau + ' màu ' + soMat + ' mặt');
            } else {
                soMat = 2;
                soMau = soMauMat1 + soMauMat2;
                if (soMauMat1 === soMauMat2) {
                    if (Number(kieu_in_tro) === kieuTroKhac)
                        soMau = soMauMat1 + soMauMat2;
                    else {
                        if (soMauMat1 > soMauMat2)
                            soMau = soMauMat1;
                        else
                            soMau = soMauMat2;
                    }
                    $(kieuInTro).css('pointer-events', '');
                } else {
                    soMau = soMauMat1 + soMauMat2;
                    kieuInTro.val(2);
                    $(kieuInTro).css('pointer-events', 'none');
                }
                QuyCachInTxt.html('Mặt 1: ' + soMauMat1 + ' màu - Mặt 2: ' + soMauMat2 + ' màu.');
            }

            json = {
                'soMau': soMau,
                'soMat': soMat
            };

            return json;
        };

        this.tinhQuyCachIn = function (quyCach, rootFrame) {
            var _this = this;
            quyCach = rootFrame.find(quyCachInBia).val();
            var quyCachInBia_ = _this.quyCachIn(quyCach, rootFrame.find('input[name*="SoMauInBia"]'), rootFrame.find('input[name*="SoMatInBia"]'), rootFrame.find('.QuyCachInTxt:first'), rootFrame.find(kieuInTroBia));
            rootFrame.find(soMauInBia).val(quyCachInBia_.soMau);
            rootFrame.find(soMatInBia).val(quyCachInBia_.soMat);
            if(quyCachInBia_.soMau > 0 && quyCachInBia_.soMat){
                rootFrame.find(quyCachInBia).css('outline', 'none').attr('title', '');
            }
            rootFrame.find('select[name*="[SoMauInBiaMax]"] option[value="' + quyCach + '"]').prop('selected', true);

            //IN RUOT
            rootFrame.find('.inruot_elm').each(function (i) {
                var $this = $(this), quyCach;
                quyCach = $this.find(quyCachInRuot).val();
                if (quyCach !== '' && quyCach !== undefined && quyCach !== "undefined") {
                    var quyCachInRuot_ = _this.quyCachIn(quyCach, $this.find('input[name*="SoMauInRuot"]'), $this.find('input[name*="SoMatInRuot"]'), $this.find('.QuyCachInTxt'), $this.find(kieuInTroRuot));

                    // console.log(quyCachInRuot_);
                    if(quyCachInRuot_.soMau > 0 && quyCachInRuot_.soMat){
                        $this.find(quyCachInRuot).css('outline', 'none').attr('title', '');
                    }
                    $this.find('input[name*="SoMauInRuot"]').val(quyCachInRuot_.soMau);
                    $this.find('input[name*="SoMatInRuot"]').val(quyCachInRuot_.soMat);

                    if (i > 0) {
                        rootFrame.find('select[id$="ext_color_inner_page_amount_' + (i + 1) + '"] option[value="' + quyCach + '"]').prop('selected', true);
                    } else {
                        rootFrame.find('select[name*="[SoMauInRuotMax]"] option[value="' + quyCach + '"]').prop('selected', true);
                    }
                }
            });

            //IN RUOT TAY CUOI
            rootFrame.find('.taycuoi_elm').each(function () {
                var $this = $(this), quyCach;
                quyCach = $this.find(quyCachInRuot).val();
                if (quyCach !== '' && quyCach !== undefined && quyCach !== "undefined") {
                    var quyCachInRuot_ = _this.quyCachIn(quyCach, $this.find('input[name*="SoMauInRuot"]'), $this.find('input[name*="SoMatInRuot"]'), $this.find('.QuyCachInTxt'), $this.find(kieuInTroRuot));

                    // console.log(quyCachInRuot_);
                    if(quyCachInRuot_.soMau > 0 && quyCachInRuot_.soMat){
                        $this.find(quyCachInRuot).css('outline', 'none').attr('title', '');
                    }
                    $this.find('input[name*="SoMauInRuot"]').val(quyCachInRuot_.soMau);
                    $this.find('input[name*="SoMatInRuot"]').val(quyCachInRuot_.soMat);

                }
            });
            return true;
        };

        this.selectNhaCungCapIn = function (frame) {
            var _this = this,
                selectKhoMayBiaElm = frame.find(khoMayInBia),
                roorFrame = _this.getRootFrame(frame),
                selectKhoMayRuotElm;
            _this.selectKhoMayIn(selectKhoMayBiaElm.get(0), frame);
            // if (_this.hasInnerPage(roorFrame)) {
            //     $('li.inruot_item', roorFrame).each(function () {
            //         selectKhoMayRuotElm = $('select[name*="KhoMayInRuot"]', this);
            //         _this.selectKhoMayIn(selectKhoMayRuotElm.get(0), this);
            //     });
            //
            // }
        };

        this.selectKhoMayIn = function (select, frame) {
            if (isNaN($(select).val()))
                return false;
            var _this = this, khoMayIn = this.getKhoMayInById($(select).val());
            if (khoMayIn) {
                if (select.name.indexOf('KhoMayInBia') !== -1) {
                    var DonGiaKemBia = parseFloat(khoMayIn.price_k) * parseFloat(khoMayIn.kem_dai) * parseFloat(khoMayIn.kem_rong),
                        DonGiaKemBia_ = parseFloat(_this.removeFormat(frame.find(donGiaKemCmBia).val())),
                        DonGiaKemCmBia_ = parseFloat(_this.removeFormat(frame.find(donGiaKemCmBia).val())),
                        DonGiaInBia_ = parseFloat(_this.removeFormat(frame.find(donGiaInBia).val())),
                        DaiKemBia_ = parseFloat(_this.removeFormat(frame.find(daiKemBia).val())),
                        RongKemBia_ = parseFloat(_this.removeFormat(frame.find(rongKemBia).val()));
                    if (!frame.find(donGiaKemBia).hasClass('userChanged') || isNaN(DonGiaKemBia_) || DonGiaKemBia_ === 0) {
                        frame.find(donGiaKemBia).val(parseFloat(DonGiaKemBia));
                        frame.find(donGiaKemBia).removeClass('userChanged');
                    }

                    if (!frame.find(donGiaKemCmBia).hasClass('userChanged') || isNaN(DonGiaKemCmBia_) || DonGiaKemCmBia_ === 0) {
                        frame.find(donGiaKemCmBia).val(parseFloat(khoMayIn.price_k));
                        frame.find(donGiaKemCmBia).removeClass('userChanged');
                    }

                    if (!frame.find(donGiaInBia).hasClass('userChanged') || isNaN(DonGiaInBia_) || DonGiaInBia_ === 0) {
                        frame.find(donGiaInBia).val(parseFloat(khoMayIn.price_i));
                        frame.find(donGiaInBia).removeClass('userChanged');
                    }

                    if (!frame.find(daiKemBia).hasClass('userChanged') || isNaN(DaiKemBia_) || DaiKemBia_ === 0) {
                        frame.find(daiKemBia).val(parseFloat(khoMayIn.kem_dai));
                        frame.find(daiKemBia).removeClass('userChanged');
                    }

                    if (!frame.find(rongKemBia).hasClass('userChanged') || isNaN(RongKemBia_) || RongKemBia_ === 0) {
                        frame.find(rongKemBia).val(parseFloat(khoMayIn.kem_rong));
                        frame.find(rongKemBia).removeClass('userChanged');
                    }
                } else if (select.name.indexOf('KhoMayInRuot') !== -1) {
                    var elm = $(select).closest('li'),
                        DonGiaKemRuot = parseFloat(khoMayIn.price_k) * parseFloat(khoMayIn.kem_dai) * parseFloat(khoMayIn.kem_rong),
                        DonGiaKemRuot_ = parseFloat(_this.removeFormat(elm.find('input[name*="DonGiaKemRuot"]').val())),
                        DonGiaKemCmRuot_ = parseFloat(_this.removeFormat(elm.find('input[name*="DonGiaKemCmRuot"]').val())),
                        DonGiaInRuot_ = parseFloat(_this.removeFormat(elm.find('input[name*="DonGiaInRuot"]').val())),
                        DaiKemRuot_ = parseFloat(_this.removeFormat(elm.find('input[name*="DaiKemRuot"]').val())),
                        RongKemRuot_ = parseFloat(_this.removeFormat(elm.find('input[name*="RongKemRuot"]').val()));

                    if (!elm.find('input[name*="DonGiaKemRuot"]').hasClass('userChanged') || isNaN(DonGiaKemRuot_) || DonGiaKemRuot_ === 0) {
                        elm.find('input[name*="DonGiaKemRuot"]').val(DonGiaKemRuot).formatCurrency();
                        elm.find('input[name*="DonGiaKemRuot"]').removeClass('userChanged');
                    }

                    if (!elm.find('input[name*="DonGiaKemCmRuot"]').hasClass('userChanged') || isNaN(DonGiaKemCmRuot_) || DonGiaKemCmRuot_ === 0) {
                        elm.find('input[name*="DonGiaKemCmRuot"]').val(parseFloat(khoMayIn.price_k)).formatCurrency();
                        elm.find('input[name*="DonGiaKemCmRuot"]').removeClass('userChanged');
                    }

                    if (!elm.find('input[name*="DonGiaInRuot"]').hasClass('userChanged') || isNaN(DonGiaInRuot_) || DonGiaInRuot_ === 0) {
                        elm.find('input[name*="DonGiaInRuot"]').val(parseFloat(khoMayIn.price_i));
                        elm.find('input[name*="DonGiaInRuot"]').removeClass('userChanged');
                    }

                    if (!elm.find('input[name*="DaiKemRuot"]').hasClass('userChanged') || isNaN(DaiKemRuot_) || DaiKemRuot_ === 0) {
                        elm.find('input[name*="DaiKemRuot"]').val(parseFloat(khoMayIn.kem_dai));
                        elm.find('input[name*="DaiKemRuot"]').removeClass('userChanged');
                    }

                    if (!elm.find('input[name*="RongKemRuot"]').hasClass('userChanged') || isNaN(RongKemRuot_) || RongKemRuot_ === 0) {
                        elm.find('input[name*="RongKemRuot"]').val(parseFloat(khoMayIn.kem_rong));
                        elm.find('input[name*="RongKemRuot"]').removeClass('userChanged');
                    }
                }
            } else {
                if (select.name.indexOf('KhoMayInBia') !== -1) {
                    if (!frame.find('input[name*="DonGiaKemBia"]').hasClass('userChanged'))
                        frame.find('input[name*="DonGiaKemBia"]').val(0).formatCurrency();
                    if (!frame.find('input[name*="DonGiaKemCmBia"]').hasClass('userChanged'))
                        frame.find('input[name*="DonGiaKemCmBia"]').val(0).formatCurrency();
                    if (!frame.find('input[name*="DonGiaInBia"]').hasClass('userChanged'))
                        frame.find('input[name*="DonGiaInBia"]').val(0);
                    if (!frame.find('input[name*="DaiKemBia"]').hasClass('userChanged'))
                        frame.find('input[name*="DaiKemBia"]').val(0);
                    if (!frame.find('input[name*="RongKemBia"]').hasClass('userChanged'))
                        frame.find('input[name*="RongKemBia"]').val(0);
                } else if (select.name.indexOf('KhoMayInRuot') !== -1) {
                    var elm = $(select).closest('li');
                    if (!elm.find('input[name*="DonGiaKemRuot"]').hasClass('userChanged'))
                        elm.find('input[name*="DonGiaKemRuot"]').val(0).formatCurrency();
                    if (!elm.find('input[name*="DonGiaKemCmRuot"]').hasClass('userChanged'))
                        elm.find('input[name*="DonGiaKemCmRuot"]').val(0).formatCurrency();
                    if (!elm.find('input[name*="DonGiaInRuot"]').hasClass('userChanged'))
                        elm.find('input[name*="DonGiaInRuot"]').val(0);
                    if (!elm.find('input[name*="DaiKemRuot"]').hasClass('userChanged'))
                        elm.find('input[name*="DaiKemRuot"]').val(0);
                    if (!elm.find('input[name*="RongKemRuot"]').hasClass('userChanged'))
                        elm.find('input[name*="RongKemRuot"]').val(0);
                }
            }
        };

        this.getKhoMayInById = function (id) {
            if (id === undefined || !id || id <= 0)
                return false;
            var result = false;
            if (giakhomayin_json) {
                result = giakhomayin_json[id];
            }

            if (!result) {
                if (contents_json) {
                    $.each(contents_json, function (k, v) {
                        if (id === v.content_id) {
                            result = v;
                        }
                    });
                }
            }
            return result;
        };

        this.showHideByProduct = function (selectElm) {
            var rootFrame = this.getRootFrame(selectElm),
                product_id = parseInt(rootFrame.find(productId).val()), product,
                kieu_hop = parseInt(rootFrame.find(kieuHop).val());
            if (product_id > 0) {
                product = this.getProductById(product_id);

                rootFrame.find($hasInnerPage).hide();
                rootFrame.find($kichThuocKhac).hide();
                rootFrame.find($kichThuocCao).hide();
                if (product.product_type === sanPhamHop || product.product_type === sanPhamTui || product.product_type === sanPhamPhongBi) {
                    rootFrame.find(kieuDongBox).hide();
                    rootFrame.find($kichThuocKhac).show();
                    rootFrame.find($boiSongE).show();
                    rootFrame.find($kichThuocNap).hide();
                    rootFrame.find($caiDayHop).show();
                    rootFrame.find($taiNap).find('label').html('Tai nắp');
                    rootFrame.find($caiDayHop).find('label').html('Cài đáy hộp');
                    if (product.product_type === sanPhamHop) {
                        rootFrame.find($kichThuocCao).show();
                        rootFrame.find(kieuHopBox).show();
                        rootFrame.find($caiDayHop).hide();
                        if (kieu_hop === hopCungDinhHinh) {
                            rootFrame.find($kichThuocNap).show();
                        } else if (kieu_hop === hopMocDay) {
                            rootFrame.find($caiDayHop).show();
                        }
                    } else if (product.product_type === sanPhamTui) {
                        rootFrame.find($kichThuocCao).show();
                        rootFrame.find($taiNap).find('label').html('Gập mép túi');
                        rootFrame.find($caiDayHop).find('label').html('Trừ đáy túi');
                    } else {
                        rootFrame.find($boiSongE).hide();
                        rootFrame.find($caiDayHop).find('label').html('Trừ mặt sau');
                    }
                } else if (product.has_inner_page === 1) {
                    rootFrame.find(kieuDongBox).show();
                    rootFrame.find(kieuHopBox).hide();
                    rootFrame.find($hasInnerPage).show();
                } else {
                    rootFrame.find(kieuDongBox).hide();
                    rootFrame.find(kieuHopBox).hide();
                }

                if (product.has_inner_page === 1) {
                    rootFrame.find('li.inruot_elm:first').show();
                    rootFrame.find('tbody.tbody_giay_ruot_order:first').show();
                } else {
                    rootFrame.find('.trang-ruot-lists li:not(:first), .tbody_giay_ruot_order:not(:first), li.inruot_elm:not(:first), li.taycuoi_elm').remove();
                    // rootFrame.find('.output-lists, .gia-cong-lists').empty();
                    if (kieu_hop !== hopCungDinhHinh) {
                        rootFrame.find('tbody.tbody_giay_ruot_order, li.inruot_elm').hide();
                        rootFrame.find('input[name*="ChiPhiInRuot"], input[name*="TongChiPhiInRuot"], input[name*="TongChiPhiGiayInRuot"], input[name*="TongChiPhiInTayCuoi"], input[name*="TongChiPhiGiayInTayCuoi"], input[name*="ToGacChiPhi"],input[name*="ToGacChiPhiVat"], input[name*="BiaCartonChiPhi"], input[name*="BiaCartonChiPhiVat"]').val(0);
                    }
                }
            }
        };

        this.getProductId = function (selectElm) {
            var rootFrame = this.getRootFrame(selectElm),
                i = rootFrame.find(productId).val();
            return Number(i);
        };

        this.countInnerPage = function (selectElm) {
            var selectElm = this.getRootFrame(selectElm),
                total_inner_page = selectElm.find('li.trang-ruot-item').length;
            return total_inner_page;
        };

        this.fillGiayRuotThem = function (selectElm) {
            var inner_page_count = this.countInnerPage(selectElm),
                rootFrame = this.getRootFrame(selectElm),
                first_tbody = rootFrame.find('.tbody_giay_ruot_order:first'),
                html = first_tbody.clone();
            html.find('.ten-loai-giay').html('Giấy ruột ' + inner_page_count);
            html.find('select, input').each(function () {
                if (this.nodeName === 'INPUT' && this.name.indexOf('GiayRuotPrice') !== -1) {
                    this.value = first_tbody.find('input[name="' + this.name + '"]').val();
                } else if (this.nodeName === 'INPUT') {
                    if(this.name.indexOf('InCungKhoGiay') !== -1) {
                        this.value = 1;
                        $(this).prop('checked', true);
                    }else
                        this.value = 0;
                }else {
                    this.value = first_tbody.find('select[name="' + this.name + '"]').val();
                }
                this.name = this.name.replace(/\[([a-zA-Z]+)\]/g, "[$1" + inner_page_count + "]");
                html.find('.select2.select2-container').remove();
                html.find('select[name*="NhaCungCap"]').select2({
                    allowClear: true,
                    language: "vi",
                    placeholder: "Chọn nhà cung cấp",
                    theme: "krajee",
                    width: "100%"
                });
            });
            $(html).attr('data-ruot', inner_page_count);
            $(html).insertAfter(rootFrame.find('.tbody_giay_ruot_order:last'));
            this.updateProductSelect(selectElm);
        };

        this.fillKieuInThem = function (selectElm) {
            var inner_page_count = this.countInnerPage(selectElm),
                rootFrame = this.getRootFrame(selectElm),
                html = rootFrame.find('.kieuin-item.inruot_elm:first').clone();
            html.removeClass('kieuinruot').addClass('kieuinruot' + inner_page_count);
            html.find('th:eq(0)').html('In ruột loại ' + inner_page_count);
            html.find('.ten-loai-giay').html('Giấy ruột ' + inner_page_count);
            html.find('select, input').each(function () {
                if (this.name.indexOf('kieu_in_tro_ruot') !== -1)
                    this.name = this.name.replace('[kieu_in_tro_ruot]', "[kieu_in_tro_ruot" + inner_page_count + "]");
                else
                    this.name = this.name.replace(/\[([a-zA-Z]+)\]/g, "[$1" + inner_page_count + "]");
            });
            $(html).attr('data-ruot', inner_page_count);
            $(html).insertAfter(rootFrame.find('.kieuin-item:last'));
        };

        this.fillOutput = function (selectElm, is_ruot) {
            if (is_ruot === undefined) {
                is_ruot = '';
            }
            var _this = this, rootFrame = _this.getRootFrame(selectElm),
                count_output = rootFrame.find('.output-item').length,
                product_id = _this.getProductId(selectElm),
                product = _this.getProductById(product_id),
                count_inner_page = $('.trang-ruot-item').length, data_type,
                count_output_ruot = rootFrame.find('select[name*="ExportBiaRuot"] option[value="ruot"]').length;

            if (!product) {
                krajeeDialog.alert('Vui lòng chọn loại sản phẩm');
            }

            if (is_ruot && count_output_ruot <= 0)
                return false;
            $.ajax({
                'type': 'post',
                'url': link_fillOutput,
                'data': {
                    count_order: 0,
                    count_output: count_output,
                    count_inner_page: count_inner_page,
                    list_output_default: product.output_default,
                    is_ruot: is_ruot
                },
                'success': function (html) {

                    if (rootFrame.find('.output-item').length > 0)
                        $(html).insertAfter(rootFrame.find('.output-item:last'));
                    else
                        rootFrame.find('.output-lists').append(html);

                    data_type = $(html).find('select[name*="ExportBiaRuot"]').html();
                    if (data_type) {
                        rootFrame.find('select[name*="ExportBiaRuot"]').each(function () {
                            var val = this.value;
                            $(this).html(data_type).val(val);
                        });
                    }
                    rootFrame.find('.output-item').each(function(i){
                        if(i % 3 === 0){
                            $(this).addClass('clearfix');
                        }
                    });
                    rootFrame.find('.output-item select[name*="ExportNhaCungCap"]').each(function () {
                        $(this).select2({
                            allowClear: true,
                            language: "vi",
                            placeholder: "Chọn nhà cung cấp",
                            theme: "krajee",
                            width: "100%"
                        });
                    });
                    _this.tinhChiPhiXuatRa(selectElm);
                    _this.updateChiPhiTab(selectElm);
                }
            });
        };

        this.fillGiaCong = function (selectElm, is_ruot) {
            if (is_ruot === undefined) {
                is_ruot = '';
            }
            var _this = this, rootFrame = _this.getRootFrame(selectElm),
                count_giacong = rootFrame.find('.gia-cong-item').length,
                count_inner_page = $('.trang-ruot-item').length,
                product_id = _this.getProductId(selectElm),
                product = _this.getProductById(product_id), data_type,
                count_gia_cong_ruot = rootFrame.find('select[name*="Bia_Ruot"] option[value="ruot"]').length;

            if (!product) {
                krajeeDialog.alert('Vui lòng chọn loại sản phẩm');
            }

            if (is_ruot && count_gia_cong_ruot <= 0)
                return false;
            $.ajax({
                'type': 'post',
                'url': link_fillGiaCong,
                'data': {
                    count_order: 0,
                    count_giacong: count_giacong,
                    count_inner_page: count_inner_page,
                    product: product,
                    is_ruot: is_ruot
                },
                'success': function (html) {
                    if (rootFrame.find('.gia-cong-item').length > 0)
                        $(html).insertAfter(rootFrame.find('.gia-cong-item:last'));
                    else
                        rootFrame.find('.gia-cong-lists').append(html);

                    data_type = $(html).find('select[name*="[Bia_Ruot]"]').html();
                    if (data_type) {
                        rootFrame.find('select[name*="[Bia_Ruot]"]').each(function () {
                            var val = this.value;
                            $(this).html(data_type).val(val);
                        });
                    }
                    _this.tinhChiPhiGiaCong(selectElm);
                    _this.updateChiPhiTab(selectElm);
                }
            });
        };

        this.updateOutputList = function (selectElm) {
            var rootFrame = this.getRootFrame(selectElm);
            if (rootFrame.find('.output-item').length > 0) {
                rootFrame.find('.output-item').each(function (i) {
                    $(this).find('th:eq(0) .pull-left').html('Xuất ra ' + (i + 1));
                });
            }
        };

        this.updateGiaCongList = function (selectElm) {
            var rootFrame = this.getRootFrame(selectElm);
            if (rootFrame.find('.gia-cong-item').length > 0) {
                rootFrame.find('.gia-cong-item').each(function (i) {
                    $(this).find('th:eq(0) .pull-left').html('Gia công ' + (i + 1));
                });
            }
        };

        this.updateProductSelect = function (selectElm) {

            var _this = this, rootFrame = _this.getRootFrame(selectElm),
                product_id = _this.getProductId(selectElm),
                kieu_dong = _this.getProductKieuDong(selectElm),
                divisible_, error = 0,
                product = _this.getProductById(product_id), option, isSubOrder = _this.isSubOrder(selectElm),
                change_size = false;
            if (selectElm.nodeName === 'SELECT' && selectElm.name.indexOf('product_id') !== -1) {
                change_size = true;
            }
            //fill kich thuoc theo san pham
            if (product_id > 0 && change_size === true && is_update === 0) {
                rootFrame.find(input_productLength).val(product.length);
                rootFrame.find(input_productWidth).val(product.width);
                rootFrame.find(input_productThick).val(product.thick);
                rootFrame.find(input_kichThuocTai).val(product.kich_thuoc_tai_dan);
                rootFrame.find(input_taiNap).val(product.kich_thuoc_tai_nap);
                rootFrame.find(input_caiDay).val(product.kich_thuoc_cai_day);
                rootFrame.find(input_boiSongE).val(product.boi_song_e);
                rootFrame.find(input_buHaoNoiXen).val(product.bu_hao_noi_xen);
                rootFrame.find('input[name*="has_inner_page"]').val(product.has_inner_page);
                rootFrame.find('input[name*="product_type"]').val(product.product_type);
                //fill dinh luong giay bia
                if (!$.isEmptyObject(product.dinh_luong_giay_bia) && !isSubOrder) {
                    rootFrame.find(dinhLuongGiayBia).children('option:not(:first-child)').remove();
                    $.each(product.dinh_luong_giay_bia, function (k, v) {
                        if (k !== 'default')
                            rootFrame.find(dinhLuongGiayBia).append(
                                option = $('<option>').val(k).html(k)
                            );
                    });
                    if (product.dinh_luong_giay_bia.default)
                        rootFrame.find(dinhLuongGiayBia).val(product.dinh_luong_giay_bia.default);
                }

                //fill chat lieu giay bia
                if (product.chat_lieu_bia_thuong_dung > 0 && !isSubOrder) {
                    rootFrame.find(chatLieuGiayBia).val(product.chat_lieu_bia_thuong_dung);
                    $.ajax({
                        'url': link_getNccGiay,
                        'type': 'get',
                        'data': {'chatLieuId': product.chat_lieu_bia_thuong_dung},
                        'success': function (data) {
                            rootFrame.find(nccGiayBia).html(data);
                            var val_giaybianhacungcap = rootFrame.find(nccGiayBia).find("option:nth-child(2)").val();
                            rootFrame.find(nccGiayBia).val(val_giaybianhacungcap);
                            if (!$.isEmptyObject(supplier_json)) {
                                var don_gia_dinh_luong, don_gia = 0,
                                    chatLieuId = product.chat_lieu_bia_thuong_dung,
                                    dinhluong = Number(rootFrame.find(dinhLuongGiayBia).val());

                                if (supplier_json[val_giaybianhacungcap]) {
                                    don_gia_dinh_luong = jQuery.parseJSON(supplier_json[val_giaybianhacungcap].don_gia_dinh_luong);
                                    if(don_gia_dinh_luong[chatLieuId][dinhluong]) {
                                        don_gia = don_gia_dinh_luong[chatLieuId][dinhluong];
                                        rootFrame.find(donGiaGiayBia).val(don_gia);
                                    }
                                }
                            }
                        }
                    });
                }

                //fill nha cung cap in mac dinh
                if (product.ncc_in_default && product.ncc_in_default > 0) {
                    rootFrame.find(nccIn).val(Number(product.ncc_in_default)).trigger("change");
                    rootFrame.find(congThucIn).val(supplier_json[product.ncc_in_default].calcu_type);
                    $.ajax({
                        'dataType': 'json',
                        'type': 'post',
                        'url': link_getNccMacDinh,
                        'data': {ncc_id: product.ncc_in_default},
                        'success': function (json) {
                            if (!$.isEmptyObject(json)) {
                                rootFrame.find('select[name*="KhoMayIn"]').val(json.kg_id);
                                rootFrame.find('input[name*="DonGiaKem"]').val(json.price_k * json.kem_rong * json.kem_dai);
                                rootFrame.find('input[name*="DonGiaKemCm"]').val(json.price_k);
                                rootFrame.find('#kieu-in input[name*="DonGiaIn"]:not(input[name*="DonGiaInHasntFormula"])').val(json.price_i);
                                rootFrame.find('input[name*="DaiKem"]').val(json.kem_dai);
                                rootFrame.find('input[name*="RongKem"]').val(json.kem_rong);
                                _this.numberInput();
                            }
                        }
                    });
                }

                //fill kieu in bia
                if (product.KhoInBia_default) {
                    rootFrame.find(quyCachInBia).val(product.KhoInBia_default);
                    if (product.KieuInTroBia_default) {
                        _this.tinhQuyCachIn(product.KieuInTroBia_default, rootFrame);
                    }
                } else {
                    _this.tinhQuyCachIn('4/0', rootFrame);
                }

                //fill kieu in ruot
                if (product.KhoInRuot_default) {
                    rootFrame.find(quyCachInRuot).val(product.KhoInRuot_default);
                    if (product.KieuInTroRuot_default) {
                        _this.tinhQuyCachIn(product.KieuInTroRuot_default, rootFrame);
                    }
                } else {
                    _this.tinhQuyCachIn('1/1', rootFrame);
                }

                //fill thong tin giay ruot
                if ((product.has_inner_page === 1 || product.thick > 0) && !isSubOrder) {
                    if (product.chat_lieu_ruot_thuong_dung) {
                        rootFrame.find(chatLieuGiayRuot).val(product.chat_lieu_ruot_thuong_dung);
                        $.ajax({
                            'url': link_getNccGiay,
                            'type': 'get',
                            'data': {'chatLieuId': product.chat_lieu_ruot_thuong_dung},
                            'success': function (data) {
                                rootFrame.find(nccGiayRuot).html(data);
                                var val_giayruotnhacungcap = rootFrame.find(nccGiayRuot).find("option:nth-child(2)").val();
                                rootFrame.find(nccGiayRuot).val(val_giayruotnhacungcap);
                                if (!$.isEmptyObject(supplier_json)) {
                                    var don_gia_dinh_luong, don_gia = 0,
                                        chatLieuId = product.chat_lieu_ruot_thuong_dung,
                                        dinhluong = Number(rootFrame.find(dinhLuongGiayRuot).val());
                                    if (supplier_json[val_giayruotnhacungcap]) {
                                        don_gia_dinh_luong = jQuery.parseJSON(supplier_json[val_giayruotnhacungcap].don_gia_dinh_luong);
                                        if(don_gia_dinh_luong[chatLieuId][dinhluong]) {
                                            don_gia = don_gia_dinh_luong[chatLieuId][dinhluong];
                                            rootFrame.find('input[name*="GiayRuotPrice"]').val(don_gia);
                                        }
                                    }
                                }
                            }
                        });
                    }

                    if (product.dinh_luong_giay_ruot) {
                        rootFrame.find(dinhLuongGiayRuot).children('option:not(:first-child)').remove();
                        $.each(product.dinh_luong_giay_ruot, function (k, v) {
                            if (k !== 'default')
                                rootFrame.find(dinhLuongGiayRuot).append(
                                    option = $('<option>').val(k).html(k)
                                );
                        });
                        if (product.dinh_luong_giay_ruot.default)
                            rootFrame.find(dinhLuongGiayRuot).val(product.dinh_luong_giay_ruot.default);
                    }

                    if (product.dinh_luong_to_gac && !isSubOrder) {
                        rootFrame.find(dinhLuongToGac).children('option:not(:first-child)').remove();
                        $.each(product.dinh_luong_to_gac, function (k, v) {
                            if (k !== 'default')
                                rootFrame.find(dinhLuongToGac).append(
                                    option = $('<option>').val(k).html(k)
                                );
                        });
                        if (product.dinh_luong_to_gac.default)
                            rootFrame.find(dinhLuongToGac).val(product.dinh_luong_to_gac.default);
                    }

                    if (product.chat_lieu_to_gac_thuong_dung && !isSubOrder) {
                        rootFrame.find(chatLieuToGac).val(product.chat_lieu_to_gac_thuong_dung);
                        $.ajax({
                            'url': link_getNccGiay,
                            'type': 'get',
                            'data': {'chatLieuId': product.chat_lieu_to_gac_thuong_dung},
                            'success': function (data) {
                                rootFrame.find(nccToGac).html(data);
                                var val_togacnhacungcap = rootFrame.find(nccToGac).find("option:nth-child(2)").val();
                                rootFrame.find(nccToGac).val(val_togacnhacungcap);
                                if (!$.isEmptyObject(supplier_json)) {
                                    var don_gia_dinh_luong, don_gia = 0,
                                        chatLieuId = product.chat_lieu_to_gac_thuong_dung,
                                        dinhluong = Number(rootFrame.find(dinhLuongToGac).val());
                                    if (supplier_json[val_togacnhacungcap]) {
                                        don_gia_dinh_luong = jQuery.parseJSON(supplier_json[val_togacnhacungcap].don_gia_dinh_luong);
                                        if(don_gia_dinh_luong[chatLieuId][dinhluong]) {
                                            don_gia = don_gia_dinh_luong[chatLieuId][dinhluong];
                                            rootFrame.find(donGiaToGac).val(don_gia);
                                        }
                                    }
                                }
                            }
                        });
                    }
                }

                //fill xuat ra mac dinh
                rootFrame.find('.output-lists').empty();
                rootFrame.find('.them_loai_gia_cong').attr('disabled', false);
                if (product.output_default && is_photo === 0) {
                    var total_inner_page = 0;
                    if(product.has_inner_page === 1)
                        total_inner_page = _this.countInnerPage(rootFrame);

                    $.ajax({
                        'type': 'post',
                        'url': link_fillOutput,
                        'data': {
                            list_output_default: product.output_default,
                            count_inner: total_inner_page,
                            count_order: 0
                        },
                        'success': function (html) {
                            rootFrame.find('.output-lists').append(html);
                            _this.tinhChiPhiXuatRa(selectElm);
                            _this.updateChiPhiTab(selectElm);
                        }
                    });
                }

                //fill gia cong mac dinh
                rootFrame.find('.gia-cong-lists').empty();
                if(is_photo === 0) {
                    if (product.ncc_gia_cong_default) {
                        var total_inner_page = 0;
                        if (product.has_inner_page === 1)
                            total_inner_page = _this.countInnerPage(rootFrame);
                        $.ajax({
                            'type': 'post',
                            'url': link_fillGiaCong,
                            'data': {
                                product: product,
                                count_inner: total_inner_page,
                                count_order: 0
                            },
                            'success': function (html) {
                                rootFrame.find('.gia-cong-lists').append(html);
                                rootFrame.find('.gia-cong-lists select[name*="NhaCungCapId"]').each(function () {
                                    $(this).select2({
                                        allowClear: true,
                                        language: "vi",
                                        placeholder: "Chọn nhà cung cấp",
                                        theme: "krajee",
                                        width: "100%"
                                    });
                                });
                                _this.tinhChiPhiGiaCong(selectElm);
                                _this.updateChiPhiTab(selectElm);
                            }
                        });
                    } else {
                        rootFrame.find('.gia-cong-lists').append('<div class="col-md-12 text-danger">Để thiết lập loại gia công vui lòng thiết lập trong quản lý danh sách sản phẩm <a href="/acp/products/update?id=' + product_id + '" target="_blank">tại đây</a></div>');
                        rootFrame.find('.them_loai_gia_cong').attr('disabled', true);
                    }
                }

                //

                $('.kieuin-item').each(function () {
                    var $this_ = $(this), quycach = $.trim($this_.find('input[name*=QuyCachIn]').val());
                    if (quycach === '0/0') {
                        $this_.find('input[name*=QuyCachIn]').css('outline', '2px solid #c00').attr('title', 'Quy cách in không hợp lệ.');
                        rootFrame.find('.inbia_elm .QuyCachInTxt').empty();

                    } else if (quycach.match(/^(\d+)\/(\d+)$/)) {
                        $this_.find('input[name*=QuyCachIn]').css('outline', 'none').attr('title', '');
                        _this.tinhQuyCachIn(quycach, rootFrame);

                    } else {
                        $this_.find('input[name*=QuyCachIn]').css('outline', '2px solid #c00').attr('title', 'Quy cách in không hợp lệ.');
                    }
                });
            }

            //this.getProductSize(rootFrame, product);

            if (kieu_dong !== '' && product && Number(product.has_inner_page) === 1) {
                if (Number(kieu_dong) === DONG_GIUA) {
                    divisible_ = 4;
                } else {
                    divisible_ = 2;
                }
                rootFrame.find('input[name*="inner_page_amount"]').each(function () {
                    var so_luong_ruot = _this.removeFormat($(this).val());
                    so_luong_ruot = parseInt(so_luong_ruot);
                    if (so_luong_ruot % divisible_ !== 0) {
                        error = 1;
                        $(this).css({'border': '1px solid red'});
                        $(this).closest('[class$="inner_page_amount"]').find('.help-block').css({'height': '22px'}).html('Số trang phải là bội của ' + divisible_);

                    } else {
                        $(this).css({'border': ''});
                        $(this).closest('[class$="inner_page_amount"]').find('.help-block').css({'height': '0'}).empty();
                    }
                });
                if (error === 1)
                    has_error = 1;
                else
                    has_error = 0;
            }
        };

        // this.timKhoGiayIn = function () {
        //     var formData = new FormData($('#form-create-order')[0]);
        //     $.ajaxQueue({
        //         type: 'POST',
        //         url: link_findPaperSize,
        //         data:formData,
        //         cache:false,
        //         contentType: false,
        //         processData: false,
        //         success: function(data) {
        //             console.log(data);
        //         }
        //     });
        // };

        this.getProductKieuDong = function (selectElm) {
            var rootFrame = this.getRootFrame(selectElm),
                k = rootFrame.find('select[name*="KieuDong"] :selected').val();
            return Number(k);
        };

        this.getProductKieuHop = function (selectElm) {
            var rootFrame = this.getRootFrame(selectElm),
                k = rootFrame.find('select[name*="KieuHop"] :selected').val();
            return Number(k);
        };

        this.getProductAmount = function (selectElm) {
            var rootFrame = this.getRootFrame(selectElm),
                a = rootFrame.find('input[name*="[amount]"]').val();
            return this.removeFormat(a);
        };

        this.getChuaKepNhip = function (selectElm) {
            var rootFrame = this.getRootFrame(selectElm),
                a = rootFrame.find('input[name*="[chua_kep_nhip]"]').val();
            return this.removeFormat(a);
        };

        this.getProductWidth = function (selectElm) {
            //chieu rong san pham
            var rootFrame = this.getRootFrame(selectElm),
                w = rootFrame.find('input[name*="[width]"][type="text"]').val();
            return this.removeFormat(w);
        };

        this.isProductTwiceWidth = function (productJson) {
            //chieu rong nhan 2
          if(productJson)
            return Number(productJson.twice_width) === 1;
        };

        this.getProductLength = function (selectElm) {
            //chieu dai san pham
            var rootFrame = this.getRootFrame(selectElm),
                l = rootFrame.find('input[name*="[length]"][type="text"]').val();
            return this.removeFormat(l);
        };

        this.getProductThick = function (selectElm) {
            // be day san pham
            var rootFrame = this.getRootFrame(selectElm),
                t = rootFrame.find('input[name*="thick"][type="text"]').val();
            return this.removeFormat(t);
        };

        this.hasInnerPage = function (elm) {
            //neu san pham co trang ruot
            var product_id = this.getProductId(elm),
                productJson = this.getProductById(product_id);
          if(productJson)
            return Number(productJson.has_inner_page) === 1;
        };

        this.getProductInnerPageAmount = function (selectElm) {
            // so trang ruot
            var rootFrame = this.getRootFrame(selectElm), a = rootFrame.find('input[name*="inner_page_amount"]').val();
            return Number(a);
        };

        this.timKhoGiayBia = function (selectElm, real) {
            if (!real || real === undefined)
                real = 0;
            var _this = this,
                rootFrame = _this.getRootFrame(selectElm),
                kieuDong = _this.getProductKieuDong(selectElm),
                soLuong = _this.getProductAmount(selectElm),
                soSanPham = soLuong,
                chuaKepNhip = _this.getChuaKepNhip(selectElm),
                SoMatInBia = parseFloat(rootFrame.find('input[name*="SoMatInBia"]').val()),
                DonGiaKem = rootFrame.find('input[name*="DonGiaKemBia"]').val(),
                DonGiaIn = rootFrame.find('input[name*="DonGiaInBia"]').val(),
                daiIn = parseFloat(rootFrame.find('input[name*="sp_bia_length"]').val()),
                rongIn = parseFloat(rootFrame.find('input[name*="sp_bia_width"]').val()),
                caoIn = parseFloat(rootFrame.find('input[name*="sp_bia_height"]').val()),
                chatLieu = parseInt(rootFrame.find('select[name*="GiayBiaChatLieu"]').val()),
                dinhLuong = parseInt(rootFrame.find('select[name*="GiayBiaDinhLuong"]').val()),
                donGia = rootFrame.find('input[name*="GiayBiaPrice"]').val(),
                nhaCungCap = parseInt(rootFrame.find('select[name*="GiayBiaNhaCungCap"]').val()),
                taiSanPham = parseInt(rootFrame.find('input[name*="has_ear"]').val()),
                viTriTai = parseInt(rootFrame.find('select[name*="vi_tri_tai"]').val()),
                taiDai = parseFloat(rootFrame.find('input[name*="tai_dai"]').val()),
                taiRong = parseFloat(rootFrame.find('input[name*="tai_rong"]').val()),
                soToBuHao = rootFrame.find('input[name*="GiayBiaSoToBuHao"]').val(),
                soMauKem = parseFloat(rootFrame.find('input[name*="SoMauInBia"]').val()),
                matIn = parseFloat(rootFrame.find('input[name*="[SoMatInBia]"]').val()),
                NhaCungCapIn = parseFloat(rootFrame.find('select[name*="[NhaCungCapIn]"] :selected').val()),
                khoGiay = parseFloat(rootFrame.find('select[name*="[GiayBiaKhoGiayId]"]').val()),
                cachTinh = $.trim(rootFrame.find('input[name*="calcu_type"]').val()),
                biaGhepRuot = parseInt(rootFrame.find('input[name*="[bia_ghep_ruot]"]:checked').val()),
                viTriNhip = parseInt(rootFrame.find('input[name*="vt_nhip_bia"]:checked').val()),
                MauPha = parseInt(rootFrame.find('input[name*="MauPhaBia"]:checked').val()),
                HeSoMauPha = parseFloat(rootFrame.find("#he_so_mau_pha").val()),
                kieuHop = _this.getProductKieuHop(selectElm),
                kichThuocTai = parseFloat(rootFrame.find('input[name*="kich_thuoc_tai"]').val()),
                kichThuocCaiDay = parseFloat(rootFrame.find('input[name*="kich_thuoc_cai_day"]').val()),
                boiSongE = parseInt(rootFrame.find('input[name*="boi_song_e"]:checked').val()),
                taiNap = parseFloat(rootFrame.find('input[name*="tai_nap"]').val()),
                kichThuocNap = parseFloat(rootFrame.find('input[name*="kich_thuoc_nap"]').val()),
                kieuInGiaTrucTiep = parseInt(rootFrame.find('input[name*="[checkbox_hasnt_formula_kieu_in]"]:checked').val()),
                giayInGiaTrucTiep = parseInt(rootFrame.find('input[name*="[checkbox_hasnt_formula_giay_in]"]:checked').val()),
                loaiSanPham = parseInt(rootFrame.find('input[name*="product_type"]').val()),
                khoMay = parseFloat(rootFrame.find('select[name*="[KhoMayInBia]"]').val()),
                giaTriboiSongE = parseFloat(rootFrame.find('#gia_tri_boi_song_e').val()),
                buHaoNoiXen = parseFloat(rootFrame.find('#bu_hao_noi_xen').val()),
                khoKemDai = parseFloat(rootFrame.find('input[name*="DaiKemBia"]').val()),
                khoKemRong = parseFloat(rootFrame.find('input[name*="RongKemBia"]').val()),
                SapXep = parseFloat(rootFrame.find('input[name*="[SapXepGiayBia]"]').val()),
                quyCachIn = $.trim(rootFrame.find('input[name*="[QuyCachIn]"]').val()),
                kieuTro = parseFloat(rootFrame.find('select[name*="[kieu_in_tro]"]').val()),
                thanhPham = parseFloat(rootFrame.find('input[name*="[GiayBiaThanhPham]"]').val());
            if (isNaN(soLuong) || soLuong <= 0 || isNaN(daiIn) || daiIn <= 0 || isNaN(rongIn) || rongIn <= 0 || isNaN(nhaCungCap) || nhaCungCap <= 0 || isNaN(chatLieu) || chatLieu <= 0 || isNaN(dinhLuong) || dinhLuong <= 0)
                return;

            soToBuHao = this.removeFormat(soToBuHao);

            if (!rootFrame.find('input[name*="[GiayBiaThanhPham]"]').hasClass('userChanged')) {
                thanhPham = 0;
            }

            if (soSanPham === 0 || soSanPham === undefined)
                return;

            if (real === 1) {
                $('#giay-in select').removeClass('userChanged');
                $('#giay-in .buhao').removeClass('userChanged');
                thanhPham = khoGiay = khoMay = 0;
            }

            if (!rootFrame.find('input[name*="[GiayBiaSoToBuHao]"]').hasClass('userChanged')) {
                soToBuHao = -1;
            }

            if ((!rootFrame.find('select[name*="[GiayBiaKhoGiayId]"]').hasClass('userChanged') && soToBuHao === -1)) {
                khoGiay = 0;
            }

            if (real === 3) {
                khoGiay = khoMay = khoKemDai = khoKemRong = DonGiaIn = DonGiaKem = thanhPham = 0;
            }

            if (rootFrame.find('input[name*="[bia_ghep_ruot]"]').is(":checked")) {
                var tbody_ = $('.tbody_giay_bia_order:first');
                $('input[name*="GiayBiaThanhPham"], input[name*="GiayBiaSoTo"], input[name*="GiayBiaSoToBuHao"]', tbody_).val(0);
                $('select, input, span.select2', tbody_).css({'pointer-events': 'none'});
            }

            if (giayInGiaTrucTiep === 1) {
                donGia = 0;
            }

            if (!rootFrame.find('select[name*="[kieu_in_tro]"]').hasClass('userChanged')) {
                kieuTro = '';
            }

            if (real === 3 || real === 1 || real === 0 || (real === 2 && SapXep === 0) || SapXep === 0) {
                $.ajaxQueue({
                    url: link_getProductSize,
                    type: "GET",
                    data: {
                        'dai': daiIn,
                        'rong': rongIn,
                        'cao': caoIn,
                        'chuaKepNhip': chuaKepNhip,
                        'chatLieu': chatLieu,
                        'dinhLuong': dinhLuong,
                        'nhaCungCap': nhaCungCap,
                        'soLuong': soLuong,
                        'donGia': donGia,
                        'type': 'gb',
                        'taiSanPham': taiSanPham,
                        'viTriTai': viTriTai,
                        'taiDai': taiDai,
                        'taiRong': taiRong,
                        'soToBuHao': soToBuHao,
                        'soMauKem': soMauKem,
                        'matIn': matIn,
                        'khoMay': khoMay,
                        'nhaCungCapIn': NhaCungCapIn,
                        'real': real,
                        'khoGiay': khoGiay,
                        'kieuDong': kieuDong,
                        'soSanPham': soSanPham,
                        'cachTinh': cachTinh,
                        'biaGhepRuot': biaGhepRuot,
                        'viTriNhip': viTriNhip,
                        'mauPha': MauPha,
                        'heSoMauPha': HeSoMauPha,
                        'kieuHop': kieuHop,
                        'kichThuocTai': kichThuocTai,
                        'kichThuocCaiDay': kichThuocCaiDay,
                        'boiSongE': boiSongE,
                        'taiNap': taiNap,
                        'kichThuocNap': kichThuocNap,
                        'loaiSanPham': loaiSanPham,
                        'giaTriBoiSongE': giaTriboiSongE,
                        'buHaoNoiXen': buHaoNoiXen,
                        'DonGiaKem': DonGiaKem,
                        'DonGiaIn': DonGiaIn,
                        'khoKemDai': khoKemDai,
                        'khoKemRong': khoKemRong,
                        'kieuInGiaTrucTiep': kieuInGiaTrucTiep,
                        'thanhPham': thanhPham,
                        'banTinh': 'thuongmai',
                        'quyCachIn':quyCachIn,
                        'kieuTro': kieuTro
                    },
                    success: function (jsonData) {
                        if (jsonData.success === true) {
                            if (rootFrame.find('input[name*="[GiayBiaThanhPham]"]').hasClass('userChanged') && real === 1) {
                                rootFrame.find('input[name*="[GiayBiaThanhPham]"]').removeClass('userChanged');
                            }
                            if ((NhaCungCapIn === '' || NhaCungCapIn === 0 || isNaN(NhaCungCapIn)) && jsonData.ncc !== '' && jsonData.ncc !== undefined) {
                                rootFrame.find('select[name*="[NhaCungCapIn]"]').val(jsonData.ncc.supplierid).trigger('change');
                                return false;
                            }

                            if ((khoMay === '' || khoMay === 0 || isNaN(khoMay)) && jsonData.khoMay !== '' && jsonData.khoMay !== undefined) {
                                rootFrame.find('select[name*="[KhoMayInBia]"]').val(jsonData.khoMay.kg_id).trigger('change', false);
                            } else if ((khoKemDai < jsonData.khoMay.kem_dai || khoKemRong < jsonData.khoMay.kem_rong) && giayInGiaTrucTiep !== 1) {
                                rootFrame.find('select[name*="[KhoMayInBia]"]').val(jsonData.khoMay.kg_id).trigger('change', false);
                            }

                            if (giayInGiaTrucTiep !== 1) {
                                rootFrame.find('select[name*="[GiayBiaKhoGiayId]"]').val(jsonData.info.content_id);
                                rootFrame.find('input[name*="[GiayBiaThanhPham]"]').val(jsonData.thanhPham);
                                rootFrame.find('input[name*="[GiayBiaSoTo]"][type="text"]').val(jsonData.soTo);
                                rootFrame.find('input[name*="[GiayBiaSoToBuHao]"]').val(jsonData.toBuHao);
                            }

                            rootFrame.find('input[name*="[GiayBiaLength]"][type="hidden"]').val(jsonData.info.length);
                            rootFrame.find('input[name*="[GiayBiaWidth]"][type="hidden"]').val(jsonData.info.width);
                            rootFrame.find('input[name*="[GiayBiaSoTo]"][type="hidden"]').val(jsonData.soTo);
                            rootFrame.find('input[name*="[GiayBiaChiPhi]"][type=hidden]').val(jsonData.chiPhiGiayIn);
                            rootFrame.find('input[name*="[ChiPhiInBia]"][type=hidden]').val(jsonData.chiPhiIn);
                            rootFrame.find('input[name*="[TongChiPhiInBia]"][type=hidden]').val(jsonData.chiPhiIn);
                            rootFrame.find('input[name*="[TongChiPhiGiayInBia]"][type=hidden]').val(jsonData.chiPhiGiayIn);
                            if(kieuTro === '') {
                                rootFrame.find('select[name*="[kieu_in_tro]"]').val(jsonData.kieu_in_tro);
                                rootFrame.find('input[name*="[SoMauInBia]"]').val(jsonData.soMauKem);
                                rootFrame.find('select[name*="[QuyCachIn]"]').css('outline', 'none').attr('title', '');
                            }

                            if (biaGhepRuot === 1) {
                                rootFrame.find('input[name*="[GiayBiaThanhPham]"]').val(0);
                                rootFrame.find('input[name*="[GiayBiaSoTo]"][type="text"]').val(0);
                                rootFrame.find('input[name*="[GiayBiaSoToBuHao]"]').val(0);

                                rootFrame.find('input[name*="[GiayBiaSoTo]"][type="hidden"]').val(0);
                                rootFrame.find('input[name*="[TongChiPhiInBia]"][type=hidden]').val(0);
                                rootFrame.find('input[name*="[TongChiPhiGiayInBia]"][type=hidden]').val(0);
                            }
                            _this.numberInput();
                            _this.tinhChiPhiXuatRa(selectElm);
                            _this.tinhChiPhiGiaCong(selectElm);
                            _this.tinhChiPhiGiayIn(selectElm);
                            _this.tinhChiPhiIn(selectElm);
                            _this.updateChiPhiTab(selectElm);
                        } else {
                            if (jsonData.msg !== '')
                                krajeeDialog.alert(jsonData.msg);
                            if(previous_val){
                                rootFrame.find('select[name*="[GiayBiaKhoGiayId]"]').val(previous_val);
                            }
                            return false;
                        }
                    }
                });
            }
        };

        this.timKhoGiayRuot = function (selectElm, real, stt) {
            if (!real || real === undefined)
                real = 0;
            if(!stt || stt === undefined)
                stt = '';
            var _this = this,
                rootFrame = _this.getRootFrame(selectElm),
                soLuong_ = 0, nap_hop = 0,
                elm = $(rootFrame).find('select[name*="[GiayRuotKhoGiayId' + stt + ']"]'),
                tbodyObj = $(elm).closest('tbody'),
                kieuDong = _this.getProductKieuDong(selectElm),
                soLuong = _this.getProductAmount(selectElm),
                soSanPham = _this.getProductAmount(selectElm),
                soTrangRuot = _this.getProductInnerPageAmount(selectElm),
                chuaKepNhip = _this.getChuaKepNhip(selectElm),
                SoMatInBia = parseFloat(rootFrame.find('input[name*="SoMatInBia"]').val()),
                DonGiaKem = rootFrame.find('input[name*="[DonGiaKemRuot' + stt + ']"]').val(),
                DonGiaIn = rootFrame.find('input[name*="[DonGiaInRuot' + stt + ']"]').val(),
                daiIn = parseFloat(rootFrame.find('input[name*="sp_ruot_length"]').val()),
                rongIn = parseFloat(rootFrame.find('input[name*="sp_ruot_width"]').val()),
                chatLieu = parseInt(rootFrame.find('select[name*="[GiayRuotChatLieu' + stt + ']"]').val()),
                dinhLuong = parseInt(rootFrame.find('select[name*="[GiayRuotDinhLuong' + stt + ']"]').val()),
                donGia = rootFrame.find('input[name*="[GiayRuotPrice' + stt + ']"]').val(),
                nhaCungCap = parseInt(rootFrame.find('select[name*="[GiayRuotNhaCungCap' + stt + ']"]').val()),
                taiSanPham = parseInt(rootFrame.find('input[name*="has_ear"]').val()),
                viTriTai = parseInt(rootFrame.find('select[name*="vi_tri_tai"]').val()),
                taiDai = parseFloat(rootFrame.find('input[name*="tai_dai"]').val()),
                taiRong = parseFloat(rootFrame.find('input[name*="tai_rong"]').val()),
                soToBuHao = rootFrame.find('input[name*="[GiayRuotSoToBuHao' + stt + ']"]').val(),
                soMauKem = parseFloat(rootFrame.find('input[name*="[SoMauInRuot' + stt + ']"]').val()),
                matIn = parseFloat(rootFrame.find('input[name*="[SoMatInRuot' + stt + ']"]').val()),
                NhaCungCapIn = rootFrame.find('select[name*="[NhaCungCapIn]"] :selected').val(),
                khoGiay = parseInt(rootFrame.find('select[name*="[GiayRuotKhoGiayId' + stt + ']"]').val()),
                cachTinh = $.trim(rootFrame.find('input[name*="calcu_type"]').val()),
                biaGhepRuot = parseInt(rootFrame.find('input[name*="[bia_ghep_ruot]"]:checked').val()),
                viTriNhip = parseInt(rootFrame.find('input[name*="[vt_nhip_ruot' + stt + '"]:checked').val()),
                MauPha = parseInt(rootFrame.find('input[name*="MauPhaBia"]:checked').val()),
                HeSoMauPha = parseFloat(rootFrame.find("#he_so_mau_pha").val()),
                inChungKho = parseInt(tbodyObj.find('input[name*="InCungKhoGiay"]:checked').val()),
                kieuHop = _this.getProductKieuHop(selectElm),
                kichThuocTai = parseFloat(rootFrame.find('input[name*="kich_thuoc_tai"]').val()),
                kichThuocCaiDay = parseFloat(rootFrame.find('input[name*="kich_thuoc_cai_day"]').val()),
                boiSongE = parseInt(rootFrame.find('input[name*="boi_song_e"]:checked').val()),
                taiNap = parseFloat(rootFrame.find('input[name*="tai_nap"]').val()),
                kichThuocNap = parseFloat(rootFrame.find('input[name*="kich_thuoc_nap"]').val()),
                kieuInGiaTrucTiep = parseInt(rootFrame.find('input[name*="[checkbox_hasnt_formula_kieu_in]"]:checked').val()),
                giayInGiaTrucTiep = parseInt(rootFrame.find('input[name*="[checkbox_hasnt_formula_giay_in]"]:checked').val()),
                loaiSanPham = parseInt(rootFrame.find('input[name*="product_type"]').val()),
                khoMay = parseFloat(rootFrame.find('select[name*="[KhoMayInRuot' + stt + ']"]').val()),
                khoKemDai = parseFloat(rootFrame.find('input[name*="[DaiKemRuot' + stt + ']"]').val()),
                khoKemRong = parseFloat(rootFrame.find('input[name*="[RongKemRuot' + stt + ']"]').val()),
                khoGiayTayCuoi = parseInt(rootFrame.find('select[name*="[TayCuoiKhoGiayId' + stt + ']"]').val()),
                count_order = rootFrame.index(),
                SapXep = parseFloat(rootFrame.find('input[name*="[SapXepGiayRuot' + stt + ']"]').val()),
                quyCachIn = $.trim(rootFrame.find('input[name*="[QuyCachInRuot' + stt + ']"]').val()),
                buHaoTayCuoi = -1,
                giaTriboiSongE = parseFloat(rootFrame.find('#gia_tri_boi_song_e').val()),
                buHaoNoiXen = parseFloat(rootFrame.find('#bu_hao_noi_xen').val()),
                thanhPham = parseFloat(rootFrame.find('input[name*="[GiayRuotThanhPham' + stt + ']"]').val()),
                kieuTro = parseFloat(rootFrame.find('select[name*="[kieu_in_tro_ruot' + stt + ']"]').val()),
                thanhPhamTayCuoi = parseFloat(rootFrame.find('input[name*="[TayCuoiThanhPham' + stt + ']"]').val());

            if (rootFrame.find('input[name*="[GiayRuotSoToBuHaoThem' + stt + ']"]').length && rootFrame.find('input[name*="[GiayRuotSoToBuHaoThem' + stt + ']"]').hasClass('userChanged')) {
                buHaoTayCuoi = parseFloat(rootFrame.find('input[name*="[GiayRuotSoToBuHaoThem' + stt + ']"]').val());

                buHaoTayCuoi = this.removeFormat(buHaoTayCuoi);
            }

            if (!rootFrame.find('input[name*="[GiayRuotThanhPham' + stt + ']"]').hasClass('userChanged')) {
                thanhPham = 0;
            }

            if (!rootFrame.find('input[name*="[TayCuoiThanhPham' + stt + ']"]').hasClass('userChanged')) {
                thanhPhamTayCuoi = 0;
            }

            if (real === 1) {
                buHaoTayCuoi = -1;
                thanhPham = 0;
                thanhPhamTayCuoi = 0;
            }

            soToBuHao = this.removeFormat(soToBuHao);

            if (soSanPham === 0 || soSanPham === undefined)
                return;

            if (!rootFrame.find('input[name*="[GiayRuotSoToBuHao' + stt + ']"]').hasClass('userChanged')) {
                soToBuHao = -1;
            }

            if (!rootFrame.find('select[name*="[GiayRuotKhoGiayId' + stt + ']"]').hasClass('userChanged') && soToBuHao === -1 && thanhPham === 0) {
                khoGiay = 0;
                khoGiayTayCuoi = 0;
            }

            if (kieuHop !== hopCungDinhHinh) {
                if (stt > 0) {
                    soLuong_ = parseFloat($("#orderinfo-" + count_order + "-ext_inner_page_amount_" + (stt - 2)).val());
                    MauPha = parseFloat(rootFrame.find('input[id$="orderinfo-' + count_order + '_ext_mau_pha_ruot_' + (stt - 2) + '"]:checked').val());
                } else {
                    soLuong_ = parseFloat(rootFrame.find('input[name*="[inner_page_amount]"]').val());
                    MauPha = parseInt(rootFrame.find('input[name*="MauPhaRuot"]:checked').val());
                }
            } else {
                soLuong_ = soLuong;
                nap_hop = 1;
            }
            console.log(soSanPham, soLuong_, daiIn, rongIn, nhaCungCap, chatLieu, dinhLuong);
            if (isNaN(soSanPham) || soSanPham <= 0 || isNaN(soLuong_) || soLuong_ <= 0 || isNaN(daiIn) || daiIn <= 0 || isNaN(rongIn) || rongIn <= 0 || isNaN(nhaCungCap) || nhaCungCap <= 0 || isNaN(chatLieu) || chatLieu <= 0 || isNaN(dinhLuong) || dinhLuong <= 0)
                return;
            if (real === 3) {
                khoGiay = khoMay = khoKemDai = khoKemRong = DonGiaIn = DonGiaKem = thanhPham = 0;
            }

            if (giayInGiaTrucTiep === 1) {
                donGia = 0;
            }
            if (!rootFrame.find('select[name*="[kieu_in_tro_ruot' + stt + ']"]').hasClass('userChanged')) {
                kieuTro = '';
            }
            if ((SapXep === 0 && real === 2) || real === 1 || real === 0 || real === 3 || SapXep === 0) {
                $.ajaxQueue({
                    url: link_getProductSize,
                    type: "GET",
                    cache: false,
                    data: {
                        'dai': daiIn,
                        'rong': rongIn,
                        'chuaKepNhip': chuaKepNhip,
                        'chatLieu': chatLieu,
                        'dinhLuong': dinhLuong,
                        'nhaCungCap': nhaCungCap,
                        'soLuong': soLuong_,
                        'donGia': donGia,
                        'type': 'gr',
                        'taiSanPham': taiSanPham,
                        'viTriTai': viTriTai,
                        'taiDai': taiDai,
                        'taiRong': taiRong,
                        'soToBuHao': soToBuHao,
                        'soMauKem': soMauKem,
                        'matIn': matIn,
                        'nhaCungCapIn': NhaCungCapIn,
                        'real': real,
                        'khoGiay': khoGiay,
                        'kieuDong': kieuDong,
                        'soSanPham': soSanPham,
                        'cachTinh': cachTinh,
                        'biaGhepRuot': biaGhepRuot,
                        'inChungKho': inChungKho,
                        'khoGiayTayCuoi': khoGiayTayCuoi,
                        'viTriNhip': viTriNhip,
                        'mauPha': MauPha,
                        'heSoMauPha': HeSoMauPha,
                        'kieuHop': kieuHop,
                        'kichThuocTai': kichThuocTai,
                        'kichThuocCaiDay': kichThuocCaiDay,
                        'boiSongE': boiSongE,
                        'taiNap': taiNap,
                        'kichThuocNap': kichThuocNap,
                        'nap_hop': nap_hop,
                        'khoMay': khoMay,
                        'loaiSanPham': loaiSanPham,
                        'DonGiaKem': DonGiaKem,
                        'DonGiaIn': DonGiaIn,
                        'khoKemDai': khoKemDai,
                        'khoKemRong': khoKemRong,
                        'buHaoTayCuoi': buHaoTayCuoi,
                        'quyCachIn': quyCachIn,
                        'kieuInGiaTrucTiep': kieuInGiaTrucTiep,
                        'giaTriBoiSongE': giaTriboiSongE,
                        'buHaoNoiXen': buHaoNoiXen,
                        'thanhPham': thanhPham,
                        'thanhPhamTayCuoi': thanhPhamTayCuoi,
                        'banTinh': 'thuongmai',
                        'kieuTro':kieuTro

                    }, success: function (jsonData) {
                        if (jsonData.success === true) {
                            if (tbodyObj.find('select[name*="[GiayRuotKhoGiayId' + (stt > 0 ? stt : '') + ']"]').hasClass('userChanged') && real === 1) {
                                tbodyObj.find('select[name*="[GiayRuotKhoGiayId' + (stt > 0 ? stt : '') + ']"]').removeClass('userChanged');
                            }
                            if (tbodyObj.find('input[name*="[GiayRuotThanhPham' + (stt > 0 ? stt : '') + ']"]').hasClass('userChanged') && real === 1) {
                                tbodyObj.find('input[name*="[GiayRuotThanhPham' + (stt > 0 ? stt : '') + ']"]').removeClass('userChanged');
                            }
                            var khomayin = rootFrame.find('select[name*="[KhoMayInRuot' + (stt > 0 ? stt : '') + ']"]').val();

                            if ((khomayin === '' || khomayin === 0) && jsonData.khoMay !== '' && jsonData.khoMay !== undefined)
                                rootFrame.find('select[name*="[KhoMayInRuot' + (stt > 0 ? stt : '') + ']"]').val(jsonData.khoMay.kg_id).trigger('change', false);
                            else if (khoKemDai < jsonData.khoMay.kem_dai || khoKemRong < jsonData.khoMay.kem_rong) {
                                rootFrame.find('select[name*="[KhoMayInRuot' + (stt > 0 ? stt : '') + ']"]').val(jsonData.khoMay.kg_id).trigger('change', false);
                            }

                            if (stt > 0) {
                                soTrangRuot = parseFloat($("#OrderInfo_" + count_order + "_ext_inner_page_amount_" + stt).val());
                            } else {
                                if (!rootFrame.find('input[name*="[bia_ghep_ruot]"]').is(":checked"))
                                    soTrangRuot = parseFloat($("#OrderInfo_" + count_order + "_inner_page_amount").val());
                                if (!$('select[name*="ToGacKhoGiayId"]', rootFrame).hasClass('userChanged')) {
                                    $('select[name*="ToGacKhoGiayId"]', rootFrame).val(jsonData.info.content_id);
                                }
                            }
                            if(kieuTro === '')
                                rootFrame.find('select[name*="[kieu_in_tro_ruot' + stt + ']"]').val(jsonData.kieu_in_tro);

                            rootFrame.find('select[name*="[GiayRuotKhoGiayId' + stt + ']"]').val(jsonData.info.content_id);
                            tbodyObj.find('input[name*="[GiayRuotLength' + stt + ']"][type="hidden"]').val(jsonData.info.length);
                            tbodyObj.find('input[name*="[GiayRuotWidth' + stt + ']"][type="hidden"]').val(jsonData.info.width);
                            tbodyObj.find('input[name*="[GiayRuotThanhPham' + stt + ']"]').val(jsonData.thanhPham);
                            tbodyObj.find('input[name*="[GiayRuotSoTo' + stt + ']"]').val(jsonData.soTo);
                            tbodyObj.find('input[name*="[GiayRuotSoTo' + stt + ']"][type="hidden"]').val(jsonData.soTo);
                            tbodyObj.find('input[name*="[SoTay' + stt + ']"]').val(jsonData.soTay);
                            tbodyObj.find('input[name*="[TrangTrenTay' + stt + ']"]').val(jsonData.trangTrenTay);
                            tbodyObj.find('input[name*="[TrangDu' + stt + ']"]').val(jsonData.trangDu);
                            tbodyObj.find('input[name*="[GiayRuotSoToBuHao' + stt + ']"]').val(jsonData.toBuHao);
                            tbodyObj.find('input[name*="[GiayRuotChiPhi' + stt + ']"][type=hidden]').val(jsonData.chiPhiGiayIn);
                            tbodyObj.find('input[name*="[TongChiPhiInRuot' + stt + ']"][type=hidden]').val(jsonData.chiPhiIn);
                            tbodyObj.find('input[name*="[TongChiPhiGiayInRuot' + stt + ']"][type=hidden]').val(jsonData.chiPhiGiayIn);

                            if (!$.isEmptyObject(jsonData.tay_cuoi)) {
                                var $kieuin_ruot = rootFrame.find(".kieuin-lists li.kieuinruot" + stt),
                                    $kieuin_taycuoi = rootFrame.find(".kieuin-lists li.kieuintaycuoi" + stt);
                                if (!$kieuin_ruot.hasClass('taycuoi_elm') && $kieuin_taycuoi.length <= 0 && jsonData.trangTrenTay > 0) {
                                    var html_tay_cuoi = $kieuin_ruot.clone();
                                    html_tay_cuoi.removeClass('inruot_elm kieuinruot' + stt).addClass('taycuoi_elm kieuintaycuoi' + stt);
                                    html_tay_cuoi.find('th:first').html('Tay cuối ruột ' + stt);
                                    html_tay_cuoi.attr('data-tay_cuoi', stt);
                                    html_tay_cuoi.find('input, select').each(function () {
                                        if (this.name.indexOf('KhoMayInRuot') !== -1) {
                                            this.name = this.name.replace('[KhoMayInRuot' + stt + ']', '[KhoMayInRuotTayCuoi' + stt + ']');
                                        } else if (this.name.indexOf('DaiKemRuot') !== -1) {
                                            this.name = this.name.replace('[DaiKemRuot' + stt + ']', '[DaiKemRuotTayCuoi' + stt + ']');
                                        } else if (this.name.indexOf('RongKemRuot') !== -1) {
                                            this.name = this.name.replace('[RongKemRuot' + stt + ']', '[RongKemRuotTayCuoi' + stt + ']');
                                        } else if (this.name.indexOf('DonGiaKemRuot') !== -1) {
                                            this.name = this.name.replace('[DonGiaKemRuot' + stt + ']', '[DonGiaKemRuotTayCuoi' + stt + ']');
                                        } else if (this.name.indexOf('DonGiaKemCmRuot') !== -1) {
                                            this.name = this.name.replace('[DonGiaKemCmRuot' + stt + ']', '[DonGiaKemCmRuotTayCuoi' + stt + ']');
                                        } else if (this.name.indexOf('DonGiaInRuot') !== -1) {
                                            this.name = this.name.replace('[DonGiaInRuot' + stt + ']', '[DonGiaInRuotTayCuoi' + stt + ']');
                                        } else if (this.name.indexOf('kieu_in_tro_ruot') !== -1) {
                                            this.name = this.name.replace('[kieu_in_tro_ruot' + stt + ']', '[kieu_in_tro_ruotTayCuoi' + stt + ']');
                                        } else if (this.name.indexOf('QuyCachInRuot') !== -1) {
                                            this.name = this.name.replace('[QuyCachInRuot' + stt + ']', '[QuyCachInRuotTayCuoi' + stt + ']');
                                        } else if (this.name.indexOf('SoMauInRuot') !== -1) {
                                            this.name = this.name.replace('[SoMauInRuot' + stt + ']', '[SoMauInRuotTayCuoi' + stt + ']');
                                        } else if (this.name.indexOf('SoMatInRuot') !== -1) {
                                            this.name = this.name.replace('[SoMatInRuot' + stt + ']', '[SoMatInRuotTayCuoi' + stt + ']');
                                        } else if (this.name.indexOf('ChiPhiInRuot') !== -1) {
                                            this.name = this.name.replace('[ChiPhiInRuot' + stt + ']', '[ChiPhiInRuotTayCuoi' + stt + ']');
                                        }
                                    });
                                    $(html_tay_cuoi).insertAfter(rootFrame.find(".kieuin-lists li.kieuinruot" + stt));
                                    html_tay_cuoi.find('input[name*="[SoMauInRuotTayCuoi' + stt + ']"]').val(jsonData.tay_cuoi.soMauKem);
                                    html_tay_cuoi.find('input[name*="[SoMatInRuotTayCuoi' + stt + ']"]').val(jsonData.tay_cuoi.soMatIn);
                                }

                                var khomayintaycuoi = rootFrame.find('select[name*="[KhoMayInRuotTayCuoi' + stt + ']"]').val(),
                                    khoKemDaiTayCuoi = parseFloat(rootFrame.find('input[name*="[DaiKemRuotTayCuoi' + stt + ']"]').val()),
                                    khoKemRongTayCuoi = parseFloat(rootFrame.find('input[name*="[RongKemRuotTayCuoi' + stt + ']"]').val());
                                if (real === 3) {
                                    khomayintaycuoi = khoKemDaiTayCuoi = khoKemRongTayCuoi = 0;
                                }
                                if ((khomayintaycuoi === '' || khomayintaycuoi === 0) && jsonData.tay_cuoi.khoMay !== '' && jsonData.tay_cuoi.khoMay !== undefined)
                                    rootFrame.find('select[name*="[KhoMayInRuotTayCuoi' + stt + ']"]').val(jsonData.tay_cuoi.khoMay.kg_id).trigger('change', false);
                                else if ((khoKemDaiTayCuoi < jsonData.tay_cuoi.khoMay.kem_dai || khoKemRongTayCuoi < jsonData.tay_cuoi.khoMay.kem_rong) && !rootFrame.find('select[name*="[KhoMayInRuotTayCuoi' + stt + ']"]').hasClass('userChanged')) {
                                    rootFrame.find('select[name*="[KhoMayInRuotTayCuoi' + stt + ']"]').val(jsonData.tay_cuoi.khoMay.kg_id).trigger('change', false);
                                }

                                tbodyObj.find('input[name*="[TayCuoiSoTo' + stt + ']"][type=hidden]').val(jsonData.tay_cuoi.soTo);
                                tbodyObj.find('input[name*="[TongChiPhiInTayCuoi' + stt + ']"][type=hidden]').val(jsonData.tay_cuoi.chiPhiIn);
                                tbodyObj.find('input[name*="[TongChiPhiGiayInTayCuoi' + stt + ']"][type=hidden]').val(jsonData.tay_cuoi.chiPhiGiayIn);
                                tbodyObj.find('input[name*="[GiayRuotTayCuoiLength' + stt + ']"][type="hidden"]').val(jsonData.tay_cuoi.info.length);
                                tbodyObj.find('input[name*="[GiayRuotTayCuoiWidth' + stt + ']"][type="hidden"]').val(jsonData.tay_cuoi.info.width);

                                if(kieuTro === '') {
                                    rootFrame.find('select[name*="[kieu_in_tro_ruot' + stt + ']"]').val(jsonData.kieu_in_tro);
                                    rootFrame.find('input[name*="[SoMauInRuot' + stt + ']"]').val(jsonData.soMauKem);
                                    rootFrame.find('select[name*="[QuyCachInRuot' + stt + ']"]').css('outline', 'none').attr('title', '');
                                }

                                if (giayInGiaTrucTiep !== 1) {
                                    tbodyObj.find('select[name*="[TayCuoiKhoGiayId' + stt + ']"]').val(jsonData.tay_cuoi.info.content_id);
                                    tbodyObj.find('input[name*="[TayCuoiThanhPham' + stt + ']"]').val(jsonData.tay_cuoi.thanhPham);
                                    tbodyObj.find('input[name*="[TayCuoiSoTo' + stt + ']"][type=text]').val(jsonData.tay_cuoi.soTo);
                                    tbodyObj.find('input[name*="[GiayRuotSoToBuHaoThem' + stt + ']"]').val(jsonData.tay_cuoi.toBuHao);
                                    tbodyObj.find('.tay_cuoi_ruot').show();

                                    if (jsonData.tay_cuoi.info.content_id === jsonData.info.content_id) {
                                        tbodyObj.find('input[name*="[InCungKhoGiay' + stt + ']"]').prop('checked', true);
                                        inChungKho = 1;
                                    } else if (real !== 3 && real !== 1) {
                                        tbodyObj.find('input[name*="[InCungKhoGiay' + stt + ']"]').prop('checked', false);
                                        inChungKho = 0;
                                    }
                                }
                                _this.timKhoGiayRuotTayCuoi(tbodyObj, 0, stt);
                            } else {
                                tbodyObj.find('.tay_cuoi_ruot').hide();
                                tbodyObj.find('input[name*="[TongChiPhiGiayInTayCuoi' + stt + ']"][type=hidden]').val(0);
                                if (rootFrame.find(".taycuoi_elm").hasClass('kieuintaycuoi' + stt))
                                    $('.kieuintaycuoi' + stt).remove();
                            }
                            _this.numberInput();
                            _this.tinhChiPhiXuatRa(selectElm);
                            _this.tinhChiPhiGiaCong(selectElm);
                            _this.tinhChiPhiIn(selectElm);
                            _this.tinhChiPhiGiayIn(selectElm);
                            _this.updateChiPhiTab(selectElm);
                        } else {
                            if (jsonData.msg !== '')
                                krajeeDialog.alert(jsonData.msg);
                            if(previous_val){
                                tbodyObj.find('select[name*="[GiayRuotKhoGiayId'+stt+']"]').val(previous_val);
                            }
                            return false;
                        }
                    }
                });
            }
        };

        this.timKhoGiayRuotTayCuoi = function (selectElm, real, stt) {
            var _this = this,
                rootFrame = _this.getRootFrame(selectElm),
                soLuong_ = 0,
                elm = $(selectElm).find('select[name*="[GiayRuotKhoGiayId' + stt + ']"]'),
                tbodyObj = $(elm).closest('tbody'),
                kieuDong = _this.getProductKieuDong(selectElm),
                soSanPham = _this.getProductAmount(selectElm),
                chuaKepNhip = _this.getChuaKepNhip(selectElm),
                SoMatInBia = parseFloat(rootFrame.find('input[name*="SoMatInBia"]').val()),
                DonGiaKem = rootFrame.find('input[name*="[DonGiaKemRuotTayCuoi' + stt + ']"]').val(),
                DonGiaIn = rootFrame.find('input[name*="[DonGiaInRuotTayCuoi' + stt + ']"]').val(),
                daiIn = parseFloat(rootFrame.find('input[name*="sp_ruot_length"]').val()),
                rongIn = parseFloat(rootFrame.find('input[name*="sp_ruot_width"]').val()),
                chatLieu = parseInt(rootFrame.find('select[name*="[GiayRuotChatLieu' + stt + ']"]').val()),
                dinhLuong = parseInt(rootFrame.find('select[name*="[GiayRuotDinhLuong' + stt + ']"]').val()),
                donGia = rootFrame.find('input[name*="[GiayRuotPrice' + stt + ']"]').val(),
                nhaCungCap = parseInt(rootFrame.find('select[name*="[GiayRuotNhaCungCap' + stt + ']"]').val()),
                soToBuHao = rootFrame.find('input[name*="[GiayRuotSoToBuHaoThem' + stt + ']"]').val(),
                soMauKem = parseFloat(rootFrame.find('input[name*="[SoMauInRuotTayCuoi' + stt + ']"]').val()),
                matIn = parseFloat(rootFrame.find('input[name*="[SoMatInRuotTayCuoi' + stt + ']"]').val()),
                NhaCungCapIn = parseFloat(rootFrame.find('select[name*="[NhaCungCapIn]"] :selected').val()),
                khoGiay = parseInt(rootFrame.find('select[name*="[TayCuoiKhoGiayId' + stt + ']"]').val()),
                cachTinh = $.trim(rootFrame.find('input[name*="calcu_type"]').val()),
                viTriNhip = parseInt(rootFrame.find('input[name*="[vt_nhip_ruot' + stt + ']"]:checked').val()),
                MauPha = parseInt(rootFrame.find('input[name*="[MauPhaRuot]"]:checked').val()),
                HeSoMauPha = parseFloat(rootFrame.find("#he_so_mau_pha").val()),
                inChungKho = parseInt(tbodyObj.find('input[name*="InCungKhoGiay' + stt + '"]:checked').val()),
                kieuInGiaTrucTiep = parseInt(rootFrame.find('input[name*="[checkbox_hasnt_formula_kieu_in]"]:checked').val()),
                giayInGiaTrucTiep = parseInt(rootFrame.find('input[name*="[checkbox_hasnt_formula_giay_in]"]:checked').val()),
                loaiSanPham = parseInt(rootFrame.find('input[name*="product_type"]').val()),
                khoMay = parseFloat(rootFrame.find('select[name*="[KhoMayInRuotTayCuoi' + stt + ']"]').val()),
                khoKemDai = parseFloat(rootFrame.find('input[name*="[DaiKemRuotTayCuoi' + stt + ']"]').val()),
                khoKemRong = parseFloat(rootFrame.find('input[name*="[RongKemRuotTayCuoi' + stt + ']"]').val()),
                count_order = rootFrame.index(),
                kieuTro = parseFloat(rootFrame.find('select[name*="[kieu_in_tro_ruotTayCuoi' + stt + ']"]').val()),
                quyCachIn = $.trim(rootFrame.find('input[name*="[QuyCachInRuotTayCuoi' + stt + ']"]').val()),
                trangDu = parseFloat(rootFrame.find('input[name*="[TrangDu' + stt + ']"]').val()),
                SapXep = parseFloat(rootFrame.find('input[name*="[SapXepGiayRuot' + stt + ']"]').val()),
                thanhPham = parseFloat(rootFrame.find('input[name*="[TayCuoiThanhPham' + stt + ']"]').val());
            if (soToBuHao && Object.prototype.toString.call(soToBuHao) === '[object String]')
                soToBuHao = soToBuHao.replace(/,/g, '');
            soToBuHao = parseFloat(soToBuHao);

            if (soSanPham === 0 || soSanPham === undefined)
                return;

            if (!rootFrame.find('input[name*="[TayCuoiThanhPham' + stt + ']"]').hasClass('userChanged')) {
                thanhPham = 0;
            }

            if (rootFrame.find('select[name*="[TayCuoiKhoGiayId' + stt + ']"]').hasClass('userChanged') && parseFloat(rootFrame.find('select[name*="[GiayRuotKhoGiayId' + stt + ']"]').val()) > 0) {
                if (parseFloat(rootFrame.find('select[name*="[TayCuoiKhoGiayId' + stt + ']"]').val()) !== parseFloat(rootFrame.find('select[name*="[GiayRuotKhoGiayId' + stt + ']"]').val())) {
                    tbodyObj.find('input[name*="InCungKhoGiay"]').prop('checked', false);
                    inChungKho = 0;
                } else {
                    tbodyObj.find('input[name*="InCungKhoGiay"]').prop('checked', true);
                    inChungKho = 1;
                }
            }

            if (isNaN(soMauKem))
                soMauKem = 1;

            if (!rootFrame.find('input[name*="[GiayRuotSoToBuHaoThem' + stt + ']"]').hasClass('userChanged')) {
                soToBuHao = -1;
            }

            if (inChungKho === 1) {
                khoGiay = parseInt(rootFrame.find('select[name*="[GiayRuotKhoGiayId' + stt + ']"]').val());
                khoMay = parseInt(rootFrame.find('select[name*="[KhoMayInRuot' + stt + ']"]').val());
            } else if (!rootFrame.find('select[name*="[TayCuoiKhoGiayId' + stt + ']"]').hasClass('userChanged') && soToBuHao === 0) {
                khoGiay = 0;
            }

            if (stt !== '') {
                soLuong_ = parseFloat($("#orderinfo-" + count_order + "-ext_inner_page_amount_" + (stt - 2)).val());
                MauPha = parseFloat(rootFrame.find('input[id$="orderinfo-' + count_order + '_ext_mau_pha_ruot_' + (stt - 2) + '"]:checked').val());
            } else {
                soLuong_ = parseFloat(rootFrame.find('input[name*="[inner_page_amount]"]').val());
                MauPha = parseInt(rootFrame.find('input[name*="[MauPhaRuot]"]:checked').val());
            }
            if (isNaN(soLuong_) || soLuong_ <= 0 || isNaN(daiIn) || daiIn <= 0 || isNaN(rongIn) || rongIn <= 0 || isNaN(nhaCungCap) || nhaCungCap <= 0 || isNaN(chatLieu) || chatLieu <= 0 || isNaN(dinhLuong) || dinhLuong <= 0)
                return;
            if (!rootFrame.find('select[name*="[kieu_in_tro_ruotTayCuoi' + stt + ']"]').hasClass('userChanged')) {
                kieuTro = '';
            }
            if (real === 1 || real === 3) {
                thanhPham = 0;
                soToBuHao = -1;
            }

            if (giayInGiaTrucTiep === 1) {
                donGia = 0;
            }
            if ((SapXep === 0 && real === 2) || real === 1 || real === 3 || real === 0 || SapXep === 0) {
                $.ajaxQueue({
                    url: link_getProductSize,
                    dataType: "json",
                    type: "GET",
                    data: {
                        'dai': daiIn,
                        'rong': rongIn,
                        'chuaKepNhip': chuaKepNhip,
                        'chatLieu': chatLieu,
                        'dinhLuong': dinhLuong,
                        'nhaCungCap': nhaCungCap,
                        'soLuong': soLuong_,
                        'donGia': donGia,
                        'type': 'gr',
                        'soToBuHao': soToBuHao,
                        'soMauKem': soMauKem,
                        'matIn': matIn,
                        'nhaCungCapIn': NhaCungCapIn,
                        'real': real,
                        'khoGiay': khoGiay,
                        'kieuDong': kieuDong,
                        'soSanPham': soSanPham,
                        'cachTinh': cachTinh,
                        'inChungKho': inChungKho,
                        'viTriNhip': viTriNhip,
                        'mauPha': MauPha,
                        'heSoMauPha': HeSoMauPha,
                        'khoMay': khoMay,
                        'loaiSanPham': loaiSanPham,
                        'DonGiaKem': DonGiaKem,
                        'DonGiaIn': DonGiaIn,
                        'khoKemDai': khoKemDai,
                        'khoKemRong': khoKemRong,
                        'tayCuoi': 1,
                        'trangDu': trangDu,
                        'khoGiayTayCuoi': khoGiay,
                        'kieuInGiaTrucTiep': kieuInGiaTrucTiep,
                        'thanhPham': thanhPham,
                        'banTinh': 'thuongmai',
                        'quyCachIn':quyCachIn,
                        'kieuTro' : kieuTro

                    }, success: function (jsonData) {

                        if (jsonData.success === true) {
                            // if ($('.thanh_pham_ruot').hasClass('userChanged') && real === 1) {
                            //     $('.thanh_pham_ruot').removeClass('userChanged');
                            // }
                            var khomayin = rootFrame.find('select[name*="[KhoMayInRuotTayCuoi' + stt + ']"]').val();

                            if ((khomayin === '' || khomayin === 0) && jsonData.khoMay !== '' && jsonData.khoMay !== undefined)
                                rootFrame.find('select[name*="[KhoMayInRuotTayCuoi' + stt + ']"]').val(jsonData.khoMay.kg_id).trigger('change', false);
                            else if (khoKemDai < jsonData.khoMay.kem_dai || khoKemRong < jsonData.khoMay.kem_rong) {
                                rootFrame.find('select[name*="[KhoMayInRuotTayCuoi' + stt + ']"]').val(jsonData.khoMay.kg_id).trigger('change', false);
                            }

                            tbodyObj.find('input[name*="[GiayRuotTayCuoiLength' + stt + ']"][type="hidden"]').val(jsonData.info.length);
                            tbodyObj.find('input[name*="[GiayRuotTayCuoiWidth' + stt + ']"][type="hidden"]').val(jsonData.info.width);
                            tbodyObj.find('input[name*="[TayCuoiSoTo' + stt + ']"][type="hidden"]').val(jsonData.soTo);
                            tbodyObj.find('input[name*="[TongChiPhiInTayCuoi' + stt + ']"][type=hidden]').val(jsonData.chiPhiIn);
                            tbodyObj.find('input[name*="[TongChiPhiGiayInTayCuoi' + stt + ']"][type=hidden]').val(jsonData.chiPhiGiayIn);
                            rootFrame.find('input[name*="[ChiPhiInRuotTayCuoi' + stt + ']"][type=hidden]').val(jsonData.chiPhiIn);

                            if(kieuTro === '') {
                                rootFrame.find('select[name*="[kieu_in_tro_ruotTayCuoi' + stt + ']"]').val(jsonData.kieu_in_tro);
                                rootFrame.find('input[name*="[SoMauInRuotTayCuoi' + stt + ']"]').val(jsonData.soMauKem);
                                rootFrame.find('select[name*="[QuyCachInRuotTayCuoi' + stt + ']"]').css('outline', 'none').attr('title', '');
                            }

                            if (giayInGiaTrucTiep !== 1) {

                                tbodyObj.find('select[name*="[TayCuoiKhoGiayId' + stt + ']"]').val(jsonData.info.content_id);
                                tbodyObj.find('input[name*="[TayCuoiThanhPham' + stt + ']"]').val(jsonData.thanhPham);
                                tbodyObj.find('input[name*="[TayCuoiSoTo' + stt + ']"][type="text"]').val(jsonData.soTo).formatCurrency();
                                tbodyObj.find('input[name*="[GiayRuotSoToBuHaoThem' + stt + ']"]').val(jsonData.toBuHao).formatCurrency();
                                if (jsonData.info.content_id === Number(rootFrame.find('select[name*="[GiayRuotKhoGiayId' + stt + ']"]').val())) {
                                    tbodyObj.find('input[name*="[InCungKhoGiay' + stt + ']"]').prop('checked', true);
                                } else{
                                    tbodyObj.find('input[name*="[InCungKhoGiay' + stt + ']"]').prop('checked', false);
                                }
                            }

                            _this.numberInput();
                            _this.tinhChiPhiXuatRa(selectElm);
                            _this.tinhChiPhiGiaCong(selectElm);
                            _this.tinhChiPhiIn(selectElm);
                            _this.tinhChiPhiGiayIn(selectElm);
                            _this.updateChiPhiTab(selectElm);

                        } else {
                            krajeeDialog.alert('Vui lòng kiểm tra lại khổ giấy in hoặc khổ máy in bên tab Kiểu in cho phù hợp');
                            if(previous_val){
                                rootFrame.find('select[name*="[TayCuoiKhoGiayId'+stt+']"]').val(previous_val);
                            }
                        }
                    }
                });
            }
        };

        this.getProductSize = function (selectElm, productJson) {
            var _this = this,
                dai = _this.getProductLength(selectElm), //chieu dai san pham
                daiIn = 0, //chieu dai san pham tren ban tin
                rong = _this.getProductWidth(selectElm), //chieu rong san pham
                rongIn = 0, //chieu rong san pham tren ban in
                thick = _this.getProductThick(selectElm),
                biaThem = 0,
                rongX2 = _this.isProductTwiceWidth(productJson), //nhan 2 chieu rong san pham
                hasInnerPage = _this.hasInnerPage(selectElm), //neu san pham co trang ruot
                kieuDong = _this.getProductKieuDong(selectElm), //dong gay hoac dong giua
                caoIn = 0, //mep gap san pham
                rootFrame = _this.getRootFrame(selectElm),
                sizeShow = $('.sizeShow', rootFrame).hide(),
                nape = parseFloat($('input[name*="nape"]', rootFrame).val()),
                biaGhepRuot = parseInt(rootFrame.find('input[name*="[bia_ghep_ruot]"]:checked').val()),
                heSoGay = parseFloat(rootFrame.find('input[name*="[he_so_gay]"]').val()), tongSoTrangRuot = 0;
            if (isNaN(dai)) {
                krajeeDialog.alert('Chiều dài không hợp lệ.');
                return false;
            } else if (isNaN(rong)) {
                krajeeDialog.alert('Chiều rộng không hợp lệ.');
                return false;
            }

            if (hasInnerPage && (isNaN(nape) || parseInt(nape) === 0) && !rootFrame.find('input[name*="nape"]').hasClass('userChanged')) {
                var soTrangRuot1 = parseInt(rootFrame.find('input[name*="[inner_page_amount]"]').val());
                if (!isNaN(soTrangRuot1))
                    tongSoTrangRuot += soTrangRuot1;
                rootFrame.find('input[name*="[ext_inner_page_amount]"]').each(function () {
                    if (!isNaN($(this).val()))
                        tongSoTrangRuot += parseInt($(this).val());
                });
                nape = eval(this.formula.TinhGay);
                nape = parseFloat(nape).toFixed(2);
                if (!isNaN(nape))
                    rootFrame.find('input[name*="nape"]').val(nape);
            }
            rootFrame.find('input[name*="sp_bia_length"]').val(dai);
            rootFrame.find('input[name*="sp_bia_width"]').val(rong);
            rootFrame.find('input[name*="sp_ruot_length"]').val(0);
            rootFrame.find('input[name*="sp_ruot_width"]').val(0);
            sizeShow.empty();
            if (thick > 0) {
                daiIn = dai;
                rongIn = rong;
                caoIn = thick;
                sizeShow.show().html('<strong style="color:#c00">Dài: ' + daiIn + 'cm - Rộng: ' + rongIn + 'cm - Cao: ' + thick + 'cm</strong>');
                rootFrame.find('input[name*="sp_bia_length"]').val(daiIn);
                rootFrame.find('input[name*="sp_bia_width"]').val(rongIn);
                rootFrame.find('input[name*="sp_bia_height"]').val(caoIn);
            } else {
                rongIn = rong;
                if (rongX2) {
                    rongIn = rong * 2;
                    if (biaGhepRuot !== 1 && hasInnerPage && !isNaN(nape))
                        biaThem = parseFloat(nape);
                    if (biaGhepRuot === 1 && kieuDong === 2)
                        sizeShow.show().html('<strong style="color:#c00">Giấy bìa: (Dài: ' + dai + 'cm - Rộng : ' + (rongIn / 2 + biaThem) + 'cm)</strong>');
                    else
                        sizeShow.show().html('<strong style="color:#c00">Giấy bìa: (Dài: ' + dai + 'cm - Rộng x2: ' + (rongIn + biaThem) + 'cm)</strong>');
                    rootFrame.find('input[name*="sp_bia_length"]').val(dai);
                    rootFrame.find('input[name*="sp_bia_width"]').val(biaGhepRuot === 1 && kieuDong === 2 ? rongIn / 2 : (biaGhepRuot === 1 ? rongIn : rongIn + biaThem));
                    if (hasInnerPage) {
                        if (kieuDong === 1 || kieuDong === undefined) {
                            sizeShow.show().append(', <strong style="color:#c00">Giấy ruột: (Dài: ' + dai + 'cm - Rộng x2: ' + rongIn + 'cm)</strong>');
                            rootFrame.find('input[name*="sp_ruot_length"]').val(dai);
                            rootFrame.find('input[name*="sp_ruot_width"]').val(rongIn);
                        } else {
                            sizeShow.show().append(', <strong style="color:#c00">Giấy ruột: (Dài: ' + dai + 'cm - Rộng: ' + eval('rongIn/2') + 'cm)</strong>');
                            rootFrame.find('input[name*="sp_ruot_length"]').val(dai);
                            rootFrame.find('input[name*="sp_ruot_width"]').val(rongIn / 2);
                        }
                    }
                } else {
                    if (hasInnerPage) {
                        if (kieuDong === 1) {
                            sizeShow.show().append(', <strong style="color:#c00">Giấy ruột: (Dài: ' + dai + 'cm - Rộng: ' + rong + 'cm)</strong>');
                            $('input[name*="sp_ruot_length"]', rootFrame).val(dai);
                            $('input[name*="sp_ruot_width"]', rootFrame).val(rong);
                        } else {
                            sizeShow.show().append(', <strong style="color:#c00">Giấy ruột: (Dài: ' + dai + 'cm - Rộng: ' + eval('rong') + 'cm)</strong>');
                            $('input[name*="sp_ruot_length"]', rootFrame).val(dai);
                            $('input[name*="sp_ruot_width"]', rootFrame).val(rong);
                        }
                    } else {
                        var san_pham = $('select[name*="product_id"] option:selected', rootFrame).text();
                        sizeShow.show().append('<strong style="color:#c00">' + san_pham + ' - (Dài: ' + dai + 'cm - Rộng: ' + rong + 'cm)</strong>');
                    }
                }
            }


            return this;
        };

        this.updateChiPhiTab = function (selectElm) {

            var rootFrame = this.getRootFrame(selectElm),
                chiPhiDonHang = 0, order = this;
            rootFrame.find('.panel-item').each(function () {
                var _this = $(this), chiPhiTab = 0;
                _this.find('.price_elm').each(function () {
                    var val = $(this).val();
                    val = order.removeFormat(val);
                    chiPhiTab += val;
                });
                console.log(chiPhiTab);
                if (!isNaN(chiPhiTab) && chiPhiTab > 0){
                    _this.find('.chiPhiText strong').html(chiPhiTab).formatCurrency();
                    chiPhiDonHang += chiPhiTab;
                }else{
                  _this.find('.chiPhiText strong').html(0);
                }

            });
            chiPhiDonHang = Math.round(chiPhiDonHang);
            if (!isNaN(chiPhiDonHang) && chiPhiDonHang > 0) {
                $('.tong_gia_tri_don_hang strong').html(chiPhiDonHang).formatCurrency();
                $('input[name="CostTotal[0]"]').val(chiPhiDonHang);
            }
        };

      this.tinhChiPhiXuatRa = function (selectElm) {
        var rootFrame = this.getRootFrame(selectElm), order = this, TongChiPhiXuatRa = 0;
        rootFrame.find('.output-item').each(function () {
            var $this = $(this),
              bia_ruot = $this.find('select[name*="ExportBiaRuot"]').val(),
              xuat_ra = Number($this.find('select[name*="ExportType"]').val()),
              dai = $this.find('input[name*="ExportLength"]').val(),
              rong = $this.find('input[name*="ExportWidth"]').val(), soMau,
              tayChan = $this.find('input[name*="ExportSoLuong"]').val(),
              tayLe = $this.find('input[name*="ExportSoLuongTayCuoi"]').val(),
              soluong = $this.find('input[name*="ExportSoLuong"]').val(),
              dai_tay_cuoi = $this.find('input[name*="ExportLengthTayCuoi"]').val(),
              rong_tay_cuoi = $this.find('input[name*="ExportWidthTayCuoi"]').val(),
              donGia = $this.find('input[name*="ExportDonGia"]').val(),
              soLuongTayCuoi = $this.find('input[name*="ExportSoLuongTayCuoi"]').val(),
              chiPhiXuatTab = 0, duTay = 0;
            dai = Number(order.removeFormat(dai));
            rong = Number(order.removeFormat(rong));
            dai_tay_cuoi = Number(order.removeFormat(dai_tay_cuoi));
            rong_tay_cuoi = Number(order.removeFormat(rong_tay_cuoi));
            soluong = Number(order.removeFormat(soluong));
            soLuongTayCuoi = Number(order.removeFormat(soLuongTayCuoi));
            tayChan = Number(order.removeFormat(tayChan));
            tayLe = Number(order.removeFormat(tayLe));
            donGia = order.removeFormat(donGia);
            if (bia_ruot === 'bia') {
              if (rootFrame.find('input[name*="[bia_ghep_ruot]"]').is(":checked")) {
                chiPhiXuatTab = 0;
              }else {
                soMau = parseFloat(rootFrame.find('input[name*="SoMauInBia"]').val());
                if ($this.find('input[type="checkbox"][name*="ExportAddColor"]').is(':checked'))
                  soMau += 1;
                if (xuat_ra === kieuRaPhim) {
                  if (isNaN(dai) || !$this.find('input[name*="ExportLength"]').hasClass('userChanged')) {
                    dai = rootFrame.find('input[name*="[GiayBiaLength]"]').val();
                    dai = order.removeFormat(dai);
                    if(isNaN(dai))
                      dai = 0;
                    $this.find('input[name*="ExportLength"]').val(dai);
                  }
                  if (isNaN(rong) || !$this.find('input[name*="ExportWidth"]').hasClass('userChanged')) {
                    rong = rootFrame.find('input[name*="[GiayBiaWidth]"]').val();
                    rong = order.removeFormat(rong);
                    if(isNaN(rong))
                      rong = 0;
                    $this.find('input[name*="ExportWidth"]').val(rong);
                  }
                  soluong = 1;
                  chiPhiXuatTab = Math.round(eval(order.formula.raPhim));
                } else {
                  var kho_may_in_id = rootFrame.find('select[name*="KhoMayInBia"]').val();
                  if (giakhomayin_json[kho_may_in_id]) {
                    dai = giakhomayin_json[kho_may_in_id].kem_dai;
                    rong = giakhomayin_json[kho_may_in_id].kem_rong;
                    if (isNaN(dai) || !$this.find('input[name*="ExportLength"]').hasClass('userChanged'))
                      $this.find('input[name*="ExportLength"]').val(dai);
                    if (isNaN(rong) || !$this.find('input[name*="ExportWidth"]').hasClass('userChanged'))
                      $this.find('input[name*="ExportWidth"]').val(rong);
                  }
                  chiPhiXuatTab = Math.round(eval(order.formula.raKemBia));
                }
              }
            } else {
              var giayruot_number = bia_ruot.replace(/ruot/g, ''),
                soTay = parseFloat(rootFrame.find('input[name*="[SoTay' + giayruot_number + ']"]').val());
              duTay = parseFloat(soTay - Math.floor(soTay));
              if (isNaN(soluong) || !$this.find('input[name*="ExportSoLuong"]').hasClass('userChanged'))
                tayChan = Math.floor(soTay);

              if (isNaN(tayLe) || !$this.find('input[name*="ExportSoLuongTayCuoi"]').hasClass('userChanged')) {
                if (duTay === 0.25 || duTay === 0.5)
                  tayLe = 1;
                else if (duTay === 0.75)
                  tayLe = 2;
              }

              soMau = parseFloat(rootFrame.find('input[name*="SoMauInRuot' + giayruot_number + '"]').val());
              if ($this.find('input[type="checkbox"][name*="ExportAddColor"]').is(':checked'))
                soMau += 1;

              if (isNaN(soluong) || !$this.find('input[name*="ExportSoLuong"]').hasClass('userChanged')) {
                $this.find('input[name*="ExportSoLuong"]').val(soluong);
                soluong = Math.ceil(soTay) - 1;
              }

              if (xuat_ra === kieuRaPhim) {
                if (isNaN(dai) || !$this.find('input[name*="ExportLength"]').hasClass('userChanged')) {
                  dai = rootFrame.find('input[name*="[GiayRuotLength' + giayruot_number + ']"]').val();
                  dai = order.removeFormat(dai);
                  if(isNaN(dai))
                    dai = 0;
                  $this.find('input[name*="ExportLength"]').val(dai);
                }

                if (isNaN(rong) || !$this.find('input[name*="ExportWidth"]').hasClass('userChanged')) {
                  rong = rootFrame.find('input[name*="[GiayRuotWidth' + giayruot_number + ']"]').val();
                  rong = order.removeFormat(rong);
                  if(isNaN(rong))
                    rong = 0;
                  $this.find('input[name*="ExportWidth"]').val(rong);
                }

                if (isNaN(dai_tay_cuoi) || !$this.find('input[name*="ExportLengthTayCuoi"]').hasClass('userChanged')) {
                  dai_tay_cuoi = rootFrame.find('input[name*="[GiayRuotLength' + giayruot_number + ']"]').val();
                  dai_tay_cuoi = order.removeFormat(dai_tay_cuoi);
                  if(isNaN(dai_tay_cuoi))
                    dai_tay_cuoi = 0;
                  $this.find('input[name*="ExportLengthTayCuoi"]').val(dai_tay_cuoi);
                }

                if (isNaN(rong_tay_cuoi) || !$this.find('input[name*="ExportWidthTayCuoi"]').hasClass('userChanged')) {
                  rong_tay_cuoi = rootFrame.find('input[name*="[GiayRuotWidth' + giayruot_number + ']"]').val();
                  rong_tay_cuoi = order.removeFormat(rong_tay_cuoi);
                  if(isNaN(rong_tay_cuoi))
                    rong_tay_cuoi = 0;
                  $this.find('input[name*="ExportWidthTayCuoi"]').val(rong_tay_cuoi);
                }

                if (!isNaN(soLuongTayCuoi) && !$this.find('input[name*="ExportSoLuongTayCuoi"]').hasClass('userChanged')) {
                  $this.find('input[name*="ExportSoLuongTayCuoi"]').val(1);
                }

                chiPhiXuatTab = Math.round(eval(order.formula.raPhim));
                chiPhiXuatTab += Math.round(eval(order.formula.raPhimTayCuoi));

              } else {
                var kho_may_in_id = rootFrame.find('select[name*="KhoMayInRuot' + giayruot_number + '"]').val();
                if (giakhomayin_json[kho_may_in_id]) {
                  dai = giakhomayin_json[kho_may_in_id].kem_dai;
                  if(isNaN(dai))
                    dai = 0;
                  rong = giakhomayin_json[kho_may_in_id].kem_rong;
                  if(isNaN(rong))
                    rong = 0;

                  if (isNaN(dai) || !$this.find('input[name*="ExportLength"]').hasClass('userChanged'))
                    $this.find('input[name*="ExportLength"]').val(dai);

                  if (isNaN(rong) || !$this.find('input[name*="ExportWidth"]').hasClass('userChanged'))
                    $this.find('input[name*="ExportWidth"]').val(rong);
                }

                if (isNaN(soluong) || !$this.find('input[name*="ExportSoLuong"]').hasClass('userChanged'))
                  $this.find('input[name*="ExportSoLuong"]').val(tayChan);
                var soMauTayCuoi = parseFloat(rootFrame.find('input[name*="SoMauInRuotTayCuoi' + giayruot_number + '"]').val());
                if ($this.find('input[type="checkbox"][name*="ExportAddColor"]').is(':checked'))
                  soMauTayCuoi += 1;
                if(isNaN(soMauTayCuoi))
                  soMauTayCuoi = 0;
                chiPhiXuatTab = Math.round(eval(order.formula.raKemRuot));
              }
              if (isNaN(tayLe) && !$this.find('input[name*="ExportSoLuongTayCuoi"]').hasClass('userChanged'))
                $this.find('input[name*="ExportSoLuongTayCuoi"]').val(0);

              if (duTay > 0 && xuat_ra === kieuRaPhim) {
                $this.find('.output_taycuoi').show();
              } else {
                $this.find('.output_taycuoi').hide();
              }

            }
            if (!isNaN(chiPhiXuatTab)) {
              $this.find('input[name*="ExportPrice"]').val(chiPhiXuatTab);
              $this.find('.ExportPriceTxt').html(chiPhiXuatTab).formatCurrency();
              TongChiPhiXuatRa += chiPhiXuatTab;
            }
          }
        );
        if (!isNaN(TongChiPhiXuatRa))
          rootFrame.find('input[name*="TongChiPhiXuatRa"][type="hidden"]').val(TongChiPhiXuatRa);
      };

        this.removeFormat = function (string) {
            if (string && Object.prototype.toString.call(string) === '[object String]')
                string = string.replace(/,/g, '');
            string = parseFloat(string);
            return string;
        };

        this.updatePaper = function (elm) {
            var tbodyObj = $(elm).closest('tbody'),
                rootFrame = this.getRootFrame(elm),
                name = elm.name,
                val = elm.value,
                GiayBiaNhaCungCap = tbodyObj.find('select[name*="GiayBiaNhaCungCap"]'),
                GiayRuotNhaCungCap = tbodyObj.find('select[name*="GiayRuotNhaCungCap"]'),
                GiayBiaDinhLuong = tbodyObj.find('select[name*="GiayBiaDinhLuong"]'),
                GiayBiaChatLieu = tbodyObj.find('select[name*="GiayBiaChatLieu"]'),
                GiayRuotChatLieu = tbodyObj.find('select[name*="GiayRuotChatLieu"]'),
                GiayRuotPrice = tbodyObj.find('input[name*="GiayRuotPrice"]'),
                GiayRuotKhoGiayId = tbodyObj.find('select[name*="GiayRuotKhoGiayId"]'),
                GiayRuotPriceRam = tbodyObj.find('input[name*="GiayRuotPriceRam"]'),
                GiayRuotPriceSheet = tbodyObj.find('input[name*="GiayRuotPriceSheet"]'),
                GiayRuotDinhLuong = tbodyObj.find('select[name *= "GiayRuotDinhLuong"]'),
                ToGacPrice = tbodyObj.find('input[name*="ToGacPrice"]'),
                ToGacChatLieu = tbodyObj.find('select[name*="ToGacChatLieu"]'),
                ToGacNhaCungCap = tbodyObj.find('select[name*="ToGacNhaCungCap"]'),
                ToGacDinhLuong = tbodyObj.find('select[name*="ToGacDinhLuong"]'),
                ToGacKhoGiayId = tbodyObj.find('select[name*="ToGacKhoGiayId"]');
            if (name === undefined)
                name = elm.attr('name');
            if (name.indexOf('GiayBiaChatLieu') !== -1) {
                val = Number($(':selected', elm).val());
                GiayBiaNhaCungCap.val(-1).prop('disabled', true);
                GiayBiaChatLieu.not(elm).val(val);
                if (rootFrame.find('input[name*="[bia_ghep_ruot]"]').is(":checked")) {
                    rootFrame.find('select[name*="[GiayRuotChatLieu]"]').val(val);
                }
                if (val > 0) {
                    $.ajax({
                        'url': link_getNccGiay,
                        'type': 'get',
                        'data': {'chatLieuId': val},
                        'success': function (data) {
                            GiayBiaNhaCungCap.html(data);
                            rootFrame.find(GiayBiaNhaCungCap.selector).prop('disabled', false);
                            if (rootFrame.find('input[name*="[bia_ghep_ruot]"]').is(":checked")) {
                                rootFrame.find('select[name*="[GiayRuotNhaCungCap]"]').html(data);
                            }
                        }
                    });
                } else {
                    rootFrame.find('input[name*="GiayBiaPrice"]').val(0);
                }
                return false;
            } else if (name.indexOf('GiayRuotChatLieu') !== -1) {
                val = Number($(':selected', elm).val());
                GiayRuotNhaCungCap.val(-1).prop('disabled', true);
                GiayRuotChatLieu.not(elm).val(val);
                if (val > 0) {
                    $.ajax({
                        'url': link_getNccGiay,
                        'type': 'get',
                        'data': {'chatLieuId': val},
                        'success': function (data) {
                            GiayRuotNhaCungCap.html(data);
                            tbodyObj.find(GiayRuotNhaCungCap.selector).prop('disabled', false); //Hai -> tbodyObj
                        }
                    });
                } else {
                    rootFrame.find('input[name*="GiayRuotPrice"]').val(0);
                }
                return false;
            } else if (name.indexOf('ToGacChatLieu') !== -1) {
                val = Number($(':selected', elm).val());
                ToGacNhaCungCap.val(-1).prop('disabled', true);
                ToGacChatLieu.not(elm).val(val);
                if (val > 0) {
                    $.ajax({
                        'url': link_getNccGiay,
                        'type': 'get',
                        'data': {'chatLieuId': val},
                        'success': function (data) {
                            ToGacNhaCungCap.html(data);
                            tbodyObj.find(ToGacNhaCungCap.selector).prop('disabled', false);
                        }
                    });
                } else {
                    rootFrame.find('input[name*="ToGacPrice"]').val(0);
                }
                return false;
            } else if (name.indexOf('GiayBiaNhaCungCap', rootFrame) !== -1) {
                val = $(':selected', elm).val();
                rootFrame.find('select[name*="GiayBiaNhaCungCap"]').val(val);
                if (rootFrame.find('input[name*="[bia_ghep_ruot]"]').is(":checked")) {
                    rootFrame.find('select[name*="[GiayRuotNhaCungCap]"]').val(val);
                }
                if (val > 0) {
                    if (supplier_json) {
                        var don_gia_dinh_luong, don_gia, chatLieuId = Number(GiayBiaChatLieu.val()),
                            dinhluong = Number(GiayBiaDinhLuong.val());
                        don_gia_dinh_luong = jQuery.parseJSON(supplier_json[val].don_gia_dinh_luong);
                        don_gia = don_gia_dinh_luong[chatLieuId][dinhluong];
                        rootFrame.find('input[name*="[GiayBiaPrice]"]').val(don_gia);
                        if (rootFrame.find('input[name*="[bia_ghep_ruot]"]').is(":checked")) {
                            rootFrame.find('input[name*="[GiayRuotPrice]"]').val(don_gia);
                            rootFrame.find('input[name*="[GiayRuotPrice]"]').trigger('change');
                        }
                    }
                } else {
                    rootFrame.find('input[name*="GiayBiaPrice"]').val(0);
                    if (rootFrame.find('input[name*="[bia_ghep_ruot]"]').is(":checked")) {
                        rootFrame.find('input[name*="[GiayRuotPrice]"]').val(0);
                        rootFrame.find('input[name*="[GiayRuotPrice]"]').trigger('change');
                    }
                }
            } else if (name.indexOf('GiayRuotNhaCungCap') !== -1) {
                val = $(':selected', elm).val();
                GiayRuotNhaCungCap.not(elm).val(val);
                if (val > 0 && supplier_json) {
                    var don_gia_dinh_luong, don_gia,
                        chatLieuId = Number(GiayRuotChatLieu.val()),
                        dinhluong = Number(GiayRuotDinhLuong.val());
                    don_gia_dinh_luong = jQuery.parseJSON(supplier_json[val].don_gia_dinh_luong);
                    don_gia = don_gia_dinh_luong[chatLieuId][dinhluong];
                    $(GiayRuotPrice).val(don_gia);
                    $(GiayRuotPriceRam).val('');
                    $(GiayRuotPriceSheet).val('');
                }
            } else if (name.indexOf('ToGacNhaCungCap') !== -1) {
                val = $(':selected', elm).val();
                ToGacNhaCungCap.not(elm).val(val);
                if (val > 0 && supplier_json) {
                    var don_gia_dinh_luong, don_gia,
                        chatLieuId = Number(ToGacChatLieu.val()),
                        dinhluong = Number(ToGacDinhLuong.val());

                    don_gia_dinh_luong = jQuery.parseJSON(supplier_json[val].don_gia_dinh_luong);
                    don_gia = don_gia_dinh_luong[chatLieuId][dinhluong];
                    $(ToGacPrice).val(don_gia);
                }
            } else if (name.indexOf('GiayBiaDinhLuong') !== -1) {
                val = $(':selected', elm).val();
                tbodyObj.find('select[name*="GiayBiaDinhLuong"]').val(val);
                if (rootFrame.find('input[name*="[bia_ghep_ruot]"]').is(":checked")) {
                    rootFrame.find('select[name*="[GiayRuotDinhLuong]"]').val(val);
                }
                if (val > 0 && supplier_json) {
                    var don_gia_dinh_luong, don_gia = 0, chatLieuId = Number($(':selected', GiayBiaChatLieu).val()),
                        dinhluong = val;
                    don_gia_dinh_luong = jQuery.parseJSON(supplier_json[rootFrame.find('select[name*="GiayBiaNhaCungCap"]').val()].don_gia_dinh_luong);
                    if (don_gia_dinh_luong) {
                        don_gia = don_gia_dinh_luong[chatLieuId][dinhluong];
                        rootFrame.find('input[name*="[GiayBiaPrice]"]').val(don_gia);
                        if (rootFrame.find('input[name*="[bia_ghep_ruot]"]').is(":checked")) {
                            rootFrame.find('input[name*="[GiayRuotPrice]"]').val(don_gia);
                            rootFrame.find('input[name*="[GiayRuotPrice]"]').trigger('change');
                        }
                    }
                } else {
                    rootFrame.find('input[name*="[GiayBiaPrice]"]').val(0);
                    if (rootFrame.find('input[name*="[bia_ghep_ruot]"]').is(":checked")) {
                        rootFrame.find('input[name*="[GiayRuotPrice]"]').val(0);
                        rootFrame.find('input[name*="[GiayRuotPrice]"]').trigger('change');
                    }
                }

            } else if (name.indexOf('GiayRuotDinhLuong') !== -1) {
                val = $(':selected', elm).val();
                tbodyObj.find('select[name*="GiayRuotDinhLuong"]').val(val);
                if (val > 0 && supplier_json) {
                    var don_gia_dinh_luong, don_gia = 0,
                        chatLieuId = Number($(':selected', GiayRuotChatLieu).val()), dinhluong = val;

                    don_gia_dinh_luong = jQuery.parseJSON(supplier_json[rootFrame.find('select[name*="GiayRuotNhaCungCap"]').val()].don_gia_dinh_luong);
                    if (don_gia_dinh_luong) {
                        don_gia = don_gia_dinh_luong[chatLieuId][dinhluong];

                        $(GiayRuotPrice).val(don_gia);
                        $(GiayRuotPriceRam).val('');
                        $(GiayRuotPriceSheet).val('');
                    }
                } else {
                    $(GiayRuotPrice).val(0);
                }

            } else if (name.indexOf('ToGacDinhLuong') !== -1) {
                val = $(':selected', elm).val();
                tbodyObj.find('select[name*="ToGacDinhLuong"]').val(val);
                if (val > 0 && supplier_json) {
                    var don_gia_dinh_luong, don_gia = 0,
                        chatLieuId = Number($(':selected', ToGacChatLieu).val()), dinhluong = val;

                    don_gia_dinh_luong = jQuery.parseJSON(supplier_json[rootFrame.find('select[name*="GiayRuotNhaCungCap"]').val()].don_gia_dinh_luong);
                    if (don_gia_dinh_luong) {
                        don_gia = don_gia_dinh_luong[chatLieuId][dinhluong];
                        $(ToGacPrice).val(don_gia);
                    }
                } else {
                    $(ToGacPrice).val(0);
                }

            } else if (name.indexOf('GiayBiaPrice') !== -1) {
                val = Number($(elm).val());
                if (rootFrame.find('input[name*="[bia_ghep_ruot]"]').is(":checked")) {
                    rootFrame.find('input[name*="[GiayRuotPrice]"]').val(val);
                    rootFrame.find('input[name*="[GiayRuotPrice]"]').trigger('change');
                }

            } else if (name.indexOf('GiayBiaKhoGiayId') !== -1) {
                val = $(':selected', elm).val();
                rootFrame.find('select[name*="GiayBiaKhoGiayId"]').val(val);
                if (rootFrame.find('input[name*="[bia_ghep_ruot]"]').is(":checked")) {
                    rootFrame.find('select[name*="[GiayRuotKhoGiayId]"]').val(val);
                }
                if (contents_json) {
                    var v = contents_json[val];
                    if (v) {
                        rootFrame.find('input[name*="GiayBiaLength"]').val(v.length);
                        rootFrame.find('input[name*="GiayBiaWidth"]').val(v.width);
                        if (rootFrame.find('input[name*="[bia_ghep_ruot]"]').is(":checked")) {
                            rootFrame.find('input[name*="[GiayRuotLength]"]').val(v.length);
                            rootFrame.find('input[name*="[GiayRuotWidth]"]').val(v.width);
                            rootFrame.find('select[name*="[GiayRuotKhoGiayId]"]').trigger('change');
                        }
                    }
                }

            } else if (name.indexOf('GiayRuotKhoGiayId') !== -1) {
                val = $(':selected', elm).val();
                $(GiayRuotKhoGiayId).val(val);
                if (contents_json) {
                    var v = contents_json[val];
                    if (v) {
                        tbodyObj.find('input[name*="GiayRuotLength"]').val(v.length);
                        tbodyObj.find('input[name*="GiayRuotWidth"]').val(v.width);
                    }
                }
            } else if (name.indexOf('ToGacKhoGiayId') !== -1) {
                val = $(':selected', elm).val();
                $(ToGacKhoGiayId).val(val);
                if (contents_json) {
                    var v = contents_json[val];
                    if (v) {
                        tbodyObj.find('input[name*="ToGacLength"]').val(v.length);
                        tbodyObj.find('input[name*="ToGacWidth"]').val(v.width);
                    }
                }
            }
            // this.tinhChiPhiGiayIn(elm);
            this.updateChiPhiTab(elm);
        };

        this.tinhChiPhiGiayInHasntFormuala = function (elm) {
            this.tinhChiPhiGiayBiaHasntFormula(elm);
            if (this.hasInnerPage(elm)) {
                this.tinhChiPhiGiayRuotHasntFormula(elm);
            }
            return this;
        };

        this.tinhChiPhiGiayBiaHasntFormula = function (elm) {
            var _this = this,
                rootFrame = _this.getRootFrame(elm),
                donGia, soLuong, phanTram = _this.vatTax;

            rootFrame.find('.GiayBiaChiPhi, .GiayBiaChiPhiVat').html(0);
            rootFrame.find('input[name*="GiayBiaChiPhi"], input[name*="GiayBiaChiPhiVat"]').val(0);
            soLuong = rootFrame.find('input[name*="[GiayBiaSoLuong]"]').val();
            soLuong = _this.removeFormat(soLuong);
            donGia = rootFrame.find('input[name*="[GiayBiaPrice]"]').val();
            donGia = _this.removeFormat(donGia);
            if (isNaN(donGia) || isNaN(soLuong)) {
                console.log('isNaN line <?php echo __LINE__ ?>');
                return false;
            }

            var giaTruocThue = eval(donGia * soLuong);
            var giaSauThue = giaTruocThue + eval(donGia * soLuong * (phanTram / 100));
            var biaGhepRuot = parseInt(rootFrame.find('input[name*="[bia_ghep_ruot]"]:checked').val());
            if (biaGhepRuot === 1) {
                giaTruocThue = 0;
                giaSauThue = 0;
            }
            giaTruocThue = Math.round(giaTruocThue);
            giaSauThue = Math.round(giaSauThue);
            rootFrame.find('.chiPhiGiayInBia').html(giaTruocThue).formatCurrency();
            rootFrame.find('.chiPhiGiayInBiaVat').html(giaSauThue).formatCurrency();
            rootFrame.find('input[name*="GiayBiaChiPhi"]').val(giaTruocThue);
            rootFrame.find('input[name*="GiayBiaChiPhiVat"]').val(giaSauThue);
            var TongChiPhiGiayIn = 0;
            TongChiPhiGiayIn = giaTruocThue;
            if (TongChiPhiGiayIn > 0) {
                rootFrame.find('#giay-in .price_elm').each(function () {
                    if ($(this).val() > 0) {
                        TongChiPhiGiayIn += parseFloat($(this).val());
                    }
                });
                rootFrame.find('input[name*="[TongChiPhiGiayIn]"]').val(TongChiPhiGiayIn);
            }
            return this;
        };

        this.tinhChiPhiGiayRuotHasntFormula = function (elm) {

            var _this = this, rootFrame = _this.getRootFrame(elm),
                donGia, soLuong, phanTram = _this.vatTax, giaTruocThue, giaSauThue;

            rootFrame.find('tbody.tbody_giay_ruot_order').each(function () {
                var tbody = $(this);
                tbody.find('.GiayRuotChiPhi, .GiayRuotChiPhiVat').html(0);
                tbody.find('input[name*="GiayRuotChiPhi"], input[name*="GiayRuotChiPhiVat"]').val(0);
                soLuong = tbody.find('input[name*="GiayRuotSoLuong"]').val();
                soLuong = _this.removeFormat(soLuong);
                donGia = tbody.find('input[name*="GiayRuotPrice"]').val();
                donGia = _this.removeFormat(donGia);
                giaTruocThue = eval(donGia * soLuong);
                giaSauThue = giaTruocThue + eval(donGia * soLuong * (phanTram / 100));
                giaTruocThue = Math.round(giaTruocThue);
                if (!$.isNumeric(giaTruocThue))
                    giaTruocThue = 0;
                giaSauThue = Math.round(giaSauThue);
                if (!$.isNumeric(giaSauThue))
                    giaSauThue = 0;
                tbody.find('.chiPhiGiayInRuot').html(giaTruocThue).formatCurrency();
                tbody.find('.chiPhiGiayInRuotVat').html(giaSauThue).formatCurrency();
                tbody.find('input[name*="GiayRuotChiPhi"]').val(giaTruocThue);
                tbody.find('input[name*="GiayRuotChiPhiVat"]').val(giaSauThue);
                var TongChiPhiGiayIn = 0;
                TongChiPhiGiayIn = giaTruocThue;
                if (TongChiPhiGiayIn > 0) {
                    rootFrame.find('#giay-in .price_elm').each(function () {
                        if ($(this).val() > 0) {
                            TongChiPhiGiayIn += parseFloat($(this).val());
                        }
                    });
                    rootFrame.find('input[name*="[TongChiPhiGiayIn]"]').val(TongChiPhiGiayIn);
                }
            });

            return this;
        };

      this.tinhChiPhiGiaCong = function (selectElm) {
        var rootFrame = this.getRootFrame(selectElm), bia_ghep_ruot = 0,
          dai, rong, soLuong, soLuongSanPham, donGia, soMatCan, congThuc, soToIn, thanhPham, chiPhiGiaCongTayCuoi = 0,
          soTrangTayCuoi = 0, soToInTayCuoi = 0, soTrang, thanhPhamTayCuoi, _this = this, TongChiPhiGiaCong = 0;
        rootFrame.find('.gia-cong-item').each(function () {
          var sub_gia_cong_tbody = $(this), giayruot_number = '',
            loaiGiay = sub_gia_cong_tbody.find('select[name*="Bia_Ruot"] :selected').val(),
            content_id = sub_gia_cong_tbody.find('select[name*="LoaiGiaCongId"] :selected').val(),
            gia_cong = _this.getContentById(content_id), chiPhiGiaCong,
            $gia_cong_kich_thuoc = sub_gia_cong_tbody.find('.gia_cong_kich_thuoc:not(.tay_cuoi)'),
            $gia_cong_so_luong = sub_gia_cong_tbody.find('.gia_cong_so_luong:not(.tay_cuoi)'),
            $gia_cong_so_to_in = sub_gia_cong_tbody.find('.gia_cong_so_to_in:not(.tay_cuoi)'),
            $gia_cong_so_mat = sub_gia_cong_tbody.find('.gia_cong_so_mat:not(.tay_cuoi)'),
            $gia_cong_don_gia = sub_gia_cong_tbody.find('.gia_cong_don_gia:not(.tay_cuoi)'),
            $gia_cong_so_trang = sub_gia_cong_tbody.find('.gia_cong_so_trang:not(.tay_cuoi)'),
            soMatCan = sub_gia_cong_tbody.find('select[name*="[SoMat]"]').val(),
            soLuong = sub_gia_cong_tbody.find('input[name*="[soLuong]"]').val(),
            soToIn = sub_gia_cong_tbody.find('input[name*="[soToIn]"]').val(),
            dai = sub_gia_cong_tbody.find('input[name*="[Length]"]').val(),
            dai_tay_cuoi = sub_gia_cong_tbody.find('input[name*="[LengthTayCuoi]"]').val(),
            rong = sub_gia_cong_tbody.find('input[name*="[Width]"]').val(),
            rong_tay_cuoi = sub_gia_cong_tbody.find('input[name*="[WidthTayCuoi]"]').val(),
            soTrang = sub_gia_cong_tbody.find('input[name*="[soTrang]"]').val(),
            soTrangTayCuoi = sub_gia_cong_tbody.find('input[name*="[soTrangTayCuoi]"]').val(),
            soToInTayCuoi = sub_gia_cong_tbody.find('input[name*="[soToInTayCuoi]"]').val(),
            soTay = 0;

          if (loaiGiay !== '' && loaiGiay !== 'bia' && is_photo !== 1)
            giayruot_number = loaiGiay.replace(/ruot/g, '');

          if(!sub_gia_cong_tbody.find('input[name*="[soLuong]"]').hasClass('userChanged'))
            soLuong = _this.getProductAmount(selectElm);

          if(!sub_gia_cong_tbody.find('select[name*="[SoMat]"]').hasClass('userChanged')){
            if(loaiGiay === 'bia')
              soMatCan = rootFrame.find('input[name*="[SoMatInBia]"]').val();
            else
              soMatCan = rootFrame.find('input[name*="[SoMatInRuot'+giayruot_number+']"]').val();
          }

          if(!sub_gia_cong_tbody.find('input[name*="[soToIn]"]').hasClass('userChanged')){
            if(loaiGiay === 'bia')
              soToIn = rootFrame.find('input[name*="[GiayBiaSoTo]"][type="hidden"]').val();
            else
              soToIn = rootFrame.find('input[name*="[GiayRuotSoTo]"][type="hidden"]').val();
          }

          soLuong = Number(_this.removeFormat(soLuong));
          soToIn = Number(_this.removeFormat(soToIn));
          soTrang = Number(_this.removeFormat(soTrang));
          dai = Number(_this.removeFormat(dai));
          rong = Number(_this.removeFormat(rong));

          if (rootFrame.find('input[name*="bia_ghep_ruot"]').is(':checked'))
            bia_ghep_ruot = 1;

          if (!gia_cong)
            return false;

          congThuc = $.trim(gia_cong.formula);

          sub_gia_cong_tbody.find('.dvgc').text(gia_cong.donViTinh);

          donGia = sub_gia_cong_tbody.find('input[name*="[DonGia]"]').val();
          donGia = _this.removeFormat(donGia);
          if (isNaN(soLuong) || soLuong === 0)
            soLuongSanPham = soLuong = _this.getProductAmount(selectElm);
          else
            soLuongSanPham = soLuong;

          //neu tich chon tinh gia truc tiep
          if (sub_gia_cong_tbody.find('input[name*="[checkbox_hasnt_formula_gia_cong]"]').is(":checked")) {
            sub_gia_cong_tbody.find(".show_formula_gia_cong").show();
            sub_gia_cong_tbody.find(".hasnt_formula_gia_cong").hide();
            var donGiaTrucTiep = sub_gia_cong_tbody.find('input[name*="DonGiaHasntFormula"]').val(),
              soLuongTrucTiep = sub_gia_cong_tbody.find('input[name*="soLuongHasntFormula"]').val();
            donGiaTrucTiep = _this.removeFormat(donGiaTrucTiep);
            soLuongTrucTiep = _this.removeFormat(soLuongTrucTiep);
            chiPhiGiaCong = donGiaTrucTiep * soLuongTrucTiep;
          } else {
            sub_gia_cong_tbody.find(".show_formula_gia_cong").hide();

            if (!isNaN(soLuongSanPham))
              sub_gia_cong_tbody.find('input[name*="[soLuong]"]').val(soLuongSanPham);

            if (loaiGiay === 'bia') {
              if (isNaN(soToIn) || !sub_gia_cong_tbody.find('input[name*="[soToIn]"]').hasClass('userChanged')) {
                soToIn = rootFrame.find(GiayBiaSoTo).val();
                soToIn = Number(_this.removeFormat(soToIn));
                if (!isNaN(soToIn))
                  sub_gia_cong_tbody.find('input[name*="[soToIn]"]').val(soToIn);
              }

              thanhPham = rootFrame.find(GiayBiaThanhPham).val();
              thanhPham = _this.removeFormat(thanhPham);

              soTrang = soToIn * thanhPham * 2;
              if (!isNaN(soTrang))
                sub_gia_cong_tbody.find('input[name*="[soTrang]"]').val(soTrang);

              if (isNaN(soMatCan) || !sub_gia_cong_tbody.find('select[name*="[SoMat]"]').hasClass('userChanged')) {
                soMatCan = rootFrame.find(soMatInBia).val();
                if (!isNaN(soMatCan))
                  sub_gia_cong_tbody.find('select[name*="[SoMat]"]').val(soMatCan);
              }

              if (congThuc.indexOf('soToIn') !== -1) {
                if (isNaN(dai) || !sub_gia_cong_tbody.find('input[name*="[Length]"]').hasClass('userChanged')) {
                  dai = rootFrame.find(GiayBiaLength).val();
                  if (!isNaN(dai))
                    sub_gia_cong_tbody.find('input[name*="[Length]"]').val(dai);
                }

                if (isNaN(rong) || !sub_gia_cong_tbody.find('input[name*="[Width]"]').hasClass('userChanged')) {
                  rong = rootFrame.find(GiayBiaWidth).val();
                  if (!isNaN(rong))
                    sub_gia_cong_tbody.find('input[name*="[Width]"]').val(rong);
                }
              } else {
                if (isNaN(dai) || !sub_gia_cong_tbody.find('input[name*="[Length]"]').hasClass('userChanged')) {
                  dai = rootFrame.find(input_productLength).val();
                  dai = Number(_this.removeFormat(dai));
                  if (!isNaN(dai))
                    sub_gia_cong_tbody.find('input[name*="[Length]"]').val(dai);
                }

                if (isNaN(rong) || !sub_gia_cong_tbody.find('input[name*="[Width]"]').hasClass('userChanged')) {
                  rong = rootFrame.find(input_productWidth).val();
                  rong = Number(_this.removeFormat(rong));
                  if (!isNaN(rong))
                    sub_gia_cong_tbody.find('input[name*="[Width]"]').val(rong);
                }
              }
            } else if(is_photo !== 1){
              soTay = parseFloat(rootFrame.find('input[name*="[SoTay' + giayruot_number + ']"]').val());
              if (isNaN(soToIn) || !sub_gia_cong_tbody.find('input[name*="[soToIn]"]').hasClass('userChanged')) {
                soToIn = rootFrame.find('input[name*="[GiayRuotSoTo' + giayruot_number + ']"][type="hidden"]').val();
                soToIn = Number(_this.removeFormat(soToIn));
                if (!isNaN(soToIn))
                  sub_gia_cong_tbody.find('input[name*="[soToIn]"]').val(soToIn);
              }

              thanhPham = rootFrame.find('input[name*="[GiayRuotThanhPham' + giayruot_number + ']"]').val();
              thanhPham = _this.removeFormat(thanhPham);

              soTrang = soToIn * thanhPham * 2;
              if (!isNaN(soTrang))
                sub_gia_cong_tbody.find('input[name*="[soTrang]"]').val(soTrang);

              //them thong tin cho tay cuoi
              soToInTayCuoi = sub_gia_cong_tbody.find('input[name*="[soToInTayCuoi]"]').val();
              soToInTayCuoi = Number(_this.removeFormat(soToInTayCuoi));
              if (isNaN(soToInTayCuoi) || !sub_gia_cong_tbody.find('input[name*="[soToInTayCuoi]"]').hasClass('userChanged')) {
                soToInTayCuoi = rootFrame.find('input[name*="[TayCuoiSoTo' + giayruot_number + ']"][type="hidden"]').val();
                soToInTayCuoi = Number(_this.removeFormat(soToInTayCuoi));
                if (!isNaN(soToInTayCuoi))
                  sub_gia_cong_tbody.find('input[name*="[soToInTayCuoi]"]').val(soToInTayCuoi);

              }
              thanhPhamTayCuoi = rootFrame.find('input[name*="[TayCuoiThanhPham' + giayruot_number + ']"]').val();
              thanhPhamTayCuoi = _this.removeFormat(thanhPhamTayCuoi);

              soTrangTayCuoi = soToInTayCuoi * thanhPhamTayCuoi * 2;
              if (!isNaN(soTrangTayCuoi))
                sub_gia_cong_tbody.find('input[name*="[soTrangTayCuoi]"]').val(soTrangTayCuoi);

              if (isNaN(soMatCan) || !sub_gia_cong_tbody.find('select[name*="[SoMat]"]').hasClass('userChanged')) {
                soMatCan = rootFrame.find('input[name*="[SoMatInRuot' + giayruot_number + ']"]').val();
                if (!isNaN(soMatCan))
                  sub_gia_cong_tbody.find('select[name*="[SoMat]"]').val(soMatCan);
              }

              if (congThuc.indexOf('soToIn') !== -1) {
                if (isNaN(dai) || !sub_gia_cong_tbody.find('input[name*="[Length]"]').hasClass('userChanged')) {
                  dai = rootFrame.find('input[name*="[GiayRuotLength' + giayruot_number + ']"]').val();
                  if (!isNaN(dai))
                    sub_gia_cong_tbody.find('input[name*="[Length]"]').val(dai);
                }

                if (isNaN(rong) || !sub_gia_cong_tbody.find('input[name*="[Width]"]').hasClass('userChanged')) {
                  rong = rootFrame.find('input[name*="[GiayRuotWidth' + giayruot_number + ']"]').val();
                  if (!isNaN(rong))
                    sub_gia_cong_tbody.find('input[name*="[Width]"]').val(rong);
                }

                //them thong tin cho tay cuoi
                if (isNaN(dai_tay_cuoi) || !sub_gia_cong_tbody.find('input[name*="[LengthTayCuoi]"]').hasClass('userChanged')) {
                  dai_tay_cuoi = rootFrame.find('input[name*="[GiayRuotTayCuoiLength' + giayruot_number + ']"]').val();
                  if (!isNaN(dai_tay_cuoi))
                    sub_gia_cong_tbody.find('input[name*="[LengthTayCuoi]"]').val(dai_tay_cuoi);
                }

                if (isNaN(rong_tay_cuoi) || !sub_gia_cong_tbody.find('input[name*="[WidthTayCuoi]"]').hasClass('userChanged')) {
                  rong_tay_cuoi = rootFrame.find('input[name*="[GiayRuotTayCuoiWidth' + giayruot_number + ']"]').val();
                  if (!isNaN(rong_tay_cuoi))
                    sub_gia_cong_tbody.find('input[name*="[WidthTayCuoi]"]').val(rong_tay_cuoi);
                }
              } else {
                if (isNaN(dai) || !sub_gia_cong_tbody.find('input[name*="[LengthTayCuoi]"]').hasClass('userChanged')) {
                  dai = rootFrame.find(input_productLength).val();
                  dai = _this.removeFormat(dai);
                  if (!isNaN(dai)) {
                    sub_gia_cong_tbody.find('input[name*="[Length]"]').val(dai);
                    sub_gia_cong_tbody.find('input[name*="[LengthTayCuoi]"]').val(dai);
                  }
                }
                if (isNaN(rong) || !sub_gia_cong_tbody.find('input[name*="[WidthTayCuoi]"]').hasClass('userChanged')) {
                  rong = rootFrame.find(input_productWidth).val();
                  rong = _this.removeFormat(rong);
                  if (!isNaN(rong))
                    sub_gia_cong_tbody.find('input[name*="[Width]"]').val(rong);
                  sub_gia_cong_tbody.find('input[name*="[WidthTayCuoi]"]').val(rong);
                }
              }
            }else{

              var san_pham_photo = $('input[value="'+loaiGiay+'"]').closest('tr');

              soMatCan = 1;
              soLuong = san_pham_photo.find('input[name*="so_luong"]').val();
              soLuong = _this.removeFormat(soLuong);
              if (isNaN(soLuong) || !sub_gia_cong_tbody.find('input[name*="[soTrang]"]').hasClass('userChanged'))
                sub_gia_cong_tbody.find('input[name*="[soTrang]"]').val(soLuong);

              if (isNaN(soLuong) || !sub_gia_cong_tbody.find('input[name*="[soToIn]"]').hasClass('userChanged'))
                sub_gia_cong_tbody.find('input[name*="[soToIn]"]').val(soLuong);

              if (isNaN(soLuong) || !sub_gia_cong_tbody.find('input[name*="[soLuong]"]').hasClass('userChanged'))
                sub_gia_cong_tbody.find('input[name*="[soLuong]"]').val(soLuong);

              if (isNaN(dai) || !sub_gia_cong_tbody.find('input[name*="[Length]"]').hasClass('userChanged')) {
                dai = rootFrame.find(input_productLength).val();
                if (!isNaN(dai))
                  sub_gia_cong_tbody.find('input[name*="[Length]"]').val(dai);
              }

              if (isNaN(rong) || !sub_gia_cong_tbody.find('input[name*="[Width]"]').hasClass('userChanged')) {
                rong = rootFrame.find(input_productWidth).val();
                if (!isNaN(rong))
                  sub_gia_cong_tbody.find('input[name*="[Width]"]').val(rong);
              }
            }

            $gia_cong_kich_thuoc.hide();
            $gia_cong_so_luong.hide();
            $gia_cong_don_gia.hide();
            $gia_cong_so_mat.hide();
            $gia_cong_so_to_in.hide();
            $gia_cong_so_trang.hide();
            sub_gia_cong_tbody.find('.kich_thuoc_tay_cuoi, .to_in_tay_cuoi, .so_trang_tay_cuoi').hide();

            //neu cong thuc co kich thuoc
            if (congThuc.indexOf('dai') !== -1 || congThuc.indexOf('rong') !== -1) {
              $gia_cong_kich_thuoc.show();
              if (soTay % 1 !== 0) {
                sub_gia_cong_tbody.find('.kich_thuoc_tay_cuoi').show();
              }
            }
            //neu cong thuc co so luong
            if (congThuc.indexOf('soLuongSanPham') !== -1 || congThuc.indexOf('soLuong') !== -1) {
              $gia_cong_so_luong.show();
            }
            //neu cong thuc co don gia
            if (congThuc.indexOf('donGia') !== -1) {
              $gia_cong_don_gia.show();
            }
            //neu cong thuc co mat can
            if (congThuc.indexOf('soMatCan') !== -1) {
              $gia_cong_so_mat.show();
            }
            //neu cong thuc co so to in
            if (congThuc.indexOf('soToIn') !== -1) {
              $gia_cong_so_to_in.show();
              if (soTay % 1 !== 0) {
                sub_gia_cong_tbody.find('.to_in_tay_cuoi').show();
              }
            }
            //neu cong thuc co so trang
            if (congThuc.indexOf('soTrang') !== -1) {
              $gia_cong_so_trang.show();
              if (soTay % 1 !== 0) {
                sub_gia_cong_tbody.find('.so_trang_tay_cuoi').show();
              }
            }
            if (loaiGiay === 'bia' || is_photo === 1) {
              sub_gia_cong_tbody.find('.tay_cuoi').hide();
            }
            chiPhiGiaCong = Math.round(eval(congThuc));
            if (bia_ghep_ruot === 1 && loaiGiay === 'bia')
              chiPhiGiaCong = 0;
            if (loaiGiay !== 'bia' && soTay % 1 !== 0) {
              dai = dai_tay_cuoi;
              rong = rong_tay_cuoi;
              soToIn = soToInTayCuoi;
              soTrang = soTrangTayCuoi;
              chiPhiGiaCongTayCuoi = Math.round(eval(congThuc));
              chiPhiGiaCong += chiPhiGiaCongTayCuoi;
            }
          }

          if (!isNaN(chiPhiGiaCong)) {
            sub_gia_cong_tbody.find('input[name*="[ChiPhiGiaCong]"]').val(chiPhiGiaCong);
            sub_gia_cong_tbody.find('.ChiPhiGiaCongTxt').html(chiPhiGiaCong).formatCurrency();
            TongChiPhiGiaCong += chiPhiGiaCong;
          }
        });
        if (!isNaN(TongChiPhiGiaCong))
          rootFrame.find('input[name*="TongChiPhiGiaCong"][type="hidden"]').val(TongChiPhiGiaCong);
        _this.numberInput();
      };

        this.getKhoGiayIn = function (selectElm, real) {
            if (!real || real === undefined)
                real = 0;
            var _this = this,
                rootFrame = _this.getRootFrame(selectElm),
                hasInnerPage = _this.hasInnerPage(selectElm),
                kieuHop = _this.getProductKieuHop(selectElm);

            _this.timKhoGiayBia(selectElm, real);

            if (hasInnerPage) {
                $('.tbody_giay_ruot_order', rootFrame).each(function (i) {
                    var stt = '';
                    if (i > 0)
                        stt = i + 1;
                    _this.timKhoGiayRuot(rootFrame, real, stt);
                });
            } else if (kieuHop === hopCungDinhHinh) {
                _this.timKhoGiayRuot(rootFrame, real, '');
            }

        };

        this.alertValidation = function (selectElm) {
            var product_id = this.getProductId(selectElm),
                product = this.getProductById(product_id),
                kieu_dong = this.getProductKieuDong(selectElm),
                kieu_hop = this.getProductKieuHop(selectElm),
                old_val = $(selectElm).data('val');
            if (selectElm.nodeName === 'INPUT' && selectElm.name.indexOf('amount') !== -1 && (!selectElm.value || selectElm.value <= 0)) {
                selectElm.value = old_val;
                if (Number(old_val) === 0)
                    krajeeDialog.alert('Vui lòng nhập số lượng chính xác');
            }
            else if (selectElm.nodeName === 'INPUT' && selectElm.name.indexOf('inner_page_amount') !== -1 && product && product.has_inner_page === 1 && (!selectElm.value || selectElm.value <= 0)) {
                selectElm.value = old_val;
                if (Number(old_val) === 0)
                    krajeeDialog.alert('Vui lòng nhập số lượng chính xác');
            }
            else if (!product) {
                krajeeDialog.alert('Vui lòng chọn loại sản phẩm');
            }
            else if (product && product.has_inner_page === 1 && kieu_dong <= 0)
                krajeeDialog.alert('Vui lòng chọn kiểu đóng');
            else if (product && product.product_type === sanPhamHop && kieu_hop <= 0)
                krajeeDialog.alert('Vui lòng chọn kiểu hộp');

        };

        this.tinhChiPhiInTest = function (rootFrame) {
            var soLuong, donGia, isInTest, chiPhiInTest = 0;
            isInTest = parseFloat(rootFrame.find('select[name*="[InTest]"] :selected').val());
            if (isInTest === 1) {
                soLuong = rootFrame.find('input[name*="SoToInTest"]').val();
                donGia = rootFrame.find('input[name*="DonGiaInTest"]').val();
                soLuong = this.removeFormat(soLuong);
                donGia = this.removeFormat(donGia);
                if (isNaN(soLuong) || isNaN(donGia)) {
                    console.log('ChiPhiInTest() - isNaN');
                } else {
                    chiPhiInTest = eval(this.formula.inTestS);
                }

            }
            chiPhiInTest = Math.round(chiPhiInTest);
            rootFrame.find('input[name*="ChiPhiInTest"]').val(chiPhiInTest);
            this.updateChiPhiTab(rootFrame);
            return false;
        };

        this.tinhChiPhiIn = function (elm) {
            var rootFrame = this.getRootFrame(elm),
                kieuHop = this.getProductKieuHop(elm),
                tongChiPhi = 0, chiPhiInRuot = 0, chiPhiInBia;

            rootFrame.find('input[name*="[ChiPhiInBia]"]').val(0);

            //neu khong tich chon su dung don gia truc tiep o tab giay in
            chiPhiInBia = parseFloat(rootFrame.find('input[name*="[TongChiPhiInBia]"]').val());
            if (!isNaN(chiPhiInBia)) {
                tongChiPhi += chiPhiInBia;
                rootFrame.find('input[name*="[ChiPhiInBia]"]').val(chiPhiInBia);
            }

            if (this.hasInnerPage(rootFrame) && Number(kieuHop) !== hopCungDinhHinh) {
                rootFrame.find('.kieuin-item.inruot_elm').each(function (i) {
                    var _count;
                    if (i === 0)
                        _count = '';
                    else
                        _count = i + 1;

                    chiPhiInRuot = parseFloat(rootFrame.find('input[name*="[TongChiPhiInRuot' + _count + ']"]').val());
                    if (!isNaN(chiPhiInRuot))
                        rootFrame.find('input[name*="[ChiPhiInRuot' + _count + ']"]').val(chiPhiInRuot);

                    if (!isNaN(chiPhiInRuot))
                        tongChiPhi += chiPhiInRuot;
                });
                rootFrame.find('.kieuin-item.taycuoi_elm').each(function (i) {
                    var _count;
                    if (i === 0)
                        _count = '';
                    else
                        _count = i + 1;

                    chiPhiInRuot = parseFloat(rootFrame.find('input[name*="[TongChiPhiInTayCuoi' + _count + ']"]').val());
                    if (!isNaN(chiPhiInRuot))
                            rootFrame.find('input[name*="[ChiPhiInRuotTayCuoi' + _count + ']"]').val(chiPhiInRuot);
                    if (!isNaN(chiPhiInRuot))
                        tongChiPhi += chiPhiInRuot;
                });

            }


            if (!isNaN(tongChiPhi))
                rootFrame.find('input[name*="[TongChiPhiIn]"][type="hidden"]').val(tongChiPhi);

            return true;
        };

        this.updateGiaCongPhotoList = function(){
            var options = null;
            if(is_photo === 1) {
                $('.tbody_photo_item tr').each(function () {
                    var value = $(this).find('input[name*="[ten_san_pham]"]').val(),
                        key = $(this).find('input[name*="[id_photo]"]').val();
                    options += '<option value="' + key + '">' + value + '</option>';
                });
            }else{
                options += '<option value="bia">Bìa</option>';
                options += '<option value="ruot">Ruột</option>';
            }
            $('.gia-cong-item').each(function () {
                var current_val = $(this).find('select[name*="Bia_Ruot"]').val();
                $(this).find('select[name*="Bia_Ruot"]').html(options).val(current_val);
                if(!$(this).find('select[name*="Bia_Ruot"]').val())
                    $(this).find('select[name*="Bia_Ruot"] option:first').prop("selected", true);
            });
        };

        this.tinhTrongLuongDonHang = function (elm) {
            var rootFrame = this.getRootFrame(elm),
                GiayBiaWidth = rootFrame.find('input[name*="[GiayBiaWidth]"]').val(),
                GiayBiaLength = rootFrame.find('input[name*="[GiayBiaLength]"]').val(),
                GiayBiaSoTo = rootFrame.find('input[name*="[GiayBiaSoTo]"][type="hidden"]').val(),
                GiayBiaSoToBuHao = rootFrame.find('input[name*="[GiayBiaSoToBuHao]"]').clone().toNumber().val(),
                GiayRuotWidth = rootFrame.find('input[name*="[GiayRuotWidth]"]').val(),
                GiayRuotLength = rootFrame.find('input[name*="[GiayRuotLength]"]').val(),
                GiayRuotSoTo = rootFrame.find('input[name*="[GiayRuotSoTo]"][type="hidden"]').val(),
                GiayBiaDinhLuong = rootFrame.find('select[name*="[GiayBiaDinhLuong]"]').val(),
                GiayRuotDinhLuong = rootFrame.find('select[name*="[GiayRuotDinhLuong]"]').val(),
                vchuyen_bia_dinhluong = GiayBiaDinhLuong / 1000000,
                vchuyen_bia_rong = GiayBiaWidth / 100,
                vchuyen_bia_dai = GiayBiaLength / 100,
                vchuyen_ruot_rong = GiayRuotWidth / 100,
                vchuyen_ruot_dai = GiayRuotLength / 100,
                vchuyen_ruot_dinhluong = GiayRuotDinhLuong / 1000000,
                vchuyen_bia_so_to, vchuyen_bia_weight, vchuyen_ruot_so_to, vchuyen_ruot_weight, weight;
            GiayBiaSoToBuHao = this.removeFormat(GiayBiaSoToBuHao);
            if (this.isSubOrder(elm)) {
                vchuyen_bia_so_to = GiayBiaSoTo + GiayBiaSoToBuHao;
                vchuyen_bia_weight = vchuyen_bia_rong * vchuyen_bia_dai * vchuyen_bia_dinhluong * vchuyen_bia_so_to;
                weight = vchuyen_bia_weight + vchuyen_bia_weight;
            } else {
                vchuyen_bia_so_to = GiayBiaSoTo;
                vchuyen_bia_weight = vchuyen_bia_rong * vchuyen_bia_dai * vchuyen_bia_dinhluong * vchuyen_bia_so_to;
                vchuyen_ruot_so_to = GiayRuotSoTo;
                vchuyen_ruot_weight = vchuyen_ruot_rong * vchuyen_ruot_dai * vchuyen_ruot_dinhluong * vchuyen_ruot_so_to;
                weight = vchuyen_bia_weight + vchuyen_ruot_weight;
            }
            weight = weight * 1000;
            rootFrame.find('input[name*="[vanChuyenWeight]"]').val(weight);
        };

        this.tinhChiPhiNVL = function (selectElm) {
            var rootFrame = this.getRootFrame(selectElm),
                this_ = this;
            rootFrame.find('.table_chi_phi_khac tbody tr').each(function () {
                if (!$(this).find('.change_quantity_other_cost').hasClass('userChanged')) {
                    $(this).find('.change_quantity_other_cost').val('');
                    $(this).find('input[name*="quantity"]').val('');
                }
            });
            var formData = $("form#form-create-order").serialize(),
                _this = $(".order_frame:first");
            $.ajax({
                'url': link_tinhNVL,
                'data': formData,
                dataType: 'json',
                'type': 'POST',
                'success': function (data) {
                    if (!$.isEmptyObject(data)) {
                        var total = 0, total_vat;
                        _this.find('input[name*="[type]"]').each(function () {
                            var tr = $(this).closest('tr'),
                                val = $(this).val();
                            if (!$.isEmptyObject(data.NVL[val])) {
                                tr.find('input[name*="[quantity]"]').val(data.NVL[val].amount);
                                // tr.find('[quantity]').next('input[name*="[quantity]"]').val(data.NVL[val].amount);
                                tr.find('input[name*="[unit_price]"]').val(data.NVL[val].price);
                                // tr.find('.unitpriceTxtHidden').val(data.NVL[val].price);
                                tr.find('input[name*="[price]"]').val(data.NVL[val].total);
                                // tr.find('.total_price').val(data.NVL[val].total);
                                total += data.NVL[val].total;
                            }
                        });
                        total_vat = total + total * 0.1;
                        rootFrame.find('.TongChiPhiKhacTxt').html(total).formatCurrency();
                        rootFrame.find('input[name*="[tongChiPhiKhac]"]').val(total);
                        rootFrame.find('.TongChiPhiKhacVATTxt').html(total_vat).formatCurrency();
                        rootFrame.find('input[name*="[tongChiPhiKhacVAT]"]').val(total_vat);
                        rootFrame.find('input[name*="[chiPhiChungKhac]"]').val(data.chiPhiChungKhac);
                        rootFrame.find('input[name*="[nctt]"]').val(data.NCTT);
                        rootFrame.find('input[name*="[nl_va_nl]"]').val(data.chiPhiNangLuong);
                        rootFrame.find('input[name*="[chiPhiPhanXuong]"]').val(data.chiPhiPhanXuong);
                        rootFrame.find('input[name*="[chiPhiBaoHiem]"]').val(data.chiPhiBaoHiem);
                        rootFrame.find('input[name*="[khauHaoTSCD]"]').val(data.KHTSCD);

                        if (!$.isEmptyObject(data.toGac)) {
                            var chiPhiGacVAT = Math.round(parseFloat(data.toGac.chiPhiGac) + parseFloat(data.toGac.chiPhiGac) * 0.1);
                            rootFrame.find('.ToGacChiPhi').html(data.toGac.chiPhiGac).formatCurrency();
                            rootFrame.find('.ToGacChiPhiVat').html(chiPhiGacVAT).formatCurrency();
                            rootFrame.find('input[name*="[ToGacChiPhi]"]').val(data.toGac.chiPhiGac);
                            rootFrame.find('input[name*="[ToGacChiPhiVat]"]').val(chiPhiGacVAT);
                            rootFrame.find('input[name*="[ToGacSoTo]"][type="text"]').val(data.toGac.soToGac);
                            rootFrame.find('input[name*="[ToGacSoTo]"][type="hidden"]').val(data.toGac.soToGac);
                        }

                        if (!$.isEmptyObject(data.biaCarton)) {
                            var chiPhiBiaCartonVAT = Math.round(parseFloat(data.biaCarton.chiPhiBiaCarton) + parseFloat(data.biaCarton.chiPhiBiaCarton) * 0.1);
                            rootFrame.find('.BiaCartonChiPhi').html(data.biaCarton.chiPhiBiaCarton).formatCurrency();
                            rootFrame.find('.BiaCartonChiPhiVat').html(chiPhiBiaCartonVAT).formatCurrency();
                            rootFrame.find('input[name*="[BiaCartonChiPhi]"]').val(data.biaCarton.chiPhiBiaCarton);
                            rootFrame.find('input[name*="[BiaCartonSoTam]"][type="text"]').val(data.biaCarton.soLuongBiaCarton);
                            rootFrame.find('input[name*="[BiaCartonSoTam]"][type="hidden"]').val(data.biaCarton.soLuongBiaCarton);
                            rootFrame.find('input[name*="[BiaCartonChiPhiVat]"]').val(chiPhiBiaCartonVAT);
                        }
                        this_.numberInput();
                        this_.updateChiPhiTab(selectElm)
                    }
                }
            });
        };

        this.getContentById = function (content_id) {
            var content = {};
            if (contents_json) {
                content = contents_json[content_id];
            }
            return content;
        };

        this.tinhChiPhiGiayIn = function (elm) {
            var rootFrame = this.getRootFrame(elm),
                chiPhiGiayBiaVat, chiPhiGiayRuotVat, tongChiPhiGiayIn = 0, chiPhiGiayRuot, chiPhiGiayRuotTayCuoi,
                chiPhiGiayBia = parseFloat(rootFrame.find('input[name*="TongChiPhiGiayInBia"]').val()),
                ToGacChiPhi = parseFloat(rootFrame.find('input[name*="ToGacChiPhi"]').val()),
                BiaCartonChiPhi = parseFloat(rootFrame.find('input[name*="BiaCartonChiPhi"]').val());
            if (rootFrame.find('input[name*="[checkbox_hasnt_formula_giay_in]"]').is(':checked')) {
                this.tinhChiPhiGiayInHasntFormuala(elm);
                return false;
            }
            if (!isNaN(chiPhiGiayBia)) {
                rootFrame.find('.chiPhiGiayInBia').html(chiPhiGiayBia).formatCurrency();
                chiPhiGiayBiaVat = Math.round(chiPhiGiayBia + chiPhiGiayBia * 0.1);
                rootFrame.find('.chiPhiGiayInBiaVat').html(chiPhiGiayBiaVat).formatCurrency();
                rootFrame.find('input[name*="GiayBiaChiPhi"]').val(chiPhiGiayBia);
                rootFrame.find('input[name*="GiayBiaChiPhiVat"]').val(chiPhiGiayBiaVat);
                tongChiPhiGiayIn += chiPhiGiayBia;
            }
            if (this.hasInnerPage(elm)) {
                rootFrame.find('tbody.tbody_giay_ruot_order').each(function () {
                    var tbody = $(this);
                    chiPhiGiayRuot = parseFloat(tbody.find('input[name*="TongChiPhiGiayInRuot"]').val());
                    chiPhiGiayRuotTayCuoi = parseFloat(tbody.find('input[name*="TongChiPhiGiayInTayCuoi"]').val());
                    if (!isNaN(chiPhiGiayRuot)) {
                        if (!isNaN(chiPhiGiayRuotTayCuoi))
                            chiPhiGiayRuot += chiPhiGiayRuotTayCuoi;
                        else
                            chiPhiGiayRuotTayCuoi = 0;

                        tbody.find('.chiPhiGiayInRuot').html(chiPhiGiayRuot).formatCurrency();
                        chiPhiGiayRuotVat = Math.round(chiPhiGiayRuot + chiPhiGiayRuot * 0.1);
                        tbody.find('.chiPhiGiayInRuotVat').html(chiPhiGiayRuotVat).formatCurrency();
                        tbody.find('input[name*="GiayRuotChiPhi"]').val(chiPhiGiayRuot);
                        tbody.find('input[name*="GiayRuotChiPhiVat"]').val(chiPhiGiayRuotVat);
                        tongChiPhiGiayIn += chiPhiGiayRuot;
                    } else {
                        chiPhiGiayRuot = 0;
                    }
                });
            } else if (this.getProductKieuHop(elm) === hopCungDinhHinh) {
                var chiPhiNapHop = parseFloat(rootFrame.find('input[name*="TongChiPhiGiayInRuot"]').val()),
                    chiPhiNapHopVat;
                if (!isNaN(chiPhiNapHop)) {
                    rootFrame.find('.GiayRuotChiPhi').html(chiPhiNapHop).formatCurrency();
                    chiPhiNapHopVat = Math.round(chiPhiNapHop + chiPhiNapHop * 0.1);
                    rootFrame.find('.GiayRuotChiPhiVat').html(chiPhiNapHopVat).formatCurrency();
                    rootFrame.find('input[name*="GiayRuotChiPhi"]').val(chiPhiNapHop);
                    rootFrame.find('input[name*="GiayRuotChiPhiVat"]').val(chiPhiNapHopVat);
                    tongChiPhiGiayIn += chiPhiNapHop;
                }
            }
            if (rootFrame.find('input[name*="[bia_cung]"]').is(':checked')) {
                tongChiPhiGiayIn += ToGacChiPhi + BiaCartonChiPhi;
            }

            if (tongChiPhiGiayIn) {
                rootFrame.find('input[name*="[TongChiPhiGiayIn]"]').val(tongChiPhiGiayIn);
            }

        };

        this.getFileDesignDropList = function (elm, data, limit) {
            var rootFrame = this.getRootFrame(elm);
            if (limit === undefined)
                limit = 5;
            $.ajax({
                'cache': false,
                'url': getFileDesignDroplist + '?limit=' + limit,
                'data': data,
                'type': 'get',
                'success': function (data) {
                    rootFrame.find('select[id$="file_design"]').html(data);
                }
            });
        };

        this.scaleImage = function (imgWidth, imgHeight, targetWidth, targetHeight, fLetterBox) {

            var result = {width: 0, height: 0, fScaleToTargetWidth: true};

            if ((imgWidth <= 0) || (imgHeight <= 0) || (targetWidth <= 0) || (targetHeight <= 0)) {
                return result;
            }

            // scale to the target width
            var scaleX1 = targetWidth;
            var scaleY1 = (imgHeight * targetWidth) / imgWidth;

            // scale to the target height
            var scaleX2 = (imgWidth * targetHeight) / imgHeight;
            var scaleY2 = targetHeight;

            // now figure out which one we should use
            var fScaleOnWidth = (scaleX2 > targetWidth);
            if (fScaleOnWidth) {
                fScaleOnWidth = fLetterBox;
            } else {
                fScaleOnWidth = !fLetterBox;
            }

            if (fScaleOnWidth) {
                result.width = Math.floor(scaleX1);
                result.height = Math.floor(scaleY1);
                result.fScaleToTargetWidth = true;
            } else {
                result.width = Math.floor(scaleX2);
                result.height = Math.floor(scaleY2);
                result.fScaleToTargetWidth = false;
            }
            result.targetLeft = Math.floor((targetWidth - result.width) / 2);
            result.targetTop = Math.floor((targetHeight - result.height) / 2);

            return result;
        };

        this.appendFileDesignToDroplist = function (rootFrame, json) {
            var option,
                json = $.parseJSON(json);
            $('select[id$="file_design"]').prepend(
                option = $('<option>').val(json.a_id).html(json.title).attr({'selected': 'selected'})
            ).trigger('change');
            return this;
        };

        this.addSupplier = function (select, group) {
            var name = $(select).attr('name'), table_closest = $(select).closest('table'),
                tbody = '', loai_gc = '', chatlieu_gb, chatlieu_gr;

            if ($(select).hasClass('ncc_giay')) {
                tbody = $(select).closest('tbody');
                chatlieu_gb = $('select[name*="GiayBiaChatLieu"]', table_closest).val();
                chatlieu_gr = $('select[name*="GiayRuotChatLieu"]', table_closest).val();
                group = 2;
            }
            else if ($(select).hasClass('ncc_gia_cong')) {
                tbody = $(select).closest('tbody');
                loai_gc = $('select[name*="LoaiGiaCongId"]', tbody).val();
                group = 5;
            }
            else if ($(select).hasClass('ncc_in_test')) {
                group = 3;
            }
            else if ($(select).hasClass('ncc_xuat_ra')) {
                group = 4;
            }
            if (isNaN(group) || group <= 0) {
                krajeeDialog.alert('Yêu cầu không hợp lệ.');
                return false;
            }
            $.ajax({
                'url': link_addSupplier,
                'data': {
                    'group': group,
                    'chatlieu_gb': chatlieu_gb,
                    'chatlieu_gr': chatlieu_gr,
                    'loai_gc_from_form': loai_gc
                },
                'type': 'get',
                'success': function (data) {
                    console.log(data);
                    $.fancybox.open({
                        content: $('<div style="padding: 5px !important;">').append(data)
                    });
                }
            });
        };

        this.searchProduct = function(){
            $.expr[':'].Contains = function(a,i,m){
                return (a.textContent || a.innerText || "").toUpperCase().indexOf(m[3].toUpperCase())>=0;
            };
            $('.find_value')
                .change( function () {
                    var filter = $(this).val(),
                        list = $('.table_list_product > tbody.list');
                    if(filter) {
                        $matches = $(list).find('td:Contains(' + filter + ')').parent();
                        $('tr', list).not($matches).slideUp();
                        $matches.slideDown();

                    } else {
                        $(list).find("tr").slideDown();
                    }
                    return false;
                })
                .keyup( function () {
                    $(this).change();
                });
        };

        this.addSubOrder = function (sub_order_length, success_callback) {
            var _this = this,
                sub_order_length_fact = _this.getSubOrderLength();
            $.ajax({
                'type': 'get',
                'data': {'count': sub_order_length},
                'url': link_addSubOrderTempl,
                'success': function (data) {
                    _this.root_tab_elm.find('.nav.nav-tabs li').removeClass('active');
                    _this.root_tab_elm.find('.tab-pane').removeClass('active');
                    var id = 'sub_inner_' + sub_order_length,
                        new_elm = $('<div class="tab-pane fade-in active order_frame" id="' + id + '">');
                    $('form', new_elm).remove();
                    _this.root_tab_elm.find('.nav.nav-tabs').append(
                        $('<li>').addClass('active').append(
                            $('<a data-toggle="tab" aria-expanded="true">').attr({
                                'href': '#sub_inner_' + sub_order_length
                            }).html('Đơn hàng ghép ' + sub_order_length + ' <a href="javascript:;" class="remove_sub_order" title="Xóa đơn hàng ghép này"><i class="fa fa-times" aria-hidden="true"></i></a>')
                        ).attr('data-id', sub_order_length)
                    );
                    _this.root_tab_elm.find('.tab-content').append(new_elm.html(data));
                    // _this.root_tab_elm.tabslet({'active': sub_order_length_fact + 1});
//                     $('#sub_inner_' + sub_order_length + '_inner').tabslet({
//                         controls: {
//                             prev: '.prev_tab',
//                             next: '.next_tab'
//                         }
//                     });
//                     $('#Orders_tabs_sub_' + sub_order_length + '_inner').on('_after', function () {
//                         var tabs = $('ul.tabs', this),
//                             index = $('li', tabs).index($('li.active', tabs));
//                         $('.prev_tab', this).prop('disabled', false);
//                         $('.next_tab', this).prop('disabled', false);
//                         if (index === 0) {
//                             $('.prev_tab', this).prop('disabled', true);
//                         } else if ((index + 1) === $('li', tabs).size()) {
//                             $('.next_tab', this).prop('disabled', true);
//                         }
//                     });
//                     var tabGiayIn = $('#Orders_tabs_sub_' + sub_order_length + ' .paper'),
//                         tabGiayInFact = $('#Orders_tabs_sub_' + sub_order_length + ' .paper_fact'),
//                         tbodyGiayBia = _this.getTbodyGiayBia($('#Orders_tabs_primary_inner').get(0)),
//                         tbodyGiayRuot = _this.getTbodyGiayRuot($('#Orders_tabs_primary_inner').get(0), '');
// //                    console.log(tbodyGiayRuot);
//
//
//                     //chat lieu
//                     $('select[name*="GiayBiaChatLieu"]', tabGiayIn).html($('select[name*="GiayBiaChatLieu"]', tbodyGiayBia).html()).val($('select[name*="GiayBiaChatLieu"] :selected', tbodyGiayBia).val());
//                     $('select[name*="GiayRuotChatLieu"]', tabGiayIn).html($('select[name*="GiayRuotChatLieu"]', tbodyGiayRuot).html()).val($('select[name*="GiayRuotChatLieu"] :selected', tbodyGiayRuot).val());
//                     //nha cung cap
//                     $('select[name*="GiayBiaNhaCungCap"]', tabGiayIn).html($('select[name*="GiayBiaNhaCungCap"]', tbodyGiayBia).html()).val($('select[name*="GiayBiaNhaCungCap"] :selected', tbodyGiayBia).val());
//                     $('select[name*="GiayRuotNhaCungCap"]', tabGiayIn).html($('select[name*="GiayRuotNhaCungCap"]', tbodyGiayRuot).html()).val($('select[name*="GiayRuotNhaCungCap"] :selected', tbodyGiayRuot).val());
//                     //dinh luong giay
//                     $('select[name*="GiayBiaDinhLuong"]', tabGiayIn).html($('select[name*="GiayBiaDinhLuong"]', tbodyGiayBia).html()).val($('select[name*="GiayBiaDinhLuong"] :selected', tbodyGiayBia).val());
//                     $('select[name*="GiayRuotDinhLuong"]', tabGiayIn).html($('select[name*="GiayRuotDinhLuong"]', tbodyGiayRuot).html()).val($('select[name*="GiayRuotDinhLuong"] :selected', tbodyGiayRuot).val());
//                     //kho giay
//                     $('select[name*="GiayBiaKhoGiayId"]', tabGiayIn).html($('select[name*="GiayBiaKhoGiayId"]', tbodyGiayBia).html()).val($('select[name*="GiayBiaKhoGiayId"] :selected', tbodyGiayBia).val());
//                     $('select[name*="GiayBiaKhoGiayIdFact"]', tabGiayInFact).html($('select[name*="GiayBiaKhoGiayId"]', tbodyGiayBia).html()).val($('select[name*="GiayBiaKhoGiayId"] :selected', tbodyGiayBia).val());
//                     $('select[name*="GiayRuotKhoGiayId"]', tabGiayIn).html($('select[name*="GiayRuotKhoGiayId"]', tbodyGiayRuot).html()).val($('select[name*="GiayRuotKhoGiayId"] :selected', tbodyGiayRuot).val());
//                     $('select[name*="GiayRuotKhoGiayIdFact"]', tabGiayInFact).html($('select[name*="GiayRuotKhoGiayId"]', tbodyGiayRuot).html()).val($('select[name*="GiayRuotKhoGiayId"] :selected', tbodyGiayRuot).val());
//                     //don gia
//                     var tabGiayInPrimary = $('#Orders_tabs_primary_inner');
//                     if ($('input[name*="[checkbox_hasnt_formula_giay_in]"]', tabGiayInPrimary).is(':checked')) {
//                         if ($('select[name*="GiayBiaNhaCungCap"] :selected', tbodyGiayBia).val() > 0) {
//                             if (supplier_json.length > 0) {
//                                 var don_gia_dinh_luong, don_gia = 0,
//                                     chatLieuId = $('select[name*="GiayBiaChatLieu"] :selected', tbodyGiayBia).val(),
//                                     dinhluong = $('select[name*="GiayBiaDinhLuong"] :selected', tbodyGiayBia).val();
//                                 $.each(supplier_json, function (k, v) {
//                                     if (v.supplierid === $('select[name*="GiayBiaNhaCungCap"] :selected', tbodyGiayBia).val()) {
//                                         don_gia_dinh_luong = jQuery.parseJSON(v.don_gia_dinh_luong);
//                                         don_gia = don_gia_dinh_luong[chatLieuId][dinhluong];
//                                         $('input[name*="[GiayBiaPrice]"]', tabGiayIn).val(don_gia);
//                                     }
//                                 });
//                             }
//                         } else {
//                             $('input[name*="[GiayBiaPrice]"]', tabGiayIn).val(0);
//                         }
//
//                         if ($('select[name*="GiayRuotNhaCungCap"] :selected', tbodyGiayRuot).val() > 0) {
//                             if (supplier_json.length > 0) {
//                                 var don_gia_dinh_luong, don_gia = 0,
//                                     chatLieuId = $('select[name*="GiayRuotChatLieu"] :selected', tbodyGiayRuot).val(),
//                                     dinhluong = $('select[name*="GiayRuotDinhLuong"] :selected', tbodyGiayRuot).val();
//                                 $.each(supplier_json, function (k, v) {
//                                     if (v.supplierid === $('select[name*="GiayRuotNhaCungCap"] :selected', tbodyGiayRuot).val()) {
//                                         //Hai added 1306
//                                         don_gia_chat_lieu_giay = v.don_gia_chat_lieu_giay;
//                                         if (don_gia_chat_lieu_giay !== null || don_gia_chat_lieu_giay !== undefined) {
//                                             don_gia = don_gia_chat_lieu_giay[chatLieuId];
//                                             $('input[name*="[GiayRuotPrice]"]', tabGiayIn).val(don_gia);
//                                         }
//
//                                         //don_gia_dinh_luong = jQuery.parseJSON(v.don_gia_dinh_luong);
//                                         //don_gia = don_gia_dinh_luong[chatLieuId][dinhluong];
//                                         //$('input[name*="GiayRuotPrice"]').val(don_gia);
//                                     }
//                                 });
//                             }
//                         } else {
//                             //$('input[name*="GiayRuotPrice"]').val(0);
//                         }
//
//                     } else {
//                         $('input[name*="[GiayBiaPrice]"]', tabGiayIn).val($('input[name*="[GiayBiaPrice]"]', tbodyGiayBia).val());
//                         $('input[name*="[GiayRuotPrice]"]', tabGiayIn).val($('input[name*="[GiayRuotPrice]"]', tbodyGiayRuot).val());
//                     }
//
// //                    $('select', tabGiayIn).not($('select[id$="GiayBiaKhoGiayId"]', tabGiayIn)).prop('disabled', true);
//
//                     jcore.setSelectStyle();
//                     $('input[name*="ThoiGianNhanHang"]').datepicker({
// //                        'minDate': 1,
//                         'changeMonth': true,
//                         'changeYear': true,
//                         'dateFormat': 'yy-mm-dd'
//                     });
                    new_elm.find('.kv-plugin-loading').remove();
                    new_elm.find('select[name*="customer_id"]').val('').select2({
                        allowClear: true,
                        language: "vi",
                        placeholder: "Chọn khách hàng",
                        theme: "krajee",
                        width: "100%"
                    });
                    new_elm.find('select[name*="NhaCungCap"]').val('').select2({
                        allowClear: true,
                        language: "vi",
                        placeholder: "Chọn nhà cung cấp",
                        theme: "krajee",
                        width: "100%"
                    });
                    new_elm.find('select[name*="otherCostSelect"]').show().select2({
                        allowClear: true,
                        language: "vi",
                        placeholder: "Nhập tiêu đề",
                        theme: "krajee",
                        width: "100%",
                        tags: true
                    });

                    if (success_callback) {
                        success_callback.call(_this);
                    }
                }
            });
        };
    };