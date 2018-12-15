<?php
/**
 * Created by PhpStorm.
 * User: Mr.Phu
 * Date: 24/04/2017
 * Time: 9:48 AM
 */

namespace backend\helpers;

use backend\models\Role;
use backend\models\Node;
use backend\models\Thanhvien;
use backend\models\Duan;

use Yii;
use yii\helpers\FileHelper;
use yii\web\HttpException;
use yii\db\ActiveRecord;

class AcpHelper
{
     public static function clean_sodienthoai($s = ''){ //Số điện thoại
        $s = str_replace('  ',' ',$s);
        $s = str_replace(' ','',$s);
        $s = str_replace(',','',$s);
        $s = str_replace('.','',$s);
        $s = trim($s);

        $s = '_0'.$s;
        $s = str_replace('_00','_0',$s);
        $s = str_replace('_0','0',$s);        
        return $s;
    }

    public static function clean_sohieu($s = ''){ //Số hiệu TC
        $s = str_replace('  ',' ',$s);
        $s = str_replace(' :',':',$s);
        $s = str_replace(': ',':',$s);
        $s = str_replace(' -','-',$s);
        $s = str_replace('- ','-',$s);  
        $s = str_replace('::',':',$s);
        return $s;
    }
    
    public static function check_own($action = '',$id = ''){ 
        $roleAcl = Role::getOneAclArray(Yii::$app->user->identity->role_id);
        if($roleAcl == 'ALL_PRIVILEGES') return true;
        if(empty($this_controller)) $this_controller = ucfirst(Yii::$app->controller->id);
        $session = Yii::$app->session;
        $user_list_bkt = $session->get('user_list_bkt');        
        switch ($this_controller) {
            case 'Bankythuat':                                                
                if(!is_numeric($id)) $id = $id->bkt_id;
                if(is_array($user_list_bkt['tatca'])) if(in_array($id,$user_list_bkt['tatca'])) return true;                
                break;
            case 'Duan': 
                // die;                                               
                if(!is_numeric($id)) $id = $id->da_id;
                if(is_array($user_list_bkt['thuky'])){
                    $da = Duan::find()->select('da_id')->andWhere(['coquanbiensoan' => $user_list_bkt['thuky']])->column();
                    // echo '<pre>';
                    // print_r($id);
                    // echo '</pre>';
                    if($da && in_array($id,$da)){
                        // echo '<pre>';
                        // print_r(1);
                        // echo '</pre>';
                        return true;
                    }else{
                        // echo '<pre>';
                        // print_r(2);
                        // echo '</pre>';
                    }
                }                
                break;
            default:
                # code...
                break;
        }
        return false;
    }

    public static function check_role($action = '',$this_controller = ''){
        $roleAcl = Role::getOneAclArray(Yii::$app->user->identity->role_id);
        if($roleAcl == 'ALL_PRIVILEGES') return true;
        if(empty($this_controller)) $this_controller = ucfirst(Yii::$app->controller->id);

        //Ngoại lệ
        if($this_controller == 'Duan'){
            if($action == 'update' && in_array('update_all', $roleAcl[$this_controller])) return true;        
        }//End



        if(!empty($roleAcl[$this_controller])) if(is_array($roleAcl[$this_controller])) if(in_array($action, $roleAcl[$this_controller])) return true;
        return false;
    }

    public static function field_null($field = ''){
        $roleAcl = Role::getOneAclArray(Yii::$app->user->identity->role_id);
        if($roleAcl == 'ALL_PRIVILEGES') return true;
        $field_st = Role::get_field_st(Yii::$app->user->identity->role_id);
        return empty($field_st[$field]);
    }

    public static function field_show($field = ''){
        $roleAcl = Role::getOneAclArray(Yii::$app->user->identity->role_id);
        if($roleAcl == 'ALL_PRIVILEGES') return true;
        $field_st = Role::get_field_st(Yii::$app->user->identity->role_id);
        if(empty($field_st[$field])) return false;
        return ($field_st[$field] == 1 || $field_st[$field] == 2);
    }

    public static function field_edit($field = ''){
        $roleAcl = Role::getOneAclArray(Yii::$app->user->identity->role_id);
        if($roleAcl == 'ALL_PRIVILEGES') return true;
        $field_st = Role::get_field_st(Yii::$app->user->identity->role_id);
        if(empty($field_st[$field])) return false;
        return ($field_st[$field] == 2);
    }


    public static function throw_error($code = 403, $msg = '') {
        throw new HttpException($code, $msg);
    }

    public static function getRoleOptions($exceptId = null)
    {
        $model = new Role;
        $options = $model->toOptionHash();
        if ($exceptId) {
            unset($options[$exceptId]);
        }
        return $options;
    }
    public static function getRoleAcl(){
        return ['custom'=>'Tùy chỉnh','full'=>'Đầy đủ','null'=>'Không có quyền'];

    }

	
    public static function getRoleValue($id)
    {
        $model = Role::find()->andWhere(['role_id' => $id])->one();
        if (!empty($model))
            return $model->role_label;
        else
            return '-';
    }

    public static function getControllerOptions($except = true, $exceptControllers = [])
    {
        $return = [];
        $controllers = FileHelper::findFiles(Yii::getAlias('@backend/controllers'), ['recursive' => true, 'only' => ['*.php']]);
        if (!empty($controllers)) {
            foreach ($controllers as $controller) {

                $filename = basename($controller);
                $controller_file = str_replace('.php', '', $filename);

                if (!empty($controller_file))
                    $return[$controller_file] = $controller_file;
            }
        }
        if ($except === true) {
            $exceptControllers = array_merge((array)$exceptControllers, Node::getExceptionControllers());
            if (!empty($exceptControllers)) {
                foreach ($exceptControllers as $v) {
                    unset($return[$v]);
                }
            }
        }
        return $return;
    }

    public static function getYNOptions() {
        return array('1' => 'Có', '0' => 'Không');
    }

    public static function getYNLabel($value = null) {
        $array = self::getYNOptions();
        if ($value === null || !array_key_exists($value, $array))
            return ' - ';
        return $array[$value];
    }

    public static function getRoleTreeHtml($selected = false, $field_st = false) {
        if ($selected !== false && $selected !== 'ALL_PRIVILEGES') {
            $selected = @unserialize($selected);
        }

        if ($field_st !== false) {
            $field_st = json_decode($field_st, true);
        }

        $arr_action_allow = ['loadregions', 'login', 'logout', 's', 'notice-tuyen', 'change-password', 'saveajax', 'get-provinces', 'get-information', 'doanhsochart', 'loinhuanchart', 'donhangchart', 'dskhchart', 'getperson', 'deletetbh', 'fileupload', 'filedelete', 'loadallclassify', 'loadallgroupclassify', 'deleteclassify', 'deletegroupclassify', 'updateclassify', 'updategroupclassify', 'getamount', 'tinh-nvl', 'loadncc', 'upload-file', 'get-file-design', 'prev-file-design', 'del-file-design', 'finished-product-sort', 'save-sort-finished', 'add-sub-order-templ', 'add-customer', 'add-supplier', 'get-ncc-gia-cong', 'addpay', 'get-product-list', 'get-product-size', 'get-kho-may-in', 'get-ncc-giay', 'get-customer-info', 'ncc-giacong', 'find-kho-may-in-mac-dinh', 'get-ton-kho', 'ds-kh-chart', 'doanh-so-chart', 'loi-nhuan-chart', 'don-hang-chart', 'ds-kh-chart', 'get-person', 'file-upload', 'file-delete', 'loadajaxkhomayin', 'addgiakhomayin', 'deletegiakhomayin', 'updategiakhomayin', 'fill-giacong-default', 'fill-output-default', 'getprovinces', 'loadschedule', 'checkexport'];
        $nodeModel = new Role();
        $array = $nodeModel->getRoleTreeArray();
        $html = '';
        $checkHtml = ' checked=true ';
        $i = 0;

        $html .= '<table class="table table-bordered profilesEditView">
                    <thead>
                        <tr class="blockHeader">
                            <th width="20%" class="text-center">                                
                                <b>MODULES</b>
                            </th>
                            <th width="70%" class="text-center">
                                <b>CHỨC NĂNG</b>
                            </th>
                            <th width="10%" class="text-center">
                                <b>TRƯỜNG DỮ LIỆu</b>
                            </th>
                        </tr>
                    </thead>
                    <tbody>';

        foreach ($array as $k => $v) {

            $i++;
            if (isset($selected[$k]) && is_array($selected[$k])){
                $html .= '<tr class=""><td><label for="'.$k.'"><input class="modulesCheckBox alignTop fl check_pr" id="'.$k.'" name="'.$k.'" type="checkbox" ' . $checkHtml . '> <b> ' . mb_strtoupper($v['label']) . '</b><span class="hide">['.$k.']</span></label></td>';
            }else{
                $html .= '<tr><td><label for="'.$k.'"><input class="modulesCheckBox alignTop fl check_pr" id="'.$k.'" name="'.$k.'" type="checkbox"><b> ' . mb_strtoupper($v['label']) . '</b><span class="hide">['.$k.']</span></label> </td>';
            }

            $html .= '<td>';
            foreach ($v['actions'] as $sk => $sv) {

                $note = $v['note'][strtolower($sv)];

                $sv = strtolower($sv);
                if (in_array($sv, $arr_action_allow))
                    continue; 

                if(strpos($sv,'___clearfix')  !== false){
                    $html.= "<div class='clearfix'></div>";                    
                }else{
                    $html.= "<span style='margin-left: 0px;display: inline-block'>";

                    if(strpos($sv,'__checked') !== false){
                        $checked_hide = ' hide';
                        $checked_c = ' checked';
                    }else{
                        $checked_hide = '';
                        $checked_c = '';
                    }

                    $type = 'checkbox';
                    // }

                    if (isset($selected[$k]) && is_array($selected[$k]) && in_array(strtolower($sv), $selected[$k])) {
                        $html.= '<label class="action-label '.$checked_hide.'"><input id="' . $k . '[' . $sv . ']" name="' . $k . '__' . $sv . '" value="' . $sv . '"' . $checkHtml . 'type="'.$type.'" class="fl">     ' . self::getActionLabel($sv, $k) . (empty($note)?'':'<span class="hide">'.$note.'</span>').'</label>';
                    } else {
                        $html.= '<label class="action-label '.$checked_hide.'"><input id="' . $k . '[' . $sv . ']" name="' . $k . '__' . $sv . '" value="' . $sv . '" type="'.$type.'" '.$checked_c.' class="fl"> ' . self::getActionLabel($sv, $k) . (empty($note)?'':'<span class="hide">'.$note.'</span>').'</label>';
                    }
                    $html.= "</span>";
                }

            }
            $html .= '</td>';
            $html .= '<td style="border-left: 1px solid #DDD !important;text-align: center">
                        <div class="row-fluid">
                            <span class="span4">&nbsp;</span>
                            <span class="span4">
                            <button type="button" data-handlerfor="fields" data-togglehandler="'.$k.'-fields" class="btn btn-default show_fields" style="padding-right: 20px; padding-left: 20px;">
                            <i class="fa fa-angle-down pull-right"></i>
                            </button>
                            </span>
                        </div>
                      </td>';

            //Get Field Setting
            $html .= '<tr class="hide ' . $k . '-fields bg-gray-light">
                        <td class="row-fluid" colspan="3" style="">
                            <div class="row-fluid" data-togglecontent="2-fields" style="display: block;">
                                <div class="span12"><label class="themeTextColor font-x-large pull-left"><strong>Trường dữ liệu</strong></label>

                                    <div class="pull-right">
                                        <span class="change-all-roler mini-slider-control ui-slider" data-value="0"><a
                                            style="margin-top: 3px" class="ui-slider-handle"></a><span
                                            style="margin: 0 20px;">Ẩn</span></span>&nbsp;&nbsp;

                                        <span class="change-all-roler mini-slider-control ui-slider"data-value="1"><a style="margin-top: 3px" class="ui-slider-handle"></a><span
                                            style="margin: 0 20px;">Chỉ Đọc</span></span>&nbsp;&nbsp;

                                        <span class="change-all-roler mini-slider-control ui-slider" data-value="2"><a
                                            style="margin-top: 3px" class="ui-slider-handle"></a><span style="margin: 0 20px;">Được Ghi</span></span>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                                <table class="table table-bordered">
                                    <tbody>';

            $field_models = $nodeModel->getRoleFields($k);
            // var_dump($field_models['label']);die();
            // print_r(array_keys($field_models));exit;
            if (isset($field_models['label']))
            {
                $field_array = $field_models['label'];




                $counter = 0;
                $numFields = count($field_array);
                $html .= '<tr><td>';
                foreach((array) $field_array as $field_name => $field_label){
                    if(strpos($field_name,'___start')  !== false){
                        $html .= '<fieldset>';
                        $html .= '<legend>'.$field_label.'</legend>';
                    }else{
                        if(strpos($field_name,'___')  === false ){
                            $field_st_value = $field_st[$k.'__'.$field_name];

                            $_readonly = '';
                            // if(strpos($field_name,'_readonly')  !== false ){
                            //     $_readonly = '-1';
                            // }               

                            $html .= '<div class="col-md-4" style="padding: 5px 0;">
                                        <input type="hidden" name="'.$k.'__'.$field_name.'" data'.$_readonly.'-range-input="'.$k.'__'.$field_name.'" value="'.$field_st_value.'" readonly="true" class="field_permission">
                                        <div class="mini-slider-control editViewMiniSlider pull-left" data-locked="" data'.$_readonly.'-range="'.$k.'__'.$field_name.'" data-value="'.$field_st_value.'"></div>
                                        <div class="pull-left field-item">
                                        <span>'.$field_label.'</span> <i>'.$k.'__'.$field_name.'</i>
                                        </div>
                                    </div>';
                        }
                    }

                    if(strpos($field_name,'___clearfix')  !== false ){
                        $html .= '<hr/ style="width: 100%;float: left;padding: 0;margin: 5px;">';
                    }                    

                    if(strpos($field_name,'___end')  !== false ){
                        $html .= '</fieldset>';
                    }
                   
                }
                $html .= '</td></tr>';
            }else{
                $html .= '<tr><td colspan="3">No Field</td></tr>';
            }
            

            $html .= '</tbody>
                    </table>
                </div>
            </td>
        </tr>';

        }
$html .= '</tbody></table>';

        return $html;
    }




    public static function getActionLabel($value = NULL, $controller) {
        $array = [
            'admin' => 'Danh sách',
            'index' => 'Xem danh sách',
            'update' => 'Chỉnh sửa',
            
            'view' => 'Xem',
            'deletemultiple' => 'Xóa tất cả',
            'delete-multiple' => 'Xóa tất cả',
            'sortorder' => 'Sắp xếp thứ tự',
            'getdoanhsobanhang' => 'Xuất dữ liệu doanh số bán hàng',
            'deleteselected' => 'Xóa chọn',
            'publish' => 'Hiển thị',
            'unpublish' => 'Không hiển thị',
            'edittable' => 'Sửa nhanh',
            'excel' => 'Xuất dữ liệu ra file excel',
            'exceluser' => 'Xuất dữ liệu thành viên',
            'deleted' => 'Xóa',
            'checkexport' => 'Kiểm tra xuất ra',
            'copy' => 'Sao chép đơn hàng',
            'save' => 'Lưu đơn hàng',
            'updatestatus' => 'Cập nhật trạng thái',
            'addcustomer' => 'Thêm mới khách hàng',
            'getcustomer' => 'Lấy thông tin khách hàng',
            'addsubordertempl' => 'Thêm đơn hàng ghép',
            'getgiacongtempl' => 'Thêm gia công',
            'getsudungkhotempl' => 'Thêm nguyên vật liệu',
            'uploadfiledesigntempl' => 'Tải file thiết kế',
            'addsupplier' => 'Thêm nhà cung cấp',
            'getproductsize' => 'Lấy kích thước sản phẩm',
            'getoutputsize' => 'Lấy kích thước ra phim/ra kẽm',
            'getgiacongsize' => 'Lấy kích thước gia công',
            'finishedproductsort' => 'Sắp xếp khổ giấy',
            'exportspv' => 'Xuất danh sách đơn hàng',
            'exportkhv' => 'Xuất danh sách đơn hàng',
            'deleteorderinfo' => 'Xóa thông tin đơn hàng',
            'savestatesortoffinished' => 'Lưu thông tin sắp xếp khổ giấy',
            'getfiledesigndroplist' => 'Lấy danh sách file thiết kế',
            'prevfiledesign' => 'Xem trước file thiết kế',
            'getprovinces' => 'Lấy thông tin Tỉnh / Thành phố',
            'getnhacungcapgiayin' => 'Lấy thông tin nhà cung cấp giấy in',
            'addkhogiayform' => 'Thêm khổ giấy',
            'getnhaccgiacong' => 'Lấy thông tin nhà cung cấp gia công',
            'taosdktmp' => 'Tạo sử dụng kho tạm',
            'getsoluongkho' => 'Lấy số lượng tồn kho',
            'exportcpvc' => 'Xuất báo cáo chi phí vận chuyển',
            'deletegiacong' => 'Xóa gia công',
            'deletesudungkho' => 'Xóa nguyên vật liệu',
            'getproductlist' => 'Lấy danh sách sản phẩm',
            'getproductinfo' => 'Lấy thông tin đơn hàng',
            'fillgiacong' => 'Điền thông tin gia công',
            'fillchiphikhac' => 'Điền thông tin chi phí khác',
            'restore-order' => 'Khôi phục đơn hàng',
            'permanently-delete' => 'Xóa vĩnh viễn đơn hàng',
            'export-page' => 'Xuất dữ liệu trang',
            'addschedule' => 'Thêm lịch dự kiến',
            'deleteschedule' => 'Xóa lịch dự kiến',
            'loadschedule' => 'Tải lịch dự kiến',
            'editschedule' => 'Sửa lịch dự kiến',
            'saveschedule' => 'Lưu lịch dự kiến',
            'tabgiay' => 'Thêm giấy ruột',
            'kieuinmorong' => 'Thêm kiểu in mở rộng',
            'getloinhuan' => 'Xuất dữ liệu lợi nhuận',
            'getdonhang' => 'Xuất dữ liệu đơn hàng',
            'getdoanhthukhachhang' => 'Xuất dữ liệu doanh thu khách hàng',
            'exportcongno' => 'Xuất dữ liệu công nợ',
            'exportdonhang' => 'Xuất dữ liệu đơn hàng',
            'checkday' => 'Kiểm tra ngày tháng',
            'addgiakhomayin' => 'Thêm mới giá khổ máy in',
            'updategiakhomayin' => 'Cập nhật giá khổ máy in',
            'loadajaxkhomayin' => 'Tải giá khổ máy in',
            'deletegiakhomayin' => 'Xóa giá khổ máy in',
            'deletetonkho' => 'Xóa tồn kho',
            'getkhomayin' => 'Lấy thông tin khổ máy in',
            'exportexcel' => 'Xuất dữ liệu',
            'sendmail' => 'Gửi Email',
            'customerinfo' => 'Tab thông tin khách hàng',
            'productinfo' => 'Tab thông tin sản phẩm',
            'printertype' => 'Tab kiểu in',
            'printerpaper' => 'Tab giấy in',
            'output' => 'Tab xuất ra',
            'printertest' => 'Tab in test',
            'giacong' => 'Tab gia công',
            'chiphikhac' => 'Tab chi phi khác',
            'sudungkho' => 'Tab sử dụng kho',
            'export-cong-no' => 'Xuất công nợ',
            'export-hoa-hong-nv' => 'Xuất hoa hồng nhân viên',
            'export-hoa-hong-kh' => 'Xuất hoa hồng khách hàng',
            'export-khach-hang' => 'Xuất danh sách khách hàng',
            'export-ncc' => 'Xuất danh sách nhà cung cấp',
            'export-don-hang' => 'Xuất danh sách đơn hàng',
            'export-doanh-thu' => 'Xuất báo cáo doanh thu',
            'export-van-chuyen' => 'Xuất báo vận chuyển',
            'get-nhap-xuat' => 'Xuất báo nhập xuất',
            'get-mot-nhap-xuatt' => 'Xuất báo nhập xuất',
            'get-phe-lieu-nhap' => 'Xuất báo phế liệu',

            'create' => 'Thêm mới',
            'delete' => 'Xóa',
            'view_details' => 'Xem', 
            'edit' => 'Sửa',

            'delete-select' => 'Xóa lựa chọn',

            // 'index' => 'Xem danh sách',
            'viewm' => 'Xem chi tiết',
            'createm' => 'Thêm mới',            
            'deletem' => 'Xóa',
            'updatem' => 'Sửa',

            'update_all' => 'Sửa tất cả',
            
            'export_hoso' => 'Xuất dữ liệu',
            'export' => 'Xuất dữ liệu',
            'import' => 'Nhập dữ liệu',            
            
            'index-hoso' => 'Xem danh sách',

            'index_hoso_bkt' => 'Danh sách hồ sơ của BKT',
            'index_hoso_all' => 'Danh sách tất cả hồ sơ',

            'createold' => 'Thêm Hồ Sơ',
            'viewm_parent' => 'Xem chi tiết',
            'deletem_parent' => 'Xóa Hồ Sơ',
            'updatem_parent' => 'Sửa Hồ Sơ',

            'uploadbohoso' => 'Upload Bộ tài liệu',

            'index-updatem' => 'Thêm mới',

            'thuchien_edit' => 'Cập nhật tab Thực hiện',
            'thuchien_view' => 'Xem tab Thực hiện',
            'gopy_edit' => 'Cập nhật tab Góp ý',
            'gopy_view' => 'Xem tab Góp ý',
            'nguoinhan_edit' => 'Cập nhật tab Người nhận',
            'nguoinhan_view' => 'Xem tab Người nhận',
            'hoso_edit' => 'Cập nhật tab Hồ Sơ',
            'hoso_view' => 'Xem tab Hồ Sơ',

            'taotaikhoan' => 'Tạo tài khoản',
            'kpmk' => 'Reset mật khẩu',
            'phanquyen' => 'Phân quyền',

            'order' => 'Chi tiết đơn hàng',

            'quocte' => 'Danh sách BKT QT',
            'quocte_updatem' => 'Sửa BKT QT',
            'deletem_qt' => 'Xóa BKT QT',
            'createm_qt' => 'Thêm BKT QT',
            'export_qt' => 'Xuất BKT QT',
            'delete-select-qt' => 'Xóa lựa chọn BKT QT',

            'thembotbonganh' => 'Thêm/bớt bộ ngành',
            'thembotquyetdinh' => 'Thêm/bớt quyết định',
            'thembottieuchuan' => 'Thêm/bớt tiêu chuẩn',

            'index_qt_all' => 'Danh sách tất cả TCQT',
            'index_qt_bkt' => 'Danh sách TCQT của BKT',


            'guimailgopy' => 'Gửi mail mời góp ý',
            'guimailmoihop' => 'Gửi mail mời họp',
            'thembotnguoinhan' => 'Thêm/bớt người nhận',
            'thembotfileduthao' => 'Upload/Delete file hồ sơ kèm theo',
            'traloigopy' => 'Trả lời góp ý',


            'index_all' => 'Danh sách tất cả các Dự án',
            'index_bkt' => 'Danh sách các Dự án của BKT',

            'thembotgroupduan' => 'Thêm/bớt nhóm dự án',
            'thembottieuchuan' => 'Thêm/bớt tiêu chuẩn',

            'downloadgopy' => 'Tải bản tổng hợp góp ý',
            'ingopy' => 'Quyền in góp ý',

            'old_groupduan' => 'Thêm/bớt nhóm dự án',
            'old_thembottieuchuan' => 'Thêm/bớt tiêu chuẩn',
            'old_downloadgopy' => 'Tải bản tổng hợp góp ý',
            'old_ingopy' => 'Quyền in góp ý',

            // 'change-info' => 'Thay đổi thông tin'
            'list-order' => 'Danh sách đơn hàng',
            'order-view' => 'Sửa đơn hàng',
            'order-delete' => 'Xóa đơn hàng',
            'delete-order-select' => 'Xóa đơn hàng được chọn',
            'export_donhang' => 'Xuất đơn hàng',

            'download' => 'Download',
            'upload-multi-file' => 'Upload bộ tài liệu',
            'xoa-file' => 'Xóa file đính kèm',

            'phapche' => 'Nhìn thấy TV Pháp chế',

            'view_hoso_bkt' => 'Xem Hồ Sơ thuộc BKT',
            // thuchien_edit', 'thuchien_view','gopy_edit', 'gopy_view','nguoinhan_edit', 'nguoinhan_view','hoso_edit', 'hoso_view'

            
        ];
        if ($value === NULL) {
            return $value;
        }
        if ($controller != '' && array_key_exists($value, $array)) {
            // return '<b>'. $array[$value] . '</b><span class="hide">[' . $value . ']</span>';
            return '<b>'. $array[$value] . '</b>';
        }else{
            return $value;
        }

    }

    public static function get_gravatar($email, $default = null, $size = 45){
        return '/uploads/config.jpg';        
        $grav_url = "https://www.gravatar.com/avatar/" . md5( strtolower( trim( $email ) ) ) . "?s=" . $size;
        if(!empty($default))
            $grav_url .= "&d=" . urlencode( $default );
        return $grav_url;
    }

    public static function alias($str = '') {
        $str = trim($str);

        if (empty($str))
            return false;

        $str = self::stripUnicode($str);
        $str = preg_replace("/[^a-zA-Z \d]/i", "", $str);
        $str = self::trimTotal($str);
        $str = str_replace(' ', '-', $str);
        return $str;
    }

    public static function fileEx($str = NULL, $dot = false) {
        if (empty($str))
            return false;
        $ex = explode('.', $str);
        $count = count($ex);
        if ($count == 1)
            return false;

        if ($dot == TRUE)
            return '.' . end($ex);
        return end($ex);
    }

    public static function stripUnicode($str) {
        if (empty($str))
            return false;

        $unicode = [
            'a' => 'á|à|ả|ã|ạ|ă|ắ|ặ|ằ|ẳ|ẵ|â|ấ|ầ|ẩ|ẫ|ậ',
            'A' => 'Á|À|Ả|Ã|Ạ|Ă|Ắ|Ặ|Ằ|Ẳ|Ẵ|Â|Ấ|Ầ|Ẩ|Ẫ|Ậ',
            'd' => 'đ',
            'D' => 'Đ',
            'e' => 'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ',
            'E' => 'É|È|Ẻ|Ẽ|Ẹ|Ê|Ế|Ề|Ể|Ễ|Ệ',
            'i' => 'í|ì|ỉ|ĩ|ị',
            'I' => 'Í|Ì|Ỉ|Ĩ|Ị',
            'o' => 'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ',
            'O' => 'Ó|Ò|Ỏ|Õ|Ọ|Ô|Ố|Ồ|Ổ|Ỗ|Ộ|Ơ|Ớ|Ờ|Ở|Ỡ|Ợ',
            'u' => 'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự',
            'U' => 'Ú|Ù|Ủ|Ũ|Ụ|Ư|Ứ|Ừ|Ử|Ữ|Ự',
            'y' => 'ý|ỳ|ỷ|ỹ|ỵ',
            'Y' => 'Ý|Ỳ|Ỷ|Ỹ|Ỵ',
        ];
        foreach ($unicode as $nonUnicode => $uni)
            $str = preg_replace("/($uni)/i", $nonUnicode, $str);
        return $str;
    }

    public static function trimTotal($str_in) {
        $str_in = trim($str_in);
        if (empty($str_in))
            return false;

        $str_in = str_replace(["\n", "\t"], ' ', $str_in);
        $str_in = explode(' ', $str_in);
        $str_out = '';
        foreach ($str_in as $v) {
            if (($c = trim($v)) != '') {
                $str_out.= ' ' . $c;
            }
        }
        return trim($str_out);
    }

   

    public static function numberFormat($number, $decimals = 2, $dec_point = '.', $thousands_sep = ',') {
        if( empty($number) )
            return 0;

        setlocale(LC_MONETARY, 'en_US');
        $number = floatval($number);
        $number = number_format($number, $decimals, $dec_point, $thousands_sep);
        return preg_replace('/.00$/', '', $number);
    }

    public static function formatDate($date, $format = "Y-m-d") {
        $return = '';
        if (!empty($date) && $date != '0000-00-00') {
            $return = date($format, strtotime($date));
        }
        return $return;
    }

    public static function removeFormat($string){
        $string = trim($string);
        $string = str_replace([','], [''], $string);
        return $string;
    }

    public static function _getCurl($url) {
        //open connection
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        //set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);
        // Include header in result? (0 = yes, 1 = no)
        curl_setopt($ch, CURLOPT_HEADER, 0);
        // Should cURL return or print out the data? (true = return, false = print)
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // Timeout in seconds
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        //execute post
        $result = curl_exec($ch);

        //close connection
        curl_close($ch);
        $data = json_decode($result, true);
        return $data;
    }

    public static function getDataTableID($model = NULL, $length = 0) {

        $length = (int) $length;

        if ($length >= 11)
            $length = 11;

        if (!is_object($model))
            throw new HttpException(500, '$model phai la 1 object.');

        $class = get_class($model);
        if ($class == 'stdClass')
            throw new HttpException(500, '$model la 1 object khong hop le.');

        $table = $model->tableSchema;
        if (empty($table))
            throw new HttpException(500, '$model la 1 object khong hop le.');

        //$con = Yii::app()->db;
        //$con = $model->dbConnection;
        if ($length == 0) {
            $model_count = (int) $model->find()->count();
            $length = strlen($model_count) > 3 ? strlen($model_count) : 3;
        } else {
            $length = $length < 3 ? 3 : $length;
        }

        $length_ = str_pad(1000, $length, 0);

        $rand = self::getRand();
        $key_random = floor($rand * $length_);

        $record_exists = $model->findOne($key_random);
        if (isset($record_exists)) {
            return self::getDataTableID($model, $length + 1);
        } else {
            return $key_random;
        }
    }

    public static function getRand() {
        $rand = (float) rand() / (float) getrandmax();
        if ($rand < 0.1) {
            $rand = self::getRand();
        }
        return $rand;
    }

    public function generateUniqueRandomString($model, $length = 11) {

        $randomString = Yii::$app->getSecurity()->generateRandomString($length);

        if(!$model->findOne([$model->primaryKey => $randomString]))
            return $randomString;
        else
            return $this->generateUniqueRandomString($model->primaryKey, $length);

    }
    public static function convert_number_to_words($number) {

        $hyphen      = ' ';
        $conjunction = '  ';
        $separator   = ' ';
        $negative    = 'âm ';
        $decimal     = ' phẩy ';
        $dictionary  = array(
            0                   => 'Không',
            1                   => 'Một',
            2                   => 'Hai',
            3                   => 'Ba',
            4                   => 'Bốn',
            5                   => 'Năm',
            6                   => 'Sáu',
            7                   => 'Bảy',
            8                   => 'Tám',
            9                   => 'Chín',
            10                  => 'Mười',
            11                  => 'Mười một',
            12                  => 'Mười hai',
            13                  => 'Mười ba',
            14                  => 'Mười bốn',
            15                  => 'Mười năm',
            16                  => 'Mười sáu',
            17                  => 'Mười bảy',
            18                  => 'Mười tám',
            19                  => 'Mười chín',
            20                  => 'Hai mươi',
            30                  => 'Ba mươi',
            40                  => 'Bốn mươi',
            50                  => 'Năm mươi',
            60                  => 'Sáu mươi',
            70                  => 'Bảy mươi',
            80                  => 'Tám mươi',
            90                  => 'Chín mươi',
            100                 => 'trăm',
            1000                => 'ngàn đồng',
            1000000             => 'triệu',
            1000000000          => 'tỷ',
            1000000000000       => 'nghìn tỷ',
            1000000000000000    => 'ngàn triệu triệu',
            1000000000000000000 => 'tỷ tỷ'
        );

        if (!is_numeric($number)) {
            return false;
        }

        if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {

            trigger_error(
                'convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
                E_USER_WARNING
            );
            return false;
        }

        if ($number < 0) {
            return $negative . self::convert_number_to_words(abs($number));
        }

        $string = $fraction = null;

        if (strpos($number, '.') !== false) {
            list($number, $fraction) = explode('.', $number);
        }

        switch (true) {
            case $number < 21:
                $string = $dictionary[$number];
                break;
            case $number < 100:
                $tens   = ((int) ($number / 10)) * 10;
                $units  = $number % 10;
                $string = $dictionary[$tens];
                if ($units) {
                    $string .= $hyphen . $dictionary[$units];
                }
                break;
            case $number < 1000:
                $hundreds  = $number / 100;
                $remainder = $number % 100;
                $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
                if ($remainder) {
                    $string .= $conjunction . self::convert_number_to_words($remainder);
                }
                break;
            default:
                $baseUnit = pow(1000, floor(log($number, 1000)));
                $numBaseUnits = (int) ($number / $baseUnit);
                $remainder = $number % $baseUnit;
                $string = self::convert_number_to_words($numBaseUnits) . ' ' . $dictionary[$baseUnit];
                if ($remainder) {
                    $string .= $remainder < 100 ? $conjunction : $separator;
                    $string .= self::convert_number_to_words($remainder);
                }
                break;
        }

        if (null !== $fraction && is_numeric($fraction)) {
            $string .= $decimal;
            $words = array();
            foreach (str_split((string) $fraction) as $number) {
                $words[] = $dictionary[$number];
            }
            $string .= implode(' ', $words);
        }

        return $string;
    }

    public static function getPreviewUrl($url) {
        return 'http://docs.google.com/viewer?url='.$url;
    }

    public static function thu($time){
        $weekday = date("l", strtotime($time));
        $weekday = strtolower($weekday);
        switch($weekday) {
            case 'monday':
                $weekday = 'Thứ hai';
                break;
            case 'tuesday':
                $weekday = 'Thứ ba';
                break;
            case 'wednesday':
                $weekday = 'Thứ tư';
                break;
            case 'thursday':
                $weekday = 'Thứ năm';
                break;
            case 'friday':
                $weekday = 'Thứ sáu';
                break;
            case 'saturday':
                $weekday = 'Thứ bảy';
                break;
            default:
                $weekday = 'Chủ nhật';
                break;
        }
        return $weekday;
    }

   
}