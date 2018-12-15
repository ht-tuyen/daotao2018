<?php
use yii\helpers\Url;

$thumuc = Yii::getAlias('@webroot');
$thumuc = str_replace('/frontend/web','/backend/web',$thumuc);

$extension = pathinfo($thumuc  .'/' . $src, PATHINFO_EXTENSION);
$arr_ext = ['pdf', 'docx', 'xlsx', 'xls', 'doc'];
if ($extension == 'pdf'):
    $url = $thumuc  .'/' . $src;

// echo $url;die;

    $content = file_get_contents($url);

    header('Content-Type: application/pdf');
    header('Content-Length: ' . strlen($content));
    header('Content-Disposition: inline; filename="YourFileName.pdf"');
    header("Cache-Control: private, must-revalidate, post-check=0, pre-check=0, public");
    header('Pragma: public');
    header('Content-Transfer-Encoding: binary');
header("Accept-Ranges: bytes");
    




    ini_set('zlib.output_compression', '0');
    echo $content;
    die($content);
else: ?>
    <style>
        body {
            margin: 0px;
            background: #0e0e0e;
            position: relative;
        }

        iframe {
            width: 100%;
            height: 100%;
        }

        img {
            position: absolute;
            left: 0;
            right: 0;
            bottom: 0;
            top: 0;
            margin: 0 auto;
            margin-top: 15%;
        }

        }
    </style>
    <?php Header('Location: '.Yii::$app->request->hostInfo . '/acp/' . $src) ?>
    <img style="text-align: center;"
         src="<?= Yii::$app->request->hostInfo . '/acp/' . $src ?>">
<?php endif; ?>
