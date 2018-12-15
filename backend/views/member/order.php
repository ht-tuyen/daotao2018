<?php

use backend\helpers\AcpHelper;
use yii\helpers\Html;
use yii\widgets\DetailView;
use backend\models\Regions;
use backend\models\Countries;
use backend\models\Thanhvien;
use backend\models\BankythuatThanhvien;


$this->title = 'Danh sách đơn hàng của: ' . $model->fullname;

?>
<div class="thanhvien-view">
    <h1><?= $this->title?></h1>
    <style type="text/css">
        .view-anhhoso {
            position: absolute;
            top: 100px;
            max-width: 33%;
            right: -180px;
            max-height: 190px;
            padding: 5px;
            border: 1px solid #ccc;
        }

        .ctbkt {
            /*border: 1px solid #aaa;*/
            padding: 0px 5px;
            margin: 0 5px 0 0;
        }

        .ctbkt:hover {
            background: #eee;
        }
    </style>
    <div style="overflow: auto; max-height: 700px;">
        <table class="table table-striped table-bordered detail-view">
            <?php
            if (isset($list_order) && !empty($list_order)):
            ?>
            <tr style="font-weight: bold">
                <td>STT</td>
                <td>Đơn hàng</td>
                <td class="">Địa chỉ giao hàng</td>                
                <td class="text-right">Thành tiền (đ)</td>
                <td class="text-right">Thời gian giao hàng</td>
                <td class="text-right">Ngày tạo đơn hàng</td>
            </tr>
            <?php foreach ($list_order as $i => $order):
                $arr_data[$i]['item'] = $order->item;
                $settings = Yii::$app->settings;
                $bang_gia = $settings->get('bang_gia_tieu_chuan');

                $country = Countries::findOne($order->country_id);
                $province = Regions::getListLabel($order->province);

                if ($order->item != '') {
                    $html_name = '';
                    $thanh_tien = 0;
                    $decode = json_decode($order->item, true);
                    
                    if(is_array($decode)) foreach ($decode as $v => $k) {  
                        if(!empty($k['soluong']) && !empty($k['dongia'])) $thanh_tien +=  ($k['soluong'] * $k['dongia']);
                        $tieuchuan = \backend\models\Tieuchuan::findOne(['tc_id' => $v]);
                        $html_name .= $tieuchuan->sohieu . ', ';  
                    }
                }
                $madonhang = 'ID:'.$order->order_id; 
                ?>
                <tr>
                    <td><?= $i + 1 ?></td>
                    <td><?= $madonhang. "<br/>" . substr($html_name, 0, -2) ?></td>

                    <td><?=  $order->sonha . (!empty($province) && $province != ' - ' ? " - " . $province : "") . (!empty($country) ? " - " . $country->title : ""); ?> </td>

                    <td class="text-right"><?= number_format($thanh_tien) ?> (đ)</td>

                    <td class="text-right"><?= $order->time ?></td>
                                      
                    <td class="text-right"><?= $order->create_time ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
    <?php else: ?>
        <h2 class="text-center">Quý khách chưa hoàn thành đơn hàng nào</h2>
    <?php endif; ?>

    <div class="clearfix"><br/></div>


</div>