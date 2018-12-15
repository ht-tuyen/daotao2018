<?php
use backend\models\Role;

$roleAcl = Role::getOneAclArray(Yii::$app->user->identity->role_id);
$role = Role::findOne(Yii::$app->user->identity->role_id);

?>
<div class="col-md-3 col-sm-6 col-xs-12">
    <div class="info-box">
        <span class="info-box-icon bg-aqua"><i class="ion ion-ios-stopwatch"></i></span>
        <div class="info-box-content">
            <?php
            // if(!empty($roleAcl['Kehoachnam'])){
            // if($roleAcl == 'ALL_PRIVILEGES' || in_array('create', $roleAcl['Kehoachnam'])){
            ?>
            <a class="text-green" href="javascript:;" onclick="openkehoach('/acp/kehoachnam/index-updatem');return false;"><i class="ion ion-plus-circled"></i> Thêm kế hoạch</a><br/>
            <?php //}} ?>
            <a class="backkehoach" href="javascript:;"><i class="ion ion-plus-circled"></i> Danh sách kế hoạch</a><br/>
            <?php
            // if(!empty($roleAcl['Kehoachnam'])){
            // if($roleAcl == 'ALL_PRIVILEGES' || in_array('create', $roleAcl['Kehoachnam'])){
            ?>
            <a class="text-green" href="javascript:;" onclick="openmodal('/acp/kehoachnam/import','2');return false;"><i class="ion ion-plus-circled"></i> Import kế hoạch</a><br>
            <?php //}} ?>
        </div>
    </div>    
</div>

<div class="col-md-3 col-sm-6 col-xs-12">
    <div class="info-box">
        <span class="info-box-icon bg-aqua"><i class="ion ion-checkmark-circled"></i></span>
        <div class="info-box-content">
            <?php
            if($roleAcl == 'ALL_PRIVILEGES' || in_array('create', $roleAcl['Duan'])){
            ?>
            <a class="text-green" href="javascript:;" onclick="openmodal('/acp/duan/createm');return false;"><i class="ion ion-plus-circled"></i> Tạo dự án</a><br/>
            <?php } ?>
            <a class="backduan" href="javascript:;"><i class="ion ion-plus-circled"></i> Danh sách dự án</a>
            
        </div>        
    </div>    
</div>

<div class="col-md-3 col-sm-6 col-xs-12">
    <div class="info-box">
        <span class="info-box-icon bg-aqua"><i class="ion ion-podium"></i></span>

        <div class="info-box-content">
            Báo cáo
        </div>        
    </div>    
</div>

<div class="col-md-3 col-sm-6 col-xs-12">
    <div class="info-box">
        <span class="info-box-icon bg-aqua"><i class="ion ion-search"></i></span>

        <div class="info-box-content">
            <a class="backsearch" href="javascript:;"><i class="ion ion-search"></i>Tìm kiếm tiêu chuẩn</a>            
        </div>        
    </div>    
</div>