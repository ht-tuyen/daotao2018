<?php
use yii\helpers\Html;
use backend\models\Content;
use backend\assets\CustomAsset;
use yii\web\View;

$settings = Yii::$app->settings;
Yii::$app->settings->clearCache();

$bundle = CustomAsset::register(Yii::$app->view, View::POS_END);
$this->registerJsFile($bundle->baseUrl . '/js/cleave.min.js', ['depends' => [backend\assets\CustomAsset::className()]]);
?>
<div class="form-group">
    <label class="control-label col-md-3">
        Xén giấy
    </label>
    <div class="col-md-9">
        <?php echo Html::textInput("Settings[btnc_xen_giay]", $settings->get('btnc_xen_giay'), ['class' => 'form-control numberOnly']); ?>
    </div>
    <div class="clearfix"></div>
</div>
<div class="form-group">
    <label class="control-label col-md-3">
        Kiểm tờ in
    </label>
    <div class="col-md-9">
        <table border="0" cellpadding="0" cellspacing="0" class="adminlist">
            <?php
            $btnc_kiem_to_in = $settings->get('btnc_kiem_to_in');
            ?>
            <tr>
                <td><strong>Giấy chung</strong></td>
                <td colspan="2">
                    <?php echo Html::textInput("Settings[btnc_kiem_to_in][gia_chung]", !empty($btnc_kiem_to_in) ? $btnc_kiem_to_in['gia_chung'] : null, ['class' => 'form-control numberOnly']); ?>
                </td>
                <td width="50"><span class="btn btn-sq-xs btn-primary kiemtoin-add-line"><i class="fa fa-plus fa-1x"></i></span></td>
            </tr>
            <?php
            if (!empty($btnc_kiem_to_in)) {
                foreach ($btnc_kiem_to_in as $k => $v){
                    if(!isset($v['chat_lieu']))
                        continue;
                    ?>
                    <tr class="kiemtoin-line-input">
                        <td>
                            <?php// echo Html::dropDownList('Settings[btnc_kiem_to_in][' . $k . '][chat_lieu]', $v['chat_lieu'], Content::getListOptionsByType(Content::TYPE_CHAT_LIEU_GIAY), ['prompt' => 'Chọn chất liệu', 'class' => 'form-control']) ?>
                        </td>
                        <td>
                            <?php// echo Html::dropDownList('Settings[btnc_kiem_to_in][' . $k . '][dinh_luong]', $v['dinh_luong'], Content::getListOptionsByType(Content::TYPE_DINH_LUONG_GIAY), ['prompt' => 'Chọn định lượng', 'class' => 'form-control']) ?>
                        </td>
                        <td>
                            <?php// echo Html::textInput("Settings[btnc_kiem_to_in][{$k}][don_gia]", $v['don_gia'], ['class' => 'form-control numberOnly']); ?>
                        </td>
                        <td><span class="kiemtoin-remove-line btn btn-sq-xs btn-danger"><i class="fa fa-minus fa-1x"></i></span></td>
                    </tr>
                    <?php
                }
            } else {
                ?>
                <tr class="kiemtoin-line-input">
                    <td>
                        <?php echo Html::dropDownList('Settings[btnc_kiem_to_in][0][chat_lieu]', '', Content::getListOptionsByType(Content::TYPE_CHAT_LIEU_GIAY), ['prompt' => 'Chọn chất liệu', 'class' => 'form-control']) ?>
                    </td>
                    <td>
                        <?php echo Html::dropDownList('Settings[btnc_kiem_to_in][0][dinh_luong]', '', Content::getListOptionsByType(Content::TYPE_DINH_LUONG_GIAY), ['prompt' => 'Chọn định lượng', 'class' => 'form-control']) ?>
                    </td>
                    <td>
                        <?php echo Html::textInput("Settings[btnc_kiem_to_in][0][don_gia]", null, ['class' => 'form-control numberOnly']); ?>
                    </td>
                    <td><span class="kiemtoin-remove-line btn btn-sq-xs btn-danger"><i class="fa fa-minus fa-1x"></i></span></td>
                </tr>
                <?php
            }
            ?>
        </table>
    </div>
    <div class="clearfix"></div>
</div>
<div class="form-group">
    <label class="control-label col-md-3">
        Gấp
    </label>
    <div class="col-md-9">
        <?php
        $btnc_gap = $settings->get('btnc_gap');
        ?>
        <table border="0" cellpadding="0" cellspacing="0" class="adminlist">
            <tr>
                <th colspan="2">Danh mục</th>
                <th>Đơn giá</th>
                <th width="50"><span class="btn btn-sq-xs btn-primary button-add-line"><i class="fa fa-plus fa-1x"></i></span></th>
            </tr>
            <?php
            if(!empty($btnc_gap) && is_array($btnc_gap)){
                foreach ($btnc_gap as $k => $v) {
                    ?>
                    <tr class="tr-line-input">
                        <td>
                            <?php echo Html::textInput("Settings[btnc_gap][$k][vach]", $v['vach'], ['class' => 'form-control']); ?>
                        </td>
                        <td>vạch</td>
                        <td>
                            <?php echo Html::textInput("Settings[btnc_gap][$k][don_gia]", $v['don_gia'], ['class' => 'form-control numberOnly']); ?>
                        </td>
                        <td><span class="button-remove-line btn btn-sq-xs btn-danger"><i class="fa fa-minus fa-1x"></i></span></td>
                    </tr>
                    <?php
                }
            }else{
                ?>
                <tr class="tr-line-input">
                    <td>
                        <?php echo Html::textInput("Settings[btnc_gap][0][vach]", null, ['class' => 'form-control']); ?>
                    </td>
                    <td>vạch</td>
                    <td>
                        <?php echo Html::textInput("Settings[btnc_gap][0][don_gia]", null, ['class' => 'form-control numberOnly']); ?>
                    </td>
                    <td><span class="button-remove-line btn btn-sq-xs btn-danger"><i class="fa fa-minus fa-1x"></i></span></td>
                </tr>
            <?php }?>
        </table>
    </div>
    <div class="clearfix"></div>
</div>
<div class="form-group">
    <label class="control-label col-md-3">
        Bắt/soạn
    </label>
    <div class="col-md-9">
        <?php
        $btnc_bat_soan = $settings->get('btnc_bat_soan');
        ?>
        <table border="0" cellpadding="0" cellspacing="0" class="adminlist">
            <tr>
                <th>Danh mục</th>
                <th>Đơn giá</th>
                <th width="50"><span class="btn btn-sq-xs btn-primary button-add-line"><i class="fa fa-plus fa-1x"></i></span></th>
            </tr>
            <?php
            if(!empty($btnc_bat_soan) && is_array($btnc_bat_soan)){
                foreach ($btnc_bat_soan as $k => $v) {
                    ?>
                    <tr class="tr-line-input">
                        <td>
                            <?php echo Html::textInput("Settings[btnc_bat_soan][$k][loai]", $v['loai'], ['class' => 'form-control']); ?>
                        </td>
                        <td>
                            <?php echo Html::textInput("Settings[btnc_bat_soan][$k][don_gia]", $v['don_gia'], ['class' => 'form-control numberOnly']); ?>
                        </td>
                        <td><span class="button-remove-line btn btn-sq-xs btn-danger"><i class="fa fa-minus fa-1x"></i></span></td>
                    </tr>
                    <?php
                }
            }else{
                ?>
                <tr class="tr-line-input">
                    <td>
                        <?php echo Html::textInput("Settings[btnc_bat_soan][0][loai]", null, ['class' => 'form-control']); ?>
                    </td>
                    <td>
                        <?php echo Html::textInput("Settings[btnc_bat_soan][0][don_gia]", null, ['class' => 'form-control numberOnly']); ?>
                    </td>
                    <td><span class="btn btn-sq-xs btn-danger button-remove-line"><i class="fa fa-minus fa-1x"></i></span></td>
                </tr>
                <?php
            }
            ?>
        </table>
    </div>
    <div class="clearfix"></div>
</div>
<div class="form-group">
    <label class="control-label col-md-3">
        Chập
    </label>
    <div class="col-md-9">
        <?php
        $btnc_chap = $settings->get('btnc_chap');
        ?>
        <table border="0" cellpadding="0" cellspacing="0" class="adminlist">
            <tr>
                <th>Danh mục</th>
                <th>Đơn giá</th>
                <th width="50"><span class="btn btn-sq-xs btn-primary button-add-line"><i class="fa fa-plus fa-1x"></i></span></th>
            </tr>
            <?php
            if(!empty($btnc_chap) && is_array($btnc_chap)){
                foreach ($btnc_chap as $k => $v) {
                    ?>
                    <tr class="tr-line-input">
                        <td>
                            <?php echo Html::textInput("Settings[btnc_chap][$k][loai]", $v['loai'], ['class' => 'form-control']); ?>
                        </td>
                        <td>
                            <?php echo Html::textInput("Settings[btnc_chap][$k][don_gia]", $v['don_gia'], ['class' => 'form-control numberOnly']); ?>
                        </td>
                        <td><span class="btn btn-sq-xs btn-danger button-remove-line"><i class="fa fa-minus fa-1x"></i></span></td>
                    </tr>
                    <?php
                }
            }else{
                ?>
                <tr class="tr-line-input">
                    <td>
                        <?php echo Html::textInput("Settings[btnc_chap][0][loai]", null, ['class' => 'form-control']); ?>
                    </td>
                    <td>
                        <?php echo Html::textInput("Settings[btnc_chap][0][don_gia]", null, ['class' => 'form-control numberOnly']); ?>
                    </td>
                    <td><span class="btn btn-sq-xs btn-danger button-remove-line"><i class="fa fa-minus fa-1x"></i></span></td>
                </tr>
                <?php
            }
            ?>
        </table>
    </div>
    <div class="clearfix"></div>
</div>
<div class="form-group">
    <label class="control-label col-md-3">
        Lồng sách
    </label>
    <div class="col-md-9">
        <?php echo Html::textInput("Settings[btnc_long_sach]", $settings->get('btnc_long_sach'), ['class' => 'form-control numberOnly']); ?>
    </div>
    <div class="clearfix"></div>
</div>
<div class="form-group">
    <label class="control-label col-md-3">
        Đóng ghim
    </label>
    <div class="col-md-9">
        <?php echo Html::textInput("Settings[btnc_dong_ghim]", $settings->get('btnc_dong_ghim'), ['class' => 'form-control numberOnly']); ?>
    </div>
    <div class="clearfix"></div>
</div>
<div class="form-group">
    <label class="control-label col-md-3">
        Khâu chỉ
    </label>
    <div class="col-md-9">
        <?php echo Html::textInput("Settings[btnc_khau_chi]", $settings->get('btnc_khau_chi'), ['class' => 'form-control numberOnly']); ?>
    </div>
    <div class="clearfix"></div>
</div>
<div class="form-group">
    <label class="control-label col-md-3">
        Vào bìa
    </label>
    <div class="col-md-9">
        <?php
        $btnc_vao_bia = $settings->get('btnc_vao_bia');
        ?>
        <table border="0" cellpadding="0" cellspacing="0" class="adminlist">
            <tr>
                <th>Danh mục</th>
                <th>Đơn giá</th>
                <th width="50"><span class="btn btn-sq-xs btn-primary button-add-line"><i class="fa fa-plus fa-1x"></i></span></th>
            </tr>
            <?php
            if(!empty($btnc_vao_bia) && is_array($btnc_vao_bia)){
                foreach ($btnc_vao_bia as $k => $v) {
                    ?>
                    <tr class="tr-line-input">
                        <td>
                            <?php echo Html::textInput("Settings[btnc_vao_bia][$k][loai]", $v['loai'], ['class' => 'form-control']); ?>
                        </td>
                        <td>
                            <?php echo Html::textInput("Settings[btnc_vao_bia][$k][don_gia]", $v['don_gia'], ['class' => 'form-control numberOnly']); ?>
                        </td>
                        <td><span class="btn btn-sq-xs btn-danger button-remove-line"><i class="fa fa-minus fa-1x"></i></span></td>
                    </tr>
                    <?php
                }
            }else {
                ?>
                <tr class="tr-line-input">
                    <td>
                        <?php echo Html::textInput("Settings[btnc_vao_bia][0][loai]", null, ['class' => 'form-control']); ?>
                    </td>
                    <td>
                        <?php echo Html::textInput("Settings[btnc_vao_bia][0][don_gia]", null, ['class' => 'form-control numberOnly']); ?>
                    </td>
                    <td><span class="btn btn-sq-xs btn-danger button-remove-line"><i class="fa fa-minus fa-1x"></i></span></td>
                </tr>
                <?php
            }
            ?>
        </table>
    </div>
    <div class="clearfix"></div>
</div>
<div class="form-group">
    <label class="control-label col-md-3">
        Dọc phụ bản
    </label>
    <div class="col-md-9">
        <?php echo Html::textInput("Settings[btnc_doc_phu_ban]", $settings->get('btnc_doc_phu_ban'), ['class' => 'form-control numberOnly']); ?>
    </div>
    <div class="clearfix"></div>
</div>
<div class="form-group">
    <label class="control-label col-md-3">
        Dán phụ bản
    </label>
    <div class="col-md-9">
        <?php echo Html::textInput("Settings[btnc_dan_phu_ban]", $settings->get('btnc_dan_phu_ban'), ['class' => 'form-control numberOnly']); ?>
    </div>
    <div class="clearfix"></div>
</div>
<div class="form-group">
    <label class="control-label col-md-3">
        Dán phong bì
    </label>
    <div class="col-md-9">
        <?php $btnc_dan_phong_bi = $settings->get('btnc_dan_phong_bi'); ?>
        <table border="0" cellpadding="0" cellspacing="0" class="adminlist">
            <tr>
                <th colspan="2">Danh mục (dài x rộng)</th>
                <th>Đơn giá</th>
                <th width="50"><span class="btn btn-sq-xs btn-primary button-add-line"><i class="fa fa-plus fa-1x"></i></span></th>
            </tr>
            <?php
            if(!empty($btnc_dan_phong_bi) && is_array($btnc_dan_phong_bi)){
                foreach ($btnc_dan_phong_bi as $k => $v) {
                    ?>
                    <tr class="tr-line-input">
                        <td>
                            <?php echo Html::textInput("Settings[btnc_dan_phong_bi][$k][dai]", $v['dai'], ['class' => 'form-control numberOnly']); ?>
                        </td>
                        <td>
                            <?php echo Html::textInput("Settings[btnc_dan_phong_bi][$k][rong]", $v['rong'], ['class' => 'form-control numberOnly']); ?>
                        </td>
                        <td>
                            <?php echo Html::textInput("Settings[btnc_dan_phong_bi][$k][don_gia]", $v['don_gia'], ['class' => 'form-control numberOnly']); ?>
                        </td>
                        <td><span class="btn btn-sq-xs btn-danger button-remove-line"><i class="fa fa-minus fa-1x"></i></span></td>
                    </tr>
                    <?php
                }
            }else {
                ?>
                <tr class="tr-line-input">
                    <td>
                        <?php echo Html::textInput("Settings[btnc_dan_phong_bi][0][dai]", null, ['class' => 'form-control numberOnly']); ?>
                    </td>
                    <td>
                        <?php echo Html::textInput("Settings[btnc_dan_phong_bi][0][rong]", null, ['class' => 'form-control numberOnly']); ?>
                    </td>
                    <td>
                        <?php echo Html::textInput("Settings[btnc_dan_phong_bi][0][don_gia]", null, ['class' => 'form-control numberOnly']); ?>
                    </td>
                    <td><span class="btn btn-sq-xs btn-danger button-remove-line"><i class="fa fa-minus fa-1x"></i></span></td>
                </tr>
                <?php
            }
            ?>
        </table>
    </div>
    <div class="clearfix"></div>
</div>
<div class="form-group">
    <label class="control-label col-md-3">
        Dán hồ sơ
    </label>
    <div class="col-md-9">
        <?php echo Html::textInput("Settings[btnc_dan_ho_so]", $settings->get('btnc_dan_ho_so'), ['class' => 'form-control numberOnly']); ?>
    </div>
    <div class="clearfix"></div>
</div>
<div class="form-group">
    <label class="control-label col-md-3">
        Kiểm sách thành phẩm
    </label>
    <div class="col-md-9">
        <?php
        $btnc_kiem_sach_than_pham = $settings->get('btnc_kiem_sach_than_pham');
        ?>
        <table border="0" cellpadding="0" cellspacing="0" class="adminlist">
            <tr>
                <th>Danh mục</th>
                <th>Đơn giá</th>
                <th width="50"><span class="btn btn-sq-xs btn-primary button-add-line"><i class="fa fa-plus fa-1x"></i></span></th>
            </tr>
            <?php
            if(!empty($btnc_kiem_sach_than_pham) && is_array($btnc_kiem_sach_than_pham)){
                foreach ($btnc_kiem_sach_than_pham as $k => $v) {
                    ?>
                    <tr class="tr-line-input">
                        <td>
                            <?php echo Html::textInput("Settings[btnc_kiem_sach_than_pham][$k][loai]", $v['loai'], ['class' => 'form-control']); ?>
                        </td>
                        <td>
                            <?php echo Html::textInput("Settings[btnc_kiem_sach_than_pham][$k][don_gia]", $v['don_gia'], ['class' => 'form-control numberOnly']); ?>
                        </td>
                        <td><span class="btn btn-sq-xs btn-danger button-remove-line"><i class="fa fa-minus fa-1x"></i></span></td>
                    </tr>
                    <?php
                }
            }else {
                ?>
                <tr class="tr-line-input">
                    <td>
                        <?php echo Html::textInput("Settings[btnc_kiem_sach_than_pham][0][loai]", null, ['class' => 'form-control']); ?>
                    </td>
                    <td>
                        <?php echo Html::textInput("Settings[btnc_kiem_sach_than_pham][0][don_gia]", null, ['class' => 'form-control numberOnly']); ?>
                    </td>
                    <td><span class="btn btn-sq-xs btn-danger button-remove-line"><i class="fa fa-minus fa-1x"></i></span></td>
                </tr>
                <?php
            }
            ?>
        </table>
    </div>
    <div class="clearfix"></div>
</div>
<div class="form-group">
    <label class="control-label col-md-3">
        Bó gói
    </label>
    <div class="col-md-9">
        <?php $btnc_bo_goi = $settings->get('btnc_bo_goi'); ?>
        <table border="0" cellpadding="0" cellspacing="0" class="adminlist">
            <tr>
                <th>Tên</th>
                <th>Giá trị đầu</th>
                <th>Giá trị cuối</th>
                <th>Đơn giá</th>
                <th width="50"><span class="btn btn-sq-xs btn-primary button-add-line"><i class="fa fa-plus fa-1x"></i></span></th>
            </tr>
            <?php
            if(!empty($btnc_bo_goi) && is_array($btnc_bo_goi)){
                foreach ($btnc_bo_goi as $k => $v) {
                    ?>
                    <tr class="tr-line-input">
                        <td>
                            <?php echo Html::textInput("Settings[btnc_bo_goi][$k][loai]", $v['loai'], ['class' => 'form-control']); ?>
                        </td>
                        <td>
                            <?php echo Html::textInput("Settings[btnc_bo_goi][$k][gia_tri_dau]", $v['gia_tri_dau'], ['class' => 'form-control numberOnly']); ?>
                        </td>
                        <td>
                            <?php echo Html::textInput("Settings[btnc_bo_goi][$k][gia_tri_cuoi]", $v['gia_tri_cuoi'], ['class' => 'form-control numberOnly']); ?>
                        </td>
                        <td>
                            <?php echo Html::textInput("Settings[btnc_bo_goi][$k][don_gia]", $v['don_gia'], ['class' => 'form-control numberOnly']); ?>
                        </td>
                        <td><span class="btn btn-sq-xs btn-danger button-remove-line"><i class="fa fa-minus fa-1x"></i></span></td>
                    </tr>
                    <?php
                }}else{
                ?>
                <tr class="tr-line-input">
                    <td>
                        <?php echo Html::textInput("Settings[btnc_bo_goi][0][loai]", null, ['class' => 'form-control']); ?>
                    </td>
                    <td>
                        <?php echo Html::textInput("Settings[btnc_bo_goi][0][gia_tri_dau]", null, ['class' => 'form-control numberOnly']); ?>
                    </td>
                    <td>
                        <?php echo Html::textInput("Settings[btnc_bo_goi][0][gia_tri_cuoi]", null, ['class' => 'form-control numberOnly']); ?>
                    </td>
                    <td>
                        <?php echo Html::textInput("Settings[btnc_bo_goi][0][don_gia]", null, ['class' => 'form-control numberOnly']); ?>
                    </td>
                    <td><span class="btn btn-sq-xs btn-danger button-remove-line"><i class="fa fa-minus fa-1x"></i></span></td>
                </tr>
            <?php }?>
        </table>
    </div>
    <div class="clearfix"></div>
</div>
<div class="form-group">
    <label class="control-label col-md-3">
        Đóng thùng
    </label>
    <div class="col-md-9">
        <?php $btnc_dong_thung = $settings->get('btnc_dong_thung'); ?>
        <table border="0" cellpadding="0" cellspacing="0" class="adminlist">
            <tr>
                <th>Tên</th>
                <th>Giá trị đầu</th>
                <th>Giá trị cuối</th>
                <th>Đơn giá</th>
                <th width="50"><span class="btn btn-sq-xs btn-primary button-add-line"><i class="fa fa-plus fa-1x"></i></span></th>
            </tr>
            <?php
            if(!empty($btnc_dong_thung) && is_array($btnc_dong_thung)){
                foreach ($btnc_dong_thung as $k => $v) {
                    ?>
                    <tr class="tr-line-input">
                        <td>
                            <?php echo Html::textInput("Settings[btnc_dong_thung][$k][loai]", $v['loai'], ['class' => 'form-control']); ?>
                        </td>
                        <td>
                            <?php echo Html::textInput("Settings[btnc_dong_thung][$k][gia_tri_dau]", $v['gia_tri_dau'], ['class' => 'form-control numberOnly']); ?>
                        </td>
                        <td>
                            <?php echo Html::textInput("Settings[btnc_dong_thung][$k][gia_tri_cuoi]", $v['gia_tri_cuoi'], ['class' => 'form-control numberOnly']); ?>
                        </td>
                        <td>
                            <?php echo Html::textInput("Settings[btnc_dong_thung][$k][don_gia]", $v['don_gia'], ['class' => 'form-control numberOnly']); ?>
                        </td>
                        <td><span class="btn btn-sq-xs btn-danger button-remove-line"><i class="fa fa-minus fa-1x"></i></span></td>
                    </tr>
                    <?php
                }}else{
                ?>
                <tr class="tr-line-input">
                    <td>
                        <?php echo Html::textInput("Settings[btnc_dong_thung][0][loai]", null, ['class' => 'form-control']); ?>
                    </td>
                    <td>
                        <?php echo Html::textInput("Settings[btnc_dong_thung][0][gia_tri_dau]", null, ['class' => 'form-control numberOnly']); ?>
                    </td>
                    <td>
                        <?php echo Html::textInput("Settings[btnc_dong_thung][0][gia_tri_cuoi]", null, ['class' => 'form-control numberOnly']); ?>
                    </td>
                    <td>
                        <?php echo Html::textInput("Settings[btnc_dong_thung][0][don_gia]", null, ['class' => 'form-control numberOnly']); ?>
                    </td>
                    <td><span class="btn btn-sq-xs btn-danger button-remove-line"><i class="fa fa-minus fa-1x"></i></span></td>
                </tr>
            <?php }?>
        </table>
    </div>
    <div class="clearfix"></div>
</div>
<div class="form-group">
    <label class="control-label col-md-3">
        Dán tem
    </label>
    <div class="col-md-9">
        <?php echo Html::textInput("Settings[btnc_dan_tem]", $settings->get('btnc_dan_tem'), ['class' => 'form-control numberOnly']); ?>
    </div>
    <div class="clearfix"></div>
</div>
<div class="form-group">
    <label class="control-label col-md-3">
        Cuốn màng chít
    </label>
    <div class="col-md-9">
        <?php echo Html::textInput("Settings[btnc_cuong_mang_chit]", $settings->get('btnc_cuong_mang_chit'), ['class' => 'form-control numberOnly']); ?>
    </div>
    <div class="clearfix"></div>
</div>
<div class="form-group">
    <label class="control-label col-md-3">
        Chế bản
    </label>
    <div class="col-md-9">
        <?php $btnc_che_ban = $settings->get('btnc_che_ban'); ?>
        <table border="0" cellpadding="0" cellspacing="0" class="adminlist">
            <tr>
                <th>Danh mục</th>
                <th>Đơn giá</th>
                <th width="50"><span class="btn btn-sq-xs btn-primary button-add-line"><i class="fa fa-plus fa-1x"></i></span></th>
            </tr>
            <?php
            if(!empty($btnc_che_ban) && is_array($btnc_che_ban)){
                foreach ($btnc_che_ban as $k => $v) {
                    ?>
                    <tr class="tr-line-input">
                        <td>
                            <?php echo Html::textInput("Settings[btnc_che_ban][$k][loai]", $v['loai'], ['class' => 'form-control']); ?>
                        </td>
                        <td>
                            <?php echo Html::textInput("Settings[btnc_che_ban][$k][don_gia]", $v['don_gia'], ['class' => 'form-control numberOnly']); ?>
                        </td>
                        <td><span class="btn btn-sq-xs btn-danger button-remove-line"><i class="fa fa-minus fa-1x"></i></span></td>
                    </tr>
                <?php }}else{?>
                <tr class="tr-line-input">
                    <td>
                        <?php echo Html::textInput("Settings[btnc_che_ban][0][loai]", null, ['class' => 'form-control']); ?>
                    </td>
                    <td>
                        <?php echo Html::textInput("Settings[btnc_che_ban][0][don_gia]", null, ['class' => 'form-control numberOnly']); ?>
                    </td>
                    <td><span class="btn btn-sq-xs btn-danger button-remove-line"><i class="fa fa-minus fa-1x"></i></span></td>
                </tr>
            <?php }?>
        </table>
    </div>
    <div class="clearfix"></div>
</div>
<div class="form-group">
    <label class="control-label col-md-3">
        Xén thành phẩm
    </label>
    <div class="col-md-9">
        <?php $btnc_xen_thanh_pham = $settings->get('btnc_xen_thanh_pham'); ?>
        <table border="0" cellpadding="0" cellspacing="0" class="adminlist">
            <tr>
                <th>Danh mục</th>
                <th colspan="2">Đơn giá</th>
                <th width="50"><span class="btn btn-sq-xs btn-primary button-add-line"><i class="fa fa-plus fa-1x"></i></span></th>
            </tr>
            <?php
            if(!empty($btnc_xen_thanh_pham) && is_array($btnc_xen_thanh_pham)){
                foreach ($btnc_xen_thanh_pham as $k => $v) {
                    ?>
                    <tr class="tr-line-input">
                        <td>
                            <?php echo Html::textInput("Settings[btnc_xen_thanh_pham][$k][loai]", $v['loai'], ['class' => 'form-control']); ?>
                        </td>
                        <td>
                            <?php echo Html::textInput("Settings[btnc_xen_thanh_pham][$k][don_gia]", $v['don_gia'], ['class' => 'form-control numberOnly']); ?>
                        </td>
                        <td>tờ</td>
                        <td><span class="btn btn-sq-xs btn-danger button-remove-line"><i class="fa fa-minus fa-1x"></i></span></td>
                    </tr>
                <?php }}else{?>
                <tr class="tr-line-input">
                    <td>
                        <?php echo Html::textInput("Settings[btnc_xen_thanh_pham][0][loai]", null, ['class' => 'form-control']); ?>
                    </td>
                    <td>
                        <?php echo Html::textInput("Settings[btnc_xen_thanh_pham][0][don_gia]", null, ['class' => 'form-control numberOnly']); ?>
                    </td>
                    <td>tờ</td>
                    <td><span class="btn btn-sq-xs btn-danger button-remove-line"><i class="fa fa-minus fa-1x"></i></span></td>
                </tr>
            <?php }?>
        </table>
    </div>
    <div class="clearfix"></div>
</div>
<div class="form-group">
    <label class="control-label col-md-3">
        Bìa cứng
    </label>
    <div class="col-md-9">
        <?php $btnc_bia_cung = $settings->get('btnc_bia_cung'); ?>
        <table border="0" cellpadding="0" cellspacing="0" class="adminlist">
            <tr>
                <th>Danh mục</th>
                <th>Đơn giá</th>
                <th width="50"><span class="btn btn-sq-xs btn-primary button-add-line"><i class="fa fa-plus fa-1x"></i></span></th>
            </tr>
            <?php
            if(!empty($btnc_bia_cung) && is_array($btnc_bia_cung)){
                foreach ($btnc_bia_cung as $k => $v) {
                    ?>
                    <tr class="tr-line-input">
                        <td>
                            <?php echo Html::textInput("Settings[btnc_bia_cung][$k][loai]", $v['loai'], ['class' => 'form-control']); ?>
                        </td>
                        <td>
                            <?php echo Html::textInput("Settings[btnc_bia_cung][$k][don_gia]", $v['don_gia'], ['class' => 'form-control numberOnly']); ?>
                        </td>
                        <td><span class="btn btn-sq-xs btn-danger button-remove-line"><i class="fa fa-minus fa-1x"></i></span></td>
                    </tr>
                <?php }}else{?>
                <tr class="tr-line-input">
                    <td>
                        <?php echo Html::textInput("Settings[btnc_bia_cung][0][loai]", null, ['class' => 'form-control']); ?>
                    </td>
                    <td>
                        <?php echo Html::textInput("Settings[btnc_bia_cung][0][don_gia]", null, ['class' => 'form-control numberOnly']); ?>
                    </td>
                    <td><span class="btn btn-sq-xs btn-danger button-remove-line"><i class="fa fa-minus fa-1x"></i></span></td>
                </tr>
            <?php }?>
        </table>
    </div>
    <div class="clearfix"></div>
</div>

    <h2 style="margin: 30px 0 15px">BẢNG TÍNH KHẤU HAO MÁY TRONG 1 NGÀY</h2>
    <div class="form-group">
        <label class="control-label col-md-3">
            Nhà xưởng
        </label>
        <div class="col-md-9">
            <?php echo Html::textInput("Settings[khau_hao_nha_xuong]", $settings->get('khau_hao_nha_xuong'), ['class' => 'form-control numberOnly']); ?>
        </div>
        <div class="clearfix"></div>
    </div>

    <div class="form-group">
        <label class="control-label col-md-3">
            Máy in 16 trang 4 màu
        </label>
        <div class="col-md-9">
            <?php echo Html::textInput("Settings[may_in_16_trang_4_mau]", $settings->get('may_in_16_trang_4_mau'), ['class' => 'form-control numberOnly']); ?>
        </div>
        <div class="clearfix"></div>
    </div>

    <div class="form-group">
        <label class="control-label col-md-3">
            Máy in 8 trang 4 màu
        </label>
        <div class="col-md-9">
            <?php echo Html::textInput("Settings[may_in_8_trang_4_mau]", $settings->get('may_in_8_trang_4_mau'), ['class' => 'form-control numberOnly']); ?>
        </div>
        <div class="clearfix"></div>
    </div>

    <div class="form-group">
        <label class="control-label col-md-3">
            Máy in 16 trang 1 màu
        </label>
        <div class="col-md-9">
            <?php echo Html::textInput("Settings[may_in_16_trang_1_mau]", $settings->get('may_in_16_trang_1_mau'), ['class' => 'form-control numberOnly']); ?>
        </div>
        <div class="clearfix"></div>
    </div>

    <div class="form-group">
        <label class="control-label col-md-3">
            Máy gấp
        </label>
        <div class="col-md-9">
            <?php echo Html::textInput("Settings[khau_hao_may_gap]", $settings->get('khau_hao_may_gap'), ['class' => 'form-control numberOnly']); ?>
        </div>
        <div class="clearfix"></div>
    </div>

    <h2 style="margin: 30px 0 15px">BẢNG HỆ SỐ BÌA CARTON THEO KHỔ SÁCH</h2>

    <div class="form-group">
        <label class="control-label col-md-3">
            Đơn giá bìa carton
        </label>
        <div class="col-md-9">
            <?php echo Html::textInput("Settings[don_gia_bia_carton]", $settings->get('don_gia_bia_carton'), ['class' => 'form-control numberOnly']); ?>
        </div>
        <div class="clearfix"></div>
    </div>

    <div class="form-group">
        <div class="col-md-12">
            <?php $bia_carton_theo_kho = $settings->get('bia_carton_theo_kho'); ?>
            <table border="0" cellpadding="0" cellspacing="0" class="adminlist">
                <tr>
                    <th style="min-width: 250px">Danh mục</th>
                    <th colspan="2" width="150">Khổ sách (Dài - Rộng)</th>
                    <th colspan="2" width="150">Khổ giấy (Dài - Rộng)</th>
                    <th>Trang/Tay sách</th>
                    <th>Hệ số</th>
                    <th width="50"><span class="btn btn-sq-xs btn-primary button-add-line"><i class="fa fa-plus fa-1x"></i></span></th>
                </tr>
                <?php
                if(!empty($bia_carton_theo_kho) && is_array($bia_carton_theo_kho)){
                    foreach ($bia_carton_theo_kho as $k => $v) {
                        ?>
                        <tr class="tr-line-input">
                            <td>
                                <?php echo Html::textInput("Settings[bia_carton_theo_kho][$k][loai]", $v['loai'], ['class' => 'form-control']); ?>
                            </td>
                            <td>
                                <?php echo Html::textInput("Settings[bia_carton_theo_kho][$k][sach_dai]", $v['sach_dai'], ['class' => 'form-control']); ?>
                            </td>
                            <td>
                                <?php echo Html::textInput("Settings[bia_carton_theo_kho][$k][sach_rong]", $v['sach_rong'], ['class' => 'form-control']); ?>
                            </td>
                            <td>
                                <?php echo Html::textInput("Settings[bia_carton_theo_kho][$k][giay_dai]", $v['giay_dai'], ['class' => 'form-control']); ?>
                            </td>
                            <td>
                                <?php echo Html::textInput("Settings[bia_carton_theo_kho][$k][giay_rong]", $v['giay_rong'], ['class' => 'form-control numberOnly']); ?>
                            </td>
                            <td>
                                <?php echo Html::textInput("Settings[bia_carton_theo_kho][$k][tay_sach]", $v['tay_sach'], ['class' => 'form-control numberOnly']); ?>
                            </td>
                            <td>
                                <?php echo Html::textInput("Settings[bia_carton_theo_kho][$k][he_so]", $v['he_so'], ['class' => 'form-control numberOnly']); ?>
                            </td>
                            <td><span class="btn btn-sq-xs btn-danger button-remove-line"><i class="fa fa-minus fa-1x"></i></span></td>
                        </tr>
                    <?php }}else{?>
                    <tr class="tr-line-input">
                        <td>
                            <?php echo Html::textInput("Settings[bia_carton_theo_kho][0][loai]", null, ['class' => 'form-control']); ?>
                        </td>
                        <td>
                            <?php echo Html::textInput("Settings[bia_carton_theo_kho][0][sach_dai]", null, ['class' => 'form-control numberOnly']); ?>
                        </td>
                        <td>
                            <?php echo Html::textInput("Settings[bia_carton_theo_kho][0][sach_rong]", null, ['class' => 'form-control']); ?>
                        </td>
                        <td>
                            <?php echo Html::textInput("Settings[bia_carton_theo_kho][0][giay_dai]", null, ['class' => 'form-control numberOnly']); ?>
                        </td>
                        <td>
                            <?php echo Html::textInput("Settings[bia_carton_theo_kho][0][giay_rong]", null, ['class' => 'form-control']); ?>
                        </td>
                        <td>
                            <?php echo Html::textInput("Settings[bia_carton_theo_kho][0][tay_sach]", null, ['class' => 'form-control']); ?>
                        </td>
                        <td>
                            <?php echo Html::textInput("Settings[bia_carton_theo_kho][0][he_so]", null, ['class' => 'form-control']); ?>
                        </td>
                        <td><span class="btn btn-sq-xs btn-danger button-remove-line"><i class="fa fa-minus fa-1x"></i></span></td>
                    </tr>
                <?php }?>
            </table>
        </div>
        <div class="clearfix"></div>
    </div>
<?php

$script = <<<XP

    $('.numberOnly').toArray().forEach(function (field) {
        new Cleave(field, {
            numeral: true,
            delimiter: '',
            numeralDecimalScale: 12
        });
    });

    $(document).on('click', '.kiemtoin-add-line', function(){
        var \$las_row = $('tr.kiemtoin-line-input:last'),
            name = \$las_row.find('input:first').attr('name'),
            length = $('tr.kiemtoin-line-input').length,
            \$html_clone = \$las_row.clone();
        \$las_row.after(\$html_clone);
        \$html_clone.find('input').val('');
        \$html_clone.find('select').val('');
        \$html_clone.find('input, select').each(function () {
            $(this).attr('name', $(this).attr('name').replace(/[0-9]/g, length));
        });
    });

    $(document).on('click', 'span.kiemtoin-remove-line', function () {
        var \$this_row = $(this).closest('tr.kiemtoin-line-input'),
            id = \$this_row.find('input[name*="id"]').val();
        if ($('tr.kiemtoin-line-input').length == 1) {
            \$this_row.find('input').val('');
            \$this_row.find('select').val('');
        } else {
            \$this_row.remove();
        }
    });

    $(document).on('click', '.button-add-line', function(){
        var \$las_row = $(this).closest('table').find('tr.tr-line-input:last'),
            name = \$las_row.find('input:first').attr('name'),
            length =  $(this).closest('table').find('tr.tr-line-input').length
            \$html_clone = \$las_row.clone();
        \$las_row.after(\$html_clone);
        \$html_clone.find('input').val('');
        \$html_clone.find('input').each(function (i) {
            $(this).attr('name', $(this).attr('name').replace(/[0-9]/g, length));
        });
    });

    $(document).on('click', 'span.button-remove-line', function () {
        var \$this_row = $(this).closest('tr.tr-line-input'),
            length = $(this).closest('table').find('tr.tr-line-input').length,
            id = \$this_row.find('input[name*="id"]').val();
        if (length == 1) {
            \$this_row.find('input').val('');
        } else {
            \$this_row.remove();
        }
    });

XP;

$this->registerJs($script);

?>