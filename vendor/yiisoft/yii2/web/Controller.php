<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace yii\web;

use Yii;
use yii\base\InlineAction;
use yii\helpers\Url;
use \backend\models\Role;

/**
 * Controller is the base class of web controllers.
 *
 * For more details and usage information on Controller, see the [guide article on controllers](guide:structure-controllers).
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class Controller extends \yii\base\Controller
{
    /**
     * @var bool whether to enable CSRF validation for the actions in this controller.
     * CSRF validation is enabled only when both this property and [[\yii\web\Request::enableCsrfValidation]] are true.
     */
    public $enableCsrfValidation = true;
    /**
     * @var array the parameters bound to the current action.
     */
    public $actionParams = [];


    /**
     * Renders a view in response to an AJAX request.
     *
     * This method is similar to [[renderPartial()]] except that it will inject into
     * the rendering result with JS/CSS scripts and files which are registered with the view.
     * For this reason, you should use this method instead of [[renderPartial()]] to render
     * a view to respond to an AJAX request.
     *
     * @param string $view the view name. Please refer to [[render()]] on how to specify a view name.
     * @param array $params the parameters (name-value pairs) that should be made available in the view.
     * @return string the rendering result.
     */
    public function renderAjax($view, $params = [])
    {
        return $this->getView()->renderAjax($view, $params, $this);
    }

    /**
     * Send data formatted as JSON.
     *
     * This method is a shortcut for sending data formatted as JSON. It will return
     * the [[Application::getResponse()|response]] application component after configuring
     * the [[Response::$format|format]] and setting the [[Response::$data|data]] that should
     * be formatted. A common usage will be:
     *
     * ```php
     * return $this->asJson($data);
     * ```
     *
     * @param mixed $data the data that should be formatted.
     * @return Response a response that is configured to send `$data` formatted as JSON.
     * @since 2.0.11
     * @see Response::$format
     * @see Response::FORMAT_JSON
     * @see JsonResponseFormatter
     */
    public function asJson($data)
    {
        $response = Yii::$app->getResponse();
        $response->format = Response::FORMAT_JSON;
        $response->data = $data;
        return $response;
    }

    /**
     * Send data formatted as XML.
     *
     * This method is a shortcut for sending data formatted as XML. It will return
     * the [[Application::getResponse()|response]] application component after configuring
     * the [[Response::$format|format]] and setting the [[Response::$data|data]] that should
     * be formatted. A common usage will be:
     *
     * ```php
     * return $this->asXml($data);
     * ```
     *
     * @param mixed $data the data that should be formatted.
     * @return Response a response that is configured to send `$data` formatted as XML.
     * @since 2.0.11
     * @see Response::$format
     * @see Response::FORMAT_XML
     * @see XmlResponseFormatter
     */
    public function asXml($data)
    {
        $response = Yii::$app->getResponse();
        $response->format = Response::FORMAT_XML;
        $response->data = $data;
        return $response;
    }

    /**
     * Binds the parameters to the action.
     * This method is invoked by [[\yii\base\Action]] when it begins to run with the given parameters.
     * This method will check the parameter names that the action requires and return
     * the provided parameters according to the requirement. If there is any missing parameter,
     * an exception will be thrown.
     * @param \yii\base\Action $action the action to be bound with parameters
     * @param array $params the parameters to be bound to the action
     * @return array the valid parameters that the action can run with.
     * @throws BadRequestHttpException if there are missing or invalid parameters.
     */
    public function bindActionParams($action, $params)
    {
        if ($action instanceof InlineAction) {
            $method = new \ReflectionMethod($this, $action->actionMethod);
        } else {
            $method = new \ReflectionMethod($action, 'run');
        }

        $args = [];
        $missing = [];
        $actionParams = [];
        foreach ($method->getParameters() as $param) {
            $name = $param->getName();
            if (array_key_exists($name, $params)) {
                if ($param->isArray()) {
                    $args[] = $actionParams[$name] = (array) $params[$name];
                } elseif (!is_array($params[$name])) {
                    $args[] = $actionParams[$name] = $params[$name];
                } else {
                    throw new BadRequestHttpException(Yii::t('yii', 'Invalid data received for parameter "{param}".', [
                        'param' => $name,
                    ]));
                }
                unset($params[$name]);
            } elseif ($param->isDefaultValueAvailable()) {
                $args[] = $actionParams[$name] = $param->getDefaultValue();
            } else {
                $missing[] = $name;
            }
        }

        if (!empty($missing)) {
            throw new BadRequestHttpException(Yii::t('yii', 'Missing required parameters: {params}', [
                'params' => implode(', ', $missing),
            ]));
        }

        $this->actionParams = $actionParams;

        return $args;
    }

   
    public function beforeAction($action)
    {        
        $this_controller = ucfirst(Yii::$app->controller->id);
        $this_action = Yii::$app->controller->action->id;        
        if(empty(Yii::$app->user->identity->role_id)) return true;
        if($this_controller == 'Site' || $this_controller == 'Api') return true;
        $roleAcl = Role::getOneAclArray(Yii::$app->user->identity->role_id);  
        
        $ds_boqua = [
                        'Default',
                        'Quyetdinh',
                        'Tailieu',
                        'Fileduthao',
                        'Kehoach',
                        'Nguoinhan',
                        'Export',
                        'Import',
                        'Importtieuchuan',
                        'Importics',
                        'Danhsachlayykien',
                        'Sendmail',
        ];  

        $action_boqua = [
                            'User_pq', //Check tại action luôn

                            'Bankythuat_viewm', //Check tại action luôn
                            'Bankythuat_updatem', //Check tại action luôn
                            // 'Bankythuat_viewm', //Check tại action luôn

                            'User_change-info',//Check tại action
                            'User_change-password',//Check tại action

                            'Thanhvien_updatem', //Check tại action
                            'Duan_updatem', //Check tại action


                            'Bankythuat_searchthanhvien',
                            'Bankythuat_searchtieuchuan',
                            'Kehoachnam_createm',
                            'Kehoachnam_searchtieuchuan',
                            'Kehoachnam_get-nam',

                            'Duan_index',
                            'Duan_addgroupduan',
                            'Duan_thembottieuchuan',
                            'Duan_removegroupduan',
                            
                            'Tieuchuanquocte_index',
                            'Tieuchuanquocte_loadduan',
                            'Tieuchuanquocte_timkiemtieuchuan',

                            'Tieuchuanquocte_updatem',//CHeck tại action

                            'Giaidoan_addmore',

                            'Tieuchuan_get-gia',

                            'Tieuchuan_createm',//Check tại action
                            'Tieuchuan_viewm',//Check tại action
                            'Tieuchuan_updatem',//Check tại action
                            'Tieuchuan_deletem',//Check tại action
                            'Tieuchuan_delete-select',//Check tại action
                            

                            'Tieuchuan_createmqt',

                            'Tieuchuan_tieuchuanlist',
                            'Tieuchuan_tieuchuanlistsohieu',
                            'Tieuchuan_loadtieuchuan',
                            'Tieuchuan_search',
                            'Tieuchuan_find',
                            'Tieuchuan_suaten',
                            'Tieuchuan_addtieuchuan',
                            'Tieuchuan_removeduan',
                            'Tieuchuan_checkptxd',
                            'Tieuchuan_suaten'];     
		$role_id = Yii::$app->user->identity->role_id;
		if ($role_id == 963 || $role_id == 900) {
			// Giang vien
			$roleAcl['Student'] = 1;
			$roleAcl['Course'] = 1;
			$roleAcl['Lesson'] = 1;
			$roleAcl['Quiz'] = 1;
			$roleAcl['Question'] = 1;
			$roleAcl['Attachment'] = 1;
			
			
		}
		if ($rold_id == 900) {
			$roleAcl['Category'] = 1;
			$roleAcl['Feedback'] = 1;
			$roleAcl['Feature'] = 1;
			$roleAcl['Banner'] = 1;
		
		}
		/*echo "<pre>";
		var_dump($roleAcl);
		echo "</pre>"; */
        if($roleAcl != 'ALL_PRIVILEGES' && !in_array($this_controller ,$ds_boqua) && !in_array($this_controller.'_'.$this_action ,$action_boqua)){
            if(empty($roleAcl[$this_controller])){
                // echo '<div class="hide">';
                // print_r($this_controller);
				// print_r($this_action."\n");
                // print_r($roleAcl[$this_controller]);
                // echo '</div>';
                print_r('<div><h1>Thông báo</h1><h3>Bạn không có quyền này!</h3></div>');
                // die;
                return false;
                // return $this->redirect('/acp',302)->send();
            }else{                
                if(is_array($roleAcl[$this_controller])) if( !in_array($this_action,$roleAcl[$this_controller])  && !in_array($this_action.'_parent',$roleAcl[$this_controller])  ){
                    //action có chứa _parent thì được phép truy cập vào action con
                    //VD update_parent thì được truy cập vào update
                    // echo '<div class="hide">';
                    // print_r($this_controller);
                    // print_r($this_action."\n");
                    // print_r($roleAcl[$this_controller]);
                    // echo '</div>';
                    print_r('<div><h1>Thông báo</h1><h3>Bạn không có quyền này.</h3></div>');
                    // die;
                    return false;
                    // return $this->redirect('/acp',302)->send();
                }
            }
        }  

        if (parent::beforeAction($action)) {
            if ($this->enableCsrfValidation && Yii::$app->getErrorHandler()->exception === null && !Yii::$app->getRequest()->validateCsrfToken()) {
                throw new BadRequestHttpException(Yii::t('yii', 'Unable to verify your data submission.'));
            }
            // echo '<pre>';
            // print_r($roleAcl);
            // echo '</pre>';
            // die;
            return true;
        }     
        return false;
    }

    /**
     * Redirects the browser to the specified URL.
     * This method is a shortcut to [[Response::redirect()]].
     *
     * You can use it in an action by returning the [[Response]] directly:
     *
     * ```php
     * // stop executing this action and redirect to login page
     * return $this->redirect(['login']);
     * ```
     *
     * @param string|array $url the URL to be redirected to. This can be in one of the following formats:
     *
     * - a string representing a URL (e.g. "http://example.com")
     * - a string representing a URL alias (e.g. "@example.com")
     * - an array in the format of `[$route, ...name-value pairs...]` (e.g. `['site/index', 'ref' => 1]`)
     *   [[Url::to()]] will be used to convert the array into a URL.
     *
     * Any relative URL will be converted into an absolute one by prepending it with the host info
     * of the current request.
     *
     * @param int $statusCode the HTTP status code. Defaults to 302.
     * See <http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html>
     * for details about HTTP status code
     * @return Response the current response object
     */
    public function redirect($url, $statusCode = 302)
    {
        return Yii::$app->getResponse()->redirect(Url::to($url), $statusCode);
    }

    /**
     * Redirects the browser to the home page.
     *
     * You can use this method in an action by returning the [[Response]] directly:
     *
     * ```php
     * // stop executing this action and redirect to home page
     * return $this->goHome();
     * ```
     *
     * @return Response the current response object
     */
    public function goHome()
    {
        return Yii::$app->getResponse()->redirect(Yii::$app->getHomeUrl());
    }

    /**
     * Redirects the browser to the last visited page.
     *
     * You can use this method in an action by returning the [[Response]] directly:
     *
     * ```php
     * // stop executing this action and redirect to last visited page
     * return $this->goBack();
     * ```
     *
     * For this function to work you have to [[User::setReturnUrl()|set the return URL]] in appropriate places before.
     *
     * @param string|array $defaultUrl the default return URL in case it was not set previously.
     * If this is null and the return URL was not set previously, [[Application::homeUrl]] will be redirected to.
     * Please refer to [[User::setReturnUrl()]] on accepted format of the URL.
     * @return Response the current response object
     * @see User::getReturnUrl()
     */
    public function goBack($defaultUrl = null)
    {
        return Yii::$app->getResponse()->redirect(Yii::$app->getUser()->getReturnUrl($defaultUrl));
    }

    /**
     * Refreshes the current page.
     * This method is a shortcut to [[Response::refresh()]].
     *
     * You can use it in an action by returning the [[Response]] directly:
     *
     * ```php
     * // stop executing this action and refresh the current page
     * return $this->refresh();
     * ```
     *
     * @param string $anchor the anchor that should be appended to the redirection URL.
     * Defaults to empty. Make sure the anchor starts with '#' if you want to specify it.
     * @return Response the response object itself
     */
    public function refresh($anchor = '')
    {
        return Yii::$app->getResponse()->redirect(Yii::$app->getRequest()->getUrl() . $anchor);
    }
}
