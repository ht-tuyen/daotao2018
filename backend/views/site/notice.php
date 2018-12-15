<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

$this->title = '';
?>
<section class="content">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="error-page" style="width: 100%">
                <h2 class="headline text-info" style="margin-top: 5px;"><i class="fa fa-warning text-red"></i></h2>

                <div class="error-content">

                    <h2 class="text-danger">
                        <?= nl2br(Html::encode($messages)) ?>
                    </h2>

                    <h4 style="line-height: 24px; margin-top: 25px" class="text-blue">
                        Vui lòng liên hệ với quản trị phần mềm hoặc người quản lý trực tiếp để giải đáp.
                        <br/>
                        Nhấn <a href='<?= Yii::$app->homeUrl ?>' class="text-danger">vào đây</a> để quay về trang chủ
                    </h4>


                </div>
            </div>
        </div>
    </div>
</section>
