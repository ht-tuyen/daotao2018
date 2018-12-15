<?php
namespace common\components;

class MoneyToString{

    public $digits = array(' không ', ' một ', ' hai ', ' ba ', ' bốn ', ' năm ', ' sáu ', ' bảy ', ' tám ', ' chín ');
    public $money_str = array('', ' nghìn', ' triệu', ' tỷ ', ' nghìn tỷ ', ' triệu tỷ ');
    public $unit = 'đồng';
    public $log = false;

    private function log($data) {
        echo '<script language="javascript" type="text/javascript">console.log("php: ' . $data . '")</script>';
    }

    private function read3Num($thNum) {
        $result = '';
        $tram = intval($thNum / 100);
        $chuc = intval(($thNum % 100) / 10);
        $donvi = $thNum % 10;
        if ($tram == 0 && $chuc == 0 && $donvi == 0)
            return '';

        if ($tram != 0) {
            $result .= $this->digits[$tram] . ' trăm ';

            if (($chuc == 0) && ($donvi != 0))
                $result .= ' linh ';
        }
        if (($chuc != 0) && ($chuc != 1)) {
            $result .= $this->digits[$chuc] . ' mươi';
            if (($chuc == 0) && ($donvi != 0))
                $result = $result . ' linh ';
        }
        if ($chuc == 1)
            $result .= ' mười ';

        switch ($donvi) {
            case 1:
                if (($chuc != 0) && ($chuc != 1))
                    $result .= ' mốt ';
                else
                    $result .= $this->digits[$donvi];
                break;
            case 5:
                if ($chuc == 0)
                    $result .= $this->digits[$donvi];
                else
                    $result .= ' lăm ';
                break;
            default:
                if ($donvi != 0)
                    $result .= $this->digits[$donvi];
                break;
        }
        return $result;
    }

    public function Convert($money) {
        $lan = 0;
        $i = 0;
        $so = 0;
        $result = "";
        $tmp = "";
        $ViTri = array();

        if ($money < 0)
            return 'Số tiền âm !';

        if ($money == 0)
            return 'Không đồng !';

        if ($money > 0)
            $so = $money;
        else
            $so = -$money;

        if ($money > 8999999999999999)
            return 'Số quá lớn!';

        $ViTri[5] = floor($so / 1000000000000000);
        $so = $so - floatval($ViTri[5]) * 1000000000000000;
        $ViTri[4] = floor($so / 1000000000000);
        $so = $so - floatval($ViTri[4]) * 1000000000000;
        $ViTri[3] = floor($so / 1000000000);
        $so = $so - floatval($ViTri[3]) * 1000000000;
        $ViTri[2] = intval($so / 1000000);
        $ViTri[1] = intval(($so % 1000000) / 1000);
        $ViTri[0] = intval($so % 1000);

        if ($ViTri[5] > 0)
            $lan = 5;
        else if ($ViTri[4] > 0)
            $lan = 4;
        else if ($ViTri[3] > 0)
            $lan = 3;
        else if ($ViTri[2] > 0)
            $lan = 2;
        else if ($ViTri[1] > 0)
            $lan = 1;
        else
            $lan = 0;

        for ($i = $lan; $i >= 0; $i--) {
            if ($i < $lan && $i == 0 && strlen($ViTri[$i]) == 1 && $ViTri[$i] > 0) {
                $tmp = ' không trăm linh ' . $this->read3Num($ViTri[$i]);
            } elseif ($i < $lan && $i == 0 && strlen($ViTri[$i]) == 2 && $ViTri[$i] > 0) {
                $tmp = ' không trăm ' . $this->read3Num($ViTri[$i]);
            } elseif ($i < $lan && $i > 0 && strlen($ViTri[$i]) < 3 && $ViTri[$i] > 0) {
                if (strlen($ViTri[$i]) == 2)
                    $tmp = ' không trăm ' . $this->read3Num($ViTri[$i]);

                else if (strlen($ViTri[$i]) == 1)
                    $tmp = ' không trăm linh ' . $this->read3Num($ViTri[$i]);
            }else {
                $tmp = $this->read3Num($ViTri[$i]);
            }

            $result .= $tmp;

            if ($ViTri[$i] > 0) {
                $result .= $this->money_str[$i];
            }
            if (($i > 0) && (strlen($tmp) > 0)) {
                $result .= ',';
            }
        }

        $result = trim($result);

        if (substr($result, -1, 1) == ',') {
            $result = substr($result, 0, strlen($result) - 1);
        }
        if ($this->log)
            $this->log($result);

        $result = strtoupper(substr($result, 0, 1)) . substr($result, 1) . ' ' . $this->unit;
        $result = str_replace(array('  ', ' ,'), array(' ', ','), $result);
        return $result;
    }

}
