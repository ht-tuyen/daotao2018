<?php
use yii\helpers\Html;
use backend\helpers\AcpHelper;

/* @var $this \yii\web\View */
/* @var $content string */
//<p style="width: 115px;float: right;font-size: 16px;line-height: 22px;padding: 5px 0 0 0;">Viện tiêu chuẩn VIỆT NAM</p>
?>
<header class="main-header">

    <?php echo Html::a('<span class="logo-mini">'.Html::img('@web/images/vsqi.png', ['class' => 'img-responsive', 'style'=> 'max-height: 40px;']).'</span><div class="logo-lg">' .Html::img('@web/images/vsqi.png', ['class' => 'img-responsive', 'style'=> 'max-height: 40px; display:inline-block;']) . '</div>', Yii::$app->homeUrl, ['class' => 'logo'])
   ?>

    <nav class="navbar navbar-fixed-top" role="navigation">

        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>

         <section class="content-header">
        <?php if (isset($this->blocks['content-header'])) { ?>
            <h1><?= $this->blocks['content-header'] ?></h1>
        <?php } else { ?>
            <h1>
                <?php
                if ($this->title !== null) {
                    echo \yii\helpers\Html::encode($this->title);
                } else {
                    echo \yii\helpers\Inflector::camel2words(
                        \yii\helpers\Inflector::id2camel($this->context->module->id)
                    );
                    echo ($this->context->module->id !== \Yii::$app->id) ? '<small>Module</small>' : '';
                } ?>
            </h1>
        <?php } ?>

        <?php
        // Breadcrumbs::widget(
        //     [
        //         'homeLink' => [
        //             'label' => 'Trang chủ',
        //             'url' => Yii::$app->homeUrl,
        //         ],
        //         'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        //     ]
        // ) ?>
    </section>


        <div class="navbar-custom-menu">
          
            <ul class="nav navbar-nav">
                <!-- <li>
                    <a href="<?php //\yii\helpers\Url::toRoute('orders/create')?>" class="create-new-order"><i class="fa fa-location-arrow" aria-hidden="true"></i> Tạo đơn hàng mới</a>
                </li> -->
                <li class="dropdown notifications-menu" style="display: none">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-bell-o"></i>
                        <span class="label label-warning">10</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="header">You have 10 notifications</li>
                        <li>
                            <!-- inner menu: contains the actual data -->
                            <ul class="menu">
                                <li>
                                    <a href="#">
                                        <i class="fa fa-users text-aqua"></i> 5 new members joined today
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <i class="fa fa-warning text-yellow"></i> Very long description here that may
                                        not fit into the page and may cause design problems
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <i class="fa fa-users text-red"></i> 5 new members joined
                                    </a>
                                </li>

                                <li>
                                    <a href="#">
                                        <i class="fa fa-shopping-cart text-green"></i> 25 sales made
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <i class="fa fa-user text-red"></i> You changed your username
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="footer"><a href="#">View all</a></li>
                    </ul>
                </li>

                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <img src="<?= AcpHelper::get_gravatar(Yii::$app->user->identity->email, null, 25) ?>" class="user-image" alt="<?=Yii::$app->user->identity->fullname?>"/>
                        <span class="hidden-xs"><?=Yii::$app->user->identity->fullname?></span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
                        <li class="user-header">
                            <img src="<?= AcpHelper::get_gravatar(Yii::$app->user->identity->email, null, 160) ?>" class="img-circle"
                                 alt="<?=Yii::$app->user->identity->fullname?>"/>
                            <?php if(!empty(Yii::$app->user->identity->user_id)){ ?>
                            <p>
                                <?= Yii::$app->user->identity->fullname?> - <?=AcpHelper::getRoleValue(Yii::$app->user->identity->role_id)?>
                                <small><?=!empty(Yii::$app->user->identity->created_at) ? 'Thành viên từ '.date('d-m-Y', strtotime(Yii::$app->user->identity->created_at)) : ''?></small>
                            </p>
                            <?php } ?>
                        </li>
                        <!-- Menu Footer-->
                        <li class="user-footer">      
                            <?php if(empty(Yii::$app->user->identity->idthanhvien)){ ?>
                                <span class="btn btn-default" onclick="openmodal('/acp/user/change-info','2');return false;">Chỉnh sửa thông tin</span>
                            <?php }else{ ?>
                                <span class="btn btn-default" onclick="openmodal('/acp/thanhvien/updatem','13');return false;">Chỉnh sửa thông tin</span>
                            <?php } ?>

                            <div class="pull-right">
                                <?php if(!empty(Yii::$app->user->identity->user_id)){ ?>
                                <?= Html::a(
                                    'Đăng xuất',
                                    ['/site/logout'],
                                    ['data-method' => 'post', 'class' => 'btn btn-default btn-flat']
                                ) ?>
                                <?php } ?>
                            </div>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>
