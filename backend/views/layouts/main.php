<?php

use yii\helpers\Html;
use backend\assets\CustomAsset;
use common\assets\CommonAsset;

use yii\helpers\Url;
use yii\bootstrap\Modal; /*Them*/
/* @var $this \yii\web\View */
/* @var $content string */


if (Yii::$app->controller->action->id === 'login') {
    /**
     * Do not use this code in your template. Remove it.
     * Instead, use the code  $this->layout = '//main-login'; in your controller.
     */
    echo $this->render(
        'main-login',
        ['content' => $content]
    );
} else {
    //common\assets\CommonAsset::register($this);
    if (class_exists('backend\assets\AppAsset')) {
        backend\assets\AppAsset::register($this);
    } else {
        app\assets\AppAsset::register($this);
    }
    
    backend\assets\BackendAsset::register($this);
    backend\assets\CustomAsset::register($this);
    


    $directoryAsset = Yii::$app->assetManager->getPublishedUrl('@vendor/almasaeed2010/adminlte/dist');
    $asset = backend\assets\AppAsset::register($this);
    $baseUrl = $asset->baseUrl;

    //$bundle = CustomAsset::register(Yii::$app->view);
    //$this->registerCssFile($bundle->baseUrl . '/css/jquery.fancybox.css', ['depends' => [backend\assets\CustomAsset::className()]]);
    //$this->registerJsFile($bundle->baseUrl . '/js/jquery.fancybox.js', ['depends' => [backend\assets\CustomAsset::className()]]);

    ?>
    <?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">
    <head> 
        <meta charset="<?= Yii::$app->charset ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>

        <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet">
 
        <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script src="https://cdn.quilljs.com/1.3.4/quill.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue-quill-editor@3.0.4/dist/vue-quill-editor.js"></script>
   
<link href="https://cdn.quilljs.com/1.3.4/quill.core.css" rel="stylesheet">
<link href="https://cdn.quilljs.com/1.3.4/quill.snow.css" rel="stylesheet">
<link href="https://cdn.quilljs.com/1.3.4/quill.bubble.css" rel="stylesheet"> 
<script src="https://unpkg.com/vue-select@latest"></script>
   
    
    </head>
    <?php
        // if($_GET['test'] == 'vsqi'){
            echo '<body class="fixed skin-blue sidebar-mini ">';
        // }else{
            // echo '<body class="fixed skin-blue sidebar-mini sidebar-collapse">';
        // }
    ?>
    
    <?php $this->beginBody() ?>
    <?php $this->endBody() ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <div class="wrapper">

        <?= $this->render(
            'header.php',
            ['directoryAsset' => $directoryAsset]
        ) ?>

        <?= $this->render(
            'left.php',
            ['directoryAsset' => $directoryAsset]
        )
        ?>

        <?= $this->render(
            'content.php',
            ['content' => $content, 'directoryAsset' => $directoryAsset]
        ) ?>

    </div>

    <?php
    if (Yii::$app->settings->get('cach_tinh_gia') == '' && Yii::$app->controller->id != 'settings'):
        ?>
        <div id="div_show_notice_tinh_gia">
            <h3 class="btn-facebook">Thông báo</h3>
            <p>Quý khách vui lòng thiết lập Tính giá đơn hàng theo Định mức hoặc theo Thương mại trước khi sử dụng phần
                mềm</p>
            <p style="color: red">Sau khi thiết lập, quý khách sẽ không thể thay đổi lựa chọn này.</p>
            <p>Xin chân thành cám ơn!</p>
            <a class="btn btn-success" href="<?= Url::toRoute('/settings/index') . '#tab2default' ?>">Thiết lập</a>
        </div>

        <?php
        $js = <<< XP
        $(document).ready(function () {
        $.fancybox.open({
            src  : '#div_show_notice_tinh_gia',
            type : 'inline',
            width : '100%',
            opts : {
                closeClickOutside : false,
                clickOutside: '',
                clickSlide:'',
                beforeClose : function () {
                    return false;
                }
            }

        });
            
            
        });
        
XP;
        $this->registerJs($js);
        $css = <<<XP
        #div_show_notice_tinh_gia{
            display: inline-block;
            padding: 0 0 20px 0;
            width: 500px;
        }
        #div_show_notice_tinh_gia p{
            font-size: 13px;
            font-weight: bold;
        }
        #div_show_notice_tinh_gia p,  #div_show_notice_tinh_gia a{
            margin-top: 10px;
            margin-left: 20px;
        }
        #div_show_notice_tinh_gia h3{
            margin: 0;
            height: 41px;
            padding-left: 10px;
            line-height: 41px;
        }
        .fancybox-close-small{display:none !important}
        
XP;
        $this->registerCss($css);
    endif; ?>
    
    <div class="loading-indicator-wrapper loader-hidden"><span class="loading-indicator-helper"></span>
        <div class="loader"></div>
    </div>

    <?php
    Modal::begin([
            'options' => [                
                'tabindex' => false
            ],
            'header'=>'',
            'id'=>'modal',
            'size'=>'modal-lg',
            'clientOptions' => ['backdrop' => 'static', 'keyboard' => false]
        ]);
    echo "<div id='modalContent' class='clcontent'></div>";
    echo "<div class='clearfix'></div>";
    Modal::end();
?>


    <?php
    Modal::begin([
            'options' => [                
                'tabindex' => false
            ],
            'header'=>'',
            'id'=>'modal12',
            'size'=>'modal-lg',
            'clientOptions' => ['backdrop' => 'static', 'keyboard' => false]
        ]);
    echo "<div id='modalContent12' class='clcontent'></div>";
    echo "<div class='clearfix'></div>";
    Modal::end();
?>


        <?php
    Modal::begin([
            'options' => [                
                'tabindex' => false
            ],
            'header'=>'',
            'id'=>'modal13',
            'size'=>'modal-lg',
            'clientOptions' => ['backdrop' => 'static', 'keyboard' => false]
        ]);
    echo "<div id='modalContent13' class='clcontent'></div>";
    echo "<div class='clearfix'></div>";
    Modal::end();
?>

    <?php
    Modal::begin([
            'options' => [                
                'tabindex' => false
            ],
            'header'=>'',
            'id'=>'modal2',
            'size'=>'modal-md',
            'clientOptions' => ['backdrop' => 'static', 'keyboard' => false]
        ]);
    echo "<div id='modalContent2' class='clcontent'></div>";
    echo "<div class='clearfix'></div>";
    Modal::end();
?>


<div class="popupalert"></div>

   
   
    </body>
    </html>
    <?php $this->endPage() ?>
<?php } ?>
