<?php
use backend\helpers\AcpHelper;
use backend\models\Node;
use dmstr\widgets\Menu;
?>
<aside class="main-sidebar">

    <section class="sidebar">
        <?php if(!empty(Yii::$app->user->identity->user_id)){ ?>
        
        <?php
        $menuArray = Node::getMenuArray();
        echo Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu'],
                'items' => $menuArray,
            ]
        ) ?>
        <?php } ?>
    </section>

</aside>
