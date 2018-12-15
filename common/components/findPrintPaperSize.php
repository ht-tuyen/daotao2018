<?php
namespace common\components;

use backend\models\Products;
use backend\models\OrderInfo;

class findPrintPaperSize
{
    var $kho_giay_rong = 0; //chieu rong kho giay
    var $kho_giay_dai = 0; //chieu dai kho giay
    var $so_san_pham_tren_1_dong = 0;
    var $loai_giay = 'gb';
    var $nap_hop = 0;

    public $inputs;

    public function HopCaiDay($kho_giay_rong, $kho_giay_dai, $kep_nhip, $chieu_rong, $chieu_dai)
    {
        $tong_so_thanh_pham_tren_to = $so_san_pham_tren_1_dong = [];
        $nap_hop = $chieu_rong + $this->inputs->taiNap;
        $tai_hop = $this->inputs->kichThuocTai;
        $kho_giay_rong = $kho_giay_rong - $this->inputs->buHaoNoiXen;
        $kho_giay_dai = $kho_giay_dai - $this->inputs->buHaoNoiXen;
        $boi_song = $this->inputs->giaTriBoiSongE;
        if ($this->inputs->boiSongE == 1) {
            $kho_giay_rong = $kho_giay_rong - $boi_song;
            $kho_giay_dai = $kho_giay_dai - $boi_song;
        }
        $kt_ngang = ($chieu_dai + $chieu_rong) * 2 + $tai_hop;
        $kt_doc = $this->inputs->cao + $nap_hop;

//        print_r([
//            'chieu ngang' => $kt_ngang,
//            'chieu doc' => $kt_doc,
//            'nap' => $nap_hop,
//            'kho rong' => $kho_giay_rong,
//            'kho dai' => $kho_giay_dai
//        ]);

        //kep nhip tu dong
        if ($this->inputs->viTriNhip == 1 || $this->inputs->viTriNhip == null) {
            //san pham dung chieu khep nhip ngang
            $so_san_pham_tren_1_dong[] = intval($kho_giay_rong / $kt_ngang);

            //san pham dung chieu khep nhip doc
            $so_san_pham_tren_1_dong[] = intval(($kho_giay_rong - $kep_nhip) / $kt_ngang);

            //san pham xoay ngang khep nhip ngang
            $so_san_pham_tren_1_dong[] = intval(($kho_giay_rong - $nap_hop) / $kt_doc);

            //san pham xoay ngang khep nhip doc
            $so_san_pham_tren_1_dong[] = intval(($kho_giay_rong - $kep_nhip - $nap_hop) / $kt_doc);

            //kep nhip ngang
        } elseif ($this->inputs->viTriNhip == 2) {
            if ($kho_giay_rong > $kho_giay_dai) {
                $so_san_pham_tren_1_dong[] = intval(($kho_giay_rong - $kep_nhip) / $kt_ngang);
                $so_san_pham_tren_1_dong[] = intval(($kho_giay_rong - $kep_nhip - $nap_hop) / $kt_doc);
            } else {
                $so_san_pham_tren_1_dong[] = intval($kho_giay_rong / $kt_ngang);
                $so_san_pham_tren_1_dong[] = intval(($kho_giay_rong - $nap_hop) / $kt_doc);
            }

            //kep nhip doc
        } elseif ($this->inputs->viTriNhip == 3) {
            if ($kho_giay_rong > $kho_giay_dai) {
                $so_san_pham_tren_1_dong[] = intval($kho_giay_rong / $kt_ngang);
                $so_san_pham_tren_1_dong[] = intval(($kho_giay_rong - $nap_hop) / $kt_doc);
            } else {
                $so_san_pham_tren_1_dong[] = intval(($kho_giay_rong - $kep_nhip) / $kt_ngang);
                $so_san_pham_tren_1_dong[] = intval(($kho_giay_rong - $kep_nhip - $nap_hop) / $kt_doc);
            }
        }

        //tong san pham sap xep trang
        $so_san_pham_tren_1_dong = @array_values($so_san_pham_tren_1_dong);
        if (!empty($so_san_pham_tren_1_dong)) {
            foreach ($so_san_pham_tren_1_dong as $k => $v) {
                $chieu_dai_san_pham = 0;
                $chieu_dai_giay = 0;
                if ($this->inputs->viTriNhip == 1 || $this->inputs->viTriNhip == null) {
                    $chieu_dai_giay = $k % 2 == 0 ? ($kho_giay_dai - $kep_nhip) : $kho_giay_dai;
                    $chieu_dai_giay = $k > 1 ? $chieu_dai_giay - $nap_hop : $chieu_dai_giay;
                    $chieu_dai_san_pham = $k > 1 ? $kt_ngang : $kt_doc;
                } elseif ($this->inputs->viTriNhip == 2) {
                    if ($kho_giay_rong > $kho_giay_dai) {
                        $chieu_dai_giay = $kho_giay_dai;
                    } else {
                        $chieu_dai_giay = $kho_giay_dai - $kep_nhip;
                    }
                    if ($k == 0)
                        $chieu_dai_giay = $chieu_dai_giay - $nap_hop;
                    $chieu_dai_san_pham = $k > 0 ? $kt_ngang : $kt_doc;
                } elseif ($this->inputs->viTriNhip == 3) {
                    if ($kho_giay_rong > $kho_giay_dai) {
                        $chieu_dai_giay = $kho_giay_dai - $kep_nhip;
                    } else {
                        $chieu_dai_giay = $kho_giay_dai;
                    }
                    if ($k == 0)
                        $chieu_dai_giay = $chieu_dai_giay - $nap_hop;
                    $chieu_dai_san_pham = $k > 0 ? $kt_ngang : $kt_doc;
                }

                $so_hang_san_pham = intval($chieu_dai_giay / $chieu_dai_san_pham);

                $so_thanh_pham_tren_to = $so_hang_san_pham * $v;

                $tong_so_thanh_pham_tren_to[] = $so_thanh_pham_tren_to;

            }
//            if (!empty($tong_so_thanh_pham_tren_to)) {
//                $maxIndex = array_search(max($tong_so_thanh_pham_tren_to), $tong_so_thanh_pham_tren_to);
//                $so_thanh_pham_tren_to = $tong_so_thanh_pham_tren_to[$maxIndex];
//            }
        }
        return $tong_so_thanh_pham_tren_to;
    }

    public function HopMocDay($kho_giay_rong, $kho_giay_dai, $kep_nhip, $chieu_rong, $chieu_dai)
    {
        $tong_so_thanh_pham_tren_to = $so_san_pham_tren_1_dong = [];
        $nap_hop = $chieu_rong + $this->inputs->taiNap;
        $tai_hop = $this->inputs->kichThuocTai;
        $kho_giay_rong = $kho_giay_rong - $this->inputs->buHaoNoiXenp;
        $kho_giay_dai = $kho_giay_dai - $this->inputs->buHaoNoiXenp;
        $boi_song = $this->inputs->giaTriBoiSongE;
        if ($this->inputs->boiSongE == 1) {
            $kho_giay_rong = $kho_giay_rong - $boi_song;
            $kho_giay_dai = $kho_giay_dai - $boi_song;
        }

        $day_hop = $chieu_rong / 2 + $this->inputs->kichThuocCaiDay;
        $kt_ngang = ($chieu_dai + $chieu_rong) * 2 + $tai_hop;
        $kt_doc = $this->inputs->cao + $day_hop;
        //kep nhip tu dong
        if ($this->inputs->viTriNhip == 1 || $this->inputs->viTriNhip == null) {
            //san pham dung chieu khep nhip ngang
            $so_san_pham_tren_1_dong[] = intval($kho_giay_rong / $kt_ngang);

            //san pham dung chieu khep nhip doc
            $so_san_pham_tren_1_dong[] = intval(($kho_giay_rong - $kep_nhip) / $kt_ngang);

            //san pham xoay ngang khep nhip ngang
            $so_san_pham_tren_1_dong[] = intval($kho_giay_rong / ($kt_doc + $nap_hop / 2));
            $rong_thuc_te = $kt_doc * $so_san_pham_tren_1_dong[2] + ceil($so_san_pham_tren_1_dong[2] / 2) * $nap_hop;
            if ($rong_thuc_te > $kho_giay_rong)
                $so_san_pham_tren_1_dong[2] = $so_san_pham_tren_1_dong[2] - 1;

            //san pham xoay ngang khep nhip doc
            $so_san_pham_tren_1_dong[] = intval(($kho_giay_rong - $kep_nhip) / ($kt_doc + $nap_hop / 2));
            $rong_thuc_te = $kt_doc * $so_san_pham_tren_1_dong[3] + ceil($so_san_pham_tren_1_dong[3] / 2) * $nap_hop;
            if ($rong_thuc_te > $kho_giay_rong - $kep_nhip)
                $so_san_pham_tren_1_dong[3] = $so_san_pham_tren_1_dong[3] - 1;

            //kep nhip ngang
        } elseif ($this->inputs->viTriNhip == 2) {
            if ($kho_giay_rong > $kho_giay_dai) {
                $so_san_pham_tren_1_dong[] = intval(($kho_giay_rong - $kep_nhip) / $kt_ngang);
                $so_san_pham_tren_1_dong[] = intval(($kho_giay_rong - $kep_nhip) / ($kt_doc + $nap_hop / 2));
                $rong_thuc_te = $kt_doc * $so_san_pham_tren_1_dong[1] + ceil($so_san_pham_tren_1_dong[1] / 2) * $nap_hop;
                if ($rong_thuc_te > $kho_giay_rong - $kep_nhip)
                    $so_san_pham_tren_1_dong[1] = $so_san_pham_tren_1_dong[1] - 1;
            } else {
                $so_san_pham_tren_1_dong[] = intval($kho_giay_rong / $kt_ngang);
                $so_san_pham_tren_1_dong[] = intval(($kho_giay_rong) / ($kt_doc + $nap_hop / 2));
                $rong_thuc_te = $kt_doc * $so_san_pham_tren_1_dong[1] + ceil($so_san_pham_tren_1_dong[1] / 2) * $nap_hop;
                if ($rong_thuc_te > $kho_giay_rong)
                    $so_san_pham_tren_1_dong[1] = $so_san_pham_tren_1_dong[1] - 1;
            }
            //kep nhip doc
        } elseif ($this->inputs->viTriNhip == 3) {
            if ($kho_giay_rong > $kho_giay_dai) {
                $so_san_pham_tren_1_dong[] = intval($kho_giay_rong / $kt_ngang);
                $so_san_pham_tren_1_dong[] = intval(($kho_giay_rong) / ($kt_doc + $nap_hop / 2));
                $rong_thuc_te = $kt_doc * $so_san_pham_tren_1_dong[1] + ceil($so_san_pham_tren_1_dong[1] / 2) * $nap_hop;
                if ($rong_thuc_te > $kho_giay_rong)
                    $so_san_pham_tren_1_dong[1] = $so_san_pham_tren_1_dong[1] - 1;
            } else {
                $so_san_pham_tren_1_dong[] = intval(($kho_giay_rong - $kep_nhip) / $kt_ngang);
                $so_san_pham_tren_1_dong[] = intval(($kho_giay_rong - $kep_nhip) / ($kt_doc + $nap_hop / 2));
                $rong_thuc_te = $kt_doc * $so_san_pham_tren_1_dong[1] + ceil($so_san_pham_tren_1_dong[1] / 2) * $nap_hop;
                if ($rong_thuc_te > $kho_giay_rong - $kep_nhip)
                    $so_san_pham_tren_1_dong[1] = $so_san_pham_tren_1_dong[1] - 1;
            }
        }
        //tong san pham sap xep trang
        $so_san_pham_tren_1_dong = @array_values($so_san_pham_tren_1_dong);
        if (!empty($so_san_pham_tren_1_dong)) {
            foreach ($so_san_pham_tren_1_dong as $k => $v) {
                $so_hang_san_pham = 0;
                if ($this->inputs->viTriNhip == 1 || $this->inputs->viTriNhip == null) {
                    $chieu_dai_giay = $k % 2 == 0 ? ($kho_giay_dai - $kep_nhip) : $kho_giay_dai;
                    $so_hang_san_pham = $k > 1 ? intval($chieu_dai_giay / $kt_ngang) : intval($chieu_dai_giay / ($kt_doc + $nap_hop / 2));
                    if ($k > 1) {
                        $dai_thuc_te = $kt_doc * $so_hang_san_pham + ceil($so_hang_san_pham / 2) * $nap_hop;
                        if ($dai_thuc_te > $chieu_dai_giay)
                            $so_hang_san_pham = $so_hang_san_pham - 1;
                    }
                } elseif ($this->inputs->viTriNhip == 2) {
                    if ($kho_giay_rong > $kho_giay_dai) {
                        $chieu_dai_giay = $kho_giay_dai;
                    } else {
                        $chieu_dai_giay = $kho_giay_dai - $kep_nhip;
                    }

                    $so_hang_san_pham = $k > 0 ? intval($chieu_dai_giay / $kt_ngang) : intval($chieu_dai_giay / ($kt_doc + $nap_hop / 2));
                    if ($k == 0) {
                        $dai_thuc_te = $kt_doc * $so_hang_san_pham + ceil($so_hang_san_pham / 2) * $nap_hop;
                        if ($dai_thuc_te > $chieu_dai_giay)
                            $so_hang_san_pham = $so_hang_san_pham - 1;
                    }
                } elseif ($this->inputs->viTriNhip == 3) {
                    if ($kho_giay_rong > $kho_giay_dai) {
                        $chieu_dai_giay = $kho_giay_dai - $kep_nhip;
                    } else {
                        $chieu_dai_giay = $kho_giay_dai;
                    }

                    $so_hang_san_pham = $k > 0 ? intval($chieu_dai_giay / $kt_ngang) : intval($chieu_dai_giay / ($kt_doc + $nap_hop / 2));
                    if ($k == 0) {
                        $dai_thuc_te = $kt_doc * $so_hang_san_pham + ceil($so_hang_san_pham / 2) * $nap_hop;
                        if ($dai_thuc_te > $chieu_dai_giay)
                            $so_hang_san_pham = $so_hang_san_pham - 1;
                    }
                }

                $so_thanh_pham_tren_to = $so_hang_san_pham * $v;

                $tong_so_thanh_pham_tren_to[] = $so_thanh_pham_tren_to;

            }
//            if (!empty($tong_so_thanh_pham_tren_to)) {
//                $maxIndex = array_search(max($tong_so_thanh_pham_tren_to), $tong_so_thanh_pham_tren_to);
//                $so_thanh_pham_tren_to = $tong_so_thanh_pham_tren_to[$maxIndex];
//            }
        }
        return $tong_so_thanh_pham_tren_to;
    }

    public function ThanHopCungDinhHinh($kho_giay_rong, $kho_giay_dai, $kep_nhip, $chieu_rong, $chieu_dai)
    {
        $tong_so_thanh_pham_tren_to = $so_san_pham_tren_1_dong = [];
        $kho_giay_rong = $kho_giay_rong - $this->inputs->buHaoNoiXenp;
        $kho_giay_dai = $kho_giay_dai - $this->inputs->buHaoNoiXenp;
        $boi_song = $this->inputs->giaTriBoiSongE;
        if ($this->inputs->boiSongE == 1) {
            $kho_giay_rong = $kho_giay_rong - $boi_song;
            $kho_giay_dai = $kho_giay_dai - $boi_song;
        }

        $kt_ngang = $chieu_rong + 2 * $this->inputs->cao;
        $kt_doc = $chieu_dai + 2 * $this->inputs->cao;
//        print_r(array(
//            'chieu ngang' => $kt_ngang,
//            'chieu doc' => $kt_doc,
//            'nap' => $nap_hop,
//            'kho rong' => $kho_giay_rong,
//            'kho dai' => $kho_giay_dai
//        ));

        //kep nhip tu dong
        if ($this->inputs->viTriNhip == 1 || $this->inputs->viTriNhip == null) {
            //san pham dung chieu khep nhip ngang
            $so_san_pham_tren_1_dong[] = intval($kho_giay_rong / $kt_ngang);

            //san pham dung chieu khep nhip doc
            $so_san_pham_tren_1_dong[] = intval(($kho_giay_rong - $kep_nhip) / $kt_ngang);

            //san pham xoay ngang khep nhip ngang
            $so_san_pham_tren_1_dong[] = intval(($kho_giay_rong) / $kt_doc);

            //san pham xoay ngang khep nhip doc
            $so_san_pham_tren_1_dong[] = intval(($kho_giay_rong - $kep_nhip) / $kt_doc);

            //kep nhip ngang
        } elseif ($this->inputs->viTriNhip == 2) {
            if ($kho_giay_rong > $kho_giay_dai) {
                $so_san_pham_tren_1_dong[] = intval(($kho_giay_rong - $kep_nhip) / $kt_ngang);
                $so_san_pham_tren_1_dong[] = intval(($kho_giay_rong - $kep_nhip) / $kt_doc);
            } else {
                $so_san_pham_tren_1_dong[] = intval($kho_giay_rong / $kt_ngang);
                $so_san_pham_tren_1_dong[] = intval(($kho_giay_rong) / $kt_doc);
            }

            //kep nhip doc
        } elseif ($this->inputs->viTriNhip == 3) {
            if ($kho_giay_rong > $kho_giay_dai) {
                $so_san_pham_tren_1_dong[] = intval($kho_giay_rong / $kt_ngang);
                $so_san_pham_tren_1_dong[] = intval(($kho_giay_rong) / $kt_doc);
            } else {
                $so_san_pham_tren_1_dong[] = intval(($kho_giay_rong - $kep_nhip) / $kt_ngang);
                $so_san_pham_tren_1_dong[] = intval(($kho_giay_rong - $kep_nhip) / $kt_doc);
            }
        }

        //tong san pham sap xep trang
        $so_san_pham_tren_1_dong = @array_values($so_san_pham_tren_1_dong);
        if (!empty($so_san_pham_tren_1_dong)) {
            foreach ($so_san_pham_tren_1_dong as $k => $v) {
                $chieu_dai_san_pham = 0;
                $chieu_dai_giay = 0;
                if ($this->inputs->viTriNhip == 1 || $this->inputs->viTriNhip == null) {
                    $chieu_dai_giay = $k % 2 == 0 ? ($kho_giay_dai - $kep_nhip) : $kho_giay_dai;
                    $chieu_dai_giay = $k > 1 ? $chieu_dai_giay : $chieu_dai_giay;
                    $chieu_dai_san_pham = $k > 1 ? $kt_ngang : $kt_doc;
                } elseif ($this->inputs->viTriNhip == 2) {
                    if ($kho_giay_rong > $kho_giay_dai) {
                        $chieu_dai_giay = $kho_giay_dai;
                    } else {
                        $chieu_dai_giay = $kho_giay_dai - $kep_nhip;
                    }
                    $chieu_dai_san_pham = $k > 0 ? $kt_ngang : $kt_doc;
                } elseif ($this->inputs->viTriNhip == 3) {
                    if ($kho_giay_rong > $kho_giay_dai) {
                        $chieu_dai_giay = $kho_giay_dai - $kep_nhip;
                    } else {
                        $chieu_dai_giay = $kho_giay_dai;
                    }
                    $chieu_dai_san_pham = $k > 0 ? $kt_ngang : $kt_doc;
                }

                $so_hang_san_pham = intval($chieu_dai_giay / $chieu_dai_san_pham);

                $so_thanh_pham_tren_to = $so_hang_san_pham * $v;

                $tong_so_thanh_pham_tren_to[] = $so_thanh_pham_tren_to;

            }
//            if (!empty($tong_so_thanh_pham_tren_to)) {
//                $maxIndex = array_search(max($tong_so_thanh_pham_tren_to), $tong_so_thanh_pham_tren_to);
//                $so_thanh_pham_tren_to = $tong_so_thanh_pham_tren_to[$maxIndex];
//            }
        }
        return $tong_so_thanh_pham_tren_to;
    }

    public function NapHopCungDinhHinh($kho_giay_rong, $kho_giay_dai, $kep_nhip, $chieu_rong, $chieu_dai)
    {
        $tong_so_thanh_pham_tren_to = $so_san_pham_tren_1_dong = [];
        $kho_giay_rong = $kho_giay_rong - $this->inputs->buHaoNoiXenp;
        $kho_giay_dai = $kho_giay_dai - $this->inputs->buHaoNoiXenp;
        $boi_song = $this->inputs->giaTriBoiSongE;
        if ($this->inputs->boiSongE == 1) {
            $kho_giay_rong = $kho_giay_rong - $boi_song;
            $kho_giay_dai = $kho_giay_dai - $boi_song;
        }
        $kt_ngang = $chieu_rong + $this->inputs->kichThuocNap * 2;
        $kt_doc = $chieu_dai + $this->inputs->kichThuocNap * 2;

//        print_r(array(
//            'chieu ngang' => $kt_ngang,
//            'chieu doc' => $kt_doc,
//            'nap' => $nap_hop,
//            'kho rong' => $kho_giay_rong,
//            'kho dai' => $kho_giay_dai
//        ));

        //kep nhip tu dong
        if ($this->inputs->viTriNhip == 1 || $this->inputs->viTriNhip == null) {
            //san pham dung chieu khep nhip ngang
            $so_san_pham_tren_1_dong[] = intval($kho_giay_rong / $kt_ngang);

            //san pham dung chieu khep nhip doc
            $so_san_pham_tren_1_dong[] = intval(($kho_giay_rong - $kep_nhip) / $kt_ngang);

            //san pham xoay ngang khep nhip ngang
            $so_san_pham_tren_1_dong[] = intval(($kho_giay_rong) / $kt_doc);

            //san pham xoay ngang khep nhip doc
            $so_san_pham_tren_1_dong[] = intval(($kho_giay_rong - $kep_nhip) / $kt_doc);

            //kep nhip ngang
        } elseif ($this->inputs->viTriNhip == 2) {
            if ($kho_giay_rong > $kho_giay_dai) {
                $so_san_pham_tren_1_dong[] = intval(($kho_giay_rong - $kep_nhip) / $kt_ngang);
                $so_san_pham_tren_1_dong[] = intval(($kho_giay_rong - $kep_nhip) / $kt_doc);
            } else {
                $so_san_pham_tren_1_dong[] = intval($kho_giay_rong / $kt_ngang);
                $so_san_pham_tren_1_dong[] = intval(($kho_giay_rong) / $kt_doc);
            }

            //kep nhip doc
        } elseif ($this->inputs->viTriNhip == 3) {
            if ($kho_giay_rong > $kho_giay_dai) {
                $so_san_pham_tren_1_dong[] = intval($kho_giay_rong / $kt_ngang);
                $so_san_pham_tren_1_dong[] = intval(($kho_giay_rong) / $kt_doc);
            } else {
                $so_san_pham_tren_1_dong[] = intval(($kho_giay_rong - $kep_nhip) / $kt_ngang);
                $so_san_pham_tren_1_dong[] = intval(($kho_giay_rong - $kep_nhip) / $kt_doc);
            }
        }
        $so_san_pham_tren_1_dong = @array_values($so_san_pham_tren_1_dong);
        //tong san pham sap xep trang
        if (!empty($so_san_pham_tren_1_dong)) {
            foreach ($so_san_pham_tren_1_dong as $k => $v) {
                $chieu_dai_san_pham = 0;
                $chieu_dai_giay = 0;
                if ($this->inputs->viTriNhip == 1 || $this->inputs->viTriNhip == null) {
                    $chieu_dai_giay = $k % 2 == 0 ? ($kho_giay_dai - $kep_nhip) : $kho_giay_dai;
                    $chieu_dai_giay = $k > 1 ? $chieu_dai_giay : $chieu_dai_giay;
                    $chieu_dai_san_pham = $k > 1 ? $kt_ngang : $kt_doc;
                } elseif ($this->inputs->viTriNhip == 2) {
                    if ($kho_giay_rong > $kho_giay_dai) {
                        $chieu_dai_giay = $kho_giay_dai;
                    } else {
                        $chieu_dai_giay = $kho_giay_dai - $kep_nhip;
                    }
                    $chieu_dai_san_pham = $k > 0 ? $kt_ngang : $kt_doc;
                } elseif ($this->inputs->viTriNhip == 3) {
                    if ($kho_giay_rong > $kho_giay_dai) {
                        $chieu_dai_giay = $kho_giay_dai - $kep_nhip;
                    } else {
                        $chieu_dai_giay = $kho_giay_dai;
                    }
                    $chieu_dai_san_pham = $k > 0 ? $kt_ngang : $kt_doc;
                }

                $so_hang_san_pham = intval($chieu_dai_giay / $chieu_dai_san_pham);

                $so_thanh_pham_tren_to = $so_hang_san_pham * $v;

                $tong_so_thanh_pham_tren_to[] = $so_thanh_pham_tren_to;

            }
//            if (!empty($tong_so_thanh_pham_tren_to)) {
//                $maxIndex = array_search(max($tong_so_thanh_pham_tren_to), $tong_so_thanh_pham_tren_to);
//                $so_thanh_pham_tren_to = $tong_so_thanh_pham_tren_to[$maxIndex];
//            }
        }
        return $tong_so_thanh_pham_tren_to;
    }

    public function CalculatorProduct($kho_giay_rong, $kho_giay_dai, $kep_nhip, $chieu_rong, $chieu_dai, $type = 'gb')
    {
        $tong_so_thanh_pham_tren_to = $so_san_pham_tren_1_dong = [];
        //kep nhip tu dong
        if ($this->inputs->viTriNhip == 1 || $this->inputs->viTriNhip == null) {
            //san pham dung chieu khep nhip ngang
            $so_san_pham_tren_1_dong[] = intval($kho_giay_rong / $chieu_rong);

            //san pham dung chieu khep nhip doc
            $so_san_pham_tren_1_dong[] = intval(($kho_giay_rong - $kep_nhip) / $chieu_rong);

            //san pham xoay ngang khep nhip ngang
            $so_san_pham_tren_1_dong[] = intval($kho_giay_rong / $chieu_dai);

            //san pham xoay ngang khep nhip doc
            $so_san_pham_tren_1_dong[] = intval(($kho_giay_rong - $kep_nhip) / $chieu_dai);

            //kep nhip ngang
        } elseif ($this->inputs->viTriNhip == 2) {
            if ($kho_giay_rong > $kho_giay_dai) {
                $so_san_pham_tren_1_dong[] = intval(($kho_giay_rong - $kep_nhip) / $chieu_rong);
                $so_san_pham_tren_1_dong[] = intval(($kho_giay_rong - $kep_nhip) / $chieu_dai);
            } else {
                $so_san_pham_tren_1_dong[] = intval($kho_giay_rong / $chieu_rong);
                $so_san_pham_tren_1_dong[] = intval($kho_giay_rong / $chieu_dai);
            }

            //kep nhip doc
        } elseif ($this->inputs->viTriNhip == 3) {
            if ($kho_giay_rong > $kho_giay_dai) {
                $so_san_pham_tren_1_dong[] = intval($kho_giay_rong / $chieu_rong);
                $so_san_pham_tren_1_dong[] = intval($kho_giay_rong / $chieu_dai);
            } else {
                $so_san_pham_tren_1_dong[] = intval(($kho_giay_rong - $kep_nhip) / $chieu_rong);
                $so_san_pham_tren_1_dong[] = intval(($kho_giay_rong - $kep_nhip) / $chieu_dai);
            }
        }
        $so_san_pham_tren_1_dong = @array_values($so_san_pham_tren_1_dong);
        //tong san pham sap xep trang
        if (!empty($so_san_pham_tren_1_dong)) {
            foreach ($so_san_pham_tren_1_dong as $k => $v) {
                $chieu_dai_giay = 0;
                $chieu_dai_san_pham = 0;
                if ($this->inputs->viTriNhip == 1 || $this->inputs->viTriNhip == null) {
                    $chieu_dai_giay = $k % 2 == 0 ? ($kho_giay_dai - $kep_nhip) : $kho_giay_dai;
                    $chieu_dai_san_pham = $k > 1 ? $chieu_rong : $chieu_dai;
                } elseif ($this->inputs->viTriNhip == 2) {
                    if ($kho_giay_rong > $kho_giay_dai) {
                        $chieu_dai_giay = $kho_giay_dai;
                    } else {
                        $chieu_dai_giay = $kho_giay_dai - $kep_nhip;
                    }
                    $chieu_dai_san_pham = $k > 0 ? $chieu_rong : $chieu_dai;
                } elseif ($this->inputs->viTriNhip == 3) {
                    if ($kho_giay_rong > $kho_giay_dai) {
                        $chieu_dai_giay = $kho_giay_dai - $kep_nhip;
                    } else {
                        $chieu_dai_giay = $kho_giay_dai;
                    }
                    $chieu_dai_san_pham = $k > 0 ? $chieu_rong : $chieu_dai;
                }

                $so_hang_san_pham = intval($chieu_dai_giay / $chieu_dai_san_pham);
                if ($type == 'gr' && $v % 2 !== 0 && $so_hang_san_pham % 2 !== 0 && $this->inputs->loaiSanPham != Products::SAN_PHAM_HOP)
                    $so_hang_san_pham = $so_hang_san_pham - 1;
                $so_thanh_pham_tren_to = $so_hang_san_pham * $v;

                if ($type == 'gr') {
                    if ($this->inputs->kieuDong == OrderInfo::DONG_GIUA && $so_thanh_pham_tren_to % 2 != 0) {
                        $div_4 = floor($so_thanh_pham_tren_to / 2);
                        $so_thanh_pham_tren_to = $div_4 * 2;
                    } elseif ($so_thanh_pham_tren_to % 6 != 0 && $so_thanh_pham_tren_to % 4 != 0 && $so_thanh_pham_tren_to > 4) {
                        $div_6 = floor($so_thanh_pham_tren_to / 6);
                        $div_4 = floor($so_thanh_pham_tren_to / 4);
                        if ($div_6 * 6 > $div_4 * 4) {
                            $so_thanh_pham_tren_to = $div_6 * 6;
                        } else {
                            $so_thanh_pham_tren_to = $div_4 * 4;
                        }
                    }
                }
                if($so_thanh_pham_tren_to > 0)
                    $tong_so_thanh_pham_tren_to[] = $so_thanh_pham_tren_to;
            }

//            if (!empty($tong_so_thanh_pham_tren_to)) {
//                $maxIndex = array_search(max($tong_so_thanh_pham_tren_to), $tong_so_thanh_pham_tren_to);
//                $so_thanh_pham_tren_to = $tong_so_thanh_pham_tren_to[$maxIndex];
//            }
        }
        return $tong_so_thanh_pham_tren_to;
//        return $so_thanh_pham_tren_to;
    }

    public function CalculatorProductEar($kho_giay_rong, $kho_giay_dai, $kep_nhip, $san_pham_rong, $san_pham_dai, $tai_rong, $tai_dai, $vi_tri_tai)
    {
        $so_san_pham_tren_1_dong = $tong_so_thanh_pham_tren_to = [];
        $chieu_rong = $san_pham_rong + ($vi_tri_tai == 2 ? $tai_rong / 2 : 0);
        $chieu_dai = $san_pham_dai + ($vi_tri_tai == 1 ? $tai_dai / 2 : 0);

        if ($this->inputs->viTriNhip == 1 || $this->inputs->viTriNhip == null || ($this->inputs->viTriNhip == 2 && $kho_giay_rong < $kho_giay_dai) || ($this->inputs->viTriNhip == 3 && $kho_giay_rong > $kho_giay_dai)) {
            //san pham dung chieu khep nhip ngang
            $san_pham_tren_1_dong = intval($kho_giay_rong / $chieu_rong);
            if ($san_pham_tren_1_dong % 2 != 0 && $vi_tri_tai == 2) {
                $chieu_rong_thuc_te = $san_pham_rong * $san_pham_tren_1_dong + ($san_pham_tren_1_dong + 1) / 2 * $tai_rong;
                if ($chieu_rong_thuc_te > $kho_giay_rong) {
                    $san_pham_tren_1_dong = $san_pham_tren_1_dong - 1;
                }
            }
            $so_san_pham_tren_1_dong[0] = $san_pham_tren_1_dong;
        }

        if ($this->inputs->viTriNhip == 1 || $this->inputs->viTriNhip == null || ($this->inputs->viTriNhip == 2 && $kho_giay_rong > $kho_giay_dai) || ($this->inputs->viTriNhip == 3 && $kho_giay_rong < $kho_giay_dai)) {
            //san pham dung chieu khep nhip doc
            $san_pham_tren_1_dong = intval(($kho_giay_rong - $kep_nhip) / $chieu_rong);
            if ($san_pham_tren_1_dong % 2 != 0 && $vi_tri_tai == 2) {
                $chieu_rong_thuc_te = $san_pham_rong * $san_pham_tren_1_dong + ($san_pham_tren_1_dong + 1) / 2 * $tai_rong;
                if ($chieu_rong_thuc_te > $kho_giay_rong - $kep_nhip) {
                    $san_pham_tren_1_dong = $san_pham_tren_1_dong - 1;
                }
            }
            $so_san_pham_tren_1_dong[1] = $san_pham_tren_1_dong;
        }

        if ($this->inputs->viTriNhip == 1 || $this->inputs->viTriNhip == null || ($this->inputs->viTriNhip == 2 && $kho_giay_rong < $kho_giay_dai) || ($this->inputs->viTriNhip == 3 && $kho_giay_rong > $kho_giay_dai)) {
            //san pham xoay ngang khep nhip ngang
            $san_pham_tren_1_dong = intval($kho_giay_rong / $chieu_dai);
            if ($san_pham_tren_1_dong % 2 != 0 && $vi_tri_tai == 1) {
                $chieu_rong_thuc_te = $san_pham_dai * $san_pham_tren_1_dong + ($san_pham_tren_1_dong + 1) / 2 * $tai_dai;
                if ($chieu_rong_thuc_te > $kho_giay_rong) {
                    $san_pham_tren_1_dong = $san_pham_tren_1_dong - 1;
                }
            }
            $so_san_pham_tren_1_dong[2] = $san_pham_tren_1_dong;
        }

        if ($this->inputs->viTriNhip == 1 || $this->inputs->viTriNhip == null || ($this->inputs->viTriNhip == 2 && $kho_giay_rong > $kho_giay_dai) || ($this->inputs->viTriNhip == 3 && $kho_giay_rong < $kho_giay_dai)) {
            //san pham xoay ngang khep nhip doc
            $san_pham_tren_1_dong = intval(($kho_giay_rong - $kep_nhip) / $chieu_dai);
            if ($san_pham_tren_1_dong % 2 != 0 && $vi_tri_tai == 1) {
                $chieu_rong_thuc_te = $san_pham_dai * $san_pham_tren_1_dong + ($san_pham_tren_1_dong + 1) / 2 * $tai_dai;
                if ($chieu_rong_thuc_te > $kho_giay_rong - $kep_nhip) {
                    $san_pham_tren_1_dong = $san_pham_tren_1_dong - 1;
                }
            }
            $so_san_pham_tren_1_dong[3] = $san_pham_tren_1_dong;
        }

        $so_san_pham_tren_1_dong = @array_values($so_san_pham_tren_1_dong);
        if (!empty($so_san_pham_tren_1_dong)) {
            foreach ($so_san_pham_tren_1_dong as $k => $v) {
                $kho_giay_dai_ = $kho_giay_rong_ = $chieu_dai_san_pham = 0;
                if ($this->inputs->viTriNhip == 1 || $this->inputs->viTriNhip == null) {
                    $kho_giay_dai_ = $k % 2 != 0 ? $kho_giay_dai - $kep_nhip : $kho_giay_dai;
                    $chieu_dai_san_pham = $k % 2 != 0 ? $chieu_rong : $chieu_dai;
                } elseif ($this->inputs->viTriNhip == 2) {
                    if ($kho_giay_rong > $kho_giay_dai) {
                        $kho_giay_dai_ = $kho_giay_dai;
                        $chieu_dai_san_pham = $k == 0 ? $chieu_dai : $chieu_rong;
                    } else {
                        $kho_giay_dai_ = $kho_giay_dai - $kep_nhip;
                        $chieu_dai_san_pham = $k == 0 ? $chieu_rong : $chieu_dai;
                    }
                } elseif ($this->inputs->viTriNhip == 3) {
                    if ($kho_giay_rong > $kho_giay_dai) {
                        $kho_giay_dai_ = $kho_giay_dai - $kep_nhip;
                        $chieu_dai_san_pham = $k == 0 ? $chieu_rong : $chieu_dai;
                    } else {
                        $kho_giay_dai_ = $kho_giay_dai;
                        $chieu_dai_san_pham = $k == 0 ? $chieu_dai : $chieu_rong;
                    }
                }

                $so_hang_san_pham = intval($kho_giay_dai_ / $chieu_dai_san_pham);
                if ($so_hang_san_pham % 2 != 0) {
                    $nhan_them_tai = $this->inputs->taiSanPham == 1 ? $tai_dai : $tai_rong;
                    if($k % 2 == 0) {
                        $chieu_dai_thuc_te = $so_hang_san_pham * $san_pham_dai + ($so_hang_san_pham + 1) / 2 * $nhan_them_tai;
                        if ($chieu_dai_thuc_te > $kho_giay_dai_) {
                            $so_hang_san_pham = $so_hang_san_pham - 1;
                        }
                    }elseif($k % 2 != 0) {
                        $chieu_dai_thuc_te = $so_hang_san_pham * $san_pham_rong + ($so_hang_san_pham + 1) / 2 * $nhan_them_tai;
                        if ($chieu_dai_thuc_te > $kho_giay_dai_) {
                            $so_hang_san_pham = $so_hang_san_pham - 1;
                        }
                    }
                }

                $tong_so_thanh_pham_tren_to[] = $so_hang_san_pham * $v;
            }
        }

        return $tong_so_thanh_pham_tren_to;
//        if (!empty($tong_so_thanh_pham_tren_to)) {
//            $maxIndex = array_search(max($tong_so_thanh_pham_tren_to), $tong_so_thanh_pham_tren_to);
//            $so_thanh_pham_tren_to = $tong_so_thanh_pham_tren_to[$maxIndex];
//        }
//
//        return $so_thanh_pham_tren_to;
    }

    public function Calculator()
    {
        if (
            $this->kho_giay_rong == 0 || $this->kho_giay_dai == 0 || $this->inputs->rong == 0 || $this->inputs->dai == 0
        )
            return false;
        //###########################################################
        $tong_so_thanh_pham_tren_to = [];
        $type = $this->loai_giay;
        //neu san pham hop
        if ($this->inputs->loaiSanPham == Products::SAN_PHAM_HOP) {
            if ($this->inputs->kieuHop == Products::HOP_CAI_DAY) {
                $tong_so_thanh_pham_tren_to = self::HopCaiDay($this->kho_giay_rong, $this->kho_giay_dai, $this->inputs->chuaKepNhip, $this->inputs->rong, $this->inputs->dai);
            } elseif ($this->inputs->kieuHop == Products::HOP_MOC_DAY) {
                $tong_so_thanh_pham_tren_to = self::HopMocDay($this->kho_giay_rong, $this->kho_giay_dai, $this->inputs->chuaKepNhip, $this->inputs->rong, $this->inputs->dai);
            } elseif ($this->inputs->kieuHop == Products::HOP_CUNG_DINH_HINH) {
                if ($this->nap_hop == 1) {
                    $tong_so_thanh_pham_tren_to = self::NapHopCungDinhHinh($this->kho_giay_rong, $this->kho_giay_dai, $this->inputs->chuaKepNhip, $this->inputs->rong, $this->inputs->dai);
                } else {
                    $tong_so_thanh_pham_tren_to = self::ThanHopCungDinhHinh($this->kho_giay_rong, $this->kho_giay_dai, $this->inputs->chuaKepNhip, $this->inputs->rong, $this->inputs->dai);
                }
            }

            //neu san pham tui giay
        } elseif($this->inputs->loaiSanPham == Products::SAN_PHAM_TUI_GIAY){

            $san_pham_dai = $this->inputs->cao + $this->inputs->taiNap + $this->inputs->rong - $this->inputs->kichThuocCaiDay;
            $san_pham_rong = $this->inputs->dai * 2 + $this->inputs->rong * 2 + $this->inputs->kichThuocTai;
            $tong_so_thanh_pham_tren_to = self::CalculatorProduct($this->kho_giay_rong, $this->kho_giay_dai, $this->inputs->chuaKepNhip, $san_pham_rong, $san_pham_dai, $type);

            //neu san pham phong bi
        } elseif ($this->inputs->loaiSanPham == Products::SAN_PHAM_PHONG_BI) {
            $san_pham_rong = $this->inputs->rong + $this->inputs->kichThuocTai * 2;
            $san_pham_dai = $this->inputs->dai * 2 + $this->inputs->taiNap - $this->inputs->kichThuocCaiDay;
            $tong_so_thanh_pham_tren_to = self::CalculatorProduct($this->kho_giay_rong, $this->kho_giay_dai, $this->inputs->chuaKepNhip, $san_pham_rong, $san_pham_dai, $type);
        } //neu la giay bia va san pham co tai
        elseif ($this->inputs->taiSanPham == 1 && $type == 'gb' && $this->inputs->taiDai > 0 && $this->inputs->taiRong > 0) {

            //neu san pham co tai ngang
            if ($this->inputs->viTriTai == 1) {

                //neu tai rong > 1/2 chieu rong san pham thi ko xep quay dau duoc
                if ($this->inputs->taiRong > $this->inputs->rong / 2) {
                    $san_pham_rong = $this->inputs->rong;
                    $san_pham_dai = $this->inputs->dai + $this->inputs->taiDai;
                    $tong_so_thanh_pham_tren_to = self::CalculatorProduct($this->kho_giay_rong, $this->kho_giay_dai, $this->inputs->chuaKepNhip, $san_pham_rong, $san_pham_dai);

                } else {
                    $tong_so_thanh_pham_tren_to = self::CalculatorProductEar($this->kho_giay_rong, $this->kho_giay_dai, $this->inputs->chuaKepNhip, $this->inputs->rong, $this->inputs->dai, $this->inputs->taiRong, $this->inputs->taiDai, $this->inputs->viTriTai);

                }

                //neu san pham co tai doc
            } else {

                $san_pham_rong = $this->inputs->rong + $this->inputs->taiRong;
                $san_pham_dai = $this->inputs->dai;

                //neu tai dai > 1/2 chieu dai san pham thi ko xep quay dau duoc
                if ($this->inputs->taiDai > $this->inputs->dai / 2) {
                    $tong_so_thanh_pham_tren_to = self::CalculatorProduct($this->kho_giay_rong, $this->kho_giay_dai, $this->inputs->chuaKepNhip, $san_pham_rong, $san_pham_dai);
                } else {
                    $tong_so_thanh_pham_tren_to = self::CalculatorProductEar($this->kho_giay_rong, $this->kho_giay_dai, $this->inputs->chuaKepNhip, $this->inputs->rong, $this->inputs->dai, $this->inputs->taiRong, $this->inputs->taiDai, $this->inputs->viTriTai);
                }

            }

            //neu san pham khong co tai
        } else {
            $tong_so_thanh_pham_tren_to = self::CalculatorProduct($this->kho_giay_rong, $this->kho_giay_dai, $this->inputs->chuaKepNhip, $this->inputs->rong, $this->inputs->dai, $type);

        }
        return $tong_so_thanh_pham_tren_to;
    }
}