<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>QuanLyIn mail system</title>
        <style media="all" type="text/css">
            body{
                font-size:12px;
                font-family:Arial, Helvetica, sans-serif;
            }
            table.detail{border: 1px solid #ccc; border-collapse: collapse;}
        </style>
    </head>
    <body>
        <div class="header">
            <div style="float: left;width: 48%"><img src="http://quanlyin.com/temple_mail/htvg.png" width="300" style="padding-bottom:10px" /></div>
            <div style="float: right;width: 48%;text-align: center"><h2>Đơn đặt hàng phim</h2><br /><span>Mẫu số: 02/HTE/ĐHI</span></div>
            <div style="clear: both"></div>
        </div>
        <div style="margin-top: 25px;text-align: center">
            <p style="background: #dddddd;padding: 5px 0"><strong>Ra phim / kẽm</strong></p>
            <p style="background: #dddddd;padding: 5px 0"><strong>Số: 99/DH _ Ngày: <?= date('d/m/Y'); ?></strong></p>
        </div>
        <div style="clear: both"></div>
        <div style="margin-top: 30px">
            <div style="float: left;width: 48%">
                <p style="text-decoration: underline"><strong>Bên nhận</strong></p>
                <p>Tên đơn vị: <?= $ncc->name ?></p>
                <p>Địa chỉ: <?= $ncc->address ?></p>
                <p>Điện thoại: <?= $ncc->phone ?></p>
            </div>
            <div style="float: right;width: 48%">
                <p style="text-decoration: underline"><strong>Nội dung</strong></p>
                <p>Người thực hiện: <?= Yii::$app->user->identity->name ?></p>
                <p>Chuyển đến: <?= $ncc->contact_person ?></p>
                <p>Thời gian chuyển: <?= date('d/m/Y') ?></p>
            </div>
            <div style="clear: both"></div>
        </div>
        <table cellpadding="5" cellspacing="0" class="detail" width="100%" border="1">
            <tr style="background: yellow">
                <td>TT</td>
                <td>Loại giấy</td>
                <td>Loại phim</td>
                <td>Kích thước (cm)</td>
                <td>Đơn giá (đ)</td>
                <td>Thành tiền (đ)</td>
                <td>Sản phẩm / Khách hàng</td>
                <td>Ra phim khuôn</td>
                <td>Ghi chú</td>                
            </tr>
            <?php
            $i = 0;
            foreach ($model as $item): $i++;

                if ($item->ExportBiaRuot == 'bia') {
                    $loai_giay = 'Bìa';
                } else {
                    $stt = str_replace('ruot', '', $item->ExportBiaRuot);
                    if ($stt > 0)
                        $loai_giay = 'Ruột ' . $stt;
                    else
                        $loai_giay = 'Ruột';
                }
                if ($item->ExportAddColor == 1)
                    $ra_phim_khuon = 'Có';
                else
                    $ra_phim_khuon = 'Không';
                if ($item->ExportType == 1)
                    $loai_xuat_ra = 'Ra phim';
                else
                    $loai_xuat_ra = 'Ra kẽm';
                ?>
                <tr>
                    <td><?= $i ?></td>
                    <td><?= $loai_giay ?></td>
                    <td><?= $loai_xuat_ra ?></td>
                    <td><?= 'Dài: ' . $item->ExportLength . ' - Rộng: ' . $item->ExportWidth ?></td>
                    <td><?= number_format($item->ExportDonGia) ?></td>
                    <td><?= number_format($item->ExportPrice) ?></td>
                    <td><?= $orderinfo->product->title ?></td>
                    <td><?= $ra_phim_khuon ?></td>                
                    <td></td>                
                </tr>
            <?php endforeach; ?>
        </table>
        <p style="margin-top: 20px;width: 100%;text-decoration: dotted">Lưu ý</p>
        <div style="margin-top: 20px; width: 100%">
            <div style="float: left;width: 45%;text-align: center">Bên đặt hàng</div>
            <div style="float: right;width: 45%;text-align: center">Bên nhận hàng</div>
        </div>
    </body>
</html>