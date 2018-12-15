<?php
namespace common\components;

use yii;
use backend\models\Giakhomayin;
use backend\models\Products;
use backend\models\OrderInfo;
use backend\helpers\AcpHelper;

class calculatorCosts{

    public $chiPhiThietKe = 0;
    public $chiPhiIn = 0;
    public $chiPhiGiayIn = 0;
    public $chiPhiRaCan = 0;
    public $chiPhiInTest = 0;
    public $chiPhiGiaCong = 0;
    public $chiPhiKhac = 0;
    public $tongChiPhi = [];
    public $inputs = [];

    public function tongChiPhiIn(){
        //so cap tinh gia
        $settings = Yii::$app->settings;
        $calPrintingCosts = new calPrintingCosts();
        $level_quotes = (int)$settings->get('level_quotes');
        $ncc_in = Giakhomayin::find()->where(['supplierId' => intval($this->inputs->NhaCungCapIn), 'kg_default' => 1])->one();
        if($level_quotes > 2){
            for ($i = 2; $i <= $level_quotes; $i++){

                //chi phi thiet ke
                $this->chiPhiThietKe = floatval($this->inputs->ChiPhiThietKe);

                //chi phi in test
                $this->chiPhiInTest = floatval($this->inputs->order->ChiPhiInTest);

                //chi phi khac
                $this->chiPhiKhac = floatval($this->inputs->orderdata->tongChiPhiKhac) + floatval($this->inputs->orderdata->vanChuyenChiPhi);

                //tinh chi phi in va chi phi giay in
                $arr_tay = $return = $data = array();
                if(!empty($this->inputs->tay))
                    $arr_tay = json_decode($this->inputs->tay, true);
                //thong tin bia
                if(intval($this->inputs->bia_ghep_ruot) != 1) {
                    $data['bia'] = new \stdClass();
                    $data['bia']->chuaKepNhip = floatval($this->inputs->chua_kep_nhip);
                    $data['bia']->dai = floatval($this->inputs->length);
                    $data['bia']->rong = floatval($this->inputs->width) * ($this->inputs->has_inner_page == 1 ? 2 : 1) + ($this->inputs->has_inner_page == 1 && $this->inputs->nape > 0 ? $this->inputs->nape : 0);
                    $data['bia']->chatLieu = intval($this->inputs->paper->GiayBiaChatLieu);
                    $data['bia']->dinhLuong = intval($this->inputs->paper->GiayBiaDinhLuong);
                    $data['bia']->nhaCungCap = intval($this->inputs->paper->GiayBiaNhaCungCap);
                    $data['bia']->soLuong = intval($this->inputs->amount) * $i;
                    $data['bia']->soToBuHao = -1;
                    $data['bia']->soMauKem = intval($this->inputs->SoMauInBia);
                    $data['bia']->matIn = intval($this->inputs->SoMatInBia);
                    $data['bia']->nhaCungCapIn = intval($this->inputs->NhaCungCapIn);
                    $data['bia']->taiSanPham = intval($this->inputs->has_ear);
                    $data['bia']->viTriTai = intval($this->inputs->vi_tri_tai);
                    $data['bia']->taiDai = floatval($this->inputs->tai_dai);
                    $data['bia']->taiRong = floatval($this->inputs->tai_rong);
                    $data['bia']->type = 'gb';
                    $data['bia']->donGia = floatval($this->inputs->paper->GiayBiaPrice);
                    $data['bia']->khoGiay = 0;
                    $data['bia']->khoMay = !empty($ncc_in) ? $ncc_in->kg_id : 0;
                    $data['bia']->soSanPham = intval($this->inputs->amount) * $i;
                    $data['bia']->kieuDong = intval($this->inputs->KieuDong);
                    $data['bia']->cachTinh = $this->inputs->calcu_type;
                    $data['bia']->biaGhepRuot = intval($this->inputs->bia_ghep_ruot);
                    $data['bia']->soTrangRuot = intval($this->inputs->inner_page_amount);
                    $data['bia']->inChungKho = 0;
                    $data['bia']->khoGiayTayCuoi = 0;
                    $data['bia']->viTriNhip = intval($this->inputs->vt_nhip_bia);
                    $data['bia']->mauPha = intval($this->inputs->MauPhaBia);
                    $data['bia']->heSoMauPha = floatval($settings->get('he_so_mau_pha'));
                    $data['bia']->kieuHop = intval($this->inputs->KieuHop);
                    $data['bia']->cao = floatval($this->inputs->thick);
                    $data['bia']->kichThuocTai = floatval($this->inputs->kich_thuoc_tai);
                    $data['bia']->kichThuocCaiDay = floatval($this->inputs->kich_thuoc_cai_day);
                    $data['bia']->boiSongE = floatval($this->inputs->boi_song_e);
                    $data['bia']->taiNap = $this->inputs->tai_nap > 0 ? floatval($this->inputs->tai_nap) : 0;
                    $data['bia']->kichThuocNap = floatval($this->inputs->kich_thuoc_nap);
                    $data['bia']->nap_hop = 0;
                    $data['bia']->loaiSanPham = $this->inputs->product_type;
                    $data['bia']->giaTriBoiSongE = $this->inputs->product->boi_song_e;
                    $data['bia']->buHaoNoiXen = $this->inputs->product->bu_hao_noi_xen;
                    $data['bia']->DonGiaKem = !empty($ncc_in) ? $ncc_in->price_k : '';
                    $data['bia']->DonGiaIn = !empty($ncc_in) ? $ncc_in->price_i : '';
                    $data['bia']->khoKemDai = !empty($ncc_in) ? $ncc_in->kem_dai : '';
                    $data['bia']->khoKemRong = !empty($ncc_in) ? $ncc_in->kem_rong : '';
                    $data['bia']->banTinh = Yii::$app->settings->get('cach_tinh_gia') == 0 ? 'thuongmai' : 'dinhmuc';
                    $data['bia']->thanhPham = 0;
                    $data['bia']->istayCuoi = 0;
                    $data['bia']->kieuTro = $this->inputs->kieu_in_tro;
                    $data['bia']->quyCachIn = trim($this->inputs->QuyCachIn);
                    $data['bia']->kieuInGiaTrucTiep = intval($this->inputs->checkbox_hasnt_formula_kieu_in);
                    $return['bia'] = $calPrintingCosts->_productSize($data['bia']);
                    $this->chiPhiIn = floatval($return['bia']['chiPhiIn']);
                    $this->chiPhiGiayIn = floatval($return['bia']['chiPhiGiayIn']);
                }

                if($this->inputs->has_inner_page == 1 || $this->inputs->KieuHop == Products::HOP_CUNG_DINH_HINH) {
                    //thong tin bia
                    $soLuong_ = intval($this->inputs->amount) * $i;
                    $data['ruot'] = new \stdClass();
                    $data['ruot']->chuaKepNhip = floatval($this->inputs->chua_kep_nhip);
                    $data['ruot']->dai = floatval($this->inputs->length);
                    $data['ruot']->rong = floatval($this->inputs->width) * ($this->inputs->KieuDong == OrderInfo::DONG_GIUA ? 2 : 1);
                    $data['ruot']->chatLieu = intval($this->inputs->paper->GiayRuotChatLieu);
                    $data['ruot']->dinhLuong = intval($this->inputs->paper->GiayRuotDinhLuong);
                    $data['ruot']->nhaCungCap = intval($this->inputs->paper->GiayRuotNhaCungCap);
                    $data['ruot']->soLuong = intval($this->inputs->inner_page_amount);
                    $data['ruot']->soToBuHao = -1;
                    $data['ruot']->soMauKem = intval($this->inputs->SoMauInRuot);
                    $data['ruot']->matIn = intval($this->inputs->SoMatInRuot);
                    $data['ruot']->nhaCungCapIn = intval($this->inputs->NhaCungCapIn);
                    $data['ruot']->taiSanPham = intval($this->inputs->has_ear);
                    $data['ruot']->viTriTai = intval($this->inputs->vi_tri_tai);
                    $data['ruot']->taiDai = floatval($this->inputs->tai_dai);
                    $data['ruot']->taiRong = floatval($this->inputs->tai_rong);
                    $data['ruot']->type = 'gr';
                    $data['ruot']->donGia = floatval($this->inputs->paper->GiayRuotPrice);
                    $data['ruot']->khoGiay = 0;
                    $data['ruot']->khoMay = !empty($ncc_in) ? $ncc_in->kg_id : 0;
                    $data['ruot']->soSanPham = $soLuong_;
                    $data['ruot']->kieuDong = intval($this->inputs->KieuDong);
                    $data['ruot']->cachTinh = trim($this->inputs->calcu_type);
                    $data['ruot']->biaGhepRuot = intval($this->inputs->bia_ghep_ruot);
                    $data['ruot']->soTrangRuot = intval($this->inputs->inner_page_amount);
                    $data['ruot']->inChungKho = !empty($arr_tay) && isset($arr_tay['InCungKhoGiay']) && $arr_tay['InCungKhoGiay'] > 0 ? 1 : 0;
                    $data['ruot']->khoGiayTayCuoi = 0;
                    $data['ruot']->viTriNhip = intval($this->inputs->vt_nhip_ruot);
                    $data['ruot']->mauPha = intval($this->inputs->MauPhaRuot);
                    $data['ruot']->heSoMauPha = floatval($settings->get('he_so_mau_pha'));
                    $data['ruot']->kieuHop = intval($this->inputs->KieuHop);
                    $data['ruot']->cao = floatval($this->inputs->thick);
                    $data['ruot']->kichThuocTai = floatval($this->inputs->kich_thuoc_tai);
                    $data['ruot']->kichThuocCaiDay = floatval($this->inputs->kich_thuoc_cai_day);
                    $data['ruot']->boiSongE = floatval($this->inputs->boi_song_e);
                    $data['ruot']->taiNap = $this->inputs->tai_nap > 0 ? floatval($this->inputs->tai_nap) : 0;
                    $data['ruot']->kichThuocNap = floatval($this->inputs->kich_thuoc_nap);
                    $data['ruot']->nap_hop = 0;
                    $data['ruot']->loaiSanPham = intval($this->inputs->product_type);
                    $data['ruot']->DonGiaKem = !empty($ncc_in) ? $ncc_in->price_k : '';
                    $data['ruot']->DonGiaIn = !empty($ncc_in) ? $ncc_in->price_i : '';
                    $data['ruot']->khoKemDai = !empty($ncc_in) ? $ncc_in->kem_dai : '';
                    $data['ruot']->khoKemRong = !empty($ncc_in) ? $ncc_in->kem_rong : '';
                    $data['ruot']->kieuInGiaTrucTiep = floatval($this->inputs->checkbox_hasnt_formula_kieu_in);
                    $data['ruot']->istayCuoi = 0;
                    $data['ruot']->kieuTro = '';
                    $data['ruot']->quyCachIn = trim($this->inputs->QuyCachInRuot);
                    $data['ruot']->banTinh = Yii::$app->settings->get('cach_tinh_gia') == 0 ? 'thuongmai' : 'dinhmuc';
                    $data['ruot']->thanhPham = 0;
                    $data['ruot']->buHaoTayCuoi = -1;
                    $data['ruot']->thanhPhamTayCuoi = 0;
                    if($this->inputs->KieuHop == Products::HOP_CUNG_DINH_HINH) {
                        $data['ruot']->soLuong = $soLuong_;
                        $data['ruot']->nap_hop = 1;
                    }

                    $return['ruot'] = $calPrintingCosts->_productSize($data['ruot']);
//                    print_r($data['ruot']);
                    $this->chiPhiIn += floatval($return['ruot']['chiPhiIn']);
                    $this->chiPhiGiayIn += floatval($return['ruot']['chiPhiGiayIn']);
                    if(!empty($return['ruot']['tay_cuoi'])){
                        $this->chiPhiIn += floatval($return['ruot']['tay_cuoi']['chiPhiIn']);
                        $this->chiPhiGiayIn += floatval($return['ruot']['tay_cuoi']['chiPhiGiayIn']);
                    }

                    //ruot mo rong
                    if(!empty($this->inputs->paper->GiayRuotMoRong)){
                        $giayRuotMoRong = json_decode($this->inputs->paper->GiayRuotMoRong);
                        $kieuInMoRong = json_decode($this->inputs->KieuInMoRong);
                        $soLuong = json_decode($this->inputs->ext_inner_page_amount, true);
                        if (!is_array($this->inputs->ext_mau_pha_ruot) && !empty($this->inputs->ext_mau_pha_ruot))
                            $ext_mau_pha_ruot = json_decode($this->inputs->ext_mau_pha_ruot, true);
                        elseif (!empty($this->inputs->ext_mau_pha_ruot))
                            $ext_mau_pha_ruot = $this->inputs->ext_mau_pha_ruot;

                        if(!empty($giayRuotMoRong)){
                            $k_ = -1;
                            foreach ($giayRuotMoRong as $k => $v) {
                                $k_++;

                                $soLuongRuot = $soLuong[$k_];
                                $data['ruot'.$k] = new \stdClass();
                                $data['ruot'.$k]->chuaKepNhip = floatval($this->inputs->chua_kep_nhip);
                                $data['ruot'.$k]->dai = floatval($this->inputs->length);
                                $data['ruot'.$k]->rong = floatval($this->inputs->width) * ($this->inputs->KieuDong == OrderInfo::DONG_GIUA ? 2 : 1);
                                $data['ruot'.$k]->chatLieu = intval($v->GiayRuotChatLieu);
                                $data['ruot'.$k]->dinhLuong = intval($v->GiayRuotDinhLuong);
                                $data['ruot'.$k]->nhaCungCap = intval($v->GiayRuotNhaCungCap);
                                $data['ruot'.$k]->soLuong = intval($soLuongRuot);
                                $data['ruot'.$k]->soToBuHao = -1;
                                $data['ruot'.$k]->soMauKem = intval($kieuInMoRong->{$k}->SoMauInRuot);
                                $data['ruot'.$k]->soMau = intval($kieuInMoRong->{$k}->SoMauInRuot);
                                $data['ruot'.$k]->matIn = intval($kieuInMoRong->{$k}->SoMatInRuot);
                                $data['ruot'.$k]->nhaCungCapIn = intval($this->inputs->NhaCungCapIn);
                                $data['ruot'.$k]->taiSanPham = intval($this->inputs->has_ear);
                                $data['ruot'.$k]->viTriTai = intval($this->inputs->vi_tri_tai);
                                $data['ruot'.$k]->taiDai = floatval($this->inputs->tai_dai);
                                $data['ruot'.$k]->taiRong = floatval($this->inputs->tai_rong);
                                $data['ruot'.$k]->type = 'gr';
                                $data['ruot'.$k]->donGia = floatval($v->GiayRuotPrice);
                                $data['ruot'.$k]->khoGiay = 0;
                                $data['ruot'.$k]->khoMay = !empty($ncc_in) ? $ncc_in->kg_id : 0;
                                $data['ruot'.$k]->soSanPham = intval($this->inputs->amount) * $i;
                                $data['ruot'.$k]->kieuDong = intval($this->inputs->KieuDong);
                                $data['ruot'.$k]->cachTinh = trim($this->inputs->calcu_type);
                                $data['ruot'.$k]->biaGhepRuot = intval($this->inputs->bia_ghep_ruot);
                                $data['ruot'.$k]->soTrangRuot = intval($soLuongRuot);
                                $data['ruot'.$k]->inChungKho = !empty($arr_tay) && isset($arr_tay['InCungKhoGiay'.$k]) && $arr_tay['InCungKhoGiay'.$k] > 0 ? 1 : 0;
                                $data['ruot'.$k]->khoGiayTayCuoi = 0;
                                $data['ruot'.$k]->viTriNhip = intval($this->inputs->vt_nhip_ruot);
                                $data['ruot'.$k]->mauPha = intval($ext_mau_pha_ruot[$k_]);
                                $data['ruot'.$k]->heSoMauPha = floatval($settings->get('he_so_mau_pha'));
                                $data['ruot'.$k]->loaiSanPham = floatval($this->inputs->product_type);
                                $data['ruot'.$k]->DonGiaKem = !empty($ncc_in) ? $ncc_in->price_k : '';
                                $data['ruot'.$k]->DonGiaIn = !empty($ncc_in) ? $ncc_in->price_i : '';
                                $data['ruot'.$k]->khoKemDai = !empty($ncc_in) ? $ncc_in->kem_dai : '';
                                $data['ruot'.$k]->khoKemRong = !empty($ncc_in) ? $ncc_in->kem_rong : '';
                                $data['ruot'.$k]->kieuInGiaTrucTiep = intval($this->inputs->checkbox_hasnt_formula_kieu_in);
                                $data['ruot'.$k]->istayCuoi = 0;
                                $data['ruot'.$k]->nap_hop = 0;
                                $data['ruot'.$k]->quyCachIn = trim($kieuInMoRong->{$k}->QuyCachInRuot);
                                $data['ruot'.$k]->banTinh = Yii::$app->settings->get('cach_tinh_gia') == 0 ? 'thuongmai' : 'dinhmuc';
                                $data['ruot'.$k]->kieuHop = intval($this->inputs->KieuHop);
                                $data['ruot'.$k]->thanhPham = 0;
                                $data['ruot'.$k]->buHaoTayCuoi = -1;
                                $data['ruot'.$k]->thanhPhamTayCuoi = 0;
                                $data['ruot'.$k]->kieuTro = $this->inputs->kieu_in_tro_ruot;
                                $return['ruot'.$k] = $calPrintingCosts->_productSize($data['ruot'.$k]);
                                $this->chiPhiIn += floatval($return['ruot'.$k]['chiPhiIn']);
                                $this->chiPhiGiayIn += floatval($return['ruot'.$k]['chiPhiGiayIn']);
                                if(!empty($return['ruot'.$k]['tay_cuoi'])){
                                    $this->chiPhiIn += floatval($return['ruot'.$k]['tay_cuoi']['chiPhiIn']);
                                    $this->chiPhiGiayIn += floatval($return['ruot'.$k]['tay_cuoi']['chiPhiGiayIn']);
                                }
                            }
                        }
                    }
                }
                if($this->inputs->checkbox_hasnt_formula_kieu_in == 1){
                    $this->chiPhiIn = $this->inputs->ChiPhiInBia;
                }

                if($this->inputs->paper->checkbox_hasnt_formula_giay_in == 1){
                    $this->chiPhiGiayIn = $this->inputs->paper->TongChiPhiGiayIn;
                }
                //het phan tinh chi phi in + chi phi giay in

                $this->chiPhiRaCan = 0;
                //chi phi ra kem
                if(!empty($this->inputs->XuatRaMoRong)) {
                    $raPhim = json_decode($this->inputs->XuatRaMoRong, true);
                    if(!empty($raPhim)){
                        foreach ($raPhim as $v){
                            $donGia = $v['ExportDonGia'];
                            if($v['ExportType'] == 1){
                                $dai = $return[$v['ExportBiaRuot']]['info']['length'];
                                $rong = $return[$v['ExportBiaRuot']]['info']['width'];
                            }elseif($v['ExportType'] == 3){
                                $dai = $return[$v['ExportBiaRuot']]['khoMay']['kem_dai'];
                                $rong = $return[$v['ExportBiaRuot']]['khoMay']['kem_rong'];
                            }
                            if($v['ExportBiaRuot'] == 'bia')
                                $soluong = 1;
                            else
                                $soluong = floor($return[$v['ExportBiaRuot']]['soTay']);
                            $soMau = $data[$v['ExportBiaRuot']]->soMauKem;
                            if($v['ExportAddColor'] == 1)
                                $soMau += 1;
                            //( dai * rong * soluong * soMau * donGia)

                            //neu co tay le va khong chon in cung mot kho giay
                            if($v['ExportBiaRuot'] != 'bia' && !empty($return[$v['ExportBiaRuot']]['tay_cuoi'])){
                                if(strpos($v['ExportBiaRuot'],'ruot') !== false)
                                    $soMau_ = $data[$v['ExportBiaRuot']]->soMauKem;
//                                if($v['ExportAddColor'] == 1)
//                                    $soMau_ += 1;
                                $this->chiPhiRaCan += $dai * $rong * $soluong * $soMau_ * $donGia;
//                                print_r("($dai * $rong * $soluong * $soMau_ * $donGia)");

                                if(!empty($return[$v['ExportBiaRuot']]['tay_cuoi'])) {
                                    if ($v['ExportType'] == 1) {
                                        $dai = $return[$v['ExportBiaRuot']]['tay_cuoi']['info']['length'];
                                        $rong = $return[$v['ExportBiaRuot']]['tay_cuoi']['info']['width'];
                                    } elseif ($v['ExportType'] == 3) {
                                        $dai = $return[$v['ExportBiaRuot']]['tay_cuoi']['khoMay']['kem_dai'];
                                        $rong = $return[$v['ExportBiaRuot']]['tay_cuoi']['khoMay']['kem_rong'];
                                    }
                                    $soMau = $return[$v['ExportBiaRuot']]['tay_cuoi']['soMauKem'];
                                    if (!empty($dai) && !empty($rong) && !empty($soMau) && !empty($donGia))
                                        $this->chiPhiRaCan += $dai * $rong * $soMau * $donGia;
//                                print_r("|$dai * $rong * $soMau * $donGia|");
                                }
                            }else{
                                if(!empty($dai) && !empty($rong) && !empty($soMau) && !empty($donGia) && !empty($soMau))
                                    $this->chiPhiRaCan += $dai * $rong * $soluong * $soMau * $donGia;
//                                print_r("($dai * $rong * $soluong * $soMau * $donGia)");
                            }
//                            print_r($v);

                        }
                    }

                }

                //chi phi gia cong
                $this->chiPhiGiaCong = 0;
                if(!empty($this->inputs->giacong)){
                    $result = 0;
                    foreach ($this->inputs->giacong as $v){
                        if($v->checkbox_hasnt_formula_gia_cong == 1){
                            $this->chiPhiGiaCong += $v->ChiPhiGiaCong;
                        }else {
                            $dai = $return[$v->Bia_Ruot]['info']['length'];
                            $rong = $return[$v->Bia_Ruot]['info']['width'];
                            $soLuong = intval($this->inputs->amount) * $i;
                            $soToIn = $return[$v->Bia_Ruot]['soTo'];
                            $soTrang = $soToIn * $return[$v->Bia_Ruot]['thanhPham'] * 2;
                            $donGia = $v->DonGia;
                            $soMatCan = $v->SoMat;

                            $congthuc = str_replace(array('dai', 'rong', 'soLuong', 'soLuongSanPham', 'donGia', 'soMatCan', 'soToIn', 'soTrang'), array('$dai', '$rong', '$soLuong', '$soLuong', '$donGia', '$soMatCan', '$soToIn', '$soTrang'), $v->content->formula);
                            eval('$result = ' . $congthuc . ';');
                            if(!empty($result) && $result >= 0) {
                                $this->chiPhiGiaCong += $result;
                            }

                            if ($v->Bia_Ruot != 'bia' && !empty($return[$v->Bia_Ruot]['tay_cuoi'])) {
                                $dai = $return[$v->Bia_Ruot]['tay_cuoi']['info']['length'];
                                $rong = $return[$v->Bia_Ruot]['tay_cuoi']['info']['width'];
                                $soToIn = $return[$v->Bia_Ruot]['tay_cuoi']['soTo'];
                                $soTrang = $soToIn * $return[$v->Bia_Ruot]['tay_cuoi']['thanhPham'] * 2;

                                $congthuc = str_replace(array('dai', 'rong', 'soLuong', 'soLuongSanPham', 'donGia', 'soMatCan', 'soToIn', 'soTrang'), array('$dai', '$rong', '$soLuong', '$soLuong', '$donGia', '$soMatCan', '$soToIn', '$soTrang'), $v->content->formula);
                                eval('$result = ' . $congthuc . ';');
                                if(!empty($result) && $result >= 0) {
                                    $this->chiPhiGiaCong += $result;
                                }
                            }
                        }
                    }
                }
                $this->chiPhiGiaCong = ceil($this->chiPhiGiaCong);

//                print_r($data['ruot2']);
//                print_r($return);
//print_r("$this->chiPhiThietKe + $this->chiPhiIn + $this->chiPhiGiayIn + $this->chiPhiRaCan + $this->chiPhiInTest + $this->chiPhiGiaCong + $this->chiPhiKhac");
//die;

                $chiPhiTruocThue = $this->chiPhiThietKe + $this->chiPhiIn + $this->chiPhiGiayIn + $this->chiPhiRaCan + $this->chiPhiInTest + $this->chiPhiGiaCong + $this->chiPhiKhac;
                $tongChiPhiPhanTram = (int)$this->inputs->orderdata->tongChiPhiPhanTram;
                if($tongChiPhiPhanTram <= 0){
                    $tongChiPhiPhanTram = (int) $settings->get('profit_percent_for_each_product');
                }
                $soLuongSanPham = intval($this->inputs->amount) * $i;
                $donGia = sprintf('%0.2f', $chiPhiTruocThue/$soLuongSanPham);
                $this->tongChiPhi[$i]['don_gia'] = sprintf('%0.2f', ($donGia + $donGia * $tongChiPhiPhanTram / 100));
                $this->tongChiPhi[$i]['chi_phi'] = round($chiPhiTruocThue);
            }
        }
        return $this->tongChiPhi;
    }

}