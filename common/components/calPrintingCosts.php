<?php

namespace common\components;

use backend\helpers\AcpHelper;
use backend\models\OrderInfo;
use backend\models\Giakhomayin;
use backend\models\Content;
use backend\models\Tobuhao;
use backend\models\Supplier;
use backend\models\Tonkho;
use backend\models\Products;
use yii;

class calPrintingCosts
{
    /*
     * InKem = soMauKem * donGiaKem
     * InLuot = (soMauKem * donGiaKem) + ((soTo * matIn) * (soMauKem * donGiaIn))
     * */

    public $inputs;

    protected function TinhQuyCachIn($quycach, $kieutro = OrderInfo::KIEU_TRO_NO)
    {
        $return = [];
        $arr_quycach = @explode('/', $quycach);
        if (!empty($arr_quycach)) {
            $somaumat1 = $arr_quycach[0];
            $somaumat2 = $arr_quycach[1];

            if ($somaumat1 == 0 || $somaumat2 == 0) {
                $somat = 1;
                $somau = $somaumat1 + $somaumat2;
            } else {
                $somat = 2;
                if ($somaumat1 == $somaumat2) {
                    if ($kieutro == OrderInfo::KIEU_TRO_NO)
                        $somau = $somaumat2;
                    else
                        $somau = $somaumat1 + $somaumat2;
                } else {
                    $somau = $somaumat1 + $somaumat2;
                }
            }
            $return = [
                'mau_mat_1' => $somaumat1,
                'mau_mat_2' => $somaumat2,
                'so_mau' => $somau,
                'so_mat' => $somat,
                'kieu_tro_no' => $somaumat1 != $somaumat2 ? false : true
            ];
        }
        return $return;
    }

    protected function TinhInKem($array)
    {
        $so_tay = isset($array['soTay']) ? $array['soTay'] : 1;
        $chiPhiInKem = $array['soMauKem'] * $array['donGiaKem'] * $so_tay;
        if ($array['mauPha'] == 1 && $array['heSoMauPha'] > 0)
            $chiPhiInKem = $chiPhiInKem * floatval($array['heSoMauPha']);
        if ($chiPhiInKem < 0)
            $chiPhiInKem = 0;
        return round($chiPhiInKem);
    }

    protected function TinhInLuot($array)
    {
        $so_tay = isset($array['soTay']) ? $array['soTay'] : 1;

        $chiPhiInLuot = ($array['soMauKem'] * $array['donGiaKem']);

        $matIn = $array['matIn'];
        $quy_cach_in = $array['quy_cach_in'];
        $kieu_in_tro = $array['kieu_in_tro'];

        if (!empty($quy_cach_in)) {
            $arr_quycach = @explode('/', $quy_cach_in);
            $somaumat1 = intval($arr_quycach[0]);
            $somaumat2 = intval($arr_quycach[1]);
        }

        if ($matIn == 1 || $kieu_in_tro == OrderInfo::KIEU_TRO_NO) {
            $chiPhiInLuot += $array['soTo'] * $array['soMauKem'] * $array['donGiaIn'];
        } else {
            $chiPhiInLuot += $array['soTo'] * $somaumat1 * $array['donGiaIn'];
            $chiPhiInLuot += $array['soTo'] * $somaumat2 * $array['donGiaIn'];
        }
        $chiPhiInLuot = $chiPhiInLuot * $so_tay;
        if ($chiPhiInLuot < 0)
            $chiPhiInLuot = 0;
        return round($chiPhiInLuot);
    }

    protected function TinhInLuotBoKem($array)
    {
        $chiPhiInLuot = 0;
        $matIn = $array['matIn'];
        $quy_cach_in = $array['quy_cach_in'];
        $kieu_in_tro = $array['kieu_in_tro'];
        $soMauMay = $array['soMauMay'];

        if (!empty($quy_cach_in)) {
            $arr_quycach = @explode('/', $quy_cach_in);
            $somaumat1 = intval($arr_quycach[0]);
            $somaumat2 = intval($arr_quycach[1]);
        }

        if ($matIn == 1) {
            $bienMau = $array['soMauKem'] <= $soMauMay ? 1 : round($array['soMauKem'] / $soMauMay);

            $chiPhiInLuot = ($array['soTo'] * $array['matIn'] - $array['soLuong']) * $array['soTay'] * $bienMau * $array['donGiaIn'];
        } elseif ($matIn == 2) {
            if ($kieu_in_tro == OrderInfo::KIEU_TRO_NO) {

                $bienMau = $array['soMauKem'] <= $soMauMay ? 1 : round($array['soMauKem'] / $soMauMay);

                $chiPhiInLuot = ($array['soTo'] * $array['matIn'] - $array['soLuong']) * $array['soTay'] * $bienMau * $array['donGiaIn'];
            } elseif ($kieu_in_tro == OrderInfo::KIEU_TRO_KHAC) {
                $bienMau1 = $somaumat1 <= $soMauMay ? 1 : round($somaumat1 / $soMauMay);
                $bienMau2 = $somaumat2 <= $soMauMay ? 1 : round($somaumat2 / $soMauMay);

                $chiPhiInLuot = (floatval($array['soTo']) - floatval($array['soLuong']) / 2) * $bienMau1 * $array['soTay'] * floatval($array['donGiaIn']);
                $chiPhiInLuot += (floatval($array['soTo']) - floatval($array['soLuong']) / 2) * $bienMau2 * $array['soTay'] * floatval($array['donGiaIn']);
//                print_r($array);
            }
        }
        if ($chiPhiInLuot < 0)
            $chiPhiInLuot = 0;
//        $chiPhiIn = ($array['soTo'] * $array['matIn'] - $array['soLuong']) * $array['soMauKem'] * $array['donGiaIn'];
        return round($chiPhiInLuot);
    }

    protected function kieuTinhGia($type)
    {
        if (!empty($type)) {

            //cach tinh: K, L, K:1000=L:0, L:1000=K:0
            if ($type == 'K' || $type == 'L') {
                return ['all' => 1, 't' => $type];
            }

            $type_ = preg_match('/^(\w):(\d+)\=(\w):(\d+)$/', $type);

            if ($type_ == 1) {
                preg_match_all('/^(\w):(\d+)\=(\w):(\d+)$/', $type, $type_match);
                if (floatval($type_match[2][0]) > 0) {
                    return ['t' => $type_match[1][0], 'v' => floatval($type_match[2][0]), 'p' => 'T'];
                } else if (floatval($type_match[4][0]) > 0) {
                    return ['t' => $type_match[3][0], 'v' => floatval($type_match[4][0]), 'p' => 'S'];
                }
                return false;
            } else {
                $type_ = preg_match('/^(\w):(\d+):(\w)$/', $type);
                if ($type_ == 1) {
                    preg_match_all('/^(\w):(\d+):(\w)$/', $type, $type_match);
                    return ['allByType' => 1, 't' => $type_match[1][0], 'so_luong' => floatval($type_match[2][0]), 't1' => $type_match[3][0]];
                }
            }

            $type_ = explode('=', $type);
            if (strlen($type_) != 2) {
                return false;
            }

            $V1 = explode(':', $type[0][0]);
            if (strlen($V1) != 2) {
                return false;
            }
            $V2 = explode(':', $type_[1][0]);
            if (strlen($V2) != 2) {
                return false;
            }

            $arr = [
                0 => $V1[0][0],
                1 => floatval($V1[1][0]),
                2 => $V2[0][0],
                3 => floatval($V2[1][0]),
            ];

            if ($arr[1][0] > 0)
                return ['t' => $arr[0][0], 'v' => $arr[1][0], 'p' => 'T'];
            else if ($arr[3] > 0) {
                return ['t' => $arr[2][0], 'v' => $arr[3][0], 'p' => 'S'];
            } else {
                return false;
            }
        }
        return false;
    }

    public function tinhChiPhiIn()
    {
        $array = $this->inputs;
        $type = $array['type'];
        $soLuong = intval($array['soLuong']);
        $soTo = $sotoGiay = intval($array['soTo']);
        $soMauKem = intval($array['soMauKem']);
        $donGiaKem = floatval($array['donGiaKem']);
        $matIn = intval($array['matIn']);
        $donGiaIn = floatval($array['donGiaIn']);
        $thanhPham = intval($array['thanhPham']);
        $calcuType = self::kieuTinhGia($type);
        $soLuongSoSanh = $soLuong;
        $mauPha = intval($array['mauPha']);
        $heSoMauPha = floatval($array['heSoMauPha']);
        $tinh_theo_to = $array['tinh_theo_to'];
        $kieu_in_tro = $array['kieu_in_tro'];
        $quy_cach_in = $array['quyCachIn'];
        $soTay = isset($array['soTay']) ? $array['soTay'] : 1;
        $soMauMay = isset($array['soMauMay']) ? $array['soMauMay'] : 1;

        if ($tinh_theo_to > 0)
            $soLuongSoSanh = $soTo;

        $chiPhiIn = 0;
        //neu chon tinh gia
        if ($calcuType) {
            //tinh all
            if (isset($calcuType['all']) && $calcuType['all'] == 1) {
                $t = $calcuType['t'];
                if ($t === 'L') {
                    $options_L = [
                        'soMauKem' => $soMauKem,
                        'donGiaKem' => $donGiaKem,
                        'soTo' => $soTo,
                        'matIn' => $matIn,
                        'donGiaIn' => $donGiaIn,
                    ];
                    $chiPhiIn += self::TinhInLuot($options_L);
                } else {
                    $options_L = [
                        'soMauKem' => $soMauKem,
                        'donGiaKem' => $donGiaKem,
                        'mauPha' => $mauPha,
                        'heSoMauPha' => $heSoMauPha,
                        'soTay' => $soTay,
                    ];
                    $chiPhiIn += self::TinhInKem($options_L);
                }
                if ($chiPhiIn > 0) {
                    return $chiPhiIn;
                } else {
                    return 0;
                }
                //tinh all by type
            } else if (isset($calcuType['allByType']) && $calcuType['allByType'] == 1) {
                if ($soLuongSoSanh < $calcuType['so_luong']) {
                    if ($calcuType['t'] === 'K') {
                        $options_K = [
                            'soMauKem' => $soMauKem,
                            'donGiaKem' => $donGiaKem,
                            'mauPha' => $mauPha,
                            'heSoMauPha' => $heSoMauPha,
                            'soTay' => $soTay,
                        ];
                        $chiPhiIn += self::TinhInKem($options_K);
                    } else if ($calcuType['t'] === 'L') {
                        $options_L = [
                            'soMauKem' => $soMauKem,
                            'donGiaKem' => $donGiaKem,
                            'soTo' => $soTo,
                            'matIn' => $matIn,
                            'donGiaIn' => $donGiaIn,
                        ];
                        $chiPhiIn += self::TinhInLuot($options_L);
                    } else {
                        return false;
                    }
                    if ($chiPhiIn > 0) {
                        return $chiPhiIn;
                    } else {
                        return 0;
                    }
                } else {
                    if ($calcuType['t1'] == 'K') {
                        $options_K = [
                            'soMauKem' => $soMauKem,
                            'donGiaKem' => $donGiaKem,
                            'mauPha' => $mauPha,
                            'heSoMauPha' => $heSoMauPha,
                            'soTay' => $soTay,
                        ];
                        $chiPhiIn += self::TinhInKem($options_K);
                    } else if ($calcuType['t1'] == 'L') {
                        $options_L = [
                            'soMauKem' => $soMauKem,
                            'donGiaKem' => $donGiaKem,
                            'soTo' => $soLuongSoSanh,
                            'matIn' => $matIn,
                        ];
                        $chiPhiIn += self::TinhInLuot($options_L);
                    } else {
                        return false;
                    }
                    if ($chiPhiIn > 0)
                        return $chiPhiIn;
                    else {
                        return 0;
                    }
                }
            } else {
                if (!is_numeric($thanhPham)) {
                    return false;
                }
                $t = $calcuType['t']; //kieu tinh kem hoac tinh luot: K or L
                $v = $calcuType['v']; //so luong
                $p = $calcuType['p']; //truoc hoac sau

                if (($matIn > 1 && $kieu_in_tro != OrderInfo::KIEU_TRO_NO) || $kieu_in_tro == OrderInfo::KIEU_TRO_NO)
                    $v_ss = $v / 2;
                else
                    $v_ss = $v;

                if ($soLuongSoSanh > $v_ss) {
                    if ($p == 'T') {
                        if ($t == 'K') {
                            $options_K = [
                                'soMauKem' => $soMauKem,
                                'donGiaKem' => $donGiaKem,
                                'mauPha' => $mauPha,
                                'heSoMauPha' => $heSoMauPha,
                                'soTay' => $soTay,
                            ];
                            $sotoBia_ = ceil($soTo - $v * $sotoGiay / $soLuong);
                            $chiPhiIn += self::TinhInKem($options_K);

                            $options_L = [
                                'soMauKem' => $soMauKem,
                                'donGiaKem' => $donGiaKem,
                                'soTo' => $tinh_theo_to > 0 ? $soLuongSoSanh : $sotoBia_,
                                'matIn' => $matIn,
                                'donGiaIn' => $donGiaIn,
                                'soLuong' => $v,
                                'kieu_in_tro' => $kieu_in_tro,
                                'quy_cach_in' => $quy_cach_in,
                                'soTay' => $soTay,
                                'soMauMay' => $soMauMay,
                            ];
                            $chiPhiIn += self::TinhInLuotBoKem($options_L);

                        } else if ($t == 'L') {
                            $options_L = [
                                'soMauKem' => $soMauKem,
                                'donGiaKem' => $donGiaKem,
                                'soTo' => $v,
                                'matIn' => $matIn,
                                'donGiaIn' => $donGiaIn,
                                'soLuong' => $v,
                                'kieu_in_tro' => $kieu_in_tro,
                                'quy_cach_in' => $quy_cach_in,
                                'soTay' => $soTay,
                                'soMauMay' => $soMauMay,
                            ];
                            $chiPhiIn += self::TinhInLuotBoKem($options_L);
                            $options_K = [
                                'soMauKem' => $soMauKem,
                                'donGiaKem' => $donGiaKem,
                                'mauPha' => $mauPha,
                                'heSoMauPha' => $heSoMauPha,
                                'soTay' => $soTay,
                            ];
                            $chiPhiIn += self::TinhInKem($options_K);
                        }
                    } else if ($p == 'S') {
                        if ($t == 'K') {
                            $options_K = [
                                'soMauKem' => $soMauKem,
                                'donGiaKem' => $donGiaKem,
                                'mauPha' => $mauPha,
                                'heSoMauPha' => $heSoMauPha,
                                'soTay' => $soTay,
                            ];
                            $chiPhiIn += self::TinhInKem($options_K);
                            $options_L = [
                                'soMauKem' => $soMauKem,
                                'donGiaKem' => $donGiaKem,
                                'soTo' => $soLuongSoSanh,
                                'matIn' => $matIn,
                                'donGiaIn' => $donGiaIn,
                                'soLuong' => $v,
                                'kieu_in_tro' => $kieu_in_tro,
                                'quy_cach_in' => $quy_cach_in,
                                'soTay' => $soTay,
                                'soMauMay' => $soMauMay,
                            ];
                            $chiPhiIn += self::TinhInLuotBoKem($options_L);
                        } else if ($t == 'L') {
                            $options_K = [
                                'soMauKem' => $soMauKem,
                                'donGiaKem' => $donGiaKem,
                                'mauPha' => $mauPha,
                                'heSoMauPha' => $heSoMauPha,
                                'soTay' => $soTay,
                            ];
                            $chiPhiIn += self::TinhInKem($options_K);
                            $options_L = [
                                'soMauKem' => $soMauKem,
                                'donGiaKem' => $donGiaKem,
                                'soTo' => $tinh_theo_to > 0 ? $v : ceil($v / $thanhPham),
                                'matIn' => $matIn,
                                'donGiaIn' => $donGiaIn,
                                'soLuong' => $v,
                                'kieu_in_tro' => $kieu_in_tro,
                                'quy_cach_in' => $quy_cach_in,
                                'soTay' => $soTay,
                                'soMauMay' => $soMauMay,
                            ];
                            $chiPhiIn += self::TinhInLuotBoKem($options_L);
                        }
                    }
                    if ($t == 'K_') {
                        $options_K = [
                            'soMauKem' => $soMauKem,
                            'donGiaKem' => $donGiaKem,
                            'mauPha' => $mauPha,
                            'heSoMauPha' => $heSoMauPha,
                            'soTay' => $soTay,
                        ];
                        $chiPhiIn += self::TinhInKem($options_K);
                        $options_L = [
                            'soMauKem' => $soMauKem,
                            'donGiaKem' => $donGiaKem,
                            'soTo' => $soLuongSoSanh - $v,
                            'matIn' => $matIn,
                            'donGiaIn' => $donGiaIn,
                            'kieu_in_tro' => $kieu_in_tro,
                            'quy_cach_in' => $quy_cach_in,
                            'soTay' => $soTay,
                        ];
                        $chiPhiIn += self::TinhInLuot($options_L);
                    } else if ($t == 'L_') {
                        $options_K = [
                            'soMauKem' => $soMauKem,
                            'donGiaKem' => $donGiaKem,
                            'soTay' => $soTay,
                        ];
                        $options_L = [
                            'soMauKem' => $soMauKem,
                            'donGiaKem' => $donGiaKem,
                            'soTo' => $tinh_theo_to > 0 ? $v : ceil($v / $thanhPham),
                            'matIn' => $matIn,
                            'donGiaIn' => $donGiaIn,
                            'soLuong' => $v,
                            'mauPha' => $mauPha,
                            'heSoMauPha' => $heSoMauPha,
                            'kieu_in_tro' => $kieu_in_tro,
                            'quy_cach_in' => $quy_cach_in,
                            'soTay' => $soTay,
                            'soMauMay' => $soMauMay,
                        ];
                        $chiPhiIn += self::TinhInKem($options_K);
                        $chiPhiIn += self::TinhInLuotBoKem($options_L);
                    }

                    if ($chiPhiIn > 0) {
                        return $chiPhiIn;
                    } else {
                        return 0;
                    }
                } else if ($soLuongSoSanh <= $v_ss) {
                    if ($p == 'S') {
                        if ($t == 'L') {
                            $chiPhiIn += self::TinhInKem(['soMauKem' => $soMauKem, 'donGiaKem' => $donGiaKem, 'mauPha' => $mauPha, 'heSoMauPha' => $heSoMauPha, 'soTay' => $soTay,]);
                        } else {
                            $chiPhiIn += self::TinhInLuot(['soMauKem' => $soMauKem, 'donGiaKem' => $donGiaKem, 'soTo' => $soTo, 'matIn' => $matIn, 'donGiaIn' => $donGiaIn, 'soTay' => $soTay]);
                        }
                    } else if ($p == 'T') {
                        if ($t == 'K') {
                            $chiPhiIn += self::TinhInKem(['soMauKem' => $soMauKem, 'donGiaKem' => $donGiaKem, 'mauPha' => $mauPha, 'heSoMauPha' => $heSoMauPha, 'soTay' => $soTay,]);
                        } else {
                            $chiPhiIn += self::TinhInLuot(['soMauKem' => $soMauKem, 'donGiaKem' => $donGiaKem, 'soTo' => $soTo, 'matIn' => $matIn, 'donGiaIn' => $donGiaIn, 'soTay' => $soTay]);
                        }
                    }

                    if ($chiPhiIn > 0) {
                        return $chiPhiIn;
                    } else {
                        return 0;
                    }
                }
            }
        }

        if ($soLuongSoSanh <= 1000) {
            $chiPhiIn += self::TinhInKem(['soMauKem' => $soMauKem, 'donGiaKem' => $donGiaKem, 'mauPha' => $mauPha, 'heSoMauPha' => $heSoMauPha, 'soTay' => $soTay,]);
        } else {
            $chiPhiIn += self::TinhInLuot(['soMauKem' => $soMauKem, 'donGiaKem' => $donGiaKem, 'soTo' => $soTo, 'matIn' => $matIn, 'donGiaIn' => $donGiaIn, 'soTay' => $soTay]);
        }
        if ($chiPhiIn > 0) {
            return $chiPhiIn;
        } else {
            return 0;
        }
    }

    public function _productSize($data, $return_json = false)
    {
        if ($data->dai > $data->rong) {
            $data->rong;
        } else {
            $data->dai;
        }
        $banTinh = $data->banTinh;
        $result = self::_timKhoGiay($data);
        $result = @array_filter($result);
        if (!empty($result)) {
            $return = [];
            if ($data->type == 'gb' || ($data->type == 'gr' && $data->istayCuoi == 1)) {
                $min = 9999999999999; //will hold max val
                $index = null; //will hold item with max val;
                foreach ($result as $k => $v) {
                    if ($v['tong_chi_phi'] < $min) {
                        $min = $v['tong_chi_phi'];
                        $index = $k;
                    } else if ($v['tong_chi_phi'] == $min) {
                        if ($result[$index]['du_lieu']['thanh_pham'] < $v['du_lieu']['thanh_pham']) {
                            $index = $k;
                        }
                    }
                }
                $value = $result[$index]['du_lieu'];
                $tong_thanh_pham = $value['thanh_pham'];
                $kho_may = !empty($value['kho_may']) ? $value['kho_may'] : '';
                $ncc = !empty($value['ncc']) ? $value['ncc'] : '';
                $so_to = !empty($value['so_to']) ? $value['so_to'] : 0;
                $chiphigiayin = !empty($value['chi_phi_giay_in']) ? $value['chi_phi_giay_in'] : 0;
                $a = !empty($value['a']) ? $value['a'] : [];
                $khogiay = !empty($value['kho_giay']) ? $value['kho_giay'] : [];
                $chiphiin = !empty($value['chi_phi_in']) && $banTinh == 'thuongmai' ? $value['chi_phi_in'] : 0;
                $kieu_in_tro = !empty($value['kieu_in_tro']) ? $value['kieu_in_tro'] : '';
                $soMauKem = !empty($value['soMauKem']) ? $value['soMauKem'] : $data->soMauKem;

                $return = [
                    'success' => true,
                    'info' => $khogiay,
                    'ncc' => $ncc,
                    'khoMay' => $kho_may,
                    'thanhPham' => $tong_thanh_pham,
                    'soTo' => $so_to,
                    'chiPhiGiayIn' => $chiphigiayin,
                    'toBuHao' => $value['to_bu_hao'],
                    'a' => $a,
                    'don_gia_giay_in' => $value['don_gia_giay_in'],
                    'ncc_giay_in' => $value['ncc_giay_in'],
                    'chiPhiIn' => $chiphiin,
                    'kieu_in_tro' => $kieu_in_tro,
                    'soMauKem' => $soMauKem,
                ];
            } else {
                foreach ($result as $k_ => $v_) {
                    $value = $v_['du_lieu'];
                    $tong_thanh_pham = $value['thanh_pham'];
                    $kho_may = !empty($value['kho_may']) ? $value['kho_may'] : '';
                    $ncc = !empty($value['ncc']) ? $value['ncc'] : '';
                    $so_to = !empty($value['so_to']) ? $value['so_to'] : 0;
                    $so_tay = !empty($value['so_tay']) ? $value['so_tay'] : 0;
                    $trang_tren_tay = !empty($value['trang_tren_tay']) ? $value['trang_tren_tay'] : 0;
                    $chiphigiayin = !empty($value['chi_phi_giay_in']) ? $value['chi_phi_giay_in'] : 0;
                    $chiphiin = !empty($value['chi_phi_in']) && $banTinh == 'thuongmai' ? $value['chi_phi_in'] : 0;
                    $trang_du = !empty($value['trang_du']) ? $value['trang_du'] : 0;
                    $a = !empty($value['a']) ? $value['a'] : [];
                    $khogiay = !empty($value['kho_giay']) ? $value['kho_giay'] : [];
                    $kieu_in_tro = !empty($value['kieu_in_tro']) ? $value['kieu_in_tro'] : '';
                    $soMauKem = !empty($value['soMauKem']) ? $value['soMauKem'] : $data->soMauKem;

                    $return_[$k_] = [
                        'success' => true,
                        'info' => $khogiay,
                        'ncc' => $ncc,
                        'khoMay' => $kho_may,
                        'thanhPham' => $tong_thanh_pham,
                        'soTo' => $so_to,
                        'soTay' => $data->type == 'gb' && $data->biaGhepRuot != 1 ? 0 : $so_tay,
                        'trangTrenTay' => $data->type == 'gb' && $data->biaGhepRuot != 1 ? 0 : $trang_tren_tay,
                        'trangDu' => $data->type == 'gb' && $data->biaGhepRuot != 1 ? 0 : $trang_du,
                        'chiPhiGiayIn' => $chiphigiayin,
                        'toBuHao' => $value['to_bu_hao'],
                        'a' => $a,
                        'don_gia_giay_in' => $value['don_gia_giay_in'],
                        'ncc_giay_in' => $value['ncc_giay_in'],
                        'chiPhiIn' => $chiphiin,
                        'kieu_in_tro' => $kieu_in_tro,
                        'soMauKem' => $soMauKem,
                    ];
                    if ($trang_du > 0 && $data->nap_hop != 1) {
                        $data_ = $data;
                        if ($data->inChungKho == 1)
                            $data_->khoGiay = $khogiay['content_id'];
                        else
                            $data_->khoGiay = $data->khoGiayTayCuoi;
                        $data_->trangDu = $trang_du;
                        $data_->soToBuHao = $data->buHaoTayCuoi;
                        $data_->istayCuoi = 1;
                        $data_->khoMay = 0;
                        $data_->kieuTro = '';
                        $data_->thanhPham = $data->thanhPhamTayCuoi;
                        $quycachin = self::TinhQuyCachIn($data->quyCachIn);
                        $soMatIn = $data_->matIn = $quycachin['so_mat'];
                        $soMauKem = $data_->soMauKem = $quycachin['so_mau'];
                        $result_ = self::_timKhoGiay($data_);
                        unset($data_);
                        if (!empty($result_)) {
                            $min_ = 99999999999999; //will hold max val
                            $index_ = null; //will hold item with max val;
                            foreach ($result_ as $k => $v) {
                                if (isset($v['tong_chi_phi'])) {
                                    if ($v['tong_chi_phi'] < $min_) {
                                        $min_ = $v['tong_chi_phi'];
                                        $index_ = $k;
                                    } else if ($v['tong_chi_phi'] == $min_) {
                                        if ($result_[$index_]['du_lieu']['thanh_pham'] < $v['du_lieu']['thanh_pham']) {
                                            $index_ = $k;
                                        }
                                    }
                                }
                            }
                            $value_ = $result_[$index_]['du_lieu'];
                            $tong_thanh_pham = $value_['thanh_pham'];
                            $kho_may = !empty($value_['kho_may']) ? $value_['kho_may'] : '';
                            $ncc = !empty($value_['ncc']) ? $value_['ncc'] : '';
                            $so_to = !empty($value_['so_to']) ? $value_['so_to'] : 0;
                            $so_tay = !empty($value_['so_tay']) ? $value_['so_tay'] : 0;
                            $trang_tren_tay = !empty($value_['trang_tren_tay']) ? $value_['trang_tren_tay'] : 0;
                            $chiphigiayin = !empty($value_['chi_phi_giay_in']) ? $value_['chi_phi_giay_in'] : 0;
                            $trang_du = !empty($value_['trang_du']) ? $value_['trang_du'] : 0;
                            $a = !empty($value_['a']) ? $value_['a'] : [];
                            $khogiay = !empty($value_['kho_giay']) ? $value_['kho_giay'] : [];
                            $chiphiin = !empty($value_['chi_phi_in']) && $banTinh == 'thuongmai' ? $value_['chi_phi_in'] : 0;
                            $kieu_in_tro = !empty($value_['kieu_in_tro']) ? $value_['kieu_in_tro'] : '';
                            $soMauKem = !empty($value_['soMauKem']) ? $value_['soMauKem'] : $soMauKem;

                            $return_[$k_]['tay_cuoi'] = [
                                'info' => $khogiay,
                                'ncc' => $ncc,
                                'khoMay' => $kho_may,
                                'chiPhiIn' => $chiphiin,
                                'thanhPham' => $tong_thanh_pham,
                                'soTo' => $so_to,
                                'soTay' => $data->type == 'gb' && $data->biaGhepRuot != 1 ? 0 : $so_tay,
                                'trangTrenTay' => $data->type == 'gb' && $data->biaGhepRuot != 1 ? 0 : $trang_tren_tay,
                                'trangDu' => $data->type == 'gb' && $data->biaGhepRuot != 1 ? 0 : $trang_du,
                                'chiPhiGiayIn' => $chiphigiayin,
                                'toBuHao' => $value_['to_bu_hao'],
                                'soMauKem' => $soMauKem,
                                'soMatIn' => $soMatIn,
                                'a' => $a,
                                'kieu_in_tro' => $kieu_in_tro,
                                'soMauKem' => $soMauKem,
                            ];
                        }
                    }
                }
//                print_r($return_);
                if (!empty($return_)) {
                    $min = 99999999999999; //will hold max val
                    $index = null; //will hold item with max val;
                    foreach ($return_ as $k => $v) {
                        $tong_chi_phi = ($banTinh == 'thuongmai' ? $v['chiPhiIn'] : 0) + $v['chiPhiGiayIn'] + (!empty($v['tay_cuoi']) ? ($banTinh == 'thuongmai' ? $v['tay_cuoi']['chiPhiIn'] : 0) + $v['tay_cuoi']['chiPhiGiayIn'] : 0);
                        if ($tong_chi_phi < $min) {
                            $min = $tong_chi_phi;
                            $index = $k;
                        } else if ($tong_chi_phi == $min) {
                            if ($return_[$index]['thanhPham'] < $v['thanhPham']) {
                                $index = $k;
                            }
                        }
                    }

                    $return = $return_[$index];
                }

            }

        } elseif ($data->dai == 0 || $data->rong == 0 || $data->nhaCungCapIn == 0 || $data->soMauKem == 0 || $data->soLuong == 0 || $data->matIn == 0) {
            $return = ['success' => false, 'msg' => ''];
        } else {
            $return = ['success' => false, 'msg' => 'Không tìm thấy khổ giấy nào phù hợp. Vui lòng kiểm tra lại khổ giấy in hoặc khổ máy in.'];
        }
        if ($return_json) {
            header('Content-type: application/json; charset=utf-8');
            header('Cache-Control: max-age=2592000, public');
            echo json_encode($return);
            unset($return);
            unset($data);
            exit;
        }

        return $return;
    }

    protected function _timKhoGiay($data = null)
    {
        $settings = Yii::$app->settings;
        $return = [];
        $buHaoMacDinh = (int)$settings->get('so_to_bu_hao');
        $buHaTheoTay = (int)$settings->get('bu_hao_theo_tay');
        $buHaTheoSoMau = (int)$settings->get('bu_hao_theo_so_mau');
        $addCondition = '';
        if ($data->khoKemRong > 0 && $data->khoKemDai > 0 && $data->kieuInGiaTrucTiep != 1)
            $addCondition = "width <= {$data->khoKemRong} AND length <= {$data->khoKemDai}";

        $khoInMacDinh = $min_max = [];
        if ($data->khoMay > 0) {
            $khoInMacDinh = Giakhomayin::findAll($data->khoMay);
            if (!empty($khoInMacDinh) && empty($addCondition)) {
                $addCondition = "width <= {$khoInMacDinh->kem_rong} AND length <= {$khoInMacDinh->kem_dai}";
            }
        }

        $query = Content::find()->where(['status' => 1, 'type' => Content::TYPE_KHO_GIAY_IN])->select('content_id, title, width, length');
        if (!empty($addCondition))
            $query->andWhere($addCondition);
        if ($data->khoGiay > 0)
            $query->andWhere('content_id = ' . intval($data->khoGiay));
        $list_kho_giay = $query->all();
        if (!empty($list_kho_giay)) {
            foreach ($list_kho_giay as $k => $kho_giay) {
                $mang_thanh_pham = $mang_thanh_pham_max = [];
                if ($data->thanhPham > 0) {
                    $mang_thanh_pham[] = $data->thanhPham;
                } else {
                    $proInPage = new findPrintPaperSize();
                    $proInPage->inputs = $data;
                    $proInPage->kho_giay_rong = $kho_giay->width;
                    $proInPage->kho_giay_dai = $kho_giay->length;
                    $proInPage->loai_giay = $data->type;
                    $proInPage->nap_hop = $data->nap_hop > 0 ? 1 : 0;
                    $mang_thanh_pham = $proInPage->Calculator();
                }
//print_r($kho_giay->title);
                if (!empty($mang_thanh_pham)) {
                    foreach ($mang_thanh_pham as $thanh_pham) {
                        // --------------------------------------------------
                        $quy_cach = self::TinhQuyCachIn($data->quyCachIn);
                        if ($data->kieuTro == '' && $quy_cach['kieu_tro_no'] == true && $quy_cach['so_mat'] == 2) {
                            //tinh chi phi in theo tung kieu tro
                            //No tro no
                            $quy_cach = self::TinhQuyCachIn($data->quyCachIn, 1);
                            $data->kieuTro = OrderInfo::KIEU_TRO_NO;
                            $data->soMauKem = $quy_cach['mau_mat_1'];
                            $return[] = self::xuatDuLieu($data, $thanh_pham, $kho_giay, $buHaTheoTay, $buHaoMacDinh, $buHaTheoSoMau, $khoInMacDinh);

                            //No tro khac
                            $quy_cach = self::TinhQuyCachIn($data->quyCachIn, 2);
                            $data->kieuTro = OrderInfo::KIEU_TRO_KHAC;
                            $data->soMauKem = $quy_cach['so_mau'];
                            $return[] = self::xuatDuLieu($data, $thanh_pham, $kho_giay, $buHaTheoTay, $buHaoMacDinh, $buHaTheoSoMau, $khoInMacDinh);
                            $data->kieuTro = '';
                        } else {
                            if ($data->kieuTro == '')
                                $data->kieuTro = $quy_cach['kieu_tro_no'] == true ? OrderInfo::KIEU_TRO_NO : OrderInfo::KIEU_TRO_KHAC;
                            $return[] = self::xuatDuLieu($data, $thanh_pham, $kho_giay, $buHaTheoTay, $buHaoMacDinh, $buHaTheoSoMau, $khoInMacDinh);
                        }

                    }
                }
            }
        }
        unset($data);
        return $return;
    }

    protected function xuatDuLieu($data, $thanh_pham, $kho_giay, $buHaTheoTay, $buHaoMacDinh, $buHaTheoSoMau, $khoInMacDinh)
    {
        $settings = Yii::$app->settings;
        $banTinh = $data->banTinh;
        $return = [];
        // --------------------------------------------------
        $soTay = $so_to_in = $trangTrenTay = $trangDu = 0;
        $soTo = $soToGoc = $data->soLuong > 0 && $thanh_pham > 0 ? ceil($data->soLuong / $thanh_pham) : 0;
        $dai_ = $kho_giay->length / 100;
        $rong_ = $kho_giay->width / 100;

        if ($data->thanhPham > 0) {
            $proInPage = new findPrintPaperSize();
            $proInPage->inputs = $data;
            $proInPage->kho_giay_rong = $kho_giay->width;
            $proInPage->kho_giay_dai = $kho_giay->length;
            $proInPage->loai_giay = $data->type;
            $proInPage->nap_hop = $data->nap_hop > 0 ? 1 : 0;
            $mang_thanh_pham = $proInPage->Calculator();
            $san_phan_tren_to = max($mang_thanh_pham) * $data->matIn / $data->thanhPham;
        } else
            $san_phan_tren_to = $data->kieuTro == OrderInfo::KIEU_TRO_NO && $data->matIn == 2 ? 2 : 1;

        if ($data->kieuDong == OrderInfo::DONG_GIUA) {
            $nhanThem = 2;
        } else {
            $nhanThem = 1;
        }

        if ($data->type == 'gr' && $data->istayCuoi != 1) {
            $tay = $data->soLuong > 0 && $thanh_pham > 0 ? ($data->soLuong / (($thanh_pham * $data->matIn * $nhanThem) / ($data->kieuTro == OrderInfo::KIEU_TRO_NO ? 2 : 1))) : 0;
            $whole = floor($tay);
            $fraction = $tay - $whole;
            if ($data->banTinh == 'thuongmai') {
                switch (true) {
                    case $fraction == 0:
                        $soTay = floor($tay);
                        break;
                    case $fraction <= 0.5:
                        $soTay = floor($tay) + 0.5;
                        break;
                    case $fraction > 0.5:
                    default:
                        $soTay = ceil($tay);
                        break;
                }
                if (is_float($tay) && $tay <= floor($tay) + 0.5 && $data->soSanPham > 0 && $thanh_pham > 0) {
                    $sp_ = $tay >= 1 ? ($data->soLuong - floor($tay) * $thanh_pham * $nhanThem * $data->matIn / $san_phan_tren_to) : 0;
                    $trangTrenTay = $sp_ > 0 ? ceil($data->soSanPham * $sp_ / ($thanh_pham * $data->matIn * $nhanThem / $san_phan_tren_to)) : 0;
                    $trangDu = $sp_ > 0 ? $data->soSanPham * $sp_ : 0;
                } else {
                    $trangTrenTay = 0;
                    $trangDu = 0;
                }
            } else {
                switch (true) {
                    case $fraction == 0:
                        $soTay = floor($tay);
                        break;
                    case $fraction <= 0.25:
                        $soTay = floor($tay) + 0.25;
                        break;
                    case $fraction <= 0.5:
                        $soTay = floor($tay) + 0.5;
                        break;
                    case $fraction <= 0.75:
                        $soTay = floor($tay) + 0.75;
                        break;
                    case $fraction > 0.75:
                    default:
                        $soTay = ceil($tay);
                        break;
                }
                if (is_float($tay) && $tay <= floor($tay) + 0.75 && $data->soSanPham > 0 && $thanh_pham > 0) {
                    $sp_ = $tay >= 1 ? ($data->soLuong - floor($tay) * $thanh_pham * $nhanThem * $data->matIn) : 0;
                    $trangTrenTay = $sp_ > 0 ? ceil($data->soSanPham * $sp_ / ($thanh_pham * $data->matIn * $nhanThem)) : 0;
                    $trangDu = $sp_ > 0 ? $data->soSanPham * $sp_ : 0;
                } else {
                    $trangTrenTay = 0;
                    $trangDu = 0;
                }
            }

            $data->trangDu = $trangDu;
        }
        if ($data->istayCuoi == 1) {
            $bat_le = $data->trangDu / $data->soSanPham / ($data->matIn * $nhanThem);
            if ($thanh_pham >= $bat_le && $bat_le > 0) {
                if ($data->thanhPham == 0)
                    $thanh_pham = $bat_le * floor($thanh_pham / $bat_le);
                $so_to_in = $data->trangDu > 0 ? ceil($data->trangDu / $thanh_pham / ($nhanThem * 2)) : 0;
                $soTo = $so_to_in;
            } else {
                $so_to_in = 0;
            }
        } elseif ($data->type == 'gb' || $data->kieuHop == Products::HOP_CUNG_DINH_HINH) {
            $so_to_in = $soToGoc;
            $soTay = 0;
            $trangDu = 0;
            $trangTrenTay = 0;
        } elseif ($data->soLuong > 0 && $thanh_pham > 0) {
            $soTayNhan = $soTay;
            if ($soTay > 1)
                $soTayNhan = floor($soTay);
            $so_to_in = $soTayNhan * $data->soSanPham / $san_phan_tren_to;
        }

        if ($so_to_in > 0) {
            $soToBuHao = $data->soToBuHao;
            //tinh so to bu hao
            if ($soToBuHao < 0) {
                $so_to_in_ = $data->type == 'gr' && $buHaTheoTay == 1 ? $data->soSanPham : $so_to_in;

                $buhao = Tobuhao::find()->where("top_index <= {$so_to_in_} AND {$so_to_in_} <= last_index")->one();
                if (!empty($buhao)) {
                    if (strpos($buhao->value, '%') !== false) {
                        $soToBuHao = intval(ceil($so_to_in_ * floatval($buhao->value) / 100));
                    } else {
                        $soToBuHao = intval($buhao->value);
                    }
                    if ($soToBuHao < $buHaoMacDinh)
                        $soToBuHao = $buHaoMacDinh;
                } else {
                    $soToBuHao = (int)$settings->get('so_to_bu_hao');
                }

                if ($data->type == 'gr' && $buHaTheoTay == 1) {
                    if ($data->istayCuoi == 1) {
//                      $soToBuHao = $data->inChungKho == 1 ? ceil($soToBuHao * 0.5) : $soToBuHao;
                        $soToBuHao = ceil($soToBuHao * 0.5);
                    } else
                        $soToBuHao = ceil($soToBuHao * floor($soTay));
                } else
                    $soToBuHao = ceil($soToBuHao * ($buHaTheoSoMau == 1 ? $data->soMauKem : 1));

                if ($soToBuHao < $buHaoMacDinh)
                    $soToBuHao = $buHaoMacDinh;
            }
            if ($data->banTinh == 'thuongmai')
                $chi_phi_giay_in = round($rong_ * $dai_ * $data->dinhLuong * ($data->donGia / 1.1) * ($so_to_in + $soToBuHao));
            else
                $chi_phi_giay_in = round($data->donGia * $data->dinhLuong / 1000 * $rong_ * $dai_ * ($so_to_in + $soToBuHao));

            if ($data->type == 'gb' && $data->biaGhepRuot == 1)
                $chi_phi_giay_in = 0;
            //phan tinh chi phi theo kieu in
            $query_ncc = Supplier::find()->where(['status' => 1, 'groupid' => Supplier::GROUP_PRINT]);
            if ($data->nhaCungCapIn > 0)
                $query_ncc->andWhere(['supplierid' => intval($data->nhaCungCapIn)]);
            $nha_cung_cap = $query_ncc->all();
            if (!empty($nha_cung_cap) && ($chi_phi_giay_in > 0 || ($chi_phi_giay_in == 0 && $so_to_in > 0) || ($chi_phi_giay_in == 0 && $data->type == 'gb')) && $data->kieuInGiaTrucTiep != 1) {
                foreach ($nha_cung_cap as $t => $ncc) {
                    if (!empty($khoInMacDinh)) {
                        $giakhomayin_list = $khoInMacDinh;
                    } else {
                        $sqlQuery = "SELECT * FROM {{%giakhomayin}} WHERE supplierId = {$ncc->supplierid} AND kem_dai >= {$kho_giay->length} AND kem_rong >= {$kho_giay->width}";
                        $giakhomayin_list = Giakhomayin::findBySql($sqlQuery)->all();
                    }
                    if (!empty($giakhomayin_list)) {
                        foreach ($giakhomayin_list as $giakhomayin) {
                            if (isset($banTinh) && $banTinh == 'thuongmai') {
                                $soTo_ = $soTo;
                                if ($ncc->to_bu_hao == 1) {
                                    $soTo_ += $soToBuHao;
                                }
                                $chiPhiKieuIn = new calPrintingCosts();
                                $chiPhiKieuIn->inputs = [
                                    'type' => !empty($data->cachTinh) ? $data->cachTinh : $ncc->calcu_type,
                                    'soLuong' => $data->soLuong,
                                    'soTo' => $soTo_,
                                    'soMauKem' => $data->soMauKem,
                                    'donGiaKem' => $data->DonGiaKem >= 0 && $data->khoMay > 0 ? $data->DonGiaKem : $giakhomayin->price_k * $giakhomayin->kem_dai * $giakhomayin->kem_rong,
                                    'matIn' => $data->matIn,
                                    'donGiaIn' => $data->DonGiaIn >= 0 && $data->khoMay > 0 ? $data->DonGiaIn : $giakhomayin->price_i,
                                    'thanhPham' => $thanh_pham,
                                    'soToBuHao' => $soToBuHao,
                                    'tinh_theo_to' => $ncc->kieu_tinh_to,
                                    'mauPha' => $data->mauPha,
                                    'heSoMauPha' => $data->heSoMauPha,
                                    'quyCachIn' => $data->quyCachIn,
                                    'kieu_in_tro' => $data->kieuTro,
                                    'soTay' => 1,
                                    'soMauMay' => $giakhomayin->so_mau,
                                ];
                                $a_ = [
                                    $chiPhiKieuIn->inputs,
                                    "$rong_ * $dai_ * $data->dinhLuong * ($data->donGia / 1.1) * ($so_to_in + $soToBuHao)"
                                ];
                                $chi_phi_ = round($chiPhiKieuIn->tinhChiPhiIn());

                                if ($data->type == 'gr' && (!isset($data->istayCuoi) || (isset($data->istayCuoi) && $data->istayCuoi == 0)) && $data->kieuHop != Products::HOP_CUNG_DINH_HINH) {
                                    if ($ncc->to_bu_hao == 1) {
                                        $soLuongTay1 = ($so_to_in / ($soTay > 1 ? floor($soTay) : 1)) + ($soToBuHao / ($soTay > 1 ? floor($soTay) : 1));
                                        $soLuongTay2 = $so_to_in / ($soTay > 1 ? floor($soTay) : 1);
                                        if ($buHaTheoTay == 1) {
                                            $soLuongTay2 += ($soToBuHao / ($soTay > 1 ? floor($soTay) : 1));
                                        }
                                    } else {
                                        $soLuongTay1 = $soLuongTay2 = $so_to_in / ($soTay > 1 ? floor($soTay) : 1);
                                    }

                                    $chiPhiKieuIn1 = new calPrintingCosts();
                                    $chiPhiKieuIn1->inputs = [
                                        'type' => !empty($data->cachTinh) ? $data->cachTinh : $ncc->calcu_type,
                                        'soLuong' => $soLuongTay1,
                                        'soTo' => $soLuongTay1,
                                        'soMauKem' => $data->soMauKem,
                                        'donGiaKem' => $data->DonGiaKem >= 0 && $data->khoMay > 0 ? $data->DonGiaKem : $giakhomayin->price_k * $giakhomayin->kem_dai * $giakhomayin->kem_rong,
                                        'matIn' => $data->matIn,
                                        'donGiaIn' => $data->DonGiaIn >= 0 && $data->khoMay > 0 ? $data->DonGiaIn : $giakhomayin->price_i,
                                        'thanhPham' => $thanh_pham,
                                        'soToBuHao' => 0,
                                        'tinh_theo_to' => $ncc->kieu_tinh_to,
                                        'mauPha' => $data->mauPha,
                                        'heSoMauPha' => $data->heSoMauPha,
                                        'quyCachIn' => $data->quyCachIn,
                                        'kieu_in_tro' => $data->kieuTro,
                                        'soTay' => 1,
                                        'soMauMay' => $giakhomayin->so_mau,
                                    ];
                                    $chiPhiInRuot1 = $chiPhiKieuIn1->tinhChiPhiIn();
                                    $chiPhiKieuIn2 = new calPrintingCosts();
                                    $chiPhiKieuIn2->inputs = [
                                        'type' => !empty($data->cachTinh) ? $data->cachTinh : $ncc->calcu_type,
                                        'soLuong' => $soLuongTay2,
                                        'soTo' => $soLuongTay2,
                                        'soMauKem' => $data->soMauKem,
                                        'donGiaKem' => $data->DonGiaKem >= 0 && $data->khoMay > 0 ? $data->DonGiaKem : $giakhomayin->price_k * $giakhomayin->kem_dai * $giakhomayin->kem_rong,
                                        'matIn' => $data->matIn,
                                        'donGiaIn' => $data->DonGiaIn >= 0 && $data->khoMay > 0 ? $data->DonGiaIn : $giakhomayin->price_i,
                                        'thanhPham' => $thanh_pham,
                                        'soToBuHao' => 0,
                                        'tinh_theo_to' => $ncc->kieu_tinh_to,
                                        'mauPha' => $data->mauPha,
                                        'heSoMauPha' => $data->heSoMauPha,
                                        'quyCachIn' => $data->quyCachIn,
                                        'kieu_in_tro' => $data->kieuTro,
                                        'soTay' => $soTay > 0 ? (($soTay > 1 ? floor($soTay) : 1) - 1) : 0,
                                        'soMauMay' => $giakhomayin->so_mau,
                                    ];
                                    $chiPhiInRuot2 = $chiPhiKieuIn2->tinhChiPhiIn();

                                    if ($chiPhiInRuot1 >= 0 && $chiPhiInRuot2 >= 0) {
                                        $chi_phi_ = round($chiPhiInRuot1 + $chiPhiInRuot2);
                                        $a_ = [
                                            $chiPhiKieuIn1->inputs,
                                            $chiPhiKieuIn2->inputs,
                                            $chiPhiInRuot1,
                                            $chiPhiInRuot2,
                                            "$rong_ * $dai_ * $data->dinhLuong * ($data->donGia / 1.1) * ($so_to_in + $soToBuHao)"
                                        ];
                                    } else {
                                        $a_ = [];
                                        $chi_phi_ = 0;
                                    }
                                }
                                if ($chi_phi_ > 0) {
                                    $return = [
                                        'tong_chi_phi' => ($banTinh == 'thuongmai' ? $chi_phi_ : 0) + $chi_phi_giay_in,
                                        'du_lieu' => [
                                            'ncc' => $ncc->attributes,
                                            'kho_may' => $giakhomayin->attributes,
                                            'so_to' => $so_to_in,
                                            'so_tay' => $soTay,
                                            'trang_tren_tay' => $trangTrenTay,
                                            'chi_phi_in' => $chi_phi_,
                                            'chi_phi_giay_in' => $chi_phi_giay_in,
                                            'trang_du' => $trangDu,
                                            'kho_giay' => $kho_giay->attributes,
                                            'thanh_pham' => $thanh_pham,
                                            'to_bu_hao' => $soToBuHao,
                                            'a' => $a_,
                                            'don_gia_giay_in' => $data->donGia,
                                            'ncc_giay_in' => $data->nhaCungCap,
                                            'kieu_in_tro' => $data->kieuTro,
                                            'soMauKem' => intval($data->soMauKem),
                                        ]
                                    ];
                                }
                            } else {
                                $return = [
                                    'tong_chi_phi' => $chi_phi_giay_in,
                                    'du_lieu' => [
                                        'ncc' => $ncc->attributes,
                                        'kho_may' => $giakhomayin->attributes,
                                        'so_to' => $so_to_in,
                                        'so_tay' => $soTay,
                                        'trang_tren_tay' => $trangTrenTay,
                                        'chi_phi_giay_in' => $chi_phi_giay_in,
                                        'trang_du' => $trangDu,
                                        'kho_giay' => $kho_giay->attributes,
                                        'thanh_pham' => $thanh_pham,
                                        'to_bu_hao' => $soToBuHao,
                                        'don_gia_giay_in' => $data->donGia,
                                        'ncc_giay_in' => $data->nhaCungCap,
                                        'kieu_in_tro' => $data->kieuTro,
                                        'soMauKem' => intval($data->soMauKem),
                                    ]
                                ];
                            }
                        }
                    }
                }
            } elseif ($chi_phi_giay_in > 0 && $data->kieuInGiaTrucTiep == 1 && $thanh_pham > 0) {
                $return = [
                    'tong_chi_phi' => $chi_phi_giay_in,
                    'du_lieu' => [
                        'ncc' => null,
                        'kho_may' => null,
                        'so_to' => $so_to_in,
                        'so_tay' => $soTay,
                        'trang_tren_tay' => $trangTrenTay,
                        'chi_phi_giay_in' => $chi_phi_giay_in,
                        'trang_du' => $trangDu,
                        'kho_giay' => $kho_giay->attributes,
                        'thanh_pham' => $thanh_pham,
                        'to_bu_hao' => $soToBuHao,
                        'don_gia_giay_in' => $data->donGia,
                        'ncc_giay_in' => $data->nhaCungCap,
                        'kieu_in_tro' => $data->kieuTro,
                        'soMauKem' => intval($data->soMauKem),
                    ]
                ];
            } elseif (($data->nhaCungCapIn == 0 || $data->soMauKem == 0 || $data->matIn == 0) && $thanh_pham > 0) {
                $return['tong_chi_phi'] = $chi_phi_giay_in;
            }

        }
        // --------------------------------------------------
        return $return;
    }

    public function _tinhChiPhiIn($data = null)
    {
        if (empty($data))
            return false;
        $settings = Yii::$app->settings;
        $OrderInfo = $data['OrderInfo'][0];
        $OrdersPaper = $data['OrdersPaper'][0];
        $OtherCost = $data['OtherCost'][0];
        $chiphi = [
            'NVL' => [],
            'chiPhiChungKhac' => 0,
            'NCTT' => 0,
            'chiPhiNangLuong' => 0,
            'chiPhiPhanXuong' => 0,
            'KHTSCD' => 0,
            'toGac' => [],
            'biaCarton' => [],
            'chiPhiBaoHiem' => 0
        ];
        $tayChan = floor($OrdersPaper['SoTay']);
        $tayLe = $OrdersPaper['SoTay'] - $tayChan;
        $heSoTayLe = 0;
        $trangTrenTayRuot = $OrdersPaper['GiayRuotThanhPham'] * ($OrderInfo['KieuDong'] == OrderInfo::DONG_GIUA ? 4 : 2);
        $trangTrenTayBia = $OrdersPaper['GiayBiaThanhPham'] * 4;
        if ($tayLe == 0.25 || $tayLe == 0.5)
            $heSoTayLe = 1;
        elseif ($tayLe == 0.75)
            $heSoTayLe = 2;
        $OrdersPaper['GiayBiaSoTo'] = floatval(AcpHelper::removeFormat($OrdersPaper['GiayBiaSoTo']));
        $OrdersPaper['GiayRuotSoTo'] = floatval(AcpHelper::removeFormat($OrdersPaper['GiayRuotSoTo']));
        $OrdersPaper['TayCuoiSoTo'] = floatval(AcpHelper::removeFormat($OrdersPaper['TayCuoiSoTo']));
        $OrdersPaper['GiayRuotPrice'] = floatval(AcpHelper::removeFormat($OrdersPaper['GiayRuotPrice']));
        $OrdersPaper['BiaCartonDonGia'] = floatval(AcpHelper::removeFormat($OrdersPaper['BiaCartonDonGia']));
        $OrdersPaper['ToGacPrice'] = floatval(AcpHelper::removeFormat($OrdersPaper['ToGacPrice']));
        $OrderInfo['amount'] = floatval(AcpHelper::removeFormat($OrderInfo['amount']));
        $OrderInfo['inner_page_amount'] = floatval(AcpHelper::removeFormat($OrderInfo['inner_page_amount']));
        $soToInBia = $OrdersPaper['GiayBiaSoTo'] + floatval(AcpHelper::removeFormat($OrdersPaper['GiayBiaSoToBuHao']));
        $soToInRuot = $OrdersPaper['GiayRuotSoTo'] + floatval(AcpHelper::removeFormat($OrdersPaper['GiayRuotSoToBuHao'])) + ($heSoTayLe > 0 ? ($OrdersPaper['TayCuoiSoTo'] + floatval(AcpHelper::removeFormat($OrdersPaper['GiayRuotSoToBuHaoThem']))) : 0);
//======task 24816
        if ($settings->get('cach_tinh_gia') == 0)
            $kemCTP = $tayChan * $OrderInfo['SoMauInRuot'] + $heSoTayLe * (isset($OrderInfo['SoMauInRuotTayCuoi']) ? $OrderInfo['SoMauInRuotTayCuoi'] : 0);
        else
            $kemCTP = $OrderInfo['SoMauInRuot'];
        $kem = $OrderInfo['SoMauInBia'];
        $tongTaySach = $tayChan + $heSoTayLe;
        if ($OrderInfo['SoMauInRuot'] > 1) {
            $bien_so_1 = ($soToInRuot / 5000 / 24) + $tongTaySach * 15 / 60 / 24;
            $bien_so_2 = $tongTaySach * 15 / 60 / 24;
        } else {
            $bien_so_1 = $tongTaySach * 15 / 60 / 24;
            $bien_so_2 = ($soToInRuot / 5000 / 24) + $tongTaySach * 15 / 60 / 24;
        }
        $bien_so_3 = $soToInBia / 5000 / 24;
        if (isset($OrderInfo['KhoMayInBia']))
            $khoMayInBia = Giakhomayin::findOne($OrderInfo['KhoMayInBia']);
        if (isset($OrderInfo['KhoMayInRuot']))
            $khoMayInRuot = Giakhomayin::findOne($OrderInfo['KhoMayInRuot']);
        $gia_dung_dich_hien_ban_kem_1 = ((!empty($khoMayInRuot) && $khoMayInRuot->tay_sach == 16 ? $kemCTP : 0) + (!empty($khoMayInBia) && $khoMayInBia->tay_sach == 16 ? $kem : 0));
        $gia_dung_dich_hien_ban_kem_2 = ((!empty($khoMayInRuot) && $khoMayInRuot->tay_sach == 16 ? 0 : $kemCTP) + (!empty($khoMayInBia) && $khoMayInBia->tay_sach == 16 ? 0 : $kem));
        //chi phi nguyen vat lieu
        if (!empty($OtherCost)) {
            foreach ($OtherCost as $v) {
                if ($v['tonkho_id'] > 0)
                    $kho = Tonkho::find()->where(['tonkho_id' => $v['tonkho_id']])->orderBy(['tonkho_name' => SORT_ASC])->one();
                else
                    $kho = Tonkho::find()->where(['tonkho_type' => $v['type']])->orderBy(['tonkho_name' => SORT_ASC])->one();
                if (empty($kho) || empty($v['type']))
                    continue;
                $donGia = $v['unit_price'] ? floatval(str_replace(',', '', $v['unit_price'])) : $kho->tonkho_dongia;
                $amount = 0;
                switch ($v['type']) {
                    case Tonkho::TYPE_MUCIN:
                        //(Số lượng * Chiều dài * Chiều rộng * Số trang ruột) * Hệ số mực * Định mức mực in
                        $amount = !empty($v['quantity']) ? floatval(AcpHelper::removeFormat($v['quantity'])) : $OrderInfo['amount'] * $OrderInfo['length'] * $OrderInfo['width'] * $OrderInfo['inner_page_amount'] * $OrderInfo['he_so_muc'] * $kho->dinh_muc;
                        //print_r("{$OrderInfo['amount']} * {$OrderInfo['length']} * {$OrderInfo['width']} * {$OrderInfo['inner_page_amount']} * {$OrderInfo['he_so_muc']} * $kho->dinh_muc");
                        break;
                    case Tonkho::TYPE_DD_CTP:
                        $amount = $kho->dinh_muc * $gia_dung_dich_hien_ban_kem_1 * 80 * 103 + $kho->dinh_muc * $gia_dung_dich_hien_ban_kem_2 * 57 * 67;
                        break;
                    case Tonkho::TYPE_STABILAT:
                        $amount = ($soToInRuot * 2 + $soToInBia) * $kho->dinh_muc;
                        break;
                    case Tonkho::TYPE_DD_RUA_MAY:
                    case Tonkho::TYPE_GIE_LAU:
                        $amount = (2 + floor($bien_so_1) + floor($bien_so_2) + round($bien_so_3)) * $kho->dinh_muc;
                        break;
                    case Tonkho::TYPE_CAO_SU:
                        $amount = $soToInRuot * 2 * $kho->dinh_muc;
                        break;
                    case Tonkho::TYPE_THANH_LOT_DAO_XEN:
                        $amount = ($soToInRuot * ($OrderInfo['length'] + 0.5) * ($trangTrenTayRuot / 2) / 100 * ($OrderInfo['width'] + 0.5) / 100 * $OrdersPaper['GiayRuotDinhLuong'] / 1000 + $soToInBia * $OrderInfo['length'] * ($trangTrenTayBia / 2) / 100 * $OrderInfo['width'] / 100 * $OrdersPaper['GiayBiaDinhLuong'] / 1000) * $kho->dinh_muc;
                        break;
                    case Tonkho::TYPE_KEO_VAO_BIA:
                        $amount = 0;
                        break;
                    case Tonkho::TYPE_MANG:
                    case Tonkho::TYPE_KEO_CAN_MANG:
                        $amount = 0;
                        break;
                    default:
                        break;
                }
                $chiphi['NVL'][$v['type']] = [
                    'total' => round($amount * $donGia),
                    'price' => $donGia,
                    'amount' => $amount
                ];
            }
        }

        //chi phi chung khac
        $chiPhiChungKhac = (($OrderInfo['amount'] * $OrderInfo['length'] * $OrderInfo['width'] * $OrderInfo['inner_page_amount'] * $OrderInfo['SoMauInRuot'] / 247) + ($OrderInfo['length'] * $OrderInfo['width'] * $OrderInfo['SoMatInBia'] * $OrderInfo['SoMauInBia'] / 247 * $OrderInfo['amount'])) * 0.3;
        //print_r("(({$OrderInfo['amount']} * {$OrderInfo['length']} * {$OrderInfo['width']} * {$OrderInfo['inner_page_amount']} * {$OrderInfo['SoMauInRuot']} / 247) + ({$OrderInfo['length']} * {$OrderInfo['width']} * {$OrderInfo['SoMatInBia']} * {$OrderInfo['SoMauInBia']} / 247 * {$OrderInfo['amount']})) * 0.3");
        $chiphi['chiPhiChungKhac'] = round($chiPhiChungKhac);

        //chi phi nang luong
        $chiPhiNangLuong = (($bien_so_1 + $bien_so_2) * 24 * 50 + $bien_so_3 * 24 * 30 + ($tongTaySach * $OrderInfo['amount'] / 8000 / 24) * 24 * 2) * 1600;
        $chiphi['chiPhiNangLuong'] = round($chiPhiNangLuong);

        //chi phi khau hao tai san
        $bien_so_4 = $tongTaySach * $OrderInfo['amount'] / 8000 / 24;
        $KH_may_in_16_trang_4_mau = $bien_so_1 * $settings->get('may_in_16_trang_4_mau');
        $KH_may_in_16_trang_1_mau = $bien_so_2 * $settings->get('may_in_16_trang_1_mau');
        $KH_may_in_8_trang_4_mau = $bien_so_3 * $settings->get('may_in_8_trang_4_mau');
        $KH_may_gap = $bien_so_4 * $settings->get('khau_hao_may_gap');
        $KH_nha_xuong = ($bien_so_1 + $bien_so_2 + $bien_so_3 + $bien_so_4 + 2) * $settings->get('khau_hao_nha_xuong');

        $chiPhiKhauHaoTaiSan = $KH_nha_xuong + $KH_may_in_16_trang_4_mau + $KH_may_in_8_trang_4_mau + $KH_may_in_16_trang_1_mau + $KH_may_gap;
        $chiphi['data']['khtscd'] = "(($bien_so_1 + $bien_so_2 + $bien_so_3 + $bien_so_4 + 2) * " . $settings->get('khau_hao_nha_xuong') . ") + $KH_may_in_16_trang_4_mau + $KH_may_in_8_trang_4_mau + $KH_may_in_16_trang_1_mau + $KH_may_gap";
        $chiphi['KHTSCD'] = round($chiPhiKhauHaoTaiSan);

        //chi phi nhan cong truc tiep
        $may_in_M16 = Giakhomayin::find()->where(['supplierId' => $OrderInfo['NhaCungCapIn'], 'tay_sach' => 16])->one();
        $may_in_M8 = Giakhomayin::find()->where(['supplierId' => $OrderInfo['NhaCungCapIn'], 'tay_sach' => 8])->one();
        $don_gia_kem_m16 = !empty($may_in_M16) && isset($may_in_M16->gia_ra_kem) ? $may_in_M16->gia_ra_kem : 0;
        $don_gia_kem_m8 = !empty($may_in_M8) && isset($may_in_M8->gia_ra_kem) ? $may_in_M8->gia_ra_kem : 0;
        $don_gia_mac_kem_m16 = !empty($may_in_M16) && isset($may_in_M16->gia_ra_kem) ? $may_in_M16->gia_mac_kem : 0;
        $don_gia_mac_kem_m8 = !empty($may_in_M8) && isset($may_in_M8->gia_ra_kem) ? $may_in_M8->gia_mac_kem : 0;
        $don_gia_in_luot_m16 = !empty($may_in_M16) && isset($may_in_M16->gia_ra_kem) ? $may_in_M16->gia_tinh_luot : 0;
        $don_gia_in_luot_m8 = !empty($may_in_M8) && isset($may_in_M8->gia_ra_kem) ? $may_in_M8->gia_tinh_luot : 0;

        $nctt['raKem'] = $gia_dung_dich_hien_ban_kem_1 * $don_gia_kem_m16 + $gia_dung_dich_hien_ban_kem_2 * $don_gia_kem_m8;
        $chiphi['data']['raKem'] = "$gia_dung_dich_hien_ban_kem_1 * $don_gia_kem_m16 + $gia_dung_dich_hien_ban_kem_2 * $don_gia_kem_m8";
        $nctt['xenTruocIn'] = ($soToInRuot * (($OrderInfo['length'] + 0.5) * ($trangTrenTayRuot / 2)) / 100 * ($OrderInfo['width'] + 0.5) / 100 * $OrdersPaper['GiayRuotDinhLuong'] / 1000 + $soToInBia * ($OrderInfo['length'] * ($trangTrenTayBia / 2)) / 100 * $OrderInfo['width'] / 100 * $OrdersPaper['GiayBiaDinhLuong'] / 1000) / 1000 * $settings->get('btnc_xen_giay');
        $chiphi['data']['xenTruocIn'] = "($soToInRuot * (({$OrderInfo['length']} + 0.5) * ($trangTrenTayRuot / 2)) / 100 * ({$OrderInfo['width']} + 0.5) / 100 * {$OrdersPaper['GiayRuotDinhLuong']} / 1000 + $soToInBia * ({$OrderInfo['length']} * ($trangTrenTayBia / 2)) / 100 * {$OrderInfo['width']} / 100 * {$OrdersPaper['GiayBiaDinhLuong']} / 1000) / 1000";
        $nctt['macKem'] = $OrderInfo['amount'] < 1000 ? ($gia_dung_dich_hien_ban_kem_1 * $don_gia_mac_kem_m16 + $gia_dung_dich_hien_ban_kem_2 * $don_gia_mac_kem_m8) : ($gia_dung_dich_hien_ban_kem_1 * $don_gia_in_luot_m16 + $gia_dung_dich_hien_ban_kem_2 * $don_gia_in_luot_m8);
        $in_m16 = $in_m8 = 0;
        $chiphi['data']['in'] = '';
        if (!empty($may_in_M16) && !empty($may_in_M16->don_gia_in)) {
            $don_gia_in_m16 = json_decode($may_in_M16->don_gia_in);
            if (!empty($don_gia_in_m16)) {
                foreach ($don_gia_in_m16 as $v) {
                    if ($v->gia_tri_dau >= 0 && $v->gia_tri_cuoi >= 0 && $v->don_gia >= 0 && $OrderInfo['amount'] >= $v->gia_tri_dau && $OrderInfo['amount'] <= $v->gia_tri_cuoi) {
                        $in_m16 = $tongTaySach * 2 * $OrderInfo['amount'] * $v->don_gia;
                        $chiphi['data']['in'] .= "($tongTaySach * 2 * {$OrderInfo['amount']} * $v->don_gia)";
                    }
                }
            }
        }
        if (!empty($may_in_M8) && !empty($may_in_M8->don_gia_in)) {
            $don_gia_in_m8 = json_decode($may_in_M8->don_gia_in);
            if (!empty($don_gia_in_m8)) {
                foreach ($don_gia_in_m8 as $v) {
                    if ($v->gia_tri_dau >= 0 && $v->gia_tri_cuoi > $v->gia_tri_dau && $v->don_gia >= 0 && $OrderInfo['amount'] >= $v->gia_tri_dau && $OrderInfo['amount'] <= $v->gia_tri_cuoi) {
                        $in_m8 = $soToInBia * $v->don_gia;
                        $chiphi['data']['in'] .= " + ($soToInBia * $v->don_gia)";
                    }
                }
            }
        }

        $nctt['in'] = $in_m16 + $in_m8;
        $btnc_kiem_to_in = $settings->get('btnc_kiem_to_in');
        $gia_kiem_to_in_bia = $gia_kiem_to_in_ruot = 0;
        if (is_array($btnc_kiem_to_in) && !empty($btnc_kiem_to_in)) {
            $gia_kiem_to_in_bia_tam = $gia_kiem_to_in_ruot_tam = 0;
            foreach ($btnc_kiem_to_in as $v) {
                if (!isset($v['chat_lieu']))
                    continue;
                if ($v['chat_lieu'] == $OrdersPaper['GiayRuotChatLieu'])
                    $gia_kiem_to_in_ruot_tam = $v['don_gia'];
                if ($v['chat_lieu'] == $OrdersPaper['GiayRuotChatLieu'] && $v['dinh_luong'] == $OrdersPaper['GiayRuotDinhLuong'])
                    $gia_kiem_to_in_ruot = $v['don_gia'];
                if ($v['chat_lieu'] == $OrdersPaper['GiayBiaChatLieu'])
                    $gia_kiem_to_in_bia_tam = $v['don_gia'];
                if ($v['chat_lieu'] == $OrdersPaper['GiayBiaChatLieu'] && $v['dinh_luong'] == $OrdersPaper['GiayBiaDinhLuong'])
                    $gia_kiem_to_in_bia = $v['don_gia'];

            }

            if ($gia_kiem_to_in_ruot == 0 && $gia_kiem_to_in_ruot_tam > 0)
                $gia_kiem_to_in_ruot = $gia_kiem_to_in_ruot_tam;
            elseif ($gia_kiem_to_in_ruot == 0 && $gia_kiem_to_in_ruot_tam == 0)
                $gia_kiem_to_in_ruot = $btnc_kiem_to_in['gia_chung'];
            if ($gia_kiem_to_in_bia == 0 && $gia_kiem_to_in_bia_tam > 0)
                $gia_kiem_to_in_bia = $gia_kiem_to_in_bia_tam;
            elseif ($gia_kiem_to_in_bia == 0 && $gia_kiem_to_in_bia_tam == 0)
                $gia_kiem_to_in_bia = $btnc_kiem_to_in['gia_chung'];
        }
        $nctt['kiemToIn'] = $soToInRuot * $gia_kiem_to_in_ruot + $soToInBia * $gia_kiem_to_in_bia;

        $nctt['gap'] = isset($settings->get('btnc_gap')[2]['don_gia']) ? $tongTaySach * $OrderInfo['amount'] * $settings->get('btnc_gap')[2]['don_gia'] : 0;

        $nctt['batSoan'] = isset($settings->get('btnc_bat_soan')[0]['don_gia']) ? $tongTaySach * $OrderInfo['amount'] * $settings->get('btnc_bat_soan')[0]['don_gia'] : 0;

        $nctt['chap'] = $tongTaySach < 8 ? (isset($settings->get('btnc_chap')[0]['don_gia']) ? $tongTaySach * $OrderInfo['amount'] * $settings->get('btnc_chap')[0]['don_gia'] : 0) : (isset($settings->get('btnc_chap')[1]['don_gia']) ? $tongTaySach * $OrderInfo['amount'] * $settings->get('btnc_chap')[1]['don_gia'] : 0);

        $nctt['long'] = $OrderInfo['KieuDong'] == OrderInfo::DONG_GIUA ? ($tongTaySach * $OrderInfo['amount'] * $settings->get('btnc_long_sach')) : 0;

        $nctt['vao_keo_bia'] = $OrderInfo['KieuDong'] == OrderInfo::DONG_GIUA ? 0 : (isset($settings->get('btnc_vao_bia')[0]['don_gia']) ? $OrderInfo['amount'] * $settings->get('btnc_vao_bia')[0]['don_gia'] : 0);

        if ($OrderInfo['bia_cung'] == 1) {
            $nctt['doc_phu_ban'] = $OrderInfo['amount'] * $settings->get('btnc_doc_phu_ban') * 2;

            $nctt['dan_phu_ban'] = $OrderInfo['amount'] * $settings->get('btnc_dan_phu_ban');
        }

        $nctt['kiem_thanh_pham'] = $OrderInfo['KieuDong'] == OrderInfo::DONG_GIUA ? (isset($settings->get('btnc_kiem_sach_than_pham')[0]['don_gia']) ? $OrderInfo['amount'] * $settings->get('btnc_kiem_sach_than_pham')[0]['don_gia'] : 0) : (isset($settings->get('btnc_kiem_sach_than_pham')[1]['don_gia']) ? $OrderInfo['amount'] * $settings->get('btnc_kiem_sach_than_pham')[1]['don_gia'] : 0);

        $nctt['xen_thanh_pham'] = isset($settings->get('btnc_xen_thanh_pham')[1]['don_gia']) ? $OrderInfo['inner_page_amount'] / 2 * $settings->get('btnc_xen_thanh_pham')[1]['don_gia'] * $OrderInfo['amount'] : 0;

        $nctt['bia_cung'] = 0;
        if ($settings->get('btnc_bia_cung') != null && $OrderInfo['bia_cung'] == 1) {
            foreach ($settings->get('btnc_bia_cung') as $k => $v) {
                $nctt['bia_cung'] += $v['don_gia'] * $OrderInfo['amount'];
            }
        }

        $nctt['khauChi'] = $OrderInfo['KieuDong'] == OrderInfo::KHAU_KEO ? $tongTaySach * $OrderInfo['amount'] * $settings->get('btnc_khau_chi') : 0;

        $total_nctt = @array_sum($nctt);
        $chiphi['NCTT'] = $total_nctt > 0 ? round($total_nctt) : 0;
        $chiphi['NCTT_data'] = $nctt;
        if ($chiphi['NCTT'] > 0)
            $chiphi['chiPhiPhanXuong'] = round($chiphi['NCTT'] * 0.06);
        if ($chiphi['NCTT'] > 0 && $chiphi['chiPhiPhanXuong'] > 0)
            $chiphi['chiPhiBaoHiem'] = round(($chiphi['NCTT'] + $chiphi['chiPhiPhanXuong']) * 0.24);

        //thong tin to gac
        if (isset($OrderInfo['bia_cung']) && $OrderInfo['bia_cung'] == 1 && $OrdersPaper['GiayRuotPrice'] >= 0 && $OrdersPaper['ToGacPrice'] >= 0 && $OrdersPaper['GiayRuotDinhLuong'] > 0 && $OrdersPaper['ToGacDinhLuong'] > 0) {
            $soToGac = floor($OrderInfo['amount'] / ($trangTrenTayRuot / 4)) + 10;
            $donGiaToGac = ($OrdersPaper['ToGacPrice'] / $OrdersPaper['GiayRuotPrice']) * ($OrdersPaper['ToGacDinhLuong'] / $OrdersPaper['GiayRuotDinhLuong']) * $OrdersPaper['GiayRuotPrice'] * $OrdersPaper['GiayRuotDinhLuong'] / 1000 * $OrdersPaper['GiayRuotLength'] / 100 * $OrdersPaper['GiayRuotWidth'] / 100;

            $chiPhiToGac = $donGiaToGac * $soToGac;
            $chiphi['toGac'] = [
                'soToGac' => $soToGac,
                'donGiaGac' => $donGiaToGac,
                'chiPhiGac' => round($chiPhiToGac),
//                "{$OrdersPaper['ToGacPrice']} * {$OrdersPaper['GiayRuotPrice']} / 1000 * {$OrderInfo['length']} / 100 * {$OrderInfo['width']} / 100",
//                "({$OrdersPaper['ToGacPrice']} / {$OrdersPaper['GiayRuotPrice']}) * ({$OrdersPaper['ToGacDinhLuong']}/{$OrdersPaper['GiayRuotDinhLuong']}) * $donGiaToGac"
            ];
            $soLuongBiaCarton = 0;
            $heSoBiaCarton = 0;

            if ($settings->get('bia_carton_theo_kho') != null) {
                foreach ($settings->get('bia_carton_theo_kho') as $v) {
                    if ($v['sach_dai'] = $OrderInfo['length'] && $v['sach_rong'] = $OrderInfo['width'] && $v['giay_dai'] = $OrdersPaper['GiayRuotLength'] && $v['giay_rong'] = $OrdersPaper['GiayRuotWidth']) {
                        $heSoBiaCarton = $v['he_so'];
                        break;
                    }
                }
            }
            if ($heSoBiaCarton > 0)
                $soLuongBiaCarton = ceil($OrderInfo['amount'] / $heSoBiaCarton + 5);

            $chiphi['biaCarton'] = [
                'chiPhiBiaCarton' => round($soLuongBiaCarton * $OrdersPaper['BiaCartonDonGia']),
                'soLuongBiaCarton' => $soLuongBiaCarton
            ];
        }

        return $chiphi;
    }

    public function _tinhChiPhiInFormTuTaoBaoGia($data = null)
    {
        if (empty($data))
            return false;
        $settings = Yii::$app->settings;
//        $OrderInfo = $data['OrderInfo'][0];
//        $OrdersPaper = $data['OrdersPaper'][0];
        $OtherCost = '';
        $chiphi = [
            'NVL' => [],
            'chiPhiChungKhac' => 0,
            'NCTT' => 0,
            'chiPhiNangLuong' => 0,
            'chiPhiPhanXuong' => 0,
            'KHTSCD' => 0,
            'toGac' => [],
            'biaCarton' => [],
            'chiPhiBaoHiem' => 0
        ];
        $tayChan = floor($data['ruot_info'][0]['soTay']);
        $tayLe = $data['ruot_info'][0]['soTay'] - $tayChan;
        $heSoTayLe = 0;
        $trangTrenTayRuot = $data['ruot_info'][0]['thanhPham'] * ($data['KieuDong'] == OrderInfo::DONG_GIUA ? 4 : 2);
        $trangTrenTayBia = $data['bia_info']['thanhPham'] * 4;
        if ($tayLe == 0.25 || $tayLe == 0.5)
            $heSoTayLe = 1;
        elseif ($tayLe == 0.75)
            $heSoTayLe = 2;

        $soToInBia = $data['bia_info']['soTo'] + floatval(AcpHelper::removeFormat($data['bia_info']['toBuHao']));
        $soToInRuot = $data['ruot_info'][0]['soTo'] + floatval(AcpHelper::removeFormat($data['bia_info']['toBuHao'])) + ($heSoTayLe > 0 ? ($data['ruot_info'][0]['tay_cuoi']['info']['soTo'] + floatval(AcpHelper::removeFormat($data['ruot_info'][0]['tay_cuoi']['info']['toBuHao']))) : 0);
//======task 24816
        if ($settings->get('cach_tinh_gia') == 0)
            $kemCTP = $tayChan * 2 * $data['ruot_info'][0]['so_mau_in_ruot'] + $heSoTayLe * (isset($data['ruot_info'][0]['tay_cuoi']['so_mau_in_ruot']) ? $data['ruot_info'][0]['tay_cuoi']['so_mau_in_ruot'] : 0);
        else
            $kemCTP = $data['ruot_info'][0]['so_mau_in_ruot'];
        $kem = $data['bia_info']['so_mau'];
        $tongTaySach = $tayChan + $heSoTayLe;
        if ($data['ruot_info'][0]['so_mau_in_ruot'] > 1) {
            $bien_so_1 = ($soToInRuot / 5000 / 24) + $tongTaySach * 15 / 60 / 24;
            $bien_so_2 = $tongTaySach * 15 / 60 / 24;
        } else {
            $bien_so_1 = $tongTaySach * 15 / 60 / 24;
            $bien_so_2 = ($soToInRuot / 5000 / 24) + $tongTaySach * 15 / 60 / 24;
        }
        $bien_so_3 = $soToInBia / 5000 / 24;
        if (isset($data['bia_info']['khoMay']['kg_id']))
            $khoMayInBia = Giakhomayin::findOne($data['bia_info']['khoMay']['kg_id']);
        if (isset($data['ruot_info'][0]['khoMay']['kg_id']))
            $khoMayInRuot = Giakhomayin::findOne($data['ruot_info'][0]['khoMay']['kg_id']);
        $gia_dung_dich_hien_ban_kem_1 = ((!empty($khoMayInRuot) && $khoMayInRuot->tay_sach == 16 ? $kemCTP : 0) + (!empty($khoMayInBia) && $khoMayInBia->tay_sach == 16 ? $kem : 0));
        $gia_dung_dich_hien_ban_kem_2 = ((!empty($khoMayInRuot) && $khoMayInRuot->tay_sach == 16 ? 0 : $kemCTP) + (!empty($khoMayInBia) && $khoMayInBia->tay_sach == 16 ? 0 : $kem));
        //chi phi nguyen vat lieu
        $array_list_nvl = Tonkho::getTypeOptions();
        @ksort($array_list_nvl);
        if (!empty($array_list_nvl)) {
            foreach ($array_list_nvl as $k => $v) {
                if ($k < 2)
                    continue;
                $kho = Tonkho::find()->where(['tonkho_type' => $k])->orderBy(['tonkho_name' => SORT_ASC])->one();
                if (empty($kho))
                    continue;
                $donGia = $kho['tonkho_dongia'] ? floatval(str_replace(',', '', $kho['tonkho_dongia'])) : $kho->tonkho_dongia;
                $amount = 0;
                switch ($kho->tonkho_type) {
                    case Tonkho::TYPE_MUCIN:
                        $amount = $data['amount'] * $data['length'] * $data['width'] * $data['inner_page_amount'] * $data['he_so_muc'] * $kho->dinh_muc;
                        break;
                    case Tonkho::TYPE_DD_CTP:
                        $amount = $kho->dinh_muc * $gia_dung_dich_hien_ban_kem_1 * 80 * 103 + $kho->dinh_muc * $gia_dung_dich_hien_ban_kem_2 * 57 * 67;
                        break;
                    case Tonkho::TYPE_STABILAT:
                        $amount = ($soToInRuot * 2 + $soToInBia) * $kho->dinh_muc;
                        break;
                    case Tonkho::TYPE_DD_RUA_MAY:
                    case Tonkho::TYPE_GIE_LAU:
                        $amount = (2 + floor($bien_so_1) + floor($bien_so_2) + round($bien_so_3)) * $kho->dinh_muc;
                        break;
                    case Tonkho::TYPE_CAO_SU:
                        $amount = $soToInRuot * 2 * $kho->dinh_muc;
                        break;
                    case Tonkho::TYPE_THANH_LOT_DAO_XEN:
                        $amount = ($soToInRuot * ($data['length'] + 0.5) * ($trangTrenTayRuot / 2) / 100 * ($data['width'] + 0.5) / 100 * $data['ruot_info'][0]['giay_ruot_dinh_luong'] / 1000 + $soToInBia * $data['length'] * ($trangTrenTayBia / 2) / 100 * $data['width'] / 100 * $data['bia_info']['giay_bia_dinh_luong'] / 1000) * $kho->dinh_muc;
                        break;
                    case Tonkho::TYPE_KEO_VAO_BIA:
                        $amount = 0;
                        break;
                    case Tonkho::TYPE_MANG:
                    case Tonkho::TYPE_KEO_CAN_MANG:
                        $amount = 0;
                        break;
                    default:
                        break;
                }
                $chiphi['NVL'][$kho->tonkho_type] = [
                    'title' => $kho->tonkho_name,
                    'unit_price' => $kho->tonkho_dongia,
                    'unit' => $kho->tonkho_donvi,
                    'total' => round($amount * $donGia),
                    'price' => $donGia,
                    'amount' => $amount
                ];
            }
        }


        //chi phi chung khac
        $chiPhiChungKhac = (($data['amount'] * $data['length'] * $data['width'] * $data['inner_page_amount'] * $data['ruot_info'][0]['so_mau_in_ruot'] / 247) + ($data['length'] * $data['width'] * $data['bia_info']['so_mat'] * $data['bia_info']['so_mau'] / 247 * $data['amount'])) * 0.3;

        $chiphi['chiPhiChungKhac'] = round($chiPhiChungKhac);

        //chi phi nang luong
        $chiPhiNangLuong = (($bien_so_1 + $bien_so_2) * 24 * 50 + $bien_so_3 * 24 * 30 + ($tongTaySach * $data['amount'] / 8000 / 24) * 24 * 2) * 1600;
        $chiphi['chiPhiNangLuong'] = round($chiPhiNangLuong);

        //chi phi khau hao tai san
        $bien_so_4 = $tongTaySach * $data['amount'] / 8000 / 24;
        $KH_may_in_16_trang_4_mau = $bien_so_1 * $settings->get('may_in_16_trang_4_mau');
        $KH_may_in_16_trang_1_mau = $bien_so_2 * $settings->get('may_in_16_trang_1_mau');
        $KH_may_in_8_trang_4_mau = $bien_so_3 * $settings->get('may_in_8_trang_4_mau');
        $KH_may_gap = $bien_so_4 * $settings->get('khau_hao_may_gap');
        $KH_nha_xuong = ($bien_so_1 + $bien_so_2 + $bien_so_3 + $bien_so_4 + 2) * $settings->get('khau_hao_nha_xuong');

        $chiPhiKhauHaoTaiSan = $KH_nha_xuong + $KH_may_in_16_trang_4_mau + $KH_may_in_8_trang_4_mau + $KH_may_in_16_trang_1_mau + $KH_may_gap;
        $chiphi['data']['khtscd'] = "(($bien_so_1 + $bien_so_2 + $bien_so_3 + $bien_so_4 + 2) * " . $settings->get('khau_hao_nha_xuong') . ") + $KH_may_in_16_trang_4_mau + $KH_may_in_8_trang_4_mau + $KH_may_in_16_trang_1_mau + $KH_may_gap";
        $chiphi['KHTSCD'] = round($chiPhiKhauHaoTaiSan);

        //chi phi nhan cong truc tiep
        $may_in_M16 = Giakhomayin::find()->where(['supplierId' => $data['bia_info']['khoMay']['supplierId'], 'tay_sach' => 16])->one();
        $may_in_M8 = Giakhomayin::find()->where(['supplierId' => $data['bia_info']['khoMay']['supplierId'], 'tay_sach' => 8])->one();
        $don_gia_kem_m16 = !empty($may_in_M16) && isset($may_in_M16->gia_ra_kem) ? $may_in_M16->gia_ra_kem : 0;
        $don_gia_kem_m8 = !empty($may_in_M8) && isset($may_in_M8->gia_ra_kem) ? $may_in_M8->gia_ra_kem : 0;
        $don_gia_mac_kem_m16 = !empty($may_in_M16) && isset($may_in_M16->gia_ra_kem) ? $may_in_M16->gia_mac_kem : 0;
        $don_gia_mac_kem_m8 = !empty($may_in_M8) && isset($may_in_M8->gia_ra_kem) ? $may_in_M8->gia_mac_kem : 0;
        $don_gia_in_luot_m16 = !empty($may_in_M16) && isset($may_in_M16->gia_ra_kem) ? $may_in_M16->gia_tinh_luot : 0;
        $don_gia_in_luot_m8 = !empty($may_in_M8) && isset($may_in_M8->gia_ra_kem) ? $may_in_M8->gia_tinh_luot : 0;

        $nctt['raKem'] = $gia_dung_dich_hien_ban_kem_1 * $don_gia_kem_m16 + $gia_dung_dich_hien_ban_kem_2 * $don_gia_kem_m8;
        $chiphi['data']['raKem'] = "$gia_dung_dich_hien_ban_kem_1 * $don_gia_kem_m16 + $gia_dung_dich_hien_ban_kem_2 * $don_gia_kem_m8";
        $nctt['xenTruocIn'] = ($soToInRuot * (($data['length'] + 0.5) * ($trangTrenTayRuot / 2)) / 100 * ($data['width'] + 0.5) / 100 * $data['ruot_info'][0]['giay_ruot_dinh_luong'] / 1000 + $soToInBia * ($data['length'] * ($trangTrenTayBia / 2)) / 100 * $data['width'] / 100 * $data['bia_info']['giay_bia_dinh_luong'] / 1000) / 1000 * $settings->get('btnc_xen_giay');
        $chiphi['data']['xenTruocIn'] = "($soToInRuot * (({$data['length']} + 0.5) * ($trangTrenTayRuot / 2)) / 100 * ({$data['width']} + 0.5) / 100 * {$data['ruot_info'][0]['giay_ruot_dinh_luong']} / 1000 + $soToInBia * ({$data['length']} * ($trangTrenTayBia / 2)) / 100 * {$data['width']} / 100 * {$data['bia_info']['giay_bia_dinh_luong']} / 1000) / 1000";
        $nctt['macKem'] = $data['amount'] < 1000 ? ($gia_dung_dich_hien_ban_kem_1 * $don_gia_mac_kem_m16 + $gia_dung_dich_hien_ban_kem_2 * $don_gia_mac_kem_m8) : ($gia_dung_dich_hien_ban_kem_1 * $don_gia_in_luot_m16 + $gia_dung_dich_hien_ban_kem_2 * $don_gia_in_luot_m8);
        $in_m16 = $in_m8 = 0;
        $chiphi['data']['in'] = '';
        if (!empty($may_in_M16) && !empty($may_in_M16->don_gia_in)) {
            $don_gia_in_m16 = json_decode($may_in_M16->don_gia_in);
            if (!empty($don_gia_in_m16)) {
                foreach ($don_gia_in_m16 as $v) {
                    if ($v->gia_tri_dau >= 0 && $v->gia_tri_cuoi >= 0 && $v->don_gia >= 0 && $data['amount'] >= $v->gia_tri_dau && $data['amount'] <= $v->gia_tri_cuoi) {
                        $in_m16 = $tongTaySach * 2 * $data['amount'] * $v->don_gia;
                        $chiphi['data']['in'] .= "($tongTaySach * 2 * {$data['amount']} * $v->don_gia)";
                    }
                }
            }
        }
        if (!empty($may_in_M8) && !empty($may_in_M8->don_gia_in)) {
            $don_gia_in_m8 = json_decode($may_in_M8->don_gia_in);
            if (!empty($don_gia_in_m8)) {
                foreach ($don_gia_in_m8 as $v) {
                    if ($v->gia_tri_dau >= 0 && $v->gia_tri_cuoi > $v->gia_tri_dau && $v->don_gia >= 0 && $data['amount'] >= $v->gia_tri_dau && $data['amount'] <= $v->gia_tri_cuoi) {
                        $in_m8 = $soToInBia * $v->don_gia;
                        $chiphi['data']['in'] .= " + ($soToInBia * $v->don_gia)";
                    }
                }
            }
        }

        $nctt['in'] = $in_m16 + $in_m8;
        $btnc_kiem_to_in = $settings->get('btnc_kiem_to_in');
        $gia_kiem_to_in_bia = $gia_kiem_to_in_ruot = 0;
        if (is_array($btnc_kiem_to_in) && !empty($btnc_kiem_to_in)) {
            $gia_kiem_to_in_bia_tam = $gia_kiem_to_in_ruot_tam = 0;
            foreach ($btnc_kiem_to_in as $v) {
                if (!isset($v['chat_lieu']))
                    continue;
                if ($v['chat_lieu'] == $data['ruot_info'][0]['giay_ruot_chat_lieu'])
                    $gia_kiem_to_in_ruot_tam = $v['don_gia'];
                if ($v['chat_lieu'] == $data['ruot_info'][0]['giay_ruot_chat_lieu'] && $v['dinh_luong'] == $data['ruot_info'][0]['giay_ruot_dinh_luong'])
                    $gia_kiem_to_in_ruot = $v['don_gia'];
                if ($v['chat_lieu'] == $data['bia_info']['giay_bia_chat_lieu'])
                    $gia_kiem_to_in_bia_tam = $v['don_gia'];
                if ($v['chat_lieu'] == $data['bia_info']['giay_bia_chat_lieu'] && $v['dinh_luong'] == $data['bia_info']['giay_bia_dinh_luong'])
                    $gia_kiem_to_in_bia = $v['don_gia'];

            }

            if ($gia_kiem_to_in_ruot == 0 && $gia_kiem_to_in_ruot_tam > 0)
                $gia_kiem_to_in_ruot = $gia_kiem_to_in_ruot_tam;
            elseif ($gia_kiem_to_in_ruot == 0 && $gia_kiem_to_in_ruot_tam == 0)
                $gia_kiem_to_in_ruot = $btnc_kiem_to_in['gia_chung'];
            if ($gia_kiem_to_in_bia == 0 && $gia_kiem_to_in_bia_tam > 0)
                $gia_kiem_to_in_bia = $gia_kiem_to_in_bia_tam;
            elseif ($gia_kiem_to_in_bia == 0 && $gia_kiem_to_in_bia_tam == 0)
                $gia_kiem_to_in_bia = $btnc_kiem_to_in['gia_chung'];
        }
        $nctt['kiemToIn'] = $soToInRuot * $gia_kiem_to_in_ruot + $soToInBia * $gia_kiem_to_in_bia;

        $nctt['gap'] = isset($settings->get('btnc_gap')[2]['don_gia']) ? $tongTaySach * $data['amount'] * $settings->get('btnc_gap')[2]['don_gia'] : 0;

        $nctt['batSoan'] = isset($settings->get('btnc_bat_soan')[0]['don_gia']) ? $tongTaySach * $data['amount'] * $settings->get('btnc_bat_soan')[0]['don_gia'] : 0;

        $nctt['chap'] = $tongTaySach < 8 ? (isset($settings->get('btnc_chap')[0]['don_gia']) ? $tongTaySach * $data['amount'] * $settings->get('btnc_chap')[0]['don_gia'] : 0) : (isset($settings->get('btnc_chap')[1]['don_gia']) ? $tongTaySach * $data['amount'] * $settings->get('btnc_chap')[1]['don_gia'] : 0);

        $nctt['long'] = $data['KieuDong'] == OrderInfo::DONG_GIUA ? ($tongTaySach * $data['amount'] * $settings->get('btnc_long_sach')) : 0;

        $nctt['vao_keo_bia'] = $data['KieuDong'] == OrderInfo::DONG_GIUA ? 0 : (isset($settings->get('btnc_vao_bia')[0]['don_gia']) ? $data['amount'] * $settings->get('btnc_vao_bia')[0]['don_gia'] : 0);

        if ($data['bia_cung'] == 1) {
            $nctt['doc_phu_ban'] = $data['amount'] * $settings->get('btnc_doc_phu_ban') * 2;

            $nctt['dan_phu_ban'] = $data['amount'] * $settings->get('btnc_dan_phu_ban');
        }

        $nctt['kiem_thanh_pham'] = $data['KieuDong'] == OrderInfo::DONG_GIUA ? (isset($settings->get('btnc_kiem_sach_than_pham')[0]['don_gia']) ? $data['amount'] * $settings->get('btnc_kiem_sach_than_pham')[0]['don_gia'] : 0) : (isset($settings->get('btnc_kiem_sach_than_pham')[1]['don_gia']) ? $data['amount'] * $settings->get('btnc_kiem_sach_than_pham')[1]['don_gia'] : 0);

        $nctt['xen_thanh_pham'] = isset($settings->get('btnc_xen_thanh_pham')[1]['don_gia']) ? $data['inner_page_amount'] / 2 * $settings->get('btnc_xen_thanh_pham')[1]['don_gia'] * $data['amount'] : 0;

        $nctt['bia_cung'] = 0;
        if ($settings->get('btnc_bia_cung') != null && $data['bia_cung'] == 1) {
            foreach ($settings->get('btnc_bia_cung') as $k => $v) {
                $nctt['bia_cung'] += $v['don_gia'] * $data['amount'];
            }
        }

        $nctt['khauChi'] = $data['KieuDong'] == OrderInfo::KHAU_KEO ? $tongTaySach * $data['amount'] * $settings->get('btnc_khau_chi') : 0;

        $total_nctt = @array_sum($nctt);
        $chiphi['NCTT'] = $total_nctt > 0 ? round($total_nctt) : 0;
        $chiphi['NCTT_data'] = $nctt;
        if ($chiphi['NCTT'] > 0)
            $chiphi['chiPhiPhanXuong'] = round($chiphi['NCTT'] * 0.06);
        if ($chiphi['NCTT'] > 0 && $chiphi['chiPhiPhanXuong'] > 0)
            $chiphi['chiPhiBaoHiem'] = round(($chiphi['NCTT'] + $chiphi['chiPhiPhanXuong']) * 0.24);

        //thong tin to gac
        if (isset($data['bia_cung']) && $data['bia_cung'] == 1 && $data['ruot_info'][0]['don_gia_giay_in'] >= 0 && $data['ToGacPrice'] >= 0 && $data['ruot_info'][0]['giay_ruot_dinh_luong'] > 0 && $data['ToGacDinhLuong'] > 0) {
            $soToGac = floor($data['amount'] / ($trangTrenTayRuot / 4)) + 10;
            $donGiaToGac = ($data['ToGacPrice'] / $data['ruot_info'][0]['don_gia_giay_in']) * ($data['ToGacDinhLuong'] / $data['ruot_info'][0]['giay_ruot_dinh_luong']) * $data['ruot_info'][0]['don_gia_giay_in'] * $data['ruot_info'][0]['giay_ruot_dinh_luong'] / 1000 * $data['ruot_info'][0]['info']['length'] / 100 * $data['ruot_info'][0]['info']['width'] / 100;

            $chiPhiToGac = $donGiaToGac * $soToGac;
            $chiphi['toGac'] = [
                'ToGacKhoGiayId' => $data['ruot_info'][0]['info']['content_id'],
                'ToGacLength' => $data['ruot_info'][0]['info']['length'],
                'ToGacWidth' => $data['ruot_info'][0]['info']['width'],
                'soToGac' => $soToGac,
                'donGiaGac' => $donGiaToGac,
                'chiPhiGac' => round($chiPhiToGac),
            ];
            $soLuongBiaCarton = 0;
            $heSoBiaCarton = 0;

            if ($settings->get('bia_carton_theo_kho') != null) {
                foreach ($settings->get('bia_carton_theo_kho') as $v) {
                    if ($v['sach_dai'] = $data['length'] && $v['sach_rong'] = $data['width'] && $v['giay_dai'] = $data['ruot_info'][0]['info']['length'] && $v['giay_rong'] = $data['ruot_info'][0]['info']['width']) {
                        $heSoBiaCarton = $v['he_so'];
                        break;
                    }
                }
            }
            if ($heSoBiaCarton > 0)
                $soLuongBiaCarton = ceil($data['amount'] / $heSoBiaCarton + 5);

            $chiphi['biaCarton'] = [
                'chiPhiBiaCarton' => round($soLuongBiaCarton * $settings->get('don_gia_bia_carton')),
                'soLuongBiaCarton' => $soLuongBiaCarton
            ];
        }

        return $chiphi;
    }
}