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
        </style>
    </head>
    <body>
        <?php
        $day = (int) date('d');
        $month = (int) date('m');
        $str_date = date('l d M Y');
        $find = array(
            'Monday',
            'Tuesday',
            'Wednesday',
            'Thursday',
            'Friday',
            'Saturday',
            'Sunday',
        );
        $replace = array(
            'Thứ 2,',
            'Thứ 3,',
            'Thứ 4,',
            'Thứ 5,',
            'Thứ 6,',
            'Thứ 7,',
            'Chủ nhật',
        );
        $day_ = str_replace($find, $replace, $str_date);
        ?>
        <table cellpadding="0" cellspacing="0" border="0" style="background-image:url(http://quanlyin.com/temple_mail/background.jpg);background-color:#eeebe3;font-family:Arial,Helvetica,sans-serif;width:100%" width="100%">
            <tbody>
                <tr>
                    <td>
                        <table cellpadding="0" cellspacing="0" border="0" align="center">
                            <tbody>
                                <tr>
                                    <td>
                                        <table cellpadding="0" cellspacing="0" border="0" align="center">
                                            <tbody>
                                                <tr>
                                                    <td width="80" valign="top" style="text-align:center;color:white;font-weight:bold">
                                                        <img width="84" height="42" src="http://quanlyin.com/temple_mail/month/<?= $month ?>.png" style="display:block" />
                                                        <img width="84" height="65" src="http://quanlyin.com/temple_mail/day/<?= $day ?>.png" style="display:block" />
                                                    </td>
                                                    <td width="540" valign="middle">
                                                        <table cellpadding="0" cellspacing="0" border="0" valign="center">
                                                            <tbody><tr><td height="35px"><div style="margin-left:30px;color:#003366;font-weight:normal;font-family:Georgia,Times New Roman,Times,serif;font-size:30px"><?= $title ?></div></td></tr>
                                                                <tr><td height="15px"><div style="margin-left:30px;font-size:15px;color:#666666;font-family:Arial,Helvetica,sans-serif"><?= $day_ ?> — VSQI</div></td></tr>
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td valign="bottom" height="30"><img src="http://quanlyin.com/temple_mail/box-background-up.png" style="display:block" />
                                    </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>
                                        <table cellpadding="0" cellspacing="0" border="0" align="center" bgcolor="white" style="margin:0 2px;border-left:1px solid #ddd;border-right:1px solid #ddd">
                                            <tbody>
                                                <tr>
                                                    <td valign="top" width="622">
                                                        <div style="padding: 15px;"><?php echo $content; ?></div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td valign="top" height="30"><img src="http://quanlyin.com/temple_mail/box-background-down.png" style="display:block" />
                                    </td><td>
                                    </td></tr>
                                <tr>
                                    <td valign="top" height="30">
                                        <?php
                                        if (isset($signature) && $signature == 1):
                                            echo Yii::$app->settings->get('signature_send_mail');
                                        else:
                                            ?>
                                            <table cellpadding="0" cellspacing="0" border="0" width="100%">
                                                <tbody>
                                                    <tr>
                                                        <td></td>
                                                        <td width="95" style="text-align:right"><img src="http://quanlyin.com/temple_mail/htvg.png" width="200" height="50" style="padding-bottom:10px" /></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        <?php endif; ?>
                                    </td>
                                    <td></td>
                                </tr>
                                <tr></tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
    </body>
</html>