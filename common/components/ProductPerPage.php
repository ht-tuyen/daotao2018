<?php
namespace common\components;

use backend\models\OrderInfo;
use backend\models\Products;
class ProductPerPage
{

    var $so_luong_san_pham = 0;
    var $san_pham_rong = 0; //chieu rong san pham
    var $san_pham_dai = 0; //chieu dai san pham
    var $kho_giay_rong = 0; //chieu rong kho giay
    var $kho_giay_dai = 0; //chieu dai kho giay
    var $so_san_pham_them = 0;
    var $so_san_pham_tren_1_dong = 0;
    var $tong_so_san_pham = 0;
    var $totalItems = 0;
    var $so_dong_san_pham = 0;
    var $so_du_kho_giay = array();
    var $arr_san_pham_them = array();
    var $tai_san_pham = 0;
    var $vi_tri_tai = 1;
    var $tai_dai = 0;
    var $tai_rong = 0;
    var $vi_tri_kep_nhip = 0;
    var $kep_nhip = 0;
    var $kieu_hop = 0;
    var $kich_thuoc_tai = 0;
    var $kich_thuoc_cai_day = 0;
    var $san_pham_cao = 0;
    var $bu_hao_noi_xen = 0;
    var $boi_song_e = 0;
    var $tai_nap = 0;
    var $kich_thuoc_nap = 0;
    var $loai_san_pham = 0;
    var $gia_tri_boi_song_e = 0;
    var $tru_mat_sau_pb = 0;
    var $tay_cuoi = 0;
    var $bat_le = 0;
    var $kieu_dong = 0;
    var $nap_hop = 0;

    public function HopCaiDay($kho_giay_rong, $kho_giay_dai, $kep_nhip, $chieu_rong, $chieu_dai)
    {
        $tong_san_pham_tren_dong = $tong_so_hang_san_pham = $du_kho_giay = $so_san_pham_tren_1_dong = array();
        $nap_hop = $chieu_rong + $this->tai_nap;
        $tai_hop = $this->kich_thuoc_tai;
        $kho_giay_rong = $kho_giay_rong - $this->bu_hao_noi_xen;
        $kho_giay_dai = $kho_giay_dai - $this->bu_hao_noi_xen;
        $boi_song = $this->gia_tri_boi_song_e;
        if ($this->boi_song_e == 1) {
            $kho_giay_rong = $kho_giay_rong - $boi_song;
            $kho_giay_dai = $kho_giay_dai - $boi_song;
        }
        $kt_ngang = ($chieu_dai + $chieu_rong) * 2 + $tai_hop;
        $kt_doc = $this->san_pham_cao + $nap_hop;
//        print_r(array(
//            'chieu ngang' => $kt_ngang,
//            'chieu doc' => $kt_doc,
//            'nap' => $nap_hop,
//            'kho rong' => $kho_giay_rong,
//            'kho dai' => $kho_giay_dai
//        ));
        //kep nhip tu dong
        if ($this->vi_tri_kep_nhip == 1 || $this->vi_tri_kep_nhip == null) {
            //san pham dung chieu khep nhip ngang
            $so_san_pham_tren_1_dong[] = intval($kho_giay_rong / $kt_ngang);

            //san pham dung chieu khep nhip doc
            $so_san_pham_tren_1_dong[] = intval(($kho_giay_rong - $kep_nhip) / $kt_ngang);

            //san pham xoay ngang khep nhip ngang
            $so_san_pham_tren_1_dong[] = intval(($kho_giay_rong - $nap_hop) / $kt_doc);

            //san pham xoay ngang khep nhip doc
            $so_san_pham_tren_1_dong[] = intval(($kho_giay_rong - $kep_nhip - $nap_hop) / $kt_doc);

            //kep nhip ngang
        } elseif ($this->vi_tri_kep_nhip == 2) {
            if ($kho_giay_rong > $kho_giay_dai) {
                $so_san_pham_tren_1_dong[] = intval(($kho_giay_rong - $kep_nhip) / $kt_ngang);
                $so_san_pham_tren_1_dong[] = intval(($kho_giay_rong - $kep_nhip - $nap_hop) / $kt_doc);
            } else {
                $so_san_pham_tren_1_dong[] = intval($kho_giay_rong / $kt_ngang);
                $so_san_pham_tren_1_dong[] = intval(($kho_giay_rong - $nap_hop) / $kt_doc);
            }

            //kep nhip doc
        } elseif ($this->vi_tri_kep_nhip == 3) {
            if ($kho_giay_rong > $kho_giay_dai) {
                $so_san_pham_tren_1_dong[] = intval($kho_giay_rong / $kt_ngang);
                $so_san_pham_tren_1_dong[] = intval(($kho_giay_rong - $nap_hop) / $kt_doc);
            } else {
                $so_san_pham_tren_1_dong[] = intval(($kho_giay_rong - $kep_nhip) / $kt_ngang);
                $so_san_pham_tren_1_dong[] = intval(($kho_giay_rong - $kep_nhip - $nap_hop) / $kt_doc);
            }
        }
        $so_thanh_pham_tren_to = $san_pham_tren_1_dong = $dung_chieu = $hang_san_pham = 0;
        $vi_tri_kep = 1;
        //tong san pham sap xep trang
        $so_san_pham_tren_1_dong = @array_values($so_san_pham_tren_1_dong);
        if (!empty($so_san_pham_tren_1_dong)) {
            foreach ($so_san_pham_tren_1_dong as $k => $v) {
                $chieu_dai_giay = 0;
                $chieu_dai_san_pham = 0;
                $chieu_rong_giay = 0;
                $chieu_rong_san_pham = 0;
                if ($this->vi_tri_kep_nhip == 1 || $this->vi_tri_kep_nhip == null) {
                    $chieu_dai_giay = $k % 2 == 0 ? ($kho_giay_dai - $kep_nhip) : $kho_giay_dai;
                    $chieu_dai_giay = $k > 1 ? $chieu_dai_giay - $nap_hop : $chieu_dai_giay;
                    $chieu_rong_giay = $k % 2 == 0 ? $kho_giay_rong : ($kho_giay_rong - $kep_nhip);
                    $chieu_rong_giay = $k > 1 ? $chieu_rong_giay : $chieu_rong_giay - $nap_hop;
                    $chieu_dai_san_pham = $k > 1 ? $kt_ngang : $kt_doc;
                    $chieu_rong_san_pham = $k > 1 ? $kt_doc : $kt_ngang;
                } elseif ($this->vi_tri_kep_nhip == 2) {
                    if ($kho_giay_rong > $kho_giay_dai) {
                        $chieu_dai_giay = $kho_giay_dai;
                        $chieu_rong_giay = $kho_giay_rong - $kep_nhip;
                    } else {
                        $chieu_dai_giay = $kho_giay_dai - $kep_nhip;
                        $chieu_rong_giay = $kho_giay_rong;
                    }
                    if ($k == 0)
                        $chieu_dai_giay = $chieu_dai_giay - $nap_hop;
                    else
                        $chieu_rong_giay = $chieu_rong_giay - $nap_hop;
                    $chieu_dai_san_pham = $k > 0 ? $kt_ngang : $kt_doc;
                    $chieu_rong_san_pham = $k > 0 ? $kt_doc : $kt_ngang;
                } elseif ($this->vi_tri_kep_nhip == 3) {
                    if ($kho_giay_rong > $kho_giay_dai) {
                        $chieu_dai_giay = $kho_giay_dai - $kep_nhip;
                        $chieu_rong_giay = $kho_giay_rong;
                    } else {
                        $chieu_dai_giay = $kho_giay_dai;
                        $chieu_rong_giay = $kho_giay_rong - $kep_nhip;
                    }
                    if ($k == 0)
                        $chieu_dai_giay = $chieu_dai_giay - $nap_hop;
                    else
                        $chieu_rong_giay = $chieu_rong_giay - $nap_hop;
                    $chieu_dai_san_pham = $k > 0 ? $kt_ngang : $kt_doc;
                    $chieu_rong_san_pham = $k > 0 ? $kt_doc : $kt_ngang;
                }

                $so_hang_san_pham = intval($chieu_dai_giay / $chieu_dai_san_pham);

                $so_thanh_pham_tren_to = $so_hang_san_pham * $v;

                $tong_so_thanh_pham_tren_to[] = $so_thanh_pham_tren_to;
                $tong_so_hang_san_pham[] = $so_hang_san_pham;
                $tong_san_pham_tren_dong[] = $v;

                //tinh phan giay du
                $phan_giay_du_rong_1 = $chieu_rong_giay - $v * $chieu_rong_san_pham;
                $phan_giay_du_dai_1 = $chieu_dai_giay;
                $du_kho_giay[$k][] = array(
                    'width' => $phan_giay_du_rong_1,
                    'height' => $phan_giay_du_dai_1
                );

                $phan_giay_du_rong_2 = $v * $chieu_rong_san_pham;
                $phan_giay_du_dai_2 = $chieu_dai_giay - $so_hang_san_pham * $chieu_dai_san_pham;
                $du_kho_giay[$k][] = array(
                    'width' => $phan_giay_du_rong_2,
                    'height' => $phan_giay_du_dai_2
                );

            }
            if (!empty($tong_so_thanh_pham_tren_to)) {
                $maxIndex = array_search(max($tong_so_thanh_pham_tren_to), $tong_so_thanh_pham_tren_to);
                $so_thanh_pham_tren_to = $tong_so_thanh_pham_tren_to[$maxIndex];
                $san_pham_tren_1_dong = $tong_san_pham_tren_dong[$maxIndex];
                $hang_san_pham = $tong_so_hang_san_pham[$maxIndex];
                $du_kho_giay = $du_kho_giay[$maxIndex];

                if ($this->vi_tri_kep_nhip == 1 || $this->vi_tri_kep_nhip == null) {
                    if ($maxIndex % 2 == 0)
                        $vi_tri_kep = 3;
                    else
                        $vi_tri_kep = 2;
                } else {
                    $vi_tri_kep = $this->vi_tri_kep_nhip;
                }

                if ($maxIndex > 1 && ($this->vi_tri_kep_nhip == 1 || $this->vi_tri_kep_nhip == null))
                    $dung_chieu = 1;
                elseif ($maxIndex > 0 && ($this->vi_tri_kep_nhip == 2 || $this->vi_tri_kep_nhip == 3))
                    $dung_chieu = 1;

            }
        }
        return array(
            'so_pham_tren_tren_1_dong' => $san_pham_tren_1_dong,
            'hang_san_pham' => $hang_san_pham,
            'so_thanh_pham_tren_to' => $so_thanh_pham_tren_to,
            'dung_chieu' => $dung_chieu,
            'vi_tri_kep' => $vi_tri_kep,
            'dien_tich_con' => $du_kho_giay
        );
    }

    public function HopMocDay($kho_giay_rong, $kho_giay_dai, $kep_nhip, $chieu_rong, $chieu_dai)
    {
        $du_kho_giay = $so_san_pham_tren_1_dong = $tong_san_pham_tren_dong = $tong_so_hang_san_pham = array();

        $nap_hop = $chieu_rong + $this->tai_nap;
        $tai_hop = $this->kich_thuoc_tai;
        $kho_giay_rong = $kho_giay_rong - $this->bu_hao_noi_xen;
        $kho_giay_dai = $kho_giay_dai - $this->bu_hao_noi_xen;
        $boi_song = $this->gia_tri_boi_song_e;
        if ($this->boi_song_e == 1) {
            $kho_giay_rong = $kho_giay_rong - $boi_song;
            $kho_giay_dai = $kho_giay_dai - $boi_song;
        }

        $day_hop = $chieu_rong / 2 + $this->kich_thuoc_cai_day;
        $kt_ngang = ($chieu_dai + $chieu_rong) * 2 + $tai_hop;
        $kt_doc = $this->san_pham_cao + $day_hop;
//        print_r(array(
//            'chieu ngang' => $kt_ngang,
//            'chieu doc' => $kt_doc,
//            'nap' => $nap_hop,
//            'kho rong' => $kho_giay_rong,
//            'kho dai' => $kho_giay_dai
//        ));
        //kep nhip tu dong
        if ($this->vi_tri_kep_nhip == 1 || $this->vi_tri_kep_nhip == null) {
            //san pham dung chieu khep nhip ngang
            $so_san_pham_tren_1_dong[] = intval($kho_giay_rong / $kt_ngang);

            //san pham dung chieu khep nhip doc
            $so_san_pham_tren_1_dong[] = intval(($kho_giay_rong - $kep_nhip) / $kt_ngang);

            //san pham xoay ngang khep nhip ngang
            $so_san_pham_tren_1_dong[] = intval($kho_giay_rong / ($kt_doc + $nap_hop / 2));
            $rong_thuc_te = $kt_doc * $so_san_pham_tren_1_dong[2] + ceil($so_san_pham_tren_1_dong[2]/2) * $nap_hop;
            if($rong_thuc_te > $kho_giay_rong)
                $so_san_pham_tren_1_dong[2] = $so_san_pham_tren_1_dong[2] - 1;

            //san pham xoay ngang khep nhip doc
            $so_san_pham_tren_1_dong[] = intval(($kho_giay_rong - $kep_nhip) / ($kt_doc + $nap_hop / 2));
            $rong_thuc_te = $kt_doc * $so_san_pham_tren_1_dong[3] + ceil($so_san_pham_tren_1_dong[3]/2) * $nap_hop;
            if($rong_thuc_te > $kho_giay_rong - $kep_nhip)
                $so_san_pham_tren_1_dong[3] = $so_san_pham_tren_1_dong[3] - 1;

            //kep nhip ngang
        } elseif ($this->vi_tri_kep_nhip == 2) {
            if ($kho_giay_rong > $kho_giay_dai) {
                $so_san_pham_tren_1_dong[] = intval(($kho_giay_rong - $kep_nhip) / $kt_ngang);
                $so_san_pham_tren_1_dong[] = intval(($kho_giay_rong - $kep_nhip) / ($kt_doc + $nap_hop / 2));
                $rong_thuc_te = $kt_doc * $so_san_pham_tren_1_dong[1] + ceil($so_san_pham_tren_1_dong[1]/2) * $nap_hop;
                if($rong_thuc_te > $kho_giay_rong - $kep_nhip)
                    $so_san_pham_tren_1_dong[1] = $so_san_pham_tren_1_dong[1] - 1;
            } else {
                $so_san_pham_tren_1_dong[] = intval($kho_giay_rong / $kt_ngang);
                $so_san_pham_tren_1_dong[] = intval(($kho_giay_rong) / ($kt_doc + $nap_hop / 2));
                $rong_thuc_te = $kt_doc * $so_san_pham_tren_1_dong[1] + ceil($so_san_pham_tren_1_dong[1]/2) * $nap_hop;
                if($rong_thuc_te > $kho_giay_rong)
                    $so_san_pham_tren_1_dong[1] = $so_san_pham_tren_1_dong[1] - 1;
            }

            //kep nhip doc
        } elseif ($this->vi_tri_kep_nhip == 3) {
            if ($kho_giay_rong > $kho_giay_dai) {
                $so_san_pham_tren_1_dong[] = intval($kho_giay_rong / $kt_ngang);
                $so_san_pham_tren_1_dong[] = intval(($kho_giay_rong) / ($kt_doc + $nap_hop / 2));
                $rong_thuc_te = $kt_doc * $so_san_pham_tren_1_dong[1] + ceil($so_san_pham_tren_1_dong[1]/2) * $nap_hop;
                if($rong_thuc_te > $kho_giay_rong)
                    $so_san_pham_tren_1_dong[1] = $so_san_pham_tren_1_dong[1] - 1;
            } else {
                $so_san_pham_tren_1_dong[] = intval(($kho_giay_rong - $kep_nhip) / $kt_ngang);
                $so_san_pham_tren_1_dong[] = intval(($kho_giay_rong - $kep_nhip) / ($kt_doc + $nap_hop / 2));
                $rong_thuc_te = $kt_doc * $so_san_pham_tren_1_dong[1] + ceil($so_san_pham_tren_1_dong[1]/2) * $nap_hop;
                if($rong_thuc_te > $kho_giay_rong - $kep_nhip)
                    $so_san_pham_tren_1_dong[1] = $so_san_pham_tren_1_dong[1] - 1;
            }
        }

        $so_thanh_pham_tren_to = $san_pham_tren_1_dong = $dung_chieu = $hang_san_pham = $so_hang_san_pham = 0;
        $vi_tri_kep = 1;
        $so_san_pham_tren_1_dong = @array_values($so_san_pham_tren_1_dong);
        //tong san pham sap xep trang
        if (!empty($so_san_pham_tren_1_dong)) {
            foreach ($so_san_pham_tren_1_dong as $k => $v) {
                $tru_them = $chieu_rong_giay = $chieu_dai_giay = $chieu_rong_san_pham = $chieu_dai_san_pham = 0;
                if ($this->vi_tri_kep_nhip == 1 || $this->vi_tri_kep_nhip == null) {
                    $chieu_dai_giay = $k % 2 == 0 ? ($kho_giay_dai - $kep_nhip) : $kho_giay_dai;
                    $chieu_rong_giay = $k % 2 == 0 ? $kho_giay_rong : ($kho_giay_rong - $kep_nhip);
                    $so_hang_san_pham = $k > 1 ? intval($chieu_dai_giay / $kt_ngang) : intval($chieu_dai_giay / ($kt_doc + $nap_hop/2));

                    if($k>1){
                        $dai_thuc_te = $kt_doc * $so_hang_san_pham + ceil($so_hang_san_pham/2) * $nap_hop;
                        if($dai_thuc_te > $chieu_dai_giay)
                            $so_hang_san_pham = $so_hang_san_pham - 1;
                        $tru_them = ceil($so_hang_san_pham/2) * $nap_hop;
                    }
                    $chieu_dai_san_pham = $k > 1 ? $kt_ngang : $kt_doc;
                    $chieu_rong_san_pham = $k > 1 ? $kt_doc : $kt_ngang;
                } elseif ($this->vi_tri_kep_nhip == 2) {
                    if ($kho_giay_rong > $kho_giay_dai) {
                        $chieu_dai_giay = $kho_giay_dai;
                        $chieu_rong_giay = $kho_giay_rong - $kep_nhip;
                    } else {
                        $chieu_dai_giay = $kho_giay_dai - $kep_nhip;
                        $chieu_rong_giay = $kho_giay_rong;
                    }

                    $so_hang_san_pham = $k > 0 ? intval($chieu_dai_giay / $kt_ngang) : intval($chieu_dai_giay / ($kt_doc + $nap_hop/2));
                    if($k==0){
                        $dai_thuc_te = $kt_doc * $so_hang_san_pham + ceil($so_hang_san_pham/2) * $nap_hop;
                        if($dai_thuc_te > $chieu_dai_giay)
                            $so_hang_san_pham = $so_hang_san_pham - 1;
                        $tru_them = ceil($so_hang_san_pham/2) * $nap_hop;
                    }
                    $chieu_dai_san_pham = $k > 0 ? $kt_ngang : $kt_doc;
                    $chieu_rong_san_pham = $k > 0 ? $kt_doc : $kt_ngang;
                } elseif ($this->vi_tri_kep_nhip == 3) {
                    if ($kho_giay_rong > $kho_giay_dai) {
                        $chieu_dai_giay = $kho_giay_dai - $kep_nhip;
                        $chieu_rong_giay = $kho_giay_rong;
                    } else {
                        $chieu_dai_giay = $kho_giay_dai;
                        $chieu_rong_giay = $kho_giay_rong - $kep_nhip;
                    }

                    $so_hang_san_pham = $k > 0 ? intval($chieu_dai_giay / $kt_ngang) : intval($chieu_dai_giay / ($kt_doc + $nap_hop/2));
                    if($k==0){
                        $dai_thuc_te = $kt_doc * $so_hang_san_pham + ceil($so_hang_san_pham/2) * $nap_hop;
                        if($dai_thuc_te > $chieu_dai_giay)
                            $so_hang_san_pham = $so_hang_san_pham - 1;
                        $tru_them = ceil($so_hang_san_pham/2) * $nap_hop;
                    }
                    $chieu_dai_san_pham = $k > 0 ? $kt_ngang : $kt_doc;
                    $chieu_rong_san_pham = $k > 0 ? $kt_doc : $kt_ngang;
                }

                $so_thanh_pham_tren_to = $so_hang_san_pham * $v;

                $tong_so_thanh_pham_tren_to[] = $so_thanh_pham_tren_to;
                $tong_so_hang_san_pham[] = $so_hang_san_pham;
                $tong_san_pham_tren_dong[] = $v;

                //tinh phan giay du
                $phan_giay_du_rong_1 = $chieu_rong_giay - $v * $chieu_rong_san_pham - $tru_them;
                $phan_giay_du_dai_1 = $chieu_dai_giay;
                $du_kho_giay[$k][] = array(
                    'width' => $phan_giay_du_rong_1,
                    'height' => $phan_giay_du_dai_1
                );

                $phan_giay_du_rong_2 = $v * $chieu_rong_san_pham;
                $phan_giay_du_dai_2 = $chieu_dai_giay - $so_hang_san_pham * $chieu_dai_san_pham;
                $du_kho_giay[$k][] = array(
                    'width' => $phan_giay_du_rong_2,
                    'height' => $phan_giay_du_dai_2
                );

            }
            if (!empty($tong_so_thanh_pham_tren_to)) {
                $maxIndex = array_search(max($tong_so_thanh_pham_tren_to), $tong_so_thanh_pham_tren_to);
                $so_thanh_pham_tren_to = $tong_so_thanh_pham_tren_to[$maxIndex];
                $san_pham_tren_1_dong = $tong_san_pham_tren_dong[$maxIndex];
                $hang_san_pham = $tong_so_hang_san_pham[$maxIndex];
                $du_kho_giay = $du_kho_giay[$maxIndex];

                if ($this->vi_tri_kep_nhip == 1 || $this->vi_tri_kep_nhip == null) {
                    if ($maxIndex % 2 == 0)
                        $vi_tri_kep = 3;
                    else
                        $vi_tri_kep = 2;
                } else {
                    $vi_tri_kep = $this->vi_tri_kep_nhip;
                }

                if ($maxIndex > 1 && ($this->vi_tri_kep_nhip == 1 || $this->vi_tri_kep_nhip == null))
                    $dung_chieu = 1;
                elseif ($maxIndex > 0 && ($this->vi_tri_kep_nhip == 2 || $this->vi_tri_kep_nhip == 3))
                    $dung_chieu = 1;

            }
        }
        return array(
            'so_pham_tren_tren_1_dong' => $san_pham_tren_1_dong,
            'hang_san_pham' => $hang_san_pham,
            'so_thanh_pham_tren_to' => $so_thanh_pham_tren_to,
            'dung_chieu' => $dung_chieu,
            'vi_tri_kep' => $vi_tri_kep,
            'dien_tich_con' => $du_kho_giay
        );
    }

    public function ThanHopCungDinhHinh($kho_giay_rong, $kho_giay_dai, $kep_nhip, $chieu_rong, $chieu_dai)
    {
        $tong_san_pham_tren_dong = $tong_so_hang_san_pham = $du_kho_giay = array();
        $kho_giay_rong = $kho_giay_rong - $this->bu_hao_noi_xen;
        $kho_giay_dai = $kho_giay_dai - $this->bu_hao_noi_xen;
        $boi_song = $this->gia_tri_boi_song_e;
        if ($this->boi_song_e == 1) {
            $kho_giay_rong = $kho_giay_rong - $boi_song;
            $kho_giay_dai = $kho_giay_dai - $boi_song;
        }
        $kt_ngang = $chieu_rong + $this->san_pham_cao * 2;
        $kt_doc = $chieu_dai + $this->san_pham_cao * 2;
//        print_r(array(
//            'chieu ngang' => $kt_ngang,
//            'chieu doc' => $kt_doc,
//            'nap' => $nap_hop,
//            'kho rong' => $kho_giay_rong,
//            'kho dai' => $kho_giay_dai
//        ));
        //kep nhip tu dong
        if ($this->vi_tri_kep_nhip == 1 || $this->vi_tri_kep_nhip == null) {
            //san pham dung chieu khep nhip ngang
            $so_san_pham_tren_1_dong[] = intval($kho_giay_rong / $kt_ngang);

            //san pham dung chieu khep nhip doc
            $so_san_pham_tren_1_dong[] = intval(($kho_giay_rong - $kep_nhip) / $kt_ngang);

            //san pham xoay ngang khep nhip ngang
            $so_san_pham_tren_1_dong[] = intval(($kho_giay_rong) / $kt_doc);

            //san pham xoay ngang khep nhip doc
            $so_san_pham_tren_1_dong[] = intval(($kho_giay_rong - $kep_nhip) / $kt_doc);

            //kep nhip ngang
        } elseif ($this->vi_tri_kep_nhip == 2) {
            if ($kho_giay_rong > $kho_giay_dai) {
                $so_san_pham_tren_1_dong[] = intval(($kho_giay_rong - $kep_nhip) / $kt_ngang);
                $so_san_pham_tren_1_dong[] = intval(($kho_giay_rong - $kep_nhip) / $kt_doc);
            } else {
                $so_san_pham_tren_1_dong[] = intval($kho_giay_rong / $kt_ngang);
                $so_san_pham_tren_1_dong[] = intval(($kho_giay_rong) / $kt_doc);
            }

            //kep nhip doc
        } elseif ($this->vi_tri_kep_nhip == 3) {
            if ($kho_giay_rong > $kho_giay_dai) {
                $so_san_pham_tren_1_dong[] = intval($kho_giay_rong / $kt_ngang);
                $so_san_pham_tren_1_dong[] = intval(($kho_giay_rong) / $kt_doc);
            } else {
                $so_san_pham_tren_1_dong[] = intval(($kho_giay_rong - $kep_nhip) / $kt_ngang);
                $so_san_pham_tren_1_dong[] = intval(($kho_giay_rong - $kep_nhip) / $kt_doc);
            }
        }
        $so_thanh_pham_tren_to = $san_pham_tren_1_dong = $hang_san_pham = $dung_chieu = 0;
        $vi_tri_kep = 1;
        //tong san pham sap xep trang
        if (!empty($so_san_pham_tren_1_dong)) {
            foreach ($so_san_pham_tren_1_dong as $k => $v) {
                $chieu_dai_giay = 0;
                $chieu_dai_san_pham = 0;
                $chieu_rong_giay = 0;
                $chieu_rong_san_pham = 0;
                if ($this->vi_tri_kep_nhip == 1 || $this->vi_tri_kep_nhip == null) {
                    $chieu_dai_giay = $k % 2 == 0 ? ($kho_giay_dai - $kep_nhip) : $kho_giay_dai;
                    $chieu_dai_giay = $k > 1 ? $chieu_dai_giay : $chieu_dai_giay;
                    $chieu_rong_giay = $k % 2 == 0 ? $kho_giay_rong : ($kho_giay_rong - $kep_nhip);
                    $chieu_rong_giay = $k > 1 ? $chieu_rong_giay : $chieu_rong_giay;
                    $chieu_dai_san_pham = $k > 1 ? $kt_ngang : $kt_doc;
                    $chieu_rong_san_pham = $k > 1 ? $kt_doc : $kt_ngang;
                } elseif ($this->vi_tri_kep_nhip == 2) {
                    if ($kho_giay_rong > $kho_giay_dai) {
                        $chieu_dai_giay = $kho_giay_dai;
                        $chieu_rong_giay = $kho_giay_rong - $kep_nhip;
                    } else {
                        $chieu_dai_giay = $kho_giay_dai - $kep_nhip;
                        $chieu_rong_giay = $kho_giay_rong;
                    }

                    $chieu_dai_san_pham = $k > 0 ? $kt_ngang : $kt_doc;
                    $chieu_rong_san_pham = $k > 0 ? $kt_doc : $kt_ngang;
                } elseif ($this->vi_tri_kep_nhip == 3) {
                    if ($kho_giay_rong > $kho_giay_dai) {
                        $chieu_dai_giay = $kho_giay_dai - $kep_nhip;
                        $chieu_rong_giay = $kho_giay_rong;
                    } else {
                        $chieu_dai_giay = $kho_giay_dai;
                        $chieu_rong_giay = $kho_giay_rong - $kep_nhip;
                    }

                    $chieu_dai_san_pham = $k > 0 ? $kt_ngang : $kt_doc;
                    $chieu_rong_san_pham = $k > 0 ? $kt_doc : $kt_ngang;
                }

                $so_hang_san_pham = intval($chieu_dai_giay / $chieu_dai_san_pham);

                $so_thanh_pham_tren_to = $so_hang_san_pham * $v;

                $tong_so_thanh_pham_tren_to[] = $so_thanh_pham_tren_to;
                $tong_so_hang_san_pham[] = $so_hang_san_pham;
                $tong_san_pham_tren_dong[] = $v;

                //tinh phan giay du
                $phan_giay_du_rong_1 = $chieu_rong_giay - $v * $chieu_rong_san_pham;
                $phan_giay_du_dai_1 = $chieu_dai_giay;
                $du_kho_giay[$k][] = array(
                    'width' => $phan_giay_du_rong_1,
                    'height' => $phan_giay_du_dai_1
                );

                $phan_giay_du_rong_2 = $v * $chieu_rong_san_pham;
                $phan_giay_du_dai_2 = $chieu_dai_giay - $so_hang_san_pham * $chieu_dai_san_pham;
                $du_kho_giay[$k][] = array(
                    'width' => $phan_giay_du_rong_2,
                    'height' => $phan_giay_du_dai_2
                );

            }
            if (!empty($tong_so_thanh_pham_tren_to)) {
                $maxIndex = array_search(max($tong_so_thanh_pham_tren_to), $tong_so_thanh_pham_tren_to);
                $so_thanh_pham_tren_to = $tong_so_thanh_pham_tren_to[$maxIndex];
                $san_pham_tren_1_dong = $tong_san_pham_tren_dong[$maxIndex];
                $hang_san_pham = $tong_so_hang_san_pham[$maxIndex];
                $du_kho_giay = $du_kho_giay[$maxIndex];

                if ($this->vi_tri_kep_nhip == 1 || $this->vi_tri_kep_nhip == null) {
                    if ($maxIndex % 2 == 0)
                        $vi_tri_kep = 3;
                    else
                        $vi_tri_kep = 2;
                } else {
                    $vi_tri_kep = $this->vi_tri_kep_nhip;
                }

                if ($maxIndex > 1 && ($this->vi_tri_kep_nhip == 1 || $this->vi_tri_kep_nhip == null))
                    $dung_chieu = 1;
                elseif ($maxIndex > 0 && ($this->vi_tri_kep_nhip == 2 || $this->vi_tri_kep_nhip == 3))
                    $dung_chieu = 1;

            }
        }
        return array(
            'so_pham_tren_tren_1_dong' => $san_pham_tren_1_dong,
            'hang_san_pham' => $hang_san_pham,
            'so_thanh_pham_tren_to' => $so_thanh_pham_tren_to,
            'dung_chieu' => $dung_chieu,
            'vi_tri_kep' => $vi_tri_kep,
            'dien_tich_con' => $du_kho_giay
        );
    }

    public function NapHopCungDinhHinh($kho_giay_rong, $kho_giay_dai, $kep_nhip, $chieu_rong, $chieu_dai)
    {
        $tong_san_pham_tren_dong = $tong_so_hang_san_pham = $du_kho_giay = array();
        $kho_giay_rong = $kho_giay_rong - $this->bu_hao_noi_xen;
        $kho_giay_dai = $kho_giay_dai - $this->bu_hao_noi_xen;
        $boi_song = $this->gia_tri_boi_song_e;
        if ($this->boi_song_e == 1) {
            $kho_giay_rong = $kho_giay_rong - $boi_song;
            $kho_giay_dai = $kho_giay_dai - $boi_song;
        }
        $kt_ngang = $chieu_rong + $this->kich_thuoc_nap * 2;
        $kt_doc = $chieu_dai + $this->kich_thuoc_nap * 2;
//        print_r(array(
//            'chieu ngang' => $kt_ngang,
//            'chieu doc' => $kt_doc,
//            'nap' => $nap_hop,
//            'kho rong' => $kho_giay_rong,
//            'kho dai' => $kho_giay_dai
//        ));
        //kep nhip tu dong
        if ($this->vi_tri_kep_nhip == 1 || $this->vi_tri_kep_nhip == null) {
            //san pham dung chieu khep nhip ngang
            $so_san_pham_tren_1_dong[] = intval($kho_giay_rong / $kt_ngang);

            //san pham dung chieu khep nhip doc
            $so_san_pham_tren_1_dong[] = intval(($kho_giay_rong - $kep_nhip) / $kt_ngang);

            //san pham xoay ngang khep nhip ngang
            $so_san_pham_tren_1_dong[] = intval(($kho_giay_rong) / $kt_doc);

            //san pham xoay ngang khep nhip doc
            $so_san_pham_tren_1_dong[] = intval(($kho_giay_rong - $kep_nhip) / $kt_doc);

            //kep nhip ngang
        } elseif ($this->vi_tri_kep_nhip == 2) {
            if ($kho_giay_rong > $kho_giay_dai) {
                $so_san_pham_tren_1_dong[] = intval(($kho_giay_rong - $kep_nhip) / $kt_ngang);
                $so_san_pham_tren_1_dong[] = intval(($kho_giay_rong - $kep_nhip) / $kt_doc);
            } else {
                $so_san_pham_tren_1_dong[] = intval($kho_giay_rong / $kt_ngang);
                $so_san_pham_tren_1_dong[] = intval(($kho_giay_rong) / $kt_doc);
            }

            //kep nhip doc
        } elseif ($this->vi_tri_kep_nhip == 3) {
            if ($kho_giay_rong > $kho_giay_dai) {
                $so_san_pham_tren_1_dong[] = intval($kho_giay_rong / $kt_ngang);
                $so_san_pham_tren_1_dong[] = intval(($kho_giay_rong) / $kt_doc);
            } else {
                $so_san_pham_tren_1_dong[] = intval(($kho_giay_rong - $kep_nhip) / $kt_ngang);
                $so_san_pham_tren_1_dong[] = intval(($kho_giay_rong - $kep_nhip) / $kt_doc);
            }
        }
        $so_thanh_pham_tren_to = $san_pham_tren_1_dong = $dung_chieu = $hang_san_pham = 0;
        $vi_tri_kep = 1;
        //tong san pham sap xep trang
        if (!empty($so_san_pham_tren_1_dong)) {
            foreach ($so_san_pham_tren_1_dong as $k => $v) {
                $chieu_dai_giay = 0;
                $chieu_dai_san_pham = 0;
                $chieu_rong_giay = 0;
                $chieu_rong_san_pham = 0;
                if ($this->vi_tri_kep_nhip == 1 || $this->vi_tri_kep_nhip == null) {
                    $chieu_dai_giay = $k % 2 == 0 ? ($kho_giay_dai - $kep_nhip) : $kho_giay_dai;
                    $chieu_dai_giay = $k > 1 ? $chieu_dai_giay : $chieu_dai_giay;
                    $chieu_rong_giay = $k % 2 == 0 ? $kho_giay_rong : ($kho_giay_rong - $kep_nhip);
                    $chieu_rong_giay = $k > 1 ? $chieu_rong_giay : $chieu_rong_giay;
                    $chieu_dai_san_pham = $k > 1 ? $kt_ngang : $kt_doc;
                    $chieu_rong_san_pham = $k > 1 ? $kt_doc : $kt_ngang;
                } elseif ($this->vi_tri_kep_nhip == 2) {
                    if ($kho_giay_rong > $kho_giay_dai) {
                        $chieu_dai_giay = $kho_giay_dai;
                        $chieu_rong_giay = $kho_giay_rong - $kep_nhip;
                    } else {
                        $chieu_dai_giay = $kho_giay_dai - $kep_nhip;
                        $chieu_rong_giay = $kho_giay_rong;
                    }
                    $chieu_dai_san_pham = $k > 0 ? $kt_ngang : $kt_doc;
                    $chieu_rong_san_pham = $k > 0 ? $kt_doc : $kt_ngang;
                } elseif ($this->vi_tri_kep_nhip == 3) {
                    if ($kho_giay_rong > $kho_giay_dai) {
                        $chieu_dai_giay = $kho_giay_dai - $kep_nhip;
                        $chieu_rong_giay = $kho_giay_rong;
                    } else {
                        $chieu_dai_giay = $kho_giay_dai;
                        $chieu_rong_giay = $kho_giay_rong - $kep_nhip;
                    }

                    $chieu_dai_san_pham = $k > 0 ? $kt_ngang : $kt_doc;
                    $chieu_rong_san_pham = $k > 0 ? $kt_doc : $kt_ngang;
                }

                $so_hang_san_pham = intval($chieu_dai_giay / $chieu_dai_san_pham);

                $so_thanh_pham_tren_to = $so_hang_san_pham * $v;

                $tong_so_thanh_pham_tren_to[] = $so_thanh_pham_tren_to;
                $tong_so_hang_san_pham[] = $so_hang_san_pham;
                $tong_san_pham_tren_dong[] = $v;

                //tinh phan giay du
                $phan_giay_du_rong_1 = $chieu_rong_giay - $v * $chieu_rong_san_pham;
                $phan_giay_du_dai_1 = $chieu_dai_giay;
                $du_kho_giay[$k][] = array(
                    'width' => $phan_giay_du_rong_1,
                    'height' => $phan_giay_du_dai_1
                );

                $phan_giay_du_rong_2 = $v * $chieu_rong_san_pham;
                $phan_giay_du_dai_2 = $chieu_dai_giay - $so_hang_san_pham * $chieu_dai_san_pham;
                $du_kho_giay[$k][] = array(
                    'width' => $phan_giay_du_rong_2,
                    'height' => $phan_giay_du_dai_2
                );

            }
            if (!empty($tong_so_thanh_pham_tren_to)) {
                $maxIndex = array_search(max($tong_so_thanh_pham_tren_to), $tong_so_thanh_pham_tren_to);
                $so_thanh_pham_tren_to = $tong_so_thanh_pham_tren_to[$maxIndex];
                $san_pham_tren_1_dong = $tong_san_pham_tren_dong[$maxIndex];
                $hang_san_pham = $tong_so_hang_san_pham[$maxIndex];
                $du_kho_giay = $du_kho_giay[$maxIndex];

                if ($this->vi_tri_kep_nhip == 1 || $this->vi_tri_kep_nhip == null) {
                    if ($maxIndex % 2 == 0)
                        $vi_tri_kep = 3;
                    else
                        $vi_tri_kep = 2;
                } else {
                    $vi_tri_kep = $this->vi_tri_kep_nhip;
                }

                if ($maxIndex > 1 && ($this->vi_tri_kep_nhip == 1 || $this->vi_tri_kep_nhip == null))
                    $dung_chieu = 1;
                elseif ($maxIndex > 0 && ($this->vi_tri_kep_nhip == 2 || $this->vi_tri_kep_nhip == 3))
                    $dung_chieu = 1;

            }
        }
        return array(
            'so_pham_tren_tren_1_dong' => $san_pham_tren_1_dong,
            'hang_san_pham' => $hang_san_pham,
            'so_thanh_pham_tren_to' => $so_thanh_pham_tren_to,
            'dung_chieu' => $dung_chieu,
            'vi_tri_kep' => $vi_tri_kep,
            'dien_tich_con' => $du_kho_giay
        );
    }

    public function random_color($k)
    {
        $arr_color = array(
            '91D4EA', 'A3EA91', 'EACB91', '85BCB0'
        );
        return $arr_color[$k];

        mt_srand((double)microtime() * 1000000);
        $c = '';
        while (strlen($c) < 6) {
            $c .= sprintf("%02X", mt_rand(0, 255));
        }
        return $c;
    }

    public function CalculatorProduct($kho_giay_rong, $kho_giay_dai, $kep_nhip, $chieu_rong, $chieu_dai, $type = 'gb')
    {
        if ($this->vi_tri_kep_nhip == 1 || $this->vi_tri_kep_nhip == null) {
            //san pham dung chieu khep nhip ngang
            $so_san_pham_tren_1_dong[] = intval($kho_giay_rong / $chieu_rong);
            //san pham dung chieu khep nhip doc
            $so_san_pham_tren_1_dong[] = intval(($kho_giay_rong - $kep_nhip) / $chieu_rong);
            //san pham xoay ngang khep nhip ngang
            $so_san_pham_tren_1_dong[] = intval($kho_giay_rong / $chieu_dai);
            //san pham xoay ngang khep nhip doc
            $so_san_pham_tren_1_dong[] = intval(($kho_giay_rong - $kep_nhip) / $chieu_dai);
        } elseif ($this->vi_tri_kep_nhip == 2) {
            if ($kho_giay_rong > $kho_giay_dai) {
                $so_san_pham_tren_1_dong[] = intval(($kho_giay_rong - $kep_nhip) / $chieu_rong);
                $so_san_pham_tren_1_dong[] = intval(($kho_giay_rong - $kep_nhip) / $chieu_dai);
            } else {
                $so_san_pham_tren_1_dong[] = intval($kho_giay_rong / $chieu_rong);
                $so_san_pham_tren_1_dong[] = intval($kho_giay_rong / $chieu_dai);
            }
        } elseif ($this->vi_tri_kep_nhip == 3) {
            if ($kho_giay_rong > $kho_giay_dai) {
                $so_san_pham_tren_1_dong[] = intval($kho_giay_rong / $chieu_rong);
                $so_san_pham_tren_1_dong[] = intval($kho_giay_rong / $chieu_dai);
            } else {
                $so_san_pham_tren_1_dong[] = intval(($kho_giay_rong - $kep_nhip) / $chieu_rong);
                $so_san_pham_tren_1_dong[] = intval(($kho_giay_rong - $kep_nhip) / $chieu_dai);
            }
        }
        $du_kho_giay = array();
        $so_thanh_pham_tren_to = 0;
        $vi_tri_kep = 1;
        $so_pham_tren_1_dong = 0;
        $dung_chieu = 0;
        //tong san pham sap xep trang
        $so_san_pham_tren_1_dong = @array_values($so_san_pham_tren_1_dong);
        if (!empty($so_san_pham_tren_1_dong)) {
            foreach ($so_san_pham_tren_1_dong as $k => $v) {
                $chieu_dai_giay = 0;
                $chieu_rong_giay = 0;
                $chieu_dai_san_pham = 0;
                $chieu_rong_san_pham = 0;
                if ($this->vi_tri_kep_nhip == 1 || $this->vi_tri_kep_nhip == null) {
                    $chieu_rong_giay = $k % 2 == 0 ? $kho_giay_rong : ($kho_giay_rong - $kep_nhip);
                    $chieu_dai_giay = $k % 2 == 0 ? ($kho_giay_dai - $kep_nhip) : $kho_giay_dai;
                    $chieu_rong_san_pham = $k > 1 ? $chieu_dai : $chieu_rong;
                    $chieu_dai_san_pham = $k > 1 ? $chieu_rong : $chieu_dai;
                } elseif ($this->vi_tri_kep_nhip == 2) {
                    if ($kho_giay_rong > $kho_giay_dai) {
                        $chieu_rong_giay = $kho_giay_rong - $kep_nhip;
                        $chieu_dai_giay = $kho_giay_dai;
                    } else {
                        $chieu_rong_giay = $kho_giay_rong;
                        $chieu_dai_giay = $kho_giay_dai - $kep_nhip;
                    }
                    $chieu_rong_san_pham = $k > 0 ? $chieu_dai : $chieu_rong;
                    $chieu_dai_san_pham = $k > 0 ? $chieu_rong : $chieu_dai;
                } elseif ($this->vi_tri_kep_nhip == 3) {
                    if ($kho_giay_rong > $kho_giay_dai) {
                        $chieu_rong_giay = $kho_giay_rong;
                        $chieu_dai_giay = $kho_giay_dai - $kep_nhip;
                    } else {
                        $chieu_rong_giay = $kho_giay_rong - $kep_nhip;
                        $chieu_dai_giay = $kho_giay_dai;
                    }
                    $chieu_rong_san_pham = $k > 0 ? $chieu_dai : $chieu_rong;
                    $chieu_dai_san_pham = $k > 0 ? $chieu_rong : $chieu_dai;
                }

                $so_hang_san_pham = intval($chieu_dai_giay / $chieu_dai_san_pham);
                if ($type == 'gr' && $v % 2 !== 0 && $so_hang_san_pham % 2 !== 0)
                    $so_hang_san_pham = $so_hang_san_pham - 1;
                $so_thanh_pham_tren_to = $so_hang_san_pham * $v;
                if($type == 'gr') {
                    if($this->kieu_dong == OrderInfo::DONG_GIUA && $so_thanh_pham_tren_to % 4 != 0){
                        $div_4 = floor($so_thanh_pham_tren_to / 4);
                        $so_thanh_pham_tren_to = $div_4 * 4;
                    }
                    elseif ($so_thanh_pham_tren_to % 6 != 0 && $so_thanh_pham_tren_to % 4 != 0 && $so_thanh_pham_tren_to > 4) {
                        $div_6 = floor($so_thanh_pham_tren_to / 6);
                        $div_4 = floor($so_thanh_pham_tren_to / 4);
                        if ($div_6 * 6 > $div_4 * 4) {
                            $so_thanh_pham_tren_to = $div_6 * 6;
                        } else {
                            $so_thanh_pham_tren_to = $div_4 * 4;
                        }
                    }
                }
                if($this->tay_cuoi == 1 && $so_thanh_pham_tren_to < $this->bat_le)
                    $so_thanh_pham_tren_to = 0;
                elseif($this->tay_cuoi == 1 && $so_thanh_pham_tren_to >= $this->bat_le && $this->bat_le > 0){
                    $so_thanh_pham_tren_to = $this->bat_le * floor($so_thanh_pham_tren_to / $this->bat_le);
                }
                $tong_so_thanh_pham_tren_to[] = $so_thanh_pham_tren_to;
                $san_pham_tren_dong[] = $v;

                //tinh phan giay du
                $phan_giay_du_rong_1 = $chieu_rong_giay - $v * $chieu_rong_san_pham;
                $phan_giay_du_dai_1 = $chieu_dai_giay;
                $du_kho_giay[$k][] = array(
                    'width' => $phan_giay_du_rong_1,
                    'height' => $phan_giay_du_dai_1
                );

                $phan_giay_du_rong_2 = $v * $chieu_rong_san_pham;
                $phan_giay_du_dai_2 = $chieu_dai_giay - $so_hang_san_pham * $chieu_dai_san_pham;
                $du_kho_giay[$k][] = array(
                    'width' => $phan_giay_du_rong_2,
                    'height' => $phan_giay_du_dai_2
                );
            }
            if (!empty($tong_so_thanh_pham_tren_to)) {
                $maxIndex = array_search(max($tong_so_thanh_pham_tren_to), $tong_so_thanh_pham_tren_to);
                $so_thanh_pham_tren_to = $tong_so_thanh_pham_tren_to[$maxIndex];
                $so_pham_tren_1_dong = $so_san_pham_tren_1_dong[$maxIndex];
                $du_kho_giay = $du_kho_giay[$maxIndex];

                if ($this->vi_tri_kep_nhip == 1 || $this->vi_tri_kep_nhip == null) {
                    if ($maxIndex % 2 == 0)
                        $vi_tri_kep = 3;
                    else
                        $vi_tri_kep = 2;
                } else {
                    $vi_tri_kep = $this->vi_tri_kep_nhip;
                }

                if ($maxIndex > 1 && ($this->vi_tri_kep_nhip == 1 || $this->vi_tri_kep_nhip == null))
                    $dung_chieu = 1;
                elseif ($maxIndex > 0 && ($this->vi_tri_kep_nhip == 2 || $this->vi_tri_kep_nhip == 3))
                    $dung_chieu = 1;

            }
        }
        return array(
            'so_pham_tren_tren_1_dong' => $so_pham_tren_1_dong,
            'so_thanh_pham_tren_to' => $so_thanh_pham_tren_to,
            'dung_chieu' => $dung_chieu,
            'vi_tri_kep' => $vi_tri_kep,
            'dien_tich_con' => $du_kho_giay
        );
    }

    public function CalculatorProductEar($kho_giay_rong, $kho_giay_dai, $kep_nhip, $san_pham_rong, $san_pham_dai, $tai_rong, $tai_dai, $vi_tri_tai)
    {

        $so_san_pham_tren_1_dong_ = $so_san_pham_tren_1_dong = array();
        $chieu_rong = $san_pham_rong + ($vi_tri_tai == 2 ? $tai_rong / 2 : 0);
        $chieu_dai = $san_pham_dai + ($vi_tri_tai == 1 ? $tai_dai / 2 : 0);

        if ($this->vi_tri_kep_nhip == 1 || $this->vi_tri_kep_nhip == null || ($this->vi_tri_kep_nhip == 2 && $kho_giay_rong < $kho_giay_dai) || ($this->vi_tri_kep_nhip == 3 && $kho_giay_rong > $kho_giay_dai)) {
            //san pham dung chieu khep nhip ngang
            $san_pham_tren_1_dong = intval($kho_giay_rong / $chieu_rong);
            $width = $kho_giay_rong - $chieu_rong * $san_pham_tren_1_dong;
            if ($san_pham_tren_1_dong % 2 != 0 && $vi_tri_tai == 2) {
                $chieu_rong_thuc_te = $san_pham_rong * $san_pham_tren_1_dong + ($san_pham_tren_1_dong + 1) / 2 * $tai_rong;
                if ($chieu_rong_thuc_te > $kho_giay_rong) {
                    $san_pham_tren_1_dong = $san_pham_tren_1_dong - 1;
                    $width = $kho_giay_rong - ($san_pham_rong * $san_pham_tren_1_dong + ($san_pham_tren_1_dong + 1) / 2 * $tai_rong);
                } else {
                    $width = $kho_giay_rong - $chieu_rong_thuc_te;
                }
            }
            $so_san_pham_tren_1_dong[0] = $san_pham_tren_1_dong;
            if ($width > 0)
                $du_kho_giay_[0][0] = array('width' => $width, 'height' => $kho_giay_dai);
            $du_kho_giay_[0][1]['width'] = $san_pham_rong * $san_pham_tren_1_dong + ($vi_tri_tai == 2 ? ($san_pham_tren_1_dong + 1) / 2 * $tai_rong : 0);
        }

        if ($this->vi_tri_kep_nhip == 1 || $this->vi_tri_kep_nhip == null || ($this->vi_tri_kep_nhip == 2 && $kho_giay_rong > $kho_giay_dai) || ($this->vi_tri_kep_nhip == 3 && $kho_giay_rong < $kho_giay_dai)) {
            //san pham dung chieu khep nhip doc
            $san_pham_tren_1_dong = intval(($kho_giay_rong - $kep_nhip) / $chieu_rong);
            $width = $kho_giay_rong - $kep_nhip - $chieu_rong * $san_pham_tren_1_dong;
            if ($san_pham_tren_1_dong % 2 != 0 && $vi_tri_tai == 2) {
                $chieu_rong_thuc_te = $san_pham_rong * $san_pham_tren_1_dong + ($san_pham_tren_1_dong + 1) / 2 * $tai_rong;
                if ($chieu_rong_thuc_te > $kho_giay_rong - $kep_nhip) {
                    $san_pham_tren_1_dong = $san_pham_tren_1_dong - 1;
                    $width = $kho_giay_rong - $kep_nhip - ($san_pham_rong * $san_pham_tren_1_dong + ($san_pham_tren_1_dong + 1) / 2 * $tai_rong);
                } else {
                    $width = $kho_giay_rong - $kep_nhip - $chieu_rong_thuc_te;
                }
            }
            $so_san_pham_tren_1_dong[1] = $san_pham_tren_1_dong;
            if ($width > 0)
                $du_kho_giay_[1][0] = array('width' => $width, 'height' => $kho_giay_dai);
            $du_kho_giay_[1][1]['width'] = $san_pham_rong * $san_pham_tren_1_dong + ($vi_tri_tai == 2 ? ($san_pham_tren_1_dong + 1) / 2 * $tai_rong : 0);
        }

        if ($this->vi_tri_kep_nhip == 1 || $this->vi_tri_kep_nhip == null || ($this->vi_tri_kep_nhip == 2 && $kho_giay_rong < $kho_giay_dai) || ($this->vi_tri_kep_nhip == 3 && $kho_giay_rong > $kho_giay_dai)) {
            //san pham xoay ngang khep nhip ngang
            $san_pham_tren_1_dong = intval($kho_giay_rong / $chieu_dai);
            $width = $kho_giay_rong - $chieu_dai * $san_pham_tren_1_dong;
            if ($san_pham_tren_1_dong % 2 != 0 && $vi_tri_tai == 1) {
                $chieu_rong_thuc_te = $san_pham_dai * $san_pham_tren_1_dong + ($san_pham_tren_1_dong + 1) / 2 * $tai_dai;
                if ($chieu_rong_thuc_te > $kho_giay_rong) {
                    $san_pham_tren_1_dong = $san_pham_tren_1_dong - 1;
                    $width = $kho_giay_rong - ($san_pham_dai * $san_pham_tren_1_dong + ($san_pham_tren_1_dong + 1) / 2 * $tai_dai);
                } else {
                    $width = $kho_giay_rong - $chieu_rong_thuc_te;
                }
            }
            $so_san_pham_tren_1_dong[2] = $san_pham_tren_1_dong;
            if ($width > 0)
                $du_kho_giay_[2][0] = array('width' => $width, 'height' => $kho_giay_dai);
            $du_kho_giay_[2][1]['width'] = $san_pham_dai * $san_pham_tren_1_dong + ($vi_tri_tai == 1 ? ($san_pham_tren_1_dong + 1) / 2 * $tai_dai : 0);
        }

        if ($this->vi_tri_kep_nhip == 1 || $this->vi_tri_kep_nhip == null || ($this->vi_tri_kep_nhip == 2 && $kho_giay_rong > $kho_giay_dai) || ($this->vi_tri_kep_nhip == 3 && $kho_giay_rong < $kho_giay_dai)) {
            //san pham xoay ngang khep nhip doc
            $san_pham_tren_1_dong = intval(($kho_giay_rong - $kep_nhip) / $chieu_dai);
            $width = $kho_giay_rong - $kep_nhip - $chieu_dai * $san_pham_tren_1_dong;
            if ($san_pham_tren_1_dong % 2 != 0 && $vi_tri_tai == 1) {
                $chieu_rong_thuc_te = $san_pham_dai * $san_pham_tren_1_dong + ($san_pham_tren_1_dong + 1) / 2 * $tai_dai;
                if ($chieu_rong_thuc_te > $kho_giay_rong - $kep_nhip) {
                    $san_pham_tren_1_dong = $san_pham_tren_1_dong - 1;
                    $width = $kho_giay_rong - ($san_pham_dai * $san_pham_tren_1_dong + ($san_pham_tren_1_dong + 1) / 2 * $tai_dai);
                } else {
                    $width = $kho_giay_rong - $chieu_rong_thuc_te;
                }
            }
            $so_san_pham_tren_1_dong[3] = $san_pham_tren_1_dong;
            if ($width > 0)
                $du_kho_giay_[3][0] = array('width' => $width, 'height' => $kho_giay_dai);
            $du_kho_giay_[3][1]['width'] = $san_pham_dai * $san_pham_tren_1_dong + ($vi_tri_tai == 1 ? ($san_pham_tren_1_dong + 1) / 2 * $tai_dai : 0);
        }

        $dung_chieu = $so_pham_tren_1_dong = 0;
        $du_kho_giay = $so_thanh_pham_tren_trang = array();
        $so_san_pham_tren_1_dong = @array_values($so_san_pham_tren_1_dong);
        if (!empty($so_san_pham_tren_1_dong)) {
            foreach ($so_san_pham_tren_1_dong as $k => $v) {
                $kho_giay_dai_ = $kho_giay_rong_ = $chieu_dai_san_pham = 0;
                if($this->vi_tri_kep_nhip == 1 || $this->vi_tri_kep_nhip == null) {
                    $kho_giay_dai_ = $k % 2 == 0 ? $kho_giay_dai - $kep_nhip : $kho_giay_dai;
                    $chieu_dai_san_pham = $k % 2 != 0 ? $chieu_rong : $chieu_dai;
                }elseif($this->vi_tri_kep_nhip == 2){
                    if ($kho_giay_rong > $kho_giay_dai) {
                        $kho_giay_dai_ = $kho_giay_dai;
                        $chieu_dai_san_pham = $k == 0 ? $chieu_dai : $chieu_rong;
                    }else{
                        $kho_giay_dai_ = $kho_giay_dai - $kep_nhip;
                        $chieu_dai_san_pham = $k == 0 ? $chieu_rong : $chieu_dai;
                    }
                }elseif($this->vi_tri_kep_nhip == 3){
                    if ($kho_giay_rong > $kho_giay_dai) {
                        $kho_giay_dai_ = $kho_giay_dai - $kep_nhip;
                        $chieu_dai_san_pham = $k == 0 ? $chieu_rong : $chieu_dai;
                    }else{
                        $kho_giay_dai_ = $kho_giay_dai;
                        $chieu_dai_san_pham = $k == 0 ? $chieu_dai : $chieu_rong;
                    }
                }

                $so_hang_san_pham = intval($kho_giay_dai_ / $chieu_dai_san_pham);

                if ($so_hang_san_pham % 2 != 0 && $this->tai_san_pham == 1) {
                    $nhan_them_tai = $this->tai_san_pham == 1 ? $tai_dai : $tai_rong;
                    $san_pham_dai_ = $k % 2 == 0 ? $san_pham_dai : $san_pham_rong;
                    $chieu_dai_thuc_te = $so_hang_san_pham * $san_pham_dai_ + ($so_hang_san_pham + 1) / 2 * $nhan_them_tai;
                    if ($chieu_dai_thuc_te > $kho_giay_dai_) {
                        $so_hang_san_pham = $so_hang_san_pham - 1;
                        $height = $kho_giay_dai_ - ($so_hang_san_pham * $san_pham_dai_ + ($so_hang_san_pham + 1) / 2 * $nhan_them_tai);
                    } else {
                        $height = $kho_giay_dai_ - $chieu_dai_thuc_te;
                    }
                }else {
                    $height = $kho_giay_dai_ - $so_hang_san_pham * $chieu_dai_san_pham;
                }
                $du_kho_giay_[$k][1]['height'] = $height;

                //tinh phan giay du
                $phan_giay_du_rong_1 = $du_kho_giay_[$k][0]['width'];
                $phan_giay_du_dai_1 = $du_kho_giay_[$k][0]['height'];
                $du_kho_giay[$k][] = array(
                    'width' => $phan_giay_du_rong_1,
                    'height' => $phan_giay_du_dai_1
                );

                $phan_giay_du_rong_2 = $du_kho_giay_[$k][1]['width'];
                $phan_giay_du_dai_2 = $du_kho_giay_[$k][1]['height'];
                $du_kho_giay[$k][] = array(
                    'width' => $phan_giay_du_rong_2,
                    'height' => $phan_giay_du_dai_2
                );

                $so_thanh_pham_tren_trang[] = $so_hang_san_pham * $v;
                $so_san_pham_tren_1_dong_[] = $v;
            }
        }
        $so_thanh_pham_tren_to = 0;
        $vi_tri_kep = 0;
        if (!empty($so_thanh_pham_tren_trang)) {
            $maxIndex = array_search(max($so_thanh_pham_tren_trang), $so_thanh_pham_tren_trang);
            $so_thanh_pham_tren_to = $so_thanh_pham_tren_trang[$maxIndex];
            $so_pham_tren_1_dong = $so_san_pham_tren_1_dong_[$maxIndex];
            $du_kho_giay = $du_kho_giay[$maxIndex];

            if ($this->vi_tri_kep_nhip == 1 || $this->vi_tri_kep_nhip == null) {
                if ($maxIndex % 2 == 0)
                    $vi_tri_kep = 3;
                else
                    $vi_tri_kep = 2;
            } else {
                $vi_tri_kep = $this->vi_tri_kep_nhip;
            }

            if ($maxIndex > 1 && ($this->vi_tri_kep_nhip == 1 || $this->vi_tri_kep_nhip == null))
                $dung_chieu = 1;
            elseif ($maxIndex > 0 && ($this->vi_tri_kep_nhip == 2 || $this->vi_tri_kep_nhip == 3))
                $dung_chieu = 1;
        }
        return array(
            'so_pham_tren_tren_1_dong' => $so_pham_tren_1_dong,
            'so_thanh_pham_tren_to' => $so_thanh_pham_tren_to,
            'dung_chieu' => $dung_chieu,
            'vi_tri_kep' => $vi_tri_kep,
            'dien_tich_con' => $du_kho_giay
        );
    }

    public function Calculator()
    {
        if (
            $this->so_luong_san_pham == 0 || $this->kho_giay_rong == 0 || $this->kho_giay_dai == 0 || $this->san_pham_rong == 0 || $this->san_pham_dai == 0
        )
            return false;

        //###########################################################

        $type = isset($_GET['type']) ? trim($_GET['type']) : '';
        //neu khong truyen vao kho giay
        $array_return = array();
        if (empty($this->so_du_kho_giay)) {
            //neu san pham hop
            if($this->loai_san_pham == Products::SAN_PHAM_HOP) {
                if ($this->kieu_hop == Products::HOP_CAI_DAY) {
                    $array_return[] = self::HopCaiDay($this->kho_giay_rong, $this->kho_giay_dai, $this->kep_nhip, $this->san_pham_rong, $this->san_pham_dai);
                } elseif ($this->kieu_hop == Products::HOP_MOC_DAY) {
                    $array_return[] = self::HopMocDay($this->kho_giay_rong, $this->kho_giay_dai, $this->kep_nhip, $this->san_pham_rong, $this->san_pham_dai);
                } elseif ($this->kieu_hop == Products::HOP_CUNG_DINH_HINH) {
                    if ($this->nap_hop == 1)
                        $array_return[] = self::NapHopCungDinhHinh($this->kho_giay_rong, $this->kho_giay_dai, $this->kep_nhip, $this->san_pham_rong, $this->san_pham_dai);
                    else
                        $array_return[] = self::ThanHopCungDinhHinh($this->kho_giay_rong, $this->kho_giay_dai, $this->kep_nhip, $this->san_pham_rong, $this->san_pham_dai);
                }
            }elseif($this->loai_san_pham == Products::SAN_PHAM_TUI_GIAY){

                $san_pham_dai = $this->san_pham_cao + $this->tai_nap + $this->san_pham_rong - $this->kich_thuoc_cai_day;
                $san_pham_rong = $this->san_pham_dai * 2 + $this->san_pham_rong * 2 + $this->kich_thuoc_tai;
                $array_return[] = self::CalculatorProduct($this->kho_giay_rong, $this->kho_giay_dai, $this->kep_nhip, $san_pham_rong, $san_pham_dai, $type);

                //neu san pham phong bi
            }elseif($this->loai_san_pham == Products::SAN_PHAM_PHONG_BI){
                $san_pham_rong = $this->san_pham_rong + $this->kich_thuoc_tai * 2;
                $san_pham_dai = $this->san_pham_dai * 2 + $this->tai_nap - $this->tru_mat_sau_pb;
                $array_return[] = self::CalculatorProduct($this->kho_giay_rong, $this->kho_giay_dai, $this->kep_nhip, $san_pham_rong, $san_pham_dai, $type);
            }
            //neu la giay bia va san pham co tai
            elseif ($this->tai_san_pham == 1 && $type == 'gb'&& $this->tai_dai > 0 && $this->tai_rong > 0) {

                //neu san pham co tai ngang
                if ($this->vi_tri_tai == 1) {

                    //neu tai rong > 1/2 chieu rong san pham thi ko xep quay dau duoc
                    if ($this->tai_rong > $this->san_pham_rong / 2) {

                        $san_pham_rong = $this->san_pham_rong;
                        $san_pham_dai = $this->san_pham_dai + $this->tai_dai;
                        $array_return[] = self::CalculatorProduct($this->kho_giay_rong, $this->kho_giay_dai, $this->kep_nhip, $san_pham_rong, $san_pham_dai);

                    } else {

                        $array_return[] = self::CalculatorProductEar($this->kho_giay_rong, $this->kho_giay_dai, $this->kep_nhip, $this->san_pham_rong, $this->san_pham_dai, $this->tai_rong, $this->tai_dai, $this->vi_tri_tai);

                    }

                    //neu san pham co tai doc
                } else {

                    $san_pham_rong = $this->san_pham_rong + $this->tai_rong;
                    $san_pham_dai = $this->san_pham_dai;

                    //neu tai dai > 1/2 chieu dai san pham thi ko xep quay dau duoc
                    if ($this->tai_dai > $this->san_pham_dai / 2) {
                        $array_return[] = self::CalculatorProduct($this->kho_giay_rong, $this->kho_giay_dai, $this->kep_nhip, $san_pham_rong, $san_pham_dai);
                    } else {
                        $array_return[] = self::CalculatorProductEar($this->kho_giay_rong, $this->kho_giay_dai, $this->kep_nhip, $this->san_pham_rong, $this->san_pham_dai, $this->tai_rong, $this->tai_dai, $this->vi_tri_tai);
                    }

                }

                //neu san pham khong co tai
            } else {
                $array_return[] = self::CalculatorProduct($this->kho_giay_rong, $this->kho_giay_dai, $this->kep_nhip, $this->san_pham_rong, $this->san_pham_dai, $type);
            }
            //truong hop don hang ghep truyen vao phan du kho giay
        } else {
            foreach ($this->so_du_kho_giay as $k => $kho_giay) {
                $array_return[$k] = self::CalculatorProduct($kho_giay['width'], $kho_giay['height'], 0, $this->san_pham_rong, $this->san_pham_dai);
            }
        }
        return $array_return;
    }

}