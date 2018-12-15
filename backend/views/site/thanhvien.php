<?php
use yii\helpers\Html;
use backend\helpers\AcpHelper;
use yii\helpers\ArrayHelper;
use yii\web\View;
use yii\helpers\Url;

use backend\models\Thanhvien;
use backend\models\Regions;
use backend\models\Nguoinhan;

?>
<style type="text/css">
	.tab-content {
	    min-height: 260px;
	}
</style>


<?php 
	//Tìm idnguoinhan
	$idnguoinhan = 0;	
?>



<div class="row">	
    <div class="col-md-6">
    	<div class="box box-default">
            <div class="box-header with-border bg-aqua-active">
                <h3 class="box-title">
                	Thông tin cá nhân của 
                	<?php if(empty(Yii::$app->user->identity->idthanhvien)){ 
						echo Yii::$app->user->identity->fullname;
					 }else{
					 	$tv = Thanhvien::find()
										->andWhere(['tv_id' => Yii::$app->user->identity->idthanhvien])
										->one();
						if($tv){
							echo $tv->hoten;
						}
					 } ?>
                </h3>
            </div>
            <div class="box-body">
                <div class="table-responsive">

                	<ul  class="nav nav-tabs">        					    
					    <?php if(!empty(Yii::$app->user->identity->idthanhvien)){ ?>
					    	<li class="active"><a href="#user_thongtinthanhvien" data-toggle="tab" aria-expanded="true">Thông tin thành viên BKT</a></li>
					    <?php } ?>
					    <li  class="<?= (!empty(Yii::$app->user->identity->idthanhvien))?'':'active' ?>" ><a href="#user_taikhoan" data-toggle="tab" aria-expanded="false">Tài khoản truy cập</a></li>      
					</ul>

					<div class="tab-content" style="padding: 10px;">
						<div id="user_taikhoan" class="tab-pane fade <?= (!empty(Yii::$app->user->identity->idthanhvien))?'':'in active' ?>">	
							<div id="user_taikhoan_c">
							<table class="table">
								<?php if(empty(Yii::$app->user->identity->idthanhvien)){ ?>
									<tr><td width="150px"><b>Họ tên</b></td><td><?= Yii::$app->user->identity->fullname?></td></tr>
								<?php } ?>
								<tr><td width="150px"><b>Tài khoản</b></td><td><?= Yii::$app->user->identity->username?></td></tr>
								<tr><td><b>Mật khẩu</b></td><td><a href="javascript:;" onclick="openmodal('/acp/user/change-password','2');return false;">Đổi mật khẩu</a></td></tr>
								<?php if(empty(Yii::$app->user->identity->idthanhvien)){ ?>
									<tr><td><b>Email</b></td><td><?= Yii::$app->user->identity->email?></td></tr>
									<tr><td><b>Điện thoại</b></td><td><?= Yii::$app->user->identity->mobile?></td></tr>
									<tr><td><b>Địa chỉ</b></td><td><?= Yii::$app->user->identity->address?></td></tr>
								<?php } ?>
								<tr><td><b>Quyền hạn</b></td><td><?= AcpHelper::getRoleValue(Yii::$app->user->identity->role_id)?></td></tr>
								<tr><td><b>Tham gia từ</b></td><td><?= Yii::$app->user->identity->created_at?></td></tr>
								<tr><td><b>Đăng nhập cuối</b></td><td><?= Yii::$app->user->identity->updated_at?></td></tr>
								<!-- <tr><td>idthanhvien</td><td><?php //echo Yii::$app->user->identity->idthanhvien?></td></tr> -->
							</table>
							</div>						
							<?php if(empty(Yii::$app->user->identity->idthanhvien)){ ?>
								<span class="btn btn-default" onclick="openmodal('/acp/user/change-info','2');return false;">Chỉnh sửa</span>
							<?php } ?>
						</div>





						<?php if(!empty(Yii::$app->user->identity->idthanhvien)){ ?>
						<div id="user_thongtinthanhvien" class="tab-pane fade  in  active">							
							<?php 								
								if($tv){
							?>
								<div id="user_thongtinthanhvien_c">
			                    <table class="table">
									<tr><td width="120px"><b>Họ tên</b></td><td><?= $tv->hoten?></td></tr>
									<tr><td><b>Ngày sinh</b></td><td>
										<div class="pull-left" style="width: 150px; height: 10px;"><?= $tv->ngaysinh?></div>
										<div class="col-md-3"><b>Giới tính</b></div><div><?= Thanhvien::getGioitinhLabel($tv->gioitinh)?></div>
									</td></tr>


									<tr><td><b>ĐT cố định</b></td><td>
										<div class="pull-left" style="width: 150px; height: 10px;"><?= $tv->dienthoaicodinh?></div>
										<div class="col-md-3"><b>ĐT di động</b></div><div><?= $tv->dienthoaididong?></div>
									</td></tr>

									
									<tr><td><b>Số CMND</b></td><td><?= $tv->socmnd?></td></tr>


									<tr><td><b>Ngày cấp</b></td><td>
										<div class="pull-left" style="width: 150px; height: 10px;"><?= $tv->ngaycap?></div>
										<div class="col-md-3"><b>Nơi cấp</b></div><div><?= Regions::getListLabel($tv->noicap)?></div>
									</td></tr>

									<tr><td><b>Địa chỉ</b></td><td><?= $tv->diachi?></td></tr>
									<tr><td><b>Email</b></td><td><?= $tv->email?></td></tr>

									<tr><td><b>Học hàm</b></td><td>
										<div class="pull-left" style="width: 150px; height: 10px;"><?= $tv->hocham?></div>
										<div class="col-md-3"><b>Học vị</b></div><div><?= $tv->hocvi?></div>
									</td></tr>


									<tr><td><b>Cơ quan</b></td><td><?= $tv->coquan?></td></tr>
									<tr><td><b>Chuyên ngành</b></td><td><?= $tv->chuyennganh?></td></tr>
									<tr><td><b>Chức vụ</b></td><td><?= $tv->chucvu?></td></tr>
									<tr><td><b>Địa chỉ cơ quan</b></td><td><?= $tv->diachicoquan?></td></tr>
									<tr><td><b>Quá trình công tác</b></td><td><?= '...'?></td></tr>									
								</table>
								</div>

								<span class="btn btn-default" onclick="openmodal('/acp/thanhvien/updatem','13');return false;">Chỉnh sửa</span>
							<?php } ?>
						</div>
						<?php } ?>
					</div>                	
                </div>
            </div>
        </div>

        <?php if(!empty(Yii::$app->user->identity->idthanhvien)){ ?>
        <div class="box box-default">
            <div class="box-header with-border bg-aqua-active">
                <h3 class="box-title"><?= Yii::$app->user->identity->fullname?> là Thành viên các BKT:</h3>
            </div>
            <div class="box-body">
                <div class="table-responsive">
						 <?php		                       		                        	 
				            echo Yii::$app->controller->renderPartial('../site/bkt',[
				                'idthanhvien' => Yii::$app->user->identity->idthanhvien,
				            ]);							        		
                        ?>	
                </div>
            </div>
        </div>
        <?php } ?>
    </div>

    <div class="col-md-6">
        <?php if(AcpHelper::check_role('viewm','Duan')){ ?>
        <div class="box box-default">
            <div class="box-header with-border bg-aqua-active">
                <h3 class="box-title">Lịch họp</h3>
            </div>
            <div class="box-body">
                <div class="table-responsive">

                	<ul  class="nav nav-tabs">        
					    <li class="active"><a href="#da_saphop" data-toggle="tab" aria-expanded="false">Sắp tới</a></li>      
					    <li><a href="#da_dahop" data-toggle="tab" aria-expanded="true">Đã họp</a></li>					          
					</ul>

					<div class="tab-content">
						<div id="da_saphop" class="tab-pane fade in  active">	
		                        <?php		                        		                        	 
						            if($idnguoinhan > 0) echo Yii::$app->controller->renderPartial('../site/lichhop',[
						                'idnguoinhan' => $idnguoinhan,
						                'tinhtrang' => 'saphop',
						            ]);							           
					           										        		
		                        ?>		                  
						</div>

						<div id="da_dahop" class="tab-pane fade">							
		                    <?php		                        		                        	 
					            if($idnguoinhan > 0) echo Yii::$app->controller->renderPartial('../site/lichhop',[
					                'idnguoinhan' => $idnguoinhan,
					                'tinhtrang' => 'dahop',
					            ]);							        		
	                        ?>	
						</div>
					</div>


                </div>
            </div>
        </div>
        <?php } ?>        
    </div>
  

</div>





<div class="row">
   	<div class="col-md-12">        
        <?php if(AcpHelper::check_role('viewm','Duan') || AcpHelper::check_role('traloigopy','Duan')){ ?>
        <div class="box box-default">
            <div class="box-header with-border bg-aqua-active">
                <h3 class="box-title">Dự thảo tiêu chuẩn Việt Nam</h3>                
            </div>
            <div class="box-body">
                <div class="table-responsive">

                	<ul  class="nav nav-tabs">        
					    <li class="active"><a href="#da_yeucaugopy" data-toggle="tab" aria-expanded="false">Chưa góp ý</a></li>      
					    <li><a href="#da_dagopy" data-toggle="tab" aria-expanded="true">Đã góp ý</a></li>					          
					</ul>

					

					<div class="tab-content">
						<div id="da_yeucaugopy" class="tab-pane fade in  active">							
		                     <?php		                        		                        	 
					            if($idnguoinhan > 0) echo Yii::$app->controller->renderPartial('../site/gopy',[
					                'idnguoinhan' => $idnguoinhan,
					                'type' => 'chuagopy',
					            ]);							        		
	                        ?>	

						</div>

						<div id="da_dagopy" class="tab-pane fade">							
		                    <?php		                        		                        	 
					            if($idnguoinhan > 0) echo Yii::$app->controller->renderPartial('../site/gopy',[
					                'idnguoinhan' => $idnguoinhan,
					                'type' => 'dagopy',
					            ]);							        		
	                        ?>	
						</div>
					</div>
                    
                </div>
            </div>
        </div>
        <?php } ?>        
    </div>
</div>



<div class="row">
   	<div class="col-md-12">        
        <?php if(AcpHelper::check_role('indexm','Tieuchuanquocte')){ ?>
        <div class="box box-default">
            <div class="box-header with-border bg-aqua-active">
                <h3 class="box-title">Góp ý Dự thảo tiêu chuẩn quốc tế</h3>                
            </div>
            <div class="box-body">
                <div class="table-responsive">

                	<ul  class="nav nav-tabs">        
					    <li class="active"><a href="#tcqt_yeucaugopy" data-toggle="tab" aria-expanded="false">Chưa góp ý</a></li>      
					    <li><a href="#tcqt_dagopy" data-toggle="tab" aria-expanded="true">Đã góp ý</a></li>					          
					</ul>

					<div class="tab-content">
						<div id="tcqt_yeucaugopy" class="tab-pane fade in  active">							
		                     <?php		               		                     	
					            echo Yii::$app->controller->renderPartial('../site/gopy_tcqt',[
					                'idnguoinhan' => 46, //VD
					                'type' => 'chuagopy',
					            ]);							        		
	                        ?>	

						</div>

						<div id="tcqt_dagopy" class="tab-pane fade">							
		                    <?php		                        		                        	 
					            echo Yii::$app->controller->renderPartial('../site/gopy_tcqt',[
					                'idnguoinhan' => 46, //VD
					                'type' => 'dagopy',
					            ]);							        		
	                        ?>	
						</div>
					</div>
                    
                </div>
            </div>
        </div>
        <?php } ?>        
    </div>
</div>
