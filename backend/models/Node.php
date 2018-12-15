<?php

namespace backend\models;

use Yii;
use yii\helpers\Url;
use backend\helpers\AcpHelper;
/**
 * This is the model class for table "{{%node}}".
 *
 * @property integer $node_id
 * @property string $title
 * @property string $code
 * @property string $controller
 * @property integer $p_id
 * @property string $url
 * @property integer $sort_order
 * @property integer $status
 */
class Node extends \yii\db\ActiveRecord
{

    public static $_nodes = null;
	public static $_roles = [];
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%node}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['p_id', 'sort_order', 'status'], 'integer'],
	        [['roles', 'title'], 'required'],
            [['title'], 'string', 'max' => 45],
            [['code'], 'string', 'max' => 100],
            [['controller', 'class_name'], 'string', 'max' => 50],
            [['url'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'node_id' => 'ID',
            'title' => \Yii::t('app', 'Title'),
            'code' => \Yii::t('app', 'Code'),
            'controller' => \Yii::t('app', 'Controller'),
            'p_id' => \Yii::t('app', 'Parent'),
            'url' => \Yii::t('app', 'Url'),
            'sort_order' => \Yii::t('app', 'Sort'),
            'status' => \Yii::t('app', 'Status'),
            'class_name' => \Yii::t('app', 'Class Name'),
            'roles' => \Yii::t('app', 'Nhóm quản trị')
        ];
    }

    public static function createAUrl($url) {
        return Url::toRoute($url, true);
    }

    public static function getNodes() {
        if (empty(self::$_nodes)) {
            self::$_nodes = self::find()->where(['status' => ['1']])->orderBy('sort_order ASC')->all();
        }
        return self::$_nodes;
    }

	public static function getNodeTree($pid = 0, $nodes, $roleUser) {
		$this_controller = ucfirst(\Yii::$app->controller->id).'Controller';
		$settings = Yii::$app->settings;
		$menus = [];
		$cache = Yii::$app->cache;
		if (!empty($nodes)) {
			foreach ($nodes as $node) {

				if (count($node->roles)) {
					$rolesArray = [];

					foreach ( $node->roles as $role_id ) {

						if ($role_id > 0) {
							if (isset(self::$_roles[$role_id]) ) {
								$role = self::$_roles[$role_id];
							} else {
								$role = Role::findOne($role_id);
								self::$_roles[$role_id] = $role;
							}

							if ($role) {
								$rolesArray[] = $role->role_id;
								if ($role->p_id !='') {
									$key = 'cache_role_parent_'.$role->p_id;
									$roleChils = $cache->get($key);
									if ($roleChils === false) {
										$roleChils = Role::getChildRoles($role->p_id);
										$cache->set($key, $roleChils, 432000);
									}
									if (count($roleChils))  {
										$rolesArray[] = $role->p_id;
									}
								}
							}
						} else {
							continue;
						}

					}

					$rolesArray = array_unique($rolesArray);

                    //$this_controller = ucfirst(str_replace(['/acp/', 'acp/'], ['', ''], $node->url));

                    //$roleAcl = Role::getOneAclArray(Yii::$app->user->identity->role_id);
                    //$session['allowActions'] = $roleAcl;
                    //if (in_array(Yii::$app->user->identity->role_id, $rolesArray) || $roleUser->acl_desc == 'ALL_PRIVILEGES' || $roleUser->admin_use == 1) {
					//if (isset($session['allowActions'][$this_controller]) || $roleUser->acl_desc == 'ALL_PRIVILEGES' || $roleUser->admin_use == 1) {
						//allow display menu
					//} else {
					//	continue;
					//}
				}

				if ($node->p_id != $pid) {
					continue;
				}
				$node_url = $node->url;

				if (empty($node_url) || $node_url == '#') {
					$node_url = 'javascript:;';
				} else {
					$node_url = self::createAUrl(str_replace(['/acp', 'acp/'], ['', '/'], $node->url));
				}

				$explode_request_url = @explode('/', Yii::$app->request->url);
				$explode_request_url = @array_filter($explode_request_url);
				$explode_request_url = @array_values($explode_request_url);
				$explode_node_url = @explode('/', $node->url);
				$explode_node_url = @array_filter($explode_node_url);
				$explode_node_url = @array_values($explode_node_url);               

				if ($node->controller == 'ImportController'){
					$node_item = [
						'label' => Yii::t('app', "{$node->title}"),
						'url' => $node_url,
						'icon' => !empty($node->class_name) ? str_replace('fa-', '', $node->class_name) : '',
						'active' => $this_controller == $node->controller && Yii::$app->request->absoluteUrl == $node_url ? true : false,
                        'this_controller' => ucfirst(str_replace(['/acp/', 'acp/'], ['', ''], $node->url))
					];
				} else {
					$node_item = [
						'label' => Yii::t('app', "{$node->title}"),
						'url' => $node_url,
						'icon' => !empty($node->class_name) ? str_replace('fa-', '', $node->class_name) : '',
						'active' => $this_controller == $node->controller || $node->url == Yii::$app->request->url || (is_array($explode_request_url) && is_array($explode_node_url) && isset($explode_request_url[1]) && isset($explode_node_url[1]) && Yii::$app->controller->id == $explode_node_url[1] && end($explode_request_url) == 'index' && end($explode_node_url) == 'admin') ? true : false,
                        'this_controller' => ucfirst(str_replace(['/acp/', 'acp/'], ['', ''], $node->url))
					];
				}

				$sub_nodes = [];
				$sub_nodes = array_merge($sub_nodes, self::getNodeTree($node->node_id, $nodes, $roleUser));

				if (!empty($sub_nodes)) {
					$node_item['items'] = $sub_nodes;
					if(array_search(1, array_column($sub_nodes, 'active')) !== False) {
						$node_item['active'] = true;
					}
				}
				if(!empty($node_item))
					array_push($menus, $node_item);
			}
		}
		return $menus;
	}

    public static function getNodeTreeOptions($pid = 0, $margin = 0) {
        $options = array();
        $nodes = self::getNodes();
        if (!empty($nodes)) {
            foreach ($nodes as $node) {
                if ($node->p_id != $pid)
                    continue;

                $options[$node->primaryKey] = str_repeat('---', (int) $margin) . ' ' . Yii::t('app', "{$node->title}");
                $sub_nodes = self::getNodeTreeOptions($node->node_id, $margin + 1);
                if (!empty($sub_nodes)) {
                    $options = $options + $sub_nodes;
                }
            }
        }
        return $options;
    }

    public static function getMenuArray() {
	    $array = [];

	    $cache = Yii::$app->cache;
	    $key = 'cache_node';
	    $nodes = $cache->get($key);
	    if ($nodes === false) {
		    $nodes = self::getNodes();
		    $cache->set($key, $nodes);
	    }

	    $roleUser = Role::findOne(Yii::$app->user->identity->role_id);

	    $tree = self::getNodeTree(0, $nodes, $roleUser);
	    $tree = @array_filter($tree);

        $roleAcl = Role::getOneAclArray(Yii::$app->user->identity->role_id);
        
        // if($_GET['test'] == 'vsqi'){
        //     echo '<pre>';            
        //     print_r($tree);
        //     echo '</pre>';
        // }

        // die;
        // if($_GET['test'] == 'vsqi'){
        //     echo '<pre>';
        //     print_r($tree);        
        //     echo '</pre>';
        // }
        // die;

        foreach($tree as $key => $menu_item){  
            // if($_GET['test'] == 'vsqi'){
            //     echo '<pre>';
            //     print_r($menu_item['this_controller']);
            //     echo '</pre>';
            // }

            if($roleUser->acl_desc != 'ALL_PRIVILEGES' && $roleUser->admin_use != 1){
                if(($menu_item['label'] != 'Bảng điều khiển'  && $menu_item['label'] != 'Khách hàng'  && $menu_item['label'] != 'Hồ sơ'  && $menu_item['label'] != 'Ban kỹ thuật' && $menu_item['label'] != 'Xây dựng TCVN') && !isset($roleAcl[$menu_item['this_controller']]) ){  
                        unset($tree[$key]);
                    }    

                elseif(($menu_item['label'] == 'Bảng điều khiển')){
                    unset($tree[$key]);
                }

                elseif($menu_item['label'] == 'Ban kỹ thuật' ){
                    foreach($menu_item['items'] as $key_item => $item){
                        // if($_GET['test'] == 'vsqi'){
                        //     echo '<pre>';
                        //     print_r($roleAcl['Bankythuat']);
                        //     print_r($item);
                        //     echo '</pre>';
                        // }
                        if(!isset($roleAcl[$item['this_controller']])){
                            if($item['this_controller'] == 'Bankythuat/quocte'){

                                if(is_array($roleAcl['Bankythuat'])){
                                    if(in_array('quocte',$roleAcl['Bankythuat'])){
                                    }else{
                                        if(isset($tree[$key]['items'][$key_item]))
                                            unset($tree[$key]['items'][$key_item]);    
                                    }
                                }
                            }else{
                                unset($tree[$key]['items'][$key_item]);
                            }
                        }
                    }
                    if(empty($tree[$key]['items'])){
                        unset($tree[$key]);
                    }
                } 


                // elseif($menu_item['label'] == 'Quản lý tin tức' ){
                //      foreach($menu_item['items'] as $key_item => $item){  
                //         if(!empty($item['this_controller'])){                           
                //             if($item['this_controller'] == 'Chuyenmuc'  && isset($roleAcl['Chuyenmuc'])){}
                //             elseif($item['this_controller'] == 'Tintuc'  && isset($roleAcl['Tintuc'])){}
                //             else{
                //                 unset($tree[$key]['items'][$key_item]);
                //             }
                //         }
                //     }
                //     if(empty($tree[$key]['items'])){ // Nếu ko có item con thì cũng ẩn luôn cái mẹ
                //         unset($tree[$key]);
                //     } 
                // }


                


                elseif($menu_item['label'] == 'Xây dựng TCVN' ){
                    foreach($menu_item['items'] as $key_item => $item){
                        if(!isset($roleAcl[$item['this_controller']]) || !AcpHelper::check_role('index',$item['this_controller']) ){
                            unset($tree[$key]['items'][$key_item]);
                        }
                    }
                    if(empty($tree[$key]['items'])){
                        // echo 'xaydungtcvn-'.$key;
                        unset($tree[$key]);
                    }
                }

                elseif(!AcpHelper::check_role('index',$menu_item['this_controller']) && !AcpHelper::check_role('index-hoso','Duan')){
                    unset($tree[$key]);
                }

            }
            // elseif(!AcpHelper::check_role('index',$menu_item['this_controller'])){
            //     unset($tree[$key]);
            // }
        }


        // if($_GET['test'] == 'vsqi'){
        //     echo '<pre>';            
        //     print_r($tree);
        //     echo '</pre>';
        // }
        // die;

        if (!empty($tree)) {
            $array = array_merge($array, $tree);
        }
       
	    return $array;
    }

    public function getParent()
    {
        return $this->hasOne(self::className(), ['p_id' => 'node_id']);
    }

    public static function getExceptionControllers() {
        return array('DefaultController', 'MainController', 'SiteController');
    }

    public function getRemainControllers($currentController = null) {
        $exceptControllers = array();
        $collection = self::find()->all();
        if(!empty($collection)) {
            foreach ($collection as $k => $v) {
                if ($v->getAttribute('controller') == $currentController)
                    continue;
                if ($v->getAttribute('controller'))
                    $exceptControllers[] = $v->getAttribute('controller');
            }
        }
        return array_merge($exceptControllers, self::getExceptionControllers());
    }

	public function afterFind() {
		if ($this->roles != '') {
			$this->roles = explode(',', $this->roles);
		} else {
			$this->roles = array();
		}

		parent::afterFind();
	}
	public function beforeSave($insert) {

		$this->roles = implode(',', $this->roles);
		return parent::beforeSave($insert);
	}

}
