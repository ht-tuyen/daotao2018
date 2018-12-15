<?php

namespace backend\models;

use Yii;
use yii\db\Exception;
use yii\web\HttpException;
use yii\helpers\Inflector;
use yii\helpers\Html;

/**
 * This is the model class for table "{{%role}}".
 *
 * @property integer $role_id
 * @property string $role_name
 * @property string $role_label
 * @property string $acl_desc
 * @property integer $p_id
 * @property string $create_time
 * @property string $update_time
 * @property integer $status
 * @property integer $role_setting
 * @property integer $admin_use
 * @property string $list_status
 * @property integer $allow_change_user
 * @property integer $allow_show_price
 */
class Role extends \yii\db\ActiveRecord
{
    public static $_roles = null;

    /**
     * @inheritdoc
     */
    public $acl_type;

    public static function tableName()
    {
        return '{{%role}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['acl_desc', 'list_status', 'field_st'], 'string'],
            [['p_id', 'status', 'admin_use', 'allow_change_user', 'allow_show_price'], 'integer'],
            [['create_time', 'update_time', 'role_setting'], 'safe'],
            [['role_name', 'role_label'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'role_id' => 'ID',
            'role_name' => \Yii::t('app', 'Name'),
            'role_label' => \Yii::t('app', 'Label'),
            'acl_desc' => 'Quyền hạn',
            'acl_type' => 'Quyền hạn',
            'p_id' => \Yii::t('app', 'Parent'),
            'create_time' => \Yii::t('app', 'Create Time'),
            'update_time' => \Yii::t('app', 'Update Time'),
            'status' => \Yii::t('app', 'Status'),
            'admin_use' => \Yii::t('app', 'Admin Use'),
            'list_status' => \Yii::t('app', 'List Status'),
            'allow_change_user' => \Yii::t('app', 'Allow Change User'),
            'allow_show_price' => \Yii::t('app', 'Allow Show Price'),
            'role_setting' => 'Quyền thiết lập',
        ];
    }


    public static function getRoles()
    {
        if (empty(self::$_roles)) {
            self::$_roles = self::find()->where(['status' => 1])->all();
        }
        return self::$_roles;
    }

    public static function getChildRoles($parent)
    {
        $options = array();
        $_roles = self::find()->where(['status' => 1, 'p_id' => $parent])->all();
        foreach ($_roles as $role) {

            if ($role->role_id == $parent) {
                continue;
            }

            $options[] = $role;
            $sub_roles = self::getChildRoles($role->role_id);
            $options = $options + $sub_roles;
        }

        return $options;
    }

    public static function getRoleTreeOptions($pid = 0, $margin = 0)
    {
        $options = array();
        $roles = self::getRoles();
        if (!empty($roles)) {
            foreach ($roles as $role) {
                if ($role->p_id != $pid)
                    continue;

                $options[$role->primaryKey] = str_repeat('---', (int)$margin) . ' ' . Yii::t('app', "{$role->role_label}");
                $sub_roles = self::getRoleTreeOptions($role->role_id, $margin + 1);
                if (!empty($sub_roles)) {
                    $options = $options + $sub_roles;
                }
            }
        }
        return $options;
    }

    public function toOptionHash($key = 'role_id', $value = 'role_label')
    {
        $return = [];
        $result = self::find()
                        ->andWhere(['!=','role_id',1])
                        ->orderBy(['parentrole' => SORT_ASC])
                        ->all();
        $max = count($result);
        for ($i = 0; $i < $max ; $i++) {
            $link = '|-----';
            for($a = 1 ; $a < $result[$i]['depth'] ; $a++) {
                $link .= '|-----';
            }
            $return[$result[$i][$key]] = $link . $result[$i][$value];
        }      
        return $return;
    }

    public function getRoleTreeArray($level = 0)
    {
        $controllers = \yii\helpers\FileHelper::findFiles(Yii::getAlias('@app/controllers'), ['recursive' => true]);
        $actions = [];

        foreach ($controllers as $controller) {
            try {
                $contents = file_get_contents($controller);
                $controllerId = Inflector::camel2id(substr(basename($controller), 0, -14));
                $controllerId = ucfirst($controllerId);
                if ($controllerId == 'Acp')
                    continue;

                // if ($controllerId == 'Thietlap') {
                //     $default_actions = [
                //         'viewm', 'createm', 'updatem', 'deletem', '___clearfix',
                //     ];
                // } else
                $default_note = [];


                if ($controllerId == 'Bankythuat') {
                    $default_actions = ['index','createm', 'viewm', 'updatem', 'deletem','delete-select', 'export', 'import', '___clearfix', 'quocte','createm_qt','quocte_updatem','deletem_qt','delete-select-qt','export_qt'];

                    $default_note = [
                        // 'index' => 'Danh sách BKT',
                        // 'createm' => 'Thêm BKT mới',
                        // 'viewm' => 'Xem chi tiết thông tin về BKT, Xem chi tiết thông tin về thành viên BKT',
                        // 'updatem' => 'Sửa thông tin về BKT',
                        // 'deletem' => 'Xóa BKT khỏi hệ thống',
                        // 'delete-select' => 'Xóa nhiều BKT theo lựa chọn tích',
                        // 'export' => 'Xuất dữ liệu thông tin các BKT trong danh sách',
                        // 'import' => ''
                    ];
                }
                elseif ($controllerId == 'Duan') {
                    $default_actions = [
                        'index','index_all','index_bkt','viewm', 'createm', 'update_all', 'updatem', 'deletem','export','guimailgopy','guimailmoihop','thembotnguoinhan','thembotgroupduan','thembottieuchuan','traloigopy','downloadgopy','ingopy',

                        '___clearfix','___clearfix',

                        'index-hoso','index_hoso_all','index_hoso_bkt','viewm_parent', /*'view_hoso_bkt',*/ 'createold', 'updatem_parent', 'deletem_parent', 'export_hoso','uploadbohoso','old_groupduan','old_thembottieuchuan','old_downloadgopy','old_ingopy',
                    ];
                }

                elseif ($controllerId == 'Member') {
                    $default_actions = [
                        //'change-info',
                        'index', 'updatem', 'delete','delete-select','export', '___clearfix',
                        'list-order',/*'order',*/ 'order-view', 'order-delete','delete-order-select','export_donhang'
                    ];
                }

                 elseif ($controllerId == 'Ics') {
                    $default_actions = ['index', 'createm', 'updatem', 'deletem' ,'delete-select', 'export', 'import'];
                }
                elseif ($controllerId == 'Tieuchuan') {
                    $default_actions = ['index','viewm', 'createm', 'updatem', 'deletem' ,'delete-select', 'import', 'export','upload-multi-file',/*'download',*/'xoa-file'];
                }

                elseif ($controllerId == 'Kehoachnam') {
                    $default_actions = ['index','viewm', 'index-updatem', 'updatem', 'deletem', 'export', 'import','thembotbonganh','thembotquyetdinh','thembottieuchuan'];
                }
                elseif ($controllerId == 'Tieuchuanquocte') {
                    $default_actions = ['index__checked','index_qt_all','index_qt_bkt','viewm', 'createm', 'updatem', 'deletem','delete-select', 'export',/*'import',*/'___clearfix','thembottieuchuan','guimailgopy','guimailmoihop','thembotnguoinhan', 'thembotfileduthao', 'traloigopy'];
                }

                // elseif($controllerId == 'Tailieuchiase'){
                //     $default_actions = ['viewm','createm','updatem', 'deletem'];
                // }


                elseif ($controllerId == 'Thanhvien') {
                    $default_actions = [
                        'index','viewm', 'createm', 'updatem', 'deletem','delete-select', 'export','taotaikhoan','kpmk','phanquyen','phapche',
                    ];
                }

                elseif ($controllerId == 'Chuyenmuc') {
                    $default_actions = ['index', 'createm', 'updatem', 'deletem'];
                }

                elseif ($controllerId == 'Tintuc') {
                    $default_actions = ['index', 'createm', 'updatem', 'deletem'];
                }

                elseif ($controllerId == 'User') {
                    $default_actions = array();
                }

                // else {
                //     $default_actions = array('view_details', 'create', 'edit', 'delete');
                // }


                $field_models = $this->getRoleFields($controllerId);
                if (!isset($field_models['label'])) {
                    continue;
                }

                //Tìm tên controller
                if (!empty($controllerId)) {
                    $controllerName = "\backend\controllers\\" . $controllerId . 'Controller';
                    $ctlObj = new $controllerName($controllerId, 1);
                    $controllerName = $ctlObj->getControllerLabel();
                } else {
                    $controllerName = '';
                }
                // $controllerName = '123';
                //Trả về tên controller:. VD: Ban kỹ thuật

                foreach ($default_actions as $k_d_c => $action) {
                    $actions[$controllerId]['actions'][] = ucfirst($action);
                    $actions[$controllerId]['label'] = $controllerName;
                    $actions[$controllerId]['note'] = $default_note;
                }

            } catch (Exception $e) {
                throw new Exception('Something really gone wrong', 0, $e);
            }
        }
        ksort($actions);
        return $actions;
    }

    public function getRoleFields($module)
    {
        $danhsachboqua = ['Acp', 'Export', 'Phrase', 'Site', 'Log', 'Node', 'Role', 'Settings', 'Regions',
            'Import',
            'Importics',
            'Importtieuchuan',
            'Filedinhkem',
            'Thietlap',

            'Danhsachlayykien', 'Frontend', 'Language', 'Giaidoan', 'Gopy', 'Fileduthao', 'Kehoach', 'Tailieu', 'Trienkhaitcqt', 'Trienkhaitcqt', 'Trienkhaitcvn', 'Quyetdinh', 'Nguoinhan', 'Sendmail', 'Tailieuchiase'];
        if (in_array($module, $danhsachboqua)) return [];

        $fields = array();
        $controllerName = "\backend\models\\" . $module;
        $modelObj = new $controllerName();
        $list_fields = $modelObj->rules();
        // $list_fields_label = $modelObj->attributeLabels();
        $list_fields_label = $modelObj->attributeLabels_rules_show();

        $fields['fields'] = $list_fields;
        $fields['label'] = $list_fields_label;
        return $fields;
    }

    public function formArrayToAclDesc($formArray = array())
    {
        $return = array();
        if ($formArray != NULL && !empty($formArray)) {
            foreach ($formArray as $k => $v) {
                list($controller, $action) = explode('__', $k);
                $return[$controller][] = $action;
            }
        }
        return serialize($return);
    }

    public static function getOneAclArray($roleId = null, $filter = array())
    {
        if ($roleId == null) {
            // throw new HttpException(404, 'RoleId can not be empty!');
            return false;
        } else {
            $filter = array_merge($filter, array('role_id' => $roleId));

            $cache = Yii::$app->cache;
            $key = 'role_' . $roleId;
            $role_return = $cache->get($key);
            if (empty($role_return)) {
                $result = Role::findOne($filter);
                if ($result->getAttribute('acl_desc') === 'ALL_PRIVILEGES') {
                    $role_return = 'ALL_PRIVILEGES';
                } else {
                    $role_return = unserialize($result->getAttribute('acl_desc'));
                }
                $cache->set($key, $role_return);
            }
            return $role_return;
        }
    }

    public static function get_field_st($roleId = null, $filter = array())
    {
        if ($roleId == null) {
            throw new HttpException(404, 'RoleId can not be empty!');
        } else {
            $filter = array_merge($filter, array('role_id' => $roleId));
            $cache = Yii::$app->cache;
            $key = 'role_' . $roleId . '_field';
            $role_field_return = $cache->get($key);

            if (empty($role_field_return)) {
                $result = Role::findOne($filter);
                if ($result->getAttribute('field_st') == '') {
                    $role_field_return = '';
                } else {
                    $role_field_return = json_decode($result->getAttribute('field_st'), true);
                }
                $cache->set($key, $role_field_return);
            }

            return $role_field_return;
        }
    }

    public function beforeSave($insert)
    {
        if ($this->isNewRecord) {
            $this->{$this->getTableSchema()->primaryKey[0]} = \backend\helpers\AcpHelper::getDataTableID($this);
        }
        return parent::beforeSave($insert); // TODO: Change the autogenerated stub
    }

    public static function getBaseRole()
    {
        $roles = self::getRoles();
        if (!empty($roles)) {
            foreach ($roles as $role) {
                if ($role->depth == 0) {
                    $rootRole = $role->role_id;
                    break;
                }
            }
        }
        return $rootRole;
    }

    public function getChildren($role_id, $depth)
    {
        if ($role_id != '') {
            $parentrole = $role_id . '::';
            $current_depth = $depth + 1;
            $array_childrens = Role::find()
                ->andWhere(['LIKE', 'parentrole', $parentrole])
                ->andWhere(['depth' => $current_depth])
                ->all();
        }
        return $array_childrens;
    }

    public function getChildrenRoleTree($role_id, $depth)
    {
        $string_return = '';
        if ($role_id != '') {
            $parentrole = $role_id . '::';
            $current_depth = $depth + 1;
            $array_children = Role::find()
                ->andWhere(['LIKE', 'parentrole', $parentrole])
                ->andWhere(['depth' => $current_depth])
                ->all();
            if (!empty($array_children)) {
                $string_return .= '<ul>';
                foreach ($array_children as $i_cr => $children) {
                    $child_Role = $children->role_id;
                    $child_depth = $children->depth;
                    $child_parentrole = $children->parentrole;
                    $child_role_label = $children->role_label;

                    $string_return .= '<li data-role="' . $child_parentrole . '" data-roleid="' . $child_Role . '">
                        <div class="toolbar-handle">
                            <a href="/acp/role/update?id=' . $child_Role . '" data-url="/acp/role/update?id=' . $child_Role . '" class="btn draggable droppable ui-draggable ui-droppable" rel="tooltip" data-original-title="Click to edit/Drag to move">' . $child_role_label . '</a>
                            <div class="toolbar" style="display: none;">
                                &nbsp;
                                <a href="/acp/role/create?parent_roleid=' . $child_Role . '" data-url="/acp/role/create?parent_roleid=' . $child_Role . '" title="Add Role"><span class="fa fa-plus-circle"></span></a>

                                <a href="/acp/role/create?ri=' . $child_Role . '" data-url="/acp/role/create?ri=' . $child_Role . '" title="Copy Role"><span class="fa fa-copy"></span></a>

                                &nbsp;';
                    $string_return .= Html::a('<span class="fa fa-trash"></span>', ['delete', 'id' => $child_Role], [
                        'data' => [
                            'confirm' => Yii::t('app', 'Are you sure you want to delete this role?'),
                            'method' => 'post',
                        ],
                    ]);

                    $string_return .= '</div>';
                    $string_return .= self::getChildrenRoleTree($child_parentrole, $child_depth);
                }
                $string_return .= '</ul>';
            } else {
                return '';
            }
        } else {
            return '';
        }

        return $string_return;
    }

    public function getRoleSetting()
    {
        return [
            'info_company' => 'Thông tin công ty',
            'other_setiing' => 'Thiết lập khác',
            'faq' => 'Góp ý TC QT',
            'table_cal' => 'Bảng tính giá tiêu chuẩn',
            'smtp_server' => 'Thiết lập SMTP Server',
        ];
    }
}
