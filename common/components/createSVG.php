<?php
/**
 * Created by PhpStorm.
 * User: Mr.Phu
 * Date: 12/01/2017
 * Time: 10:51 AM
 */

namespace common\components;

use backend\models\Products;

class createSVG
{

    public function svg($info){
        if(!isset($info->loai_san_pham))
            return null;
        if($info->loai_san_pham == Products::SAN_PHAM_PHONG_BI){
            $svg = "<svg version=\"1.1\" id=\"Layer_1\" xmlns=\"http://www.w3.org/2000/svg\" xmlns:xlink=\"http://www.w3.org/1999/xlink\" x=\"0px\"
     y=\"0px\"
     width=\"".round($info->kt_ngang)."px\" height=\"".round($info->kt_doc)."px\" viewBox=\"0 0 ".round($info->kt_ngang)." ".round($info->kt_doc)."\" enable-background=\"new 0 0 ".round($info->kt_ngang)." ".round($info->kt_doc)."\"
     xml:space=\"preserve\">

    <line x1=\"".round($info->tai_rong)."\" y1=\"".round($info->tai_dai)."\" x2=\"".round($info->rong + $info->tai_rong)."\"
          y2=\"".round($info->tai_dai)."\"/>
    <line fill=\"none\" stroke=\"#32A8CC\" stroke-miterlimit=\"10\" stroke-dasharray=\"2,2\" x1=\"".round($info->tai_rong)."\"
          y1=\"".round($info->tai_dai)."\" x2=\"".round($info->rong + $info->tai_rong)."\" y2=\"".round($info->tai_dai)."\"/>
    <line fill=\"none\" stroke=\"#FF4000\" stroke-miterlimit=\"10\" x1=\"".round($info->tai_rong + $info->rong * 0.02)."\" y1=\"0\"
          x2=\"".round($info->tai_rong + $info->rong - $info->rong * 0.02)."\" y2=\"0\"/>
    
    <line fill=\"none\" stroke=\"#32A8CC\" stroke-miterlimit=\"10\" stroke-dasharray=\"2,2\" x1=\"".round($info->tai_rong)."\"
          y1=\"".round($info->tai_dai + $info->dai)."\" x2=\"".round($info->rong + $info->tai_rong)."\" y2=\"".round($info->tai_dai + $info->dai)."\"/>

    <line fill=\"none\" stroke=\"#FF4000\" stroke-miterlimit=\"10\" x1=\"".round($info->tai_rong)."\" y1=\"".round($info->kt_doc)."\"
          x2=\"".round($info->rong + $info->tai_rong)."\" y2=\"".round($info->kt_doc)."\"/>
    <line x1=\"".round($info->tai_rong)."\" y1=\"".round($info->tai_dai)."\" x2=\"".round($info->tai_rong)."\" y2=\"".round($info->tai_dai + $info->dai)."\"/>
    
    <line fill=\"none\" stroke=\"#32A8CC\" stroke-miterlimit=\"10\" stroke-dasharray=\"2,2\" x1=\"".round($info->tai_rong)."\"
          y1=\"".round($info->tai_dai)."\" x2=\"".round($info->tai_rong)."\" y2=\"".round($info->tai_dai + $info->dai)."\"/>

    <line fill=\"none\" stroke=\"#FF4000\" stroke-miterlimit=\"10\" x1=\"0\" y1=\"".round($info->tai_dai + $info->dai * 0.02)."\" x2=\"0\" y2=\"".round($info->tai_dai + $info->dai - $info->dai * 0.02)."\"/>
    <line fill=\"none\" stroke=\"#FF4000\" stroke-miterlimit=\"10\" x1=\"".round($info->tai_rong)."\" y1=\"".round($info->tai_dai + $info->dai)."\"
          x2=\"".round($info->tai_rong)."\" y2=\"".round($info->kt_doc)."\"/>
    <line fill=\"none\" stroke=\"#FF4000\" stroke-miterlimit=\"10\" x1=\"".round($info->rong + $info->tai_rong)."\" y1=\"".round($info->tai_dai + $info->dai)."\"
          x2=\"".round($info->rong + $info->tai_rong)."\" y2=\"".round($info->kt_doc)."\"/>
    <line x1=\"".round($info->rong + $info->tai_rong)."\" y1=\"".round($info->tai_dai)."\" x2=\"".round($info->rong + $info->tai_rong)."\"
          y2=\"".round($info->tai_dai + $info->dai)."\"/>

    <line fill=\"none\" stroke=\"#32A8CC\" stroke-miterlimit=\"10\" stroke-dasharray=\"2,2\"
          x1=\"".round($info->rong + $info->tai_rong)."\" y1=\"".round($info->tai_dai)."\" x2=\"".round($info->rong + $info->tai_rong)."\" y2=\"".round($info->tai_dai + $info->dai)."\"/>
    
    <line fill=\"none\" stroke=\"#FF4000\" stroke-miterlimit=\"10\" x1=\"".round($info->tai_rong + $info->rong * 0.02)."\" y1=\"0\"
          x2=\"".round($info->tai_rong)."\" y2=\"".round($info->tai_dai)."\"/>
    <line fill=\"none\" stroke=\"#FF4000\" stroke-miterlimit=\"10\"
          x1=\"".round($info->tai_rong + $info->rong - $info->rong * 0.02)."\" y1=\"0\"
          x2=\"".round($info->rong + $info->tai_rong)."\" y2=\"".round($info->tai_dai)."\"/>
    <line fill=\"none\" stroke=\"#FF4000\" stroke-miterlimit=\"10\" x1=\"0\" y1=\"".round($info->tai_dai + $info->dai * 0.02)."\" x2=\"".round($info->tai_rong)."\"
          y2=\"".round($info->tai_dai)."\"/>
    <line fill=\"none\" stroke=\"#FF4000\" stroke-miterlimit=\"10\" x1=\"0\" y1=\"".round($info->tai_dai + $info->dai - $info->dai * 0.02)."\" x2=\"".round($info->tai_rong)."\"
          y2=\"".round($info->tai_dai + $info->dai)."\"/>
    <line fill=\"none\" stroke=\"#FF4000\" stroke-miterlimit=\"10\" x1=\"".round($info->kt_ngang)."\" y1=\"".round($info->tai_dai + $info->dai * 0.02)."\" x2=\"".round($info->kt_ngang)."\" y2=\"".round($info->tai_dai + $info->dai - $info->dai * 0.02)."\"/>
    <line fill=\"none\" stroke=\"#FF4000\" stroke-miterlimit=\"10\" x1=\"".round($info->kt_ngang)."\" y1=\"".round($info->tai_dai + $info->dai * 0.02)."\"
          x2=\"".round($info->rong + $info->tai_rong)."\" y2=\"".round($info->tai_dai)."\"/>
    <line fill=\"none\" stroke=\"#FF4000\" stroke-miterlimit=\"10\" x1=\"".round($info->kt_ngang)."\" y1=\"".round($info->tai_dai + $info->dai - $info->dai * 0.02)."\"
          x2=\"".round($info->rong + $info->tai_rong)."\" y2=\"".round($info->tai_dai + $info->dai)."\"/>
</svg>";
        }
        elseif($info->loai_san_pham == Products::SAN_PHAM_TUI_GIAY){
            $svg = "<svg version=\"1.1\" id=\"Layer_1\" xmlns=\"http://www.w3.org/2000/svg\" xmlns:xlink=\"http://www.w3.org/1999/xlink\" x=\"0px\"
     y=\"0px\"
     width=\"".round($info->kt_ngang)."px\" height=\"".round($info->kt_doc)."px\" viewBox=\"0 0 ".round($info->kt_ngang)." ".round($info->kt_doc)."\" enable-background=\"new 0 0 ".round($info->kt_ngang)." ".round($info->kt_doc)."\"
     xml:space=\"preserve\">
     <line fill=\"none\" stroke=\"#2E58A6\" stroke-miterlimit=\"10\" stroke-dasharray=\"4,4\" x1=\"0\" y1=\"".round($info->tai_dai)."\"
              x2=\"".round($info->kt_ngang)."\" y2=\"".round($info->tai_dai)."\"/>
    <line fill=\"none\" stroke=\"#2E58A6\" stroke-miterlimit=\"10\" stroke-dasharray=\"4,4\" x1=\"0\" y1=\"".round($info->cao + $info->tai_dai)."\"
          x2=\"".round($info->kt_ngang)."\" y2=\"".round($info->cao + $info->tai_dai)."\"/>
    <line fill=\"none\" stroke=\"#2E58A6\" stroke-miterlimit=\"10\" stroke-dasharray=\"4,4\" x1=\"".round($info->tai_rong + $info->dai + $info->rong/2)."\" y1=\"".round($info->cao + $info->tai_dai - $info->rong/2)."\"
          x2=\"".round($info->kt_ngang - $info->rong/2)."\" y2=\"".round($info->cao + $info->tai_dai - $info->rong/2)."\"/>
    <line fill=\"none\" stroke=\"#2E58A6\" stroke-miterlimit=\"10\" stroke-dasharray=\"4,4\" x1=\"".round($info->tai_rong)."\" y1=\"0\"
          x2=\"".round($info->tai_rong)."\" y2=\"".round($info->kt_doc)."\"/>
    <line fill=\"none\" stroke=\"#2E58A6\" stroke-miterlimit=\"10\" stroke-dasharray=\"4,4\" x1=\"".round($info->dai + $info->tai_rong)."\" y1=\"0\"
          x2=\"".round($info->dai + $info->tai_rong)."\" y2=\"".round($info->kt_doc)."\"/>
    <line fill=\"none\" stroke=\"#2E58A6\" stroke-miterlimit=\"10\" stroke-dasharray=\"4,4\" x1=\"".round($info->dai + $info->tai_rong + $info->rong)."\" y1=\"0\"
          x2=\"".round($info->dai + $info->tai_rong + $info->rong)."\" y2=\"".round($info->kt_doc)."\"/>
    <line fill=\"none\" stroke=\"#2E58A6\" stroke-miterlimit=\"10\" stroke-dasharray=\"4,4\" x1=\"".round($info->dai * 2 + $info->tai_rong + $info->rong)."\" y1=\"0\"
          x2=\"".round($info->dai * 2 + $info->tai_rong + $info->rong)."\" y2=\"".round($info->kt_doc)."\"/>
    <line fill=\"none\" stroke=\"#2E58A6\" stroke-miterlimit=\"10\" stroke-dasharray=\"4,4\" x1=\"".round($info->kt_ngang - $info->rong/2)."\" y1=\"0\"
          x2=\"".round($info->kt_ngang - $info->rong/2)."\" y2=\"".round($info->kt_doc)."\"/>
    <line fill=\"none\" stroke=\"#FF4000\" stroke-miterlimit=\"10\" x1=\"".round($info->kt_ngang)."\" y1=\"0\" x2=\"".round($info->kt_ngang)."\" y2=\"".round($info->kt_doc)."\"/>
    <line fill=\"none\" stroke=\"#FF4000\" stroke-miterlimit=\"10\" x1=\"0\" y1=\"0\" x2=\"0\" y2=\"".round($info->kt_doc)."\"/>
    <line fill=\"none\" stroke=\"#FF4000\" stroke-miterlimit=\"10\" x1=\"0\" y1=\"".round($info->kt_doc)."\" x2=\"".round($info->kt_ngang)."\" y2=\"".round($info->kt_doc)."\"/>
    <line fill=\"none\" stroke=\"#FF4000\" stroke-miterlimit=\"10\" x1=\"0\" y1=\"0\" x2=\"".round($info->kt_ngang)."\" y2=\"0\"/>
    <line fill=\"none\" stroke=\"#2E58A6\" stroke-miterlimit=\"10\" stroke-dasharray=\"4,4\" x1=\"".round($info->tai_rong + $info->dai + $info->rong/2)."\" y1=\"0\"
          x2=\"".round($info->tai_rong + $info->dai + $info->rong/2)."\" y2=\"".round($info->kt_doc)."\"/>

    <line fill=\"none\" stroke=\"#2E58A6\" stroke-miterlimit=\"10\" stroke-dasharray=\"4,4\" x1=\"".round($info->kt_ngang - $info->rong/2)."\" y1=\"".round($info->cao+$info->tai_dai-$info->rong/2)."\"
          x2=\"".round($info->kt_ngang - $info->rong*1.5 - $info->kich_thuoc_cai_day)."\" y2=\"".round($info->kt_doc)."\"/>
    <line fill=\"none\" stroke=\"#2E58A6\" stroke-miterlimit=\"10\" stroke-dasharray=\"4,4\" x1=\"".round($info->tai_rong + $info->dai + $info->rong/2)."\" y1=\"".round($info->cao+$info->tai_dai-$info->rong/2)."\"
          x2=\"".round($info->dai + $info->tai_rong - $info->rong + $info->kich_thuoc_cai_day)."\" y2=\"".round($info->kt_doc)."\"/>
    <line fill=\"none\" stroke=\"#2E58A6\" stroke-miterlimit=\"10\" stroke-dasharray=\"4,4\" x1=\"".round($info->tai_rong + $info->dai + $info->rong/2)."\" y1=\"".round($info->cao+$info->tai_dai-$info->rong/2)."\"
          x2=\"".round($info->tai_rong + $info->dai + $info->rong * 1.5 + $info->kich_thuoc_cai_day)."\" y2=\"".round($info->kt_doc)."\"/>
    <line fill=\"none\" stroke=\"#2E58A6\" stroke-miterlimit=\"10\" stroke-dasharray=\"4,4\" x1=\"0\" y1=\"".round($info->cao+$info->tai_dai - $info->tai_rong)."\"
          x2=\"".round($info->rong + $info->tai_rong - $info->kich_thuoc_cai_day)."\" y2=\"".round($info->kt_doc)."\"/>
    <line fill=\"none\" stroke=\"#2E58A6\" stroke-miterlimit=\"10\" stroke-dasharray=\"4,4\" x1=\"".round($info->kt_ngang-$info->rong/2)."\"
          y1=\"".round($info->cao+$info->tai_dai-$info->rong/2)."\" x2=\"".round($info->kt_ngang)."\" y2=\"".round($info->cao+$info->tai_dai)."\"/>
</svg>";
        }
        elseif($info->kieu_hop == Products::HOP_CAI_DAY) {
                $svg = "<svg version=\"1.1\" id=\"Layer_1\" xmlns=\"http://www.w3.org/2000/svg\" xmlns:xlink=\"http://www.w3.org/1999/xlink\" x=\"0px\" y=\"0px\"
     width=\"" . ($info->kt_ngang) . "px\" height=\"" . ($info->kt_doc) . "px\" viewBox=\"0 0 " . ($info->kt_ngang) . " " . ($info->kt_doc) . "\" enable-background=\"new 0 0 " . ($info->kt_ngang) . " " . ($info->kt_doc) . "\" xml:space=\"preserve\">
     <polyline stroke=\"#D8532C\" stroke-miterlimit=\"10\" points=\"0," . ($info->nap + $info->cao) . " 0," . ($info->nap + $info->cao + $info->nap / 2) . " " . ($info->rong) . "," . ($info->nap + $info->cao + $info->nap / 2) . " " . ($info->rong) . "," . ($info->nap + $info->cao) . " " . ($info->dai + $info->rong) . "," . ($info->nap + $info->cao) . " " . ($info->dai + $info->rong) . "," . ($info->nap + $info->cao + $info->nap / 2) . " 
	" . ($info->dai + $info->rong * 2) . "," . ($info->nap + $info->cao + $info->nap / 2) . " " . ($info->dai + $info->rong * 2) . "," . ($info->dai * 2 + $info->rong * 2) . " " . ($info->rong * 2 + $info->dai + 3) . "," . ($info->nap * 2 + $info->cao) . " " . ($info->rong * 2 + $info->dai * 2 - 3) . "," . ($info->nap * 2 + $info->cao) . " " . ($info->dai * 2 + $info->rong * 2) . "," . ($info->dai * 2 + $info->rong * 2) . " " . ($info->dai * 2 + $info->rong * 2) . "," . ($info->nap + $info->cao) . " " . ($info->dai * 2 + $info->rong * 2 + $info->tai) . "," . ($info->nap + $info->cao - 3) . " " . ($info->dai * 2 + $info->rong * 2 + $info->tai) . "," . ($info->nap + 3) . " " . ($info->dai * 2 + $info->rong * 2) . "," . ($info->nap) . " " . ($info->dai + $info->rong * 2) . "," . ($info->nap) . " " . ($info->dai + $info->rong * 2) . "," . ($info->nap / 2) . " " . ($info->dai + $info->rong) . "," . ($info->nap / 2) . " " . ($info->dai + $info->rong) . "," . ($info->nap - $info->rong) . " " . ($info->rong + $info->dai - 3) . ",0 " . ($info->rong + 3) . ",0 " . ($info->rong) . "," . ($info->nap - $info->rong) . " 
	" . ($info->rong) . "," . ($info->nap / 2) . " 0," . ($info->nap / 2) . " 0," . ($info->nap) . " \"/>
<line fill=\"none\" stroke=\"#D8532C\" stroke-miterlimit=\"10\" x1=\"0\" y1=\"" . ($info->nap + $info->cao) . "\" x2=\"0\" y2=\"" . ($info->nap) . "\"/>
    <line fill=\"none\" stroke=\"#1E79AC\" stroke-miterlimit=\"10\" stroke-dasharray=\"3,2\" x1=\"" . ($info->rong) . "\" y1=\"" . ($info->nap + $info->cao) . "\" x2=\"" . ($info->rong) . "\" y2=\"" . ($info->nap) . "\"/>
    <line fill=\"none\" stroke=\"#1E79AC\" stroke-miterlimit=\"10\" stroke-dasharray=\"3,2\" x1=\"" . ($info->dai + $info->rong) . "\" y1=\"" . ($info->nap + $info->cao) . "\" x2=\"" . ($info->dai + $info->rong) . "\" y2=\"" . ($info->nap) . "\"/>
    <line fill=\"none\" stroke=\"#1E79AC\" stroke-miterlimit=\"10\" stroke-dasharray=\"3,2\" x1=\"" . ($info->dai + $info->rong * 2) . "\" y1=\"" . ($info->nap + $info->cao) . "\" x2=\"" . ($info->dai + $info->rong * 2) . "\" y2=\"" . ($info->nap) . "\"/>
    <line fill=\"none\" stroke=\"#1E79AC\" stroke-miterlimit=\"10\" stroke-dasharray=\"3,2\" x1=\"" . ($info->dai * 2 + $info->rong * 2) . "\" y1=\"" . ($info->nap + $info->cao) . "\" x2=\"" . ($info->dai * 2 + $info->rong * 2) . "\" y2=\"" . ($info->nap) . "\"/>
    <line fill=\"none\" stroke=\"#D8532C\" stroke-miterlimit=\"10\" x1=\"" . ($info->dai * 2 + $info->rong * 2 + $info->tai) . "\" y1=\"" . ($info->nap + $info->cao - 3) . "\" x2=\"" . ($info->dai * 2 + $info->rong * 2 + $info->tai) . "\" y2=\"" . ($info->nap + 3) . "\"/>
    <line fill=\"none\" stroke=\"#1E79AC\" stroke-miterlimit=\"10\" stroke-dasharray=\"3,2\" x1=\"0\" y1=\"" . ($info->nap) . "\" x2=\"" . ($info->rong) . "\" y2=\"" . ($info->nap) . "\"/>
    <line fill=\"none\" stroke=\"#1E79AC\" stroke-miterlimit=\"10\" stroke-dasharray=\"3,2\" x1=\"" . ($info->dai + $info->rong) . "\" y1=\"" . ($info->nap) . "\" x2=\"" . ($info->dai + $info->rong * 2) . "\" y2=\"" . ($info->nap) . "\"/>
    <line fill=\"none\" stroke=\"#1E79AC\" stroke-miterlimit=\"10\" stroke-dasharray=\"3,2\" x1=\"" . ($info->dai + $info->rong) . "\" y1=\"" . ($info->nap + $info->cao) . "\" x2=\"" . ($info->dai + $info->rong * 2) . "\" y2=\"" . ($info->nap + $info->cao) . "\"/>
    <line fill=\"none\" stroke=\"#1E79AC\" stroke-miterlimit=\"10\" stroke-dasharray=\"3,2\" x1=\"0\" y1=\"" . ($info->nap + $info->cao) . "\" x2=\"" . ($info->rong) . "\" y2=\"" . ($info->nap + $info->cao) . "\"/>
    <line fill=\"none\" stroke=\"#1E79AC\" stroke-miterlimit=\"10\" stroke-dasharray=\"3,2\" x1=\"" . ($info->rong) . "\" y1=\"" . ($info->nap) . "\" x2=\"" . ($info->dai + $info->rong) . "\" y2=\"" . ($info->nap) . "\"/>
    <line fill=\"none\" stroke=\"#D8532C\" stroke-miterlimit=\"10\" x1=\"" . ($info->rong + 3) . "\" y1=\"0\" x2=\"" . ($info->rong + $info->dai - 3) . "\" y2=\"0\"/>
    <line fill=\"none\" stroke=\"#1E79AC\" stroke-miterlimit=\"10\" stroke-dasharray=\"3,2\" x1=\"" . ($info->rong) . "\" y1=\"" . ($info->nap - $info->rong) . "\" x2=\"" . ($info->dai + $info->rong) . "\" y2=\"" . ($info->nap - $info->rong) . "\"/>
    <line fill=\"none\" stroke=\"#D8532C\" stroke-miterlimit=\"10\" x1=\"" . ($info->dai + $info->rong * 2) . "\" y1=\"" . ($info->nap) . "\" x2=\"" . ($info->dai * 2 + $info->rong * 2) . "\" y2=\"" . ($info->nap) . "\"/>
    <line fill=\"none\" stroke=\"#1E79AC\" stroke-miterlimit=\"10\" stroke-dasharray=\"3,2\" x1=\"" . ($info->dai + $info->rong * 2) . "\" y1=\"" . ($info->nap + $info->cao) . "\" x2=\"" . ($info->dai * 2 + $info->rong * 2) . "\" y2=\"" . ($info->nap + $info->cao) . "\"/>
    <line fill=\"none\" stroke=\"#D8532C\" stroke-miterlimit=\"10\" x1=\"" . ($info->rong) . "\" y1=\"" . ($info->nap + $info->cao) . "\" x2=\"" . ($info->dai + $info->rong) . "\" y2=\"" . ($info->nap + $info->cao) . "\"/>
    <line fill=\"none\" stroke=\"#D8532C\" stroke-miterlimit=\"10\" x1=\"" . ($info->rong * 2 + $info->dai + 3) . "\" y1=\"" . ($info->nap * 2 + $info->cao) . "\" x2=\"" . ($info->rong * 2 + $info->dai * 2 - 3) . "\" y2=\"" . ($info->nap * 2 + $info->cao) . "\"/>
    <line fill=\"none\" stroke=\"#1E79AC\" stroke-miterlimit=\"10\" stroke-dasharray=\"3,2\" x1=\"" . ($info->dai + $info->rong * 2) . "\" y1=\"" . ($info->dai * 2 + $info->rong * 2) . "\" x2=\"" . ($info->dai * 2 + $info->rong * 2) . "\" y2=\"" . ($info->dai * 2 + $info->rong * 2) . "\"/>
    <line fill=\"none\" stroke=\"#D8532C\" stroke-miterlimit=\"10\" x1=\"" . ($info->dai + $info->rong * 2) . "\" y1=\"" . ($info->nap / 2) . "\" x2=\"" . ($info->dai + $info->rong) . "\" y2=\"" . ($info->nap / 2) . "\"/>
    <line fill=\"none\" stroke=\"#D8532C\" stroke-miterlimit=\"10\" x1=\"0\" y1=\"" . ($info->nap / 2) . "\" x2=\"" . ($info->rong) . "\" y2=\"" . ($info->nap / 2) . "\"/>
    <line fill=\"none\" stroke=\"#D8532C\" stroke-miterlimit=\"10\" x1=\"0\" y1=\"" . ($info->nap + $info->cao + $info->nap / 2) . "\" x2=\"" . ($info->rong) . "\" y2=\"" . ($info->nap + $info->cao + $info->nap / 2) . "\"/>
    <line fill=\"none\" stroke=\"#D8532C\" stroke-miterlimit=\"10\" x1=\"" . ($info->dai + $info->rong) . "\" y1=\"" . ($info->nap + $info->cao + $info->nap / 2) . "\" x2=\"" . ($info->dai + $info->rong * 2) . "\" y2=\"" . ($info->nap + $info->cao + $info->nap / 2) . "\"/>
    <line fill=\"none\" stroke=\"#D8532C\" stroke-miterlimit=\"10\" x1=\"" . ($info->dai + $info->rong * 2) . "\" y1=\"" . ($info->nap + $info->cao) . "\" x2=\"" . ($info->dai + $info->rong * 2) . "\" y2=\"" . ($info->dai * 2 + $info->rong * 2) . "\"/>
    <line fill=\"none\" stroke=\"#D8532C\" stroke-miterlimit=\"10\" x1=\"" . ($info->dai * 2 + $info->rong * 2) . "\" y1=\"" . ($info->nap + $info->cao) . "\" x2=\"" . ($info->dai * 2 + $info->rong * 2) . "\" y2=\"" . ($info->dai * 2 + $info->rong * 2) . "\"/>
    <line fill=\"none\" stroke=\"#D8532C\" stroke-miterlimit=\"10\" x1=\"" . ($info->rong) . "\" y1=\"" . ($info->nap) . "\" x2=\"" . ($info->rong) . "\" y2=\"" . ($info->nap - $info->rong) . "\"/>
    <line fill=\"none\" stroke=\"#D8532C\" stroke-miterlimit=\"10\" x1=\"" . ($info->dai + $info->rong) . "\" y1=\"" . ($info->nap) . "\" x2=\"" . ($info->dai + $info->rong) . "\" y2=\"" . ($info->nap - $info->rong) . "\"/>
    <line fill=\"none\" stroke=\"#D8532C\" stroke-miterlimit=\"10\" x1=\"0\" y1=\"" . ($info->nap) . "\" x2=\"0\" y2=\"" . ($info->nap / 2) . "\"/>
    <line fill=\"none\" stroke=\"#D8532C\" stroke-miterlimit=\"10\" x1=\"" . ($info->dai + $info->rong * 2) . "\" y1=\"" . ($info->nap) . "\" x2=\"" . ($info->dai + $info->rong * 2) . "\" y2=\"" . ($info->nap / 2) . "\"/>
    <line fill=\"none\" stroke=\"#D8532C\" stroke-miterlimit=\"10\" x1=\"0\" y1=\"" . ($info->nap + $info->cao) . "\" x2=\"0\" y2=\"" . ($info->nap + $info->cao + $info->nap / 2) . "\"/>
    <line fill=\"none\" stroke=\"#D8532C\" stroke-miterlimit=\"10\" x1=\"" . ($info->rong) . "\" y1=\"" . ($info->nap + $info->cao) . "\" x2=\"" . ($info->rong) . "\" y2=\"" . ($info->nap + $info->cao + $info->nap / 2) . "\"/>
    <line fill=\"none\" stroke=\"#D8532C\" stroke-miterlimit=\"10\" x1=\"" . ($info->dai + $info->rong) . "\" y1=\"" . ($info->nap + $info->cao) . "\" x2=\"" . ($info->dai + $info->rong) . "\" y2=\"" . ($info->nap + $info->cao + $info->nap / 2) . "\"/>
    <line fill=\"none\" stroke=\"#D8532C\" stroke-miterlimit=\"10\" x1=\"" . ($info->dai * 2 + $info->rong * 2) . "\" y1=\"" . ($info->nap) . "\" x2=\"" . ($info->dai * 2 + $info->rong * 2 + $info->tai) . "\" y2=\"" . ($info->nap + 3) . "\"/>
    <line fill=\"none\" stroke=\"#D8532C\" stroke-miterlimit=\"10\" x1=\"" . ($info->dai * 2 + $info->rong * 2) . "\" y1=\"" . ($info->nap + $info->cao) . "\" x2=\"" . ($info->dai * 2 + $info->rong * 2 + $info->tai) . "\" y2=\"" . ($info->nap + $info->cao - 3) . "\"/>
    <line fill=\"none\" stroke=\"#D8532C\" stroke-miterlimit=\"10\" x1=\"" . ($info->rong + 3) . "\" y1=\"0\" x2=\"" . ($info->rong) . "\" y2=\"" . ($info->nap - $info->rong) . "\"/>
    <line fill=\"none\" stroke=\"#D8532C\" stroke-miterlimit=\"10\" x1=\"" . ($info->dai * 2 + $info->rong * 2) . "\" y1=\"" . ($info->dai * 2 + $info->rong * 2) . "\" x2=\"" . ($info->rong * 2 + $info->dai * 2 - 3) . "\" y2=\"" . ($info->nap * 2 + $info->cao) . "\"/>
    <line fill=\"none\" stroke=\"#D8532C\" stroke-miterlimit=\"10\" x1=\"" . ($info->dai + $info->rong * 2) . "\" y1=\"" . ($info->dai * 2 + $info->rong * 2) . "\" x2=\"" . ($info->rong * 2 + $info->dai + 3) . "\" y2=\"" . ($info->nap * 2 + $info->cao) . "\"/>
    <line fill=\"none\" stroke=\"#D8532C\" stroke-miterlimit=\"10\" x1=\"" . ($info->rong + $info->dai - 3) . "\" y1=\"0\" x2=\"" . ($info->dai + $info->rong) . "\" y2=\"" . ($info->nap - $info->rong) . "\"/>
</svg>";
        }elseif($info->kieu_hop == Products::HOP_MOC_DAY){
            if($info->chan == 0) {
                $svg = '<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
     width="'.round($info->kt_ngang).'px" height="'.round($info->kt_doc).'px" viewBox="0 0 '.round($info->kt_ngang).' '.round($info->kt_doc).'" enable-background="new 0 0 '.round($info->kt_ngang).' '.round($info->kt_doc).'" xml:space="preserve">
<line fill="none" stroke="#D8532C" stroke-miterlimit="10" x1="'.round($info->rong).'" y1="0" x2="'.round($info->rong).'" y2="'.round($info->day).'"/>
    <line fill="none" stroke="#D8532C" stroke-miterlimit="10" x1="'.round($info->rong + $info->dai).'" y1="0" x2="'.round($info->rong + $info->dai).'" y2="'.round($info->day).'"/>
    <line fill="none" stroke="#D8532C" stroke-miterlimit="10" x1="'.round($info->rong * 2 + $info->dai).'" y1="8" x2="'.round($info->rong * 2 + $info->dai).'" y2="'.round($info->day).'"/>
    <line fill="none" stroke="#D8532C" stroke-miterlimit="10" x1="'.round($info->rong * 2 + $info->dai * 2).'" y1="8" x2="'.round($info->rong * 2 + $info->dai * 2).'" y2="'.round($info->day).'"/>
    <line fill="none" stroke="#D8532C" stroke-miterlimit="10" x1="0" y1="'.round($info->day).'" x2="0" y2="'.round($info->cao + $info->day).'"/>
    <line fill="none" stroke="#1E79AC" stroke-miterlimit="10" stroke-dasharray="2,2" x1="'.round($info->rong).'" y1="'.round($info->day).'" x2="'.round($info->rong).'" y2="'.round($info->cao + $info->day).'"/>
    <line fill="none" stroke="#1E79AC" stroke-miterlimit="10" stroke-dasharray="2,2" x1="'.round($info->rong + $info->dai).'" y1="'.round($info->day).'" x2="'.round($info->rong + $info->dai).'" y2="'.round($info->cao + $info->day).'"/>
    <line fill="none" stroke="#1E79AC" stroke-miterlimit="10" stroke-dasharray="2,2" x1="'.round($info->rong * 2 + $info->dai).'" y1="'.round($info->day).'" x2="'.round($info->rong * 2 + $info->dai).'" y2="'.round($info->cao + $info->day).'"/>
    <line fill="none" stroke="#1E79AC" stroke-miterlimit="10" stroke-dasharray="2,2" x1="'.round($info->rong * 2 + $info->dai * 2).'" y1="'.round($info->day).'" x2="'.round($info->rong * 2 + $info->dai * 2).'" y2="'.round($info->cao + $info->day).'"/>
    <line fill="none" stroke="#D8532C" stroke-miterlimit="10" x1="'.round($info->kt_ngang).'" y1="'.round($info->day+3).'" x2="'.round($info->kt_ngang).'" y2="'.round($info->day + $info->cao - 3).'"/>
    <line fill="none" stroke="#1E79AC" stroke-miterlimit="10" stroke-dasharray="2,2" x1="0" y1="'.round($info->day).'" x2="'.round($info->rong).'" y2="'.round($info->day).'"/>
    <line fill="none" stroke="#1E79AC" stroke-miterlimit="10" stroke-dasharray="2,2" x1="0" y1="'.round($info->cao + $info->day).'" x2="'.round($info->rong).'" y2="'.round($info->cao + $info->day).'"/>
    <line fill="none" stroke="#D8532C" stroke-miterlimit="10" x1="5" y1="'.round($info->day + $info->cao + $info->nap/2).'" x2="'.round($info->rong - 3).'" y2="'.round($info->day + $info->cao + $info->nap/2).'"/>
    <line fill="none" stroke="#1E79AC" stroke-miterlimit="10" stroke-dasharray="2,2" x1="'.round($info->rong).'" y1="'.round($info->day).'" x2="'.round($info->rong + $info->dai).'" y2="'.round($info->day).'"/>
    <line fill="none" stroke="#1E79AC" stroke-miterlimit="10" stroke-dasharray="2,2" x1="'.round($info->rong).'" y1="'.round($info->cao + $info->day).'" x2="'.round($info->rong + $info->dai).'" y2="'.round($info->cao + $info->day).'"/>
    <line fill="none" stroke="#1E79AC" stroke-miterlimit="10" stroke-dasharray="2,2" x1="'.round($info->rong + $info->dai).'" y1="'.round($info->day).'" x2="'.round($info->rong * 2 + $info->dai).'" y2="'.round($info->day).'"/>
    <line fill="none" stroke="#1E79AC" stroke-miterlimit="10" stroke-dasharray="2,2" x1="'.round($info->rong + $info->dai).'" y1="'.round($info->cao + $info->day).'" x2="'.round($info->rong * 2 + $info->dai).'" y2="'.round($info->cao + $info->day).'"/>
    <line fill="none" stroke="#1E79AC" stroke-miterlimit="10" stroke-dasharray="2,2" x1="'.round($info->rong * 2 + $info->dai).'" y1="'.round($info->day).'" x2="'.round($info->rong * 2 + $info->dai * 2).'" y2="'.round($info->day).'"/>
    <line fill="none" stroke="#D8532C" stroke-miterlimit="10" x1="'.round($info->rong * 2 + $info->dai).'" y1="'.round($info->cao + $info->day).'" x2="'.round($info->rong * 2 + $info->dai * 2).'" y2="'.round($info->cao + $info->day).'"/>
    <line fill="none" stroke="#D8532C" stroke-miterlimit="10" x1="'.round($info->rong).'" y1="'.round($info->cao + $info->day).'" x2="'.round($info->rong).'" y2="'.round($info->day + $info->cao + $info->rong).'"/>
    <line fill="none" stroke="#D8532C" stroke-miterlimit="10" x1="'.round($info->rong + $info->dai).'" y1="'.round($info->cao + $info->day).'" x2="'.round($info->rong + $info->dai).'" y2="'.round($info->day + $info->cao + $info->rong).'"/>
    <line fill="none" stroke="#D8532C" stroke-miterlimit="10" x1="0" y1="'.round($info->cao + $info->day).'" x2="0" y2="'.round($info->cao + $info->day + 6).'"/>
    <line fill="none" stroke="#D8532C" stroke-miterlimit="10" x1="'.round($info->rong + 10).'" y1="'.round($info->kt_doc).'" x2="'.round($info->rong + $info->dai - 10).'" y2="'.round($info->kt_doc).'"/>
    <line fill="none" stroke="#1E79AC" stroke-miterlimit="10" stroke-dasharray="2,2" x1="'.round($info->rong).'" y1="'.round($info->day + $info->cao + $info->rong).'" x2="'.round($info->rong + $info->dai).'" y2="'.round($info->day + $info->cao + $info->rong).'"/>
    <line fill="none" stroke="#D8532C" stroke-miterlimit="10" x1="'.round($info->kt_ngang).'" y1="'.round($info->day+3).'" x2="'.round($info->rong * 2 + $info->dai * 2).'" y2="'.round($info->day).'"/>
    <line fill="none" stroke="#D8532C" stroke-miterlimit="10" x1="'.round($info->kt_ngang).'" y1="'.round($info->day + $info->cao - 3).'" x2="'.round($info->rong * 2 + $info->dai * 2).'" y2="'.round($info->cao + $info->day).'"/>
    <line fill="none" stroke="#D8532C" stroke-miterlimit="10" x1="'.round($info->rong * 2 + $info->dai).'" y1="8" x2="'.round($info->rong * 2 + $info->dai + $info->dai*1/3).'" y2="8"/>
    <line fill="none" stroke="#D8532C" stroke-miterlimit="10" x1="'.round($info->rong * 2 + $info->dai + $info->dai *2/3).'" y1="8" x2="'.round($info->rong * 2 + $info->dai * 2).'" y2="8"/>
    <line fill="none" stroke="#D8532C" stroke-miterlimit="10" x1="'.round($info->rong * 2 + $info->dai + $info->dai*1/3 -3).'" y1="35" x2="'.round($info->rong * 2 + $info->dai + $info->dai*2/3 + 3).'" y2="35"/>
    <line fill="none" stroke="#D8532C" stroke-miterlimit="10" x1="'.round($info->rong * 2 + $info->dai +  $info->dai*1/3-3).'" y1="35" x2="'.round($info->rong * 2 + $info->dai +  $info->dai*1/3).'" y2="8"/>
    <line fill="none" stroke="#D8532C" stroke-miterlimit="10" x1="'.round($info->rong * 2 + $info->dai + $info->dai * 2/3).'" y1="8" x2="'.round($info->rong * 2 + $info->dai+ $info->dai*2/3 +3).'" y2="35"/>
    <line fill="none" stroke="#D8532C" stroke-miterlimit="10" x1="'.round($info->rong + $info->dai).'" y1="0" x2="'.round($info->dai + $info->rong + $info->rong / 2 + 5).'" y2="0"/>
    <line fill="none" stroke="#D8532C" stroke-miterlimit="10" x1="'.round($info->rong / 2 - 5).'" y1="0" x2="'.round($info->rong).'" y2="0"/>
    <line fill="none" stroke="#D8532C" stroke-miterlimit="10" x1="'.round($info->dai + $info->rong + $info->rong / 2 + 5).'" y1="0" x2="'.round($info->dai + $info->rong + $info->rong/2).'" y2="'.round($info->day/3).'"/>
    <line fill="none" stroke="#D8532C" stroke-miterlimit="10" x1="'.round($info->dai + $info->rong + $info->rong/2).'" y1="'.round($info->day/3).'" x2="'.round($info->rong * 2 + $info->dai).'" y2="'.round($info->day).'"/>
    <line fill="none" stroke="#D8532C" stroke-miterlimit="10" x1="'.round($info->rong / 2 - 5).'" y1="0" x2="'.round($info->rong/2).'" y2="'.round($info->day/3).'"/>
    <line fill="none" stroke="#D8532C" stroke-miterlimit="10" x1="'.round($info->rong/2).'" y1="'.round($info->day/3).'" x2="0" y2="'.round($info->day).'"/>
    <line fill="none" stroke="#D8532C" stroke-miterlimit="10" x1="'.round($info->rong + $info->dai*1/3).'" y1="13" x2="'.round($info->rong + $info->dai*1/3).'" y2="28"/>
    <line fill="none" stroke="#D8532C" stroke-miterlimit="10" x1="'.round($info->rong + $info->dai*2/3).'" y1="13" x2="'.round($info->rong +  $info->dai*2/3).'" y2="28"/>
    <line fill="none" stroke="#D8532C" stroke-miterlimit="10" x1="'.round($info->rong + $info->dai*1/3).'" y1="28" x2="'.round($info->rong).'" y2="'.round($info->day).'"/>
    <line fill="none" stroke="#D8532C" stroke-miterlimit="10" x1="'.round($info->rong + $info->dai*2/3).'" y1="28" x2="'.round($info->rong + $info->dai).'" y2="'.round($info->day).'"/>
    <line fill="none" stroke="#D8532C" stroke-miterlimit="10" x1="'.round($info->rong + $info->dai*1/3).'" y1="13" x2="'.round($info->rong + $info->dai*2/3).'" y2="13"/>
    <line fill="none" stroke="#D8532C" stroke-miterlimit="10" x1="5" y1="'.round($info->day + $info->cao + $info->nap/2).'" x2="0" y2="'.round($info->cao + $info->day + 6).'"/>
    <line fill="none" stroke="#D8532C" stroke-miterlimit="10" x1="'.round($info->rong).'" y1="'.round($info->cao + $info->day).'" x2="'.round($info->rong - 3).'" y2="'.round($info->day + $info->cao + 3).'"/>
    <line fill="none" stroke="#D8532C" stroke-miterlimit="10" x1="'.round($info->rong - 3).'" y1="'.round($info->day + $info->cao + 3).'" x2="'.round($info->rong - 3).'" y2="'.round($info->day + $info->cao + $info->nap/2).'"/>
    <line fill="none" stroke="#D8532C" stroke-miterlimit="10" x1="'.round($info->rong * 2 + $info->dai - 6).'" y1="'.round($info->day + $info->cao + $info->nap/2).'" x2="'.round($info->rong + $info->dai + 3).'" y2="'.round($info->day + $info->cao + $info->nap/2).'"/>
    <line fill="none" stroke="#D8532C" stroke-miterlimit="10" x1="'.round($info->rong * 2 + $info->dai).'" y1="'.round($info->cao + $info->day).'" x2="'.round($info->rong * 2 + $info->dai).'" y2="'.round($info->cao + $info->day + 6).'"/>
    <line fill="none" stroke="#D8532C" stroke-miterlimit="10" x1="'.round($info->rong * 2 + $info->dai - 6).'" y1="'.round($info->day + $info->cao + $info->nap/2).'" x2="'.round($info->rong * 2 + $info->dai).'" y2="'.round($info->cao + $info->day + 6).'"/>
    <line fill="none" stroke="#D8532C" stroke-miterlimit="10" x1="'.round($info->rong + $info->dai).'" y1="'.round($info->cao + $info->day).'" x2="'.round($info->rong + $info->dai + 3).'" y2="'.round($info->day + $info->cao + 3).'"/>
    <line fill="none" stroke="#D8532C" stroke-miterlimit="10" x1="'.round($info->rong + $info->dai + 3).'" y1="'.round($info->day + $info->cao + 3).'" x2="'.round($info->rong + $info->dai + 3).'" y2="'.round($info->day + $info->cao + $info->nap/2).'"/>
    <line fill="none" stroke="#D8532C" stroke-miterlimit="10" x1="'.round($info->rong).'" y1="'.round($info->day + $info->cao + $info->rong).'" x2="'.round($info->rong + 10).'" y2="'.round($info->kt_doc).'"/>
    <line fill="none" stroke="#D8532C" stroke-miterlimit="10" x1="'.round($info->rong + $info->dai).'" y1="'.round($info->day + $info->cao + $info->rong).'" x2="'.round($info->rong + $info->dai - 10).'" y2="'.round($info->kt_doc).'"/>
</svg>';
            }else {
                $svg = '<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
     width="'.round($info->kt_ngang).'px" height="'.round($info->kt_doc).'px" viewBox="0 0 '.round($info->kt_ngang).' '.round($info->kt_doc).'" enable-background="new 0 0 '.round($info->kt_ngang).' '.round($info->kt_doc).'" xml:space="preserve">
<line fill="none" stroke="#D8532C" stroke-miterlimit="10" x1="'.round($info->rong).'" y1="0" x2="'.round($info->rong).'" y2="'.round($info->day).'"/>
    <line fill="none" stroke="#D8532C" stroke-miterlimit="10" x1="'.round($info->rong + $info->dai).'" y1="0" x2="'.round($info->rong + $info->dai).'" y2="'.round($info->day).'"/>
    <line fill="none" stroke="#D8532C" stroke-miterlimit="10" x1="'.round($info->rong * 2 + $info->dai).'" y1="8" x2="'.round($info->rong * 2 + $info->dai).'" y2="'.round($info->day).'"/>
    <line fill="none" stroke="#D8532C" stroke-miterlimit="10" x1="'.round($info->rong * 2 + $info->dai * 2).'" y1="8" x2="'.round($info->rong * 2 + $info->dai * 2).'" y2="'.round($info->day).'"/>
    <line fill="none" stroke="#D8532C" stroke-miterlimit="10" x1="0" y1="'.round($info->day).'" x2="0" y2="'.round($info->cao + $info->day).'"/>
    <line fill="none" stroke="#1E79AC" stroke-miterlimit="10" stroke-dasharray="2,2" x1="'.round($info->rong).'" y1="'.round($info->day).'" x2="'.round($info->rong).'" y2="'.round($info->cao + $info->day).'"/>
    <line fill="none" stroke="#1E79AC" stroke-miterlimit="10" stroke-dasharray="2,2" x1="'.round($info->rong + $info->dai).'" y1="'.round($info->day).'" x2="'.round($info->rong + $info->dai).'" y2="'.round($info->cao + $info->day).'"/>
    <line fill="none" stroke="#1E79AC" stroke-miterlimit="10" stroke-dasharray="2,2" x1="'.round($info->rong * 2 + $info->dai).'" y1="'.round($info->day).'" x2="'.round($info->rong * 2 + $info->dai).'" y2="'.round($info->cao + $info->day).'"/>
    <line fill="none" stroke="#1E79AC" stroke-miterlimit="10" stroke-dasharray="2,2" x1="'.round($info->rong * 2 + $info->dai * 2).'" y1="'.round($info->day).'" x2="'.round($info->rong * 2 + $info->dai * 2).'" y2="'.round($info->cao + $info->day).'"/>
    <line fill="none" stroke="#D8532C" stroke-miterlimit="10" x1="'.round($info->kt_ngang).'" y1="'.round( $info->day + 3).'" x2="'.round($info->kt_ngang).'" y2="'.round($info->day + $info->cao - 3).'"/>
    <line fill="none" stroke="#1E79AC" stroke-miterlimit="10" stroke-dasharray="2,2" x1="0" y1="'.round($info->day).'" x2="'.round($info->rong).'" y2="'.round($info->day).'"/>
    <line fill="none" stroke="#1E79AC" stroke-miterlimit="10" stroke-dasharray="2,2" x1="0" y1="'.round($info->cao + $info->day).'" x2="'.round($info->rong).'" y2="'.round($info->cao + $info->day).'"/>
    <line fill="none" stroke="#D8532C" stroke-miterlimit="10" x1="3" y1="'.round($info->day + $info->cao + $info->nap/2).'" x2="'.round( $info->rong - 3).'" y2="'.round($info->day + $info->cao + $info->nap/2).'"/>
    <line fill="none" stroke="#1E79AC" stroke-miterlimit="10" stroke-dasharray="2,2" x1="'.round($info->rong).'" y1="'.round($info->day).'" x2="'.round($info->rong + $info->dai).'" y2="'.round($info->day).'"/>
    <line fill="none" stroke="#1E79AC" stroke-miterlimit="10" stroke-dasharray="2,2" x1="'.round($info->rong).'" y1="'.round($info->cao + $info->day).'" x2="'.round($info->rong + $info->dai).'" y2="'.round($info->cao + $info->day).'"/>
    <line fill="none" stroke="#1E79AC" stroke-miterlimit="10" stroke-dasharray="2,2" x1="'.round($info->rong + $info->dai).'" y1="'.round($info->day).'" x2="'.round($info->rong * 2 + $info->dai).'" y2="'.round($info->day).'"/>
    <line fill="none" stroke="#1E79AC" stroke-miterlimit="10" stroke-dasharray="2,2" x1="'.round($info->rong + $info->dai).'" y1="'.round($info->cao + $info->day).'" x2="'.round($info->rong * 2 + $info->dai).'" y2="'.round($info->cao + $info->day).'"/>
    <line fill="none" stroke="#1E79AC" stroke-miterlimit="10" stroke-dasharray="2,2" x1="'.round($info->rong * 2 + $info->dai).'" y1="'.round($info->day).'" x2="'.round($info->rong * 2 + $info->dai * 2).'" y2="'.round($info->day).'"/>
    <line fill="none" stroke="#D8532C" stroke-miterlimit="10" x1="'.round($info->rong * 2 + $info->dai).'" y1="'.round($info->cao + $info->day).'" x2="'.round($info->rong * 2 + $info->dai * 2).'" y2="'.round($info->cao + $info->day).'"/>
    <line fill="none" stroke="#D8532C" stroke-miterlimit="10" x1="'.round($info->rong * 2 + $info->dai).'" y1="'.round($info->cao + $info->day).'" x2="'.round($info->rong * 2 + $info->dai).'" y2="'.round($info->day + $info->cao + $info->rong).'"/>
    <line fill="none" stroke="#D8532C" stroke-miterlimit="10" x1="'.round($info->rong * 2 + $info->dai * 2).'" y1="'.round($info->cao + $info->day).'" x2="'.round($info->rong * 2 + $info->dai * 2).'" y2="'.round($info->day + $info->cao + $info->rong).'"/>
    <line fill="none" stroke="#D8532C" stroke-miterlimit="10" x1="'.round($info->rong).'" y1="'.round($info->cao + $info->day + 6).'" x2="'.round($info->rong).'" y2="'.round($info->cao + $info->day).'"/>
    <line fill="none" stroke="#D8532C" stroke-miterlimit="10" x1="'.round($info->rong * 2 + $info->dai + 10).'" y1="'.round($info->kt_doc).'" x2="'.round($info->rong * 2 + $info->dai*2 - 10).'" y2="'.round($info->kt_doc).'"/>
    <line fill="none" stroke="#1E79AC" stroke-miterlimit="10" stroke-dasharray="2,2" x1="'.round($info->rong * 2 + $info->dai).'" y1="'.round($info->day + $info->cao + $info->rong).'" x2="'.round($info->rong * 2 + $info->dai * 2).'" y2="'.round($info->day + $info->cao + $info->rong).'"/>
    <line fill="none" stroke="#D8532C" stroke-miterlimit="10" x1="'.round($info->kt_ngang).'" y1="'.round( $info->day + 3).'" x2="'.round($info->rong * 2 + $info->dai * 2).'" y2="'.round($info->day).'"/>
    <line fill="none" stroke="#D8532C" stroke-miterlimit="10" x1="'.round($info->kt_ngang).'" y1="'.round($info->day + $info->cao - 3).'" x2="'.round($info->rong * 2 + $info->dai * 2).'" y2="'.round($info->cao + $info->day).'"/>
    <line fill="none" stroke="#D8532C" stroke-miterlimit="10" x1="'.round($info->rong * 2 + $info->dai).'" y1="8" x2="'.round($info->rong * 2 + $info->dai + $info->dai*1/3).'" y2="8"/>
    <line fill="none" stroke="#D8532C" stroke-miterlimit="10" x1="'.round($info->rong * 2 + $info->dai + $info->dai * 2/3).'" y1="8" x2="'.round($info->rong * 2 + $info->dai * 2).'" y2="8"/>
    <line fill="none" stroke="#D8532C" stroke-miterlimit="10" x1="'.round($info->rong * 2 + $info->dai + $info->dai *1/3 - 3).'" y1="35" x2="'.round($info->rong * 2  + $info->dai+ $info->dai*2/3 + 3).'" y2="35"/>
    <line fill="none" stroke="#D8532C" stroke-miterlimit="10" x1="'.round($info->rong * 2 + $info->dai + $info->dai *1/3 - 3).'" y1="35" x2="'.round($info->rong * 2 + $info->dai + $info->dai *1/3).'" y2="8"/>
    <line fill="none" stroke="#D8532C" stroke-miterlimit="10" x1="'.round($info->rong * 2 + $info->dai + $info->dai *2/3).'" y1="8" x2="'.round($info->rong * 2 + $info->dai + $info->dai *2/3 + 3).'" y2="35"/>
    <line fill="none" stroke="#D8532C" stroke-miterlimit="10" x1="'.round($info->rong + $info->dai).'" y1="0" x2="'.round($info->rong + $info->dai + $info->rong/2 + 3).'" y2="0"/>
    <line fill="none" stroke="#D8532C" stroke-miterlimit="10" x1="'.round($info->rong/2 - 3).'" y1="0" x2="'.round($info->rong).'" y2="0"/>
    <line fill="none" stroke="#D8532C" stroke-miterlimit="10" x1="'.round($info->rong+$info->dai + $info->rong/2 + 5).'" y1="0" x2="'.round($info->dai + $info->rong + $info->rong/2).'" y2="'.round($info->day/3).'"/>
    <line fill="none" stroke="#D8532C" stroke-miterlimit="10" x1="'.round($info->dai + $info->rong + $info->rong/2).'" y1="'.round($info->day/3).'" x2="'.round($info->rong * 2 + $info->dai).'" y2="'.round($info->day).'"/>
    <line fill="none" stroke="#D8532C" stroke-miterlimit="10" x1="'.round($info->rong / 2 - 5).'" y1="0" x2="'.round($info->rong/2).'" y2="'.round($info->day/3).'"/>
    <line fill="none" stroke="#D8532C" stroke-miterlimit="10" x1="'.round($info->rong/2).'" y1="'.round($info->day/3).'" x2="0" y2="'.round($info->day).'"/>
    <line fill="none" stroke="#D8532C" stroke-miterlimit="10" x1="'.round($info->rong + $info->dai/3).'" y1="13" x2="'.round($info->rong + $info->dai/3).'" y2="28"/>
    <line fill="none" stroke="#D8532C" stroke-miterlimit="10" x1="'.round($info->rong + $info->dai *2/3).'" y1="13" x2="'.round($info->rong + $info->dai *2/3).'" y2="28"/>
    <line fill="none" stroke="#D8532C" stroke-miterlimit="10" x1="'.round($info->rong + $info->dai/3).'" y1="28" x2="'.round($info->rong).'" y2="'.round($info->day).'"/>
    <line fill="none" stroke="#D8532C" stroke-miterlimit="10" x1="'.round($info->rong + $info->dai *2/3).'" y1="28" x2="'.round($info->rong + $info->dai).'" y2="'.round($info->day).'"/>
    <line fill="none" stroke="#D8532C" stroke-miterlimit="10" x1="'.round($info->rong + $info->dai*1/3).'" y1="13" x2="'.round($info->rong + $info->dai*2/3).'" y2="13"/>
    <line fill="none" stroke="#D8532C" stroke-miterlimit="10" x1="'.round($info->rong).'" y1="'.round($info->cao + $info->day + 6).'" x2="'.round( $info->rong - 3).'" y2="'.round($info->day + $info->cao + $info->nap/2).'"/>
    <line fill="none" stroke="#D8532C" stroke-miterlimit="10" x1="3" y1="'.round($info->day + $info->cao + 3).'" x2="0" y2="'.round($info->cao + $info->day).'"/>
    <line fill="none" stroke="#D8532C" stroke-miterlimit="10" x1="3" y1="'.round($info->day + $info->cao + $info->nap/2).'" x2="3" y2="'.round($info->day + $info->cao + 3).'"/>
    <line fill="none" stroke="#D8532C" stroke-miterlimit="10" x1="'.round($info->rong * 2 + $info->dai - 6).'" y1="'.round($info->day + $info->cao + $info->nap/2).'" x2="'.round($info->rong + $info->dai + 3).'" y2="'.round($info->day + $info->cao + $info->nap/2).'"/>
    <line fill="none" stroke="#D8532C" stroke-miterlimit="10" x1="'.round($info->rong + $info->dai).'" y1="'.round($info->cao + $info->day + 6).'" x2="'.round($info->rong + $info->dai).'" y2="'.round($info->cao + $info->day).'"/>
    <line fill="none" stroke="#D8532C" stroke-miterlimit="10" x1="'.round($info->rong + $info->dai).'" y1="'.round($info->cao + $info->day + 6).'" x2="'.round($info->rong + $info->dai + 3).'" y2="'.round($info->day + $info->cao + $info->nap/2).'"/>
    <line fill="none" stroke="#D8532C" stroke-miterlimit="10" x1="'.round($info->rong * 2 + $info->dai - 6).'" y1="'.round($info->day + $info->cao + 3).'" x2="'.round($info->rong * 2 + $info->dai).'" y2="'.round($info->cao + $info->day).'"/>
    <line fill="none" stroke="#D8532C" stroke-miterlimit="10" x1="'.round($info->rong * 2 + $info->dai - 6).'" y1="'.round($info->day + $info->cao + $info->nap/2).'" x2="'.round($info->rong * 2 + $info->dai - 6).'" y2="'.round($info->day + $info->cao + 3).'"/>
    <line fill="none" stroke="#D8532C" stroke-miterlimit="10" x1="'.round($info->rong * 2 + $info->dai).'" y1="'.round($info->day + $info->cao + $info->rong).'" x2="'.round($info->rong * 2 + $info->dai + 10).'" y2="'.round($info->kt_doc).'"/>
    <line fill="none" stroke="#D8532C" stroke-miterlimit="10" x1="'.round($info->rong * 2 + $info->dai * 2).'" y1="'.round($info->day + $info->cao + $info->rong).'" x2="'.round($info->rong * 2 + $info->dai * 2 - 10).'" y2="'.round($info->kt_doc).'"/>
</svg>';
            }
        }elseif($info->kieu_hop == Products::HOP_CUNG_DINH_HINH){
            $svg = '<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
     width="'.round($info->kt_ngang).'px" height="'.round($info->kt_doc).'px" viewBox="0 0 '.round($info->kt_ngang).' '.round($info->kt_doc).'" enable-background="new 0 0 '.round($info->kt_ngang).' '.round($info->kt_doc).'" xml:space="preserve">
<line fill="none" stroke="#149BC5" stroke-miterlimit="10" stroke-dasharray="2,2" x1="'.round($info->cao).'" y1="'.round($info->cao).'" x2="'.round($info->cao + $info->rong).'" y2="'.round($info->cao).'"/>
    <line fill="none" stroke="#D8542C" stroke-miterlimit="10" x1="'.round($info->cao).'" y1="0" x2="'.round($info->cao + $info->rong).'" y2="0"/>
    <line fill="none" stroke="#149BC5" stroke-miterlimit="10" stroke-dasharray="2,2" x1="'.round($info->cao).'" y1="'.round($info->cao + $info->dai).'" x2="'.round($info->cao + $info->rong).'" y2="'.round($info->cao + $info->dai).'"/>
    <line fill="none" stroke="#149BC5" stroke-miterlimit="10" stroke-dasharray="2,2" x1="'.round($info->cao).'" y1="'.round($info->cao).'" x2="'.round($info->cao).'" y2="'.round($info->cao + $info->dai).'"/>
    <line fill="none" stroke="#D8542C" stroke-miterlimit="10" x1="0" y1="'.round($info->cao).'" x2="0" y2="'.round($info->cao + $info->dai).'"/>
    <line fill="none" stroke="#149BC5" stroke-miterlimit="10" stroke-dasharray="2,2" x1="'.round($info->cao + $info->rong).'" y1="'.round($info->cao).'" x2="'.round($info->cao + $info->rong).'" y2="'.round($info->cao + $info->dai).'"/>
    <line fill="none" stroke="#D8542C" stroke-miterlimit="10" x1="'.round($info->kt_ngang).'" y1="'.round($info->cao).'" x2="'.round($info->kt_ngang).'" y2="'.round($info->cao + $info->dai).'"/>
    <line fill="none" stroke="#D8542C" stroke-miterlimit="10" x1="'.round($info->cao + $info->rong).'" y1="'.round($info->cao).'" x2="'.round($info->kt_ngang).'" y2="'.round($info->cao).'"/>
    <line fill="none" stroke="#D8542C" stroke-miterlimit="10" x1="0" y1="'.round($info->cao + $info->dai).'" x2="'.round($info->cao).'" y2="'.round($info->cao + $info->dai).'"/>
    <line fill="none" stroke="#D8542C" stroke-miterlimit="10" x1="0" y1="'.round($info->cao).'" x2="'.round($info->cao).'" y2="'.round($info->cao).'"/>
    <line fill="none" stroke="#D8542C" stroke-miterlimit="10" x1="'.round($info->cao + $info->rong).'" y1="'.round($info->cao + $info->dai).'" x2="'.round($info->kt_ngang).'" y2="'.round($info->cao + $info->dai).'"/>
    <path fill="none" stroke="#D8542C" stroke-miterlimit="10" d="M'.round($info->cao).','.round($info->cao + $info->dai).'"/>
    <line fill="none" stroke="#149BC5" stroke-miterlimit="10" stroke-dasharray="2,2" x1="'.round($info->cao).'" y1="0" x2="'.round($info->cao).'" y2="'.round($info->cao).'"/>
    <line fill="none" stroke="#D8542C" stroke-miterlimit="10" x1="'.round($info->cao - $info->tai).'" y1="3" x2="'.round($info->cao - $info->tai).'" y2="'.round($info->cao - 3).'"/>
    <path fill="none" stroke="#D8542C" stroke-miterlimit="10" d="M'.round($info->cao - $info->tai).','.round($info->cao + $info->dai).'"/>
    <line fill="none" stroke="#D8542C" stroke-miterlimit="10" x1="'.round($info->cao + $info->tai + $info->rong).'" y1="3" x2="'.round($info->cao + $info->tai + $info->rong).'" y2="'.round($info->cao - 3).'"/>
    <path fill="none" stroke="#D8542C" stroke-miterlimit="10" d="M'.round($info->cao + $info->tai + $info->rong).','.round($info->cao + $info->dai).'"/>
    <line fill="none" stroke="#149BC5" stroke-miterlimit="10" stroke-dasharray="2,2" x1="'.round($info->cao + $info->rong).'" y1="0" x2="'.round($info->cao + $info->rong).'" y2="'.round($info->cao).'"/>
    <path fill="none" stroke="#D8542C" stroke-miterlimit="10" d="M'.round($info->cao + $info->rong).','.round($info->cao + $info->dai).'"/>
    <line fill="none" stroke="#D8542C" stroke-miterlimit="10" x1="'.round($info->cao - $info->tai).'" y1="3" x2="'.round($info->cao).'" y2="0"/>
    <line fill="none" stroke="#D8542C" stroke-miterlimit="10" x1="'.round($info->cao + $info->rong).'" y1="'.round($info->cao).'" x2="'.round($info->cao + $info->tai + $info->rong).'" y2="'.round($info->cao - 3).'"/>
    <line fill="none" stroke="#D8542C" stroke-miterlimit="10" x1="'.round($info->cao + $info->tai + $info->rong).'" y1="3" x2="'.round($info->cao + $info->rong).'" y2="0"/>
    <line fill="none" stroke="#D8542C" stroke-miterlimit="10" x1="'.round($info->cao).'" y1="'.round($info->kt_doc).'" x2="'.round($info->cao + $info->rong).'" y2="'.round($info->kt_doc).'"/>
    <line fill="none" stroke="#149BC5" stroke-miterlimit="10" stroke-dasharray="2,2" x1="'.round($info->cao).'" y1="'.round($info->kt_doc).'" x2="'.round($info->cao).'" y2="'.round($info->cao + $info->dai).'"/>
    <line fill="none" stroke="#D8542C" stroke-miterlimit="10" x1="'.round($info->cao - $info->tai).'" y1="'.round($info->cao * 2+$info->dai - 3).'" x2="'.round($info->cao - $info->tai).'" y2="'.round($info->cao+$info->dai + 3).'"/>
    <line fill="none" stroke="#D8542C" stroke-miterlimit="10" x1="'.round($info->cao + $info->tai + $info->rong).'" y1="'.round($info->cao * 2+$info->dai - 3).'" x2="'.round($info->cao + $info->tai + $info->rong).'" y2="'.round($info->cao+$info->dai + 3).'"/>
    <line fill="none" stroke="#149BC5" stroke-miterlimit="10" stroke-dasharray="2,2" x1="'.round($info->cao + $info->rong).'" y1="'.round($info->kt_doc).'" x2="'.round($info->cao + $info->rong).'" y2="'.round($info->cao + $info->dai).'"/>
    <line fill="none" stroke="#D8542C" stroke-miterlimit="10" x1="'.round($info->cao - $info->tai).'" y1="'.round($info->cao * 2+$info->dai - 3).'" x2="'.round($info->cao).'" y2="'.round($info->kt_doc).'"/>
    <line fill="none" stroke="#D8542C" stroke-miterlimit="10" x1="'.round($info->cao + $info->rong).'" y1="'.round($info->cao + $info->dai).'" x2="'.round($info->cao + $info->tai + $info->rong).'" y2="'.round($info->cao+$info->dai + 3).'"/>
    <line fill="none" stroke="#D8542C" stroke-miterlimit="10" x1="'.round($info->cao + $info->tai + $info->rong).'" y1="'.round($info->cao * 2+$info->dai - 3).'" x2="'.round($info->cao + $info->rong).'" y2="'.round($info->kt_doc).'"/>
    <line fill="none" stroke="#D8542C" stroke-miterlimit="10" x1="'.round($info->cao).'" y1="'.round($info->cao + $info->dai).'" x2="'.round($info->cao - $info->tai).'" y2="'.round($info->cao+$info->dai + 3).'"/>
    <line fill="none" stroke="#D8542C" stroke-miterlimit="10" x1="'.round($info->cao).'" y1="'.round($info->cao).'" x2="'.round($info->cao - $info->tai).'" y2="'.round($info->cao - 3).'"/>
</svg>';
        }

        return $svg;
    }
}