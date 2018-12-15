<?php

namespace backend\controllers;

// use backend\models\Tobuhao;
// use backend\models\Classify;
// use backend\models\GroupClassify;
use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use backend\models\Settings;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use yii\helpers\Json;
use backend\helpers\AcpHelper;

/**
 * SettingsController implements the CRUD actions for Settings model.
 */
class SettingsController extends AcpController
{
    public function getControllerLabel() {
        return 'Cấu hình';
    }
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Settings models.
     * @return mixed
     */
    public function actionIndex()
    {
        $model = new Settings();
        if ($model->load(Yii::$app->request->post())) {
            $post_array = Yii::$app->request->post();
            if (!empty($post_array['Settings'])) {
                foreach ($post_array['Settings'] as $k => $v) {
                    if ($k == 'logo')
                        continue;

                    $setting = Settings::findOne([
                        'key' => $k,
                    ]);
                    if (!isset($setting)) {
                        $setting = new Settings;
                    }
                    if(is_array($v)) {
                        $setting->attributes = [
                            'key' => $k,
                            'value' => Json::encode($v),
                            'type' => 'string',
                            'active' => 1
                        ];
                    }else{
                        $setting->attributes = [
                            'key' => $k,
                            'value' => $v,
                            'type' => 'string',
                            'active' => 1
                        ];
                    }
                    if(!$setting->save()) Yii::error($setting->errors);
                }
            }
            // return $this->renderAjax('index', [
            //     'model' => $model,
            //     // 'toBuHao' => $toBuHao
            // ]);
           
        }
        // $toBuHao = Tobuhao::find()->all();
        return $this->render('index', [
            'model' => $model,
            // 'toBuHao' => $toBuHao
        ]);
    }

    /**
     * Finds the Settings model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Settings the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Settings::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionDeletetbh()
    {
        $id = Yii::$app->request->get('id'); // Array or selected records primary keys
        if (!$id) {
            return;
        }

        Tobuhao::deleteAll(['id' => $id]);
    }

    public function beforeAction($action)
    {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }

    public function actionFileUpload()
    {
        $setting_logo = Settings::findOne([
            'key' => 'logo'
        ]);
        if (!isset($setting_logo)) {
            $setting_logo = new Settings;
        }
        Yii::$app->params['uploadPath'] = Yii::getAlias('@anyname') . '/uploads/';
        $image = UploadedFile::getInstance($setting_logo, 'logo');
        $pathinfo = pathinfo($image);
        $filename = AcpHelper::alias($pathinfo['filename']) . '.' . $pathinfo['extension'];

        $setting_logo->attributes = [
            'key' => 'logo',
            'value' => $filename,
            'type' => 'string',
            'active' => 1
        ];
        if ($setting_logo->save(false)) {
            $path = Yii::$app->params['uploadPath'] . $setting_logo->value;
            $image->saveAs($path);
            echo Json::encode([]);
        } else {
            echo Json::encode($setting_logo->getErrors());
        }
    }

    public function actionFileDelete()
    {
        $setting_logo = Settings::findOne([
            'key' => 'logo'
        ]);
        if (!isset($setting_logo)) {
            $setting_logo = new Settings;
        }
        Yii::$app->params['uploadPath'] = Yii::getAlias('@anyname') . '/uploads/';
        @unlink(Yii::$app->params['uploadPath'] . $setting_logo->value);
        $setting_logo->attributes = [
            'key' => 'logo',
            'value' => '',
            'type' => 'string',
            'active' => 1
        ];
        if ($setting_logo->save(false)) {
            echo Json::encode([]);
        } else {
            echo Json::encode($setting_logo->getErrors());
        }
    }

    public function actionLoadallclassify()
    {
        $list_classify = Classify::find()->orderBy(['create_time' => SORT_ASC])->all();
        $html = '<tr><th>Tên phân loại</th><th>Biểu tượng</th><th>Mốc doanh thu (đ)</th><th>Mốc số lượng (đơn hàng)</th><th>Thời gian tính (tháng)</th><th>&nbsp;</th></tr>';
        if (!empty($list_classify)) {
            foreach ($list_classify as $phanloai) {
                if ($phanloai->moc_doanh_thu > 0)
                    $moc_doanh_thu = number_format($phanloai->moc_doanh_thu);
                else
                    $moc_doanh_thu = '';
                $html .= '<tr>';
                $html .= '<td style="text-align:left">' . $phanloai->name . '</td><td>' . Html::img('../../uploads/' . $phanloai->symbol, ['class' => 'file-preview-image', 'style' => 'max-height: 24px']) . '</td><td>' . $moc_doanh_thu . '</td><td>' . $phanloai->moc_so_luong . '</td><td nowrap="">' . $phanloai->thoi_gian_tinh . '</td><td style="width: 45px"><a data-fancybox data-type="ajax" data-src="' . Url::toRoute('settings/updateclassify') . '?id=' . $phanloai->classify_id . '" data-id="' . $phanloai->classify_id . '" class="update_classify fancybox" href="javascript:;"><i class="fa fa-edit"></i></a>  <a data-id="' . $phanloai->classify_id . '" class="delete_classify"><i class="fa fa-trash-o"></i></a></td>';
                $html .= '</tr>';
            }
        }
        echo $html;
    }

    public function actionLoadallgroupclassify()
    {
        $list_group_classify = GroupClassify::find()->orderBy(['create_time' => SORT_ASC])->all();
        $html = '<tr><th>Tên nhóm</th><th>Nhóm phân loại</th><th>&nbsp;</th></tr>';
        if (!empty($list_group_classify)) {
            foreach ($list_group_classify as $group_phanloai) {
                $return = array();
                $array = unserialize($group_phanloai->classify_item);
//                $criteria = new CDbCriteria;
//                if (!empty($array)) {
//                    $criteria->addInCondition('classify_id', $array);
//                    $criteria->order = 'field(classify_id, ' . implode(',', $array) . ')';
//                }
                $classifies = Classify::find()->where(['IN', 'classify_id', $array])
                    ->orderBy([new \yii\db\Expression('FIELD (classify_id, ' . implode(',', $array) . ')')])
                    ->all();
                if (!empty($classifies)) {
                    foreach ($classifies as $v) {
                        $return[] = $v->name;
                    }
                }
                $html .= '<tr>';
                $html .= '<td style="text-align:left">' . $group_phanloai->name . '</td><td>' . implode(', ', $return) . '</td><td nowrap=""><a data-fancybox data-type="iframe" data-height="auto"  data-src="' . Url::toRoute('settings/updategroupclassify') . '?id=' . $group_phanloai->group_classify_id . '" data-id="' . $group_phanloai->group_classify_id . '" class="update_group_classify"><i class="fa fa-edit"></i></a> <a data-id="' . $group_phanloai->group_classify_id . '" class="delete_group_classify"><i class="fa fa-trash-o"></i></a></td>';
                $html .= '</tr>';
            }
        }
        echo $html;
    }

    public function actionDeleteclassify()
    {
        if (!empty($_POST['id'])) {
            $classify = Classify::findOne([
                'classify_id' => $_POST['id'],
            ]);
            if ($classify->delete()) {
                echo 'success';
            } else {
                echo 'error';
            }
        }
    }

    public function actionDeletegroupclassify()
    {
        if (!empty($_POST['id'])) {
            $classify = GroupClassify::findOne([
                'group_classify_id' => $_POST['id'],
            ]);
            if ($classify->delete()) {
                echo 'success';
            } else {
                echo 'error';
            }
        }
    }

    public function actionUpdateclassify()
    {
        if (isset($_GET['id']) && $_GET['id'] != '')
            $model = Classify::findOne(['classify_id' => $_GET['id'],]);
        else
            $model = new Classify();
        Yii::$app->params['uploadPath'] = Yii::$app->basePath . '/uploads/';
        if ($model->load(Yii::$app->request->post())) {
            Yii::$app->params['uploadPath'] = Yii::getAlias('@anyname') . '/uploads/';
            $image = UploadedFile::getInstance($model, 'symbol');
            if (!empty($image)) {
                $pathinfo = pathinfo($image);
                $filename = AcpHelper::alias($pathinfo['filename']) . '.' . $pathinfo['extension'];
                $model->symbol = $filename;
            }
            if ($model->validate() && (!empty($_POST['Classify']['moc_doanh_thu']) || !empty($_POST['Classify']['moc_so_luong']))) {
                if ($model->save()) {
                    if (!empty($image)) {
                        $path = Yii::$app->params['uploadPath'] . $filename;
                        $image->saveAs($path);
                    }
                    echo 'success';
                    die;
                } else {
                    echo 'error';
                }
            } else {
                $get_error = $model->errors;
                $html = '';
                foreach ($get_error as $error) {

                    $html .= '<p>&#x25cf; ' . $error[0] . '</p>';
                }
                if (empty($_POST['Classify']['moc_doanh_thu']) && empty($_POST['Classify']['moc_so_luong'])) {
                    $html .= '<p>&#x25cf; Mốc doanh thu hoặc mốc số lượng không được phép rỗng</p>';
                }
                echo $html;
                die;
            }
        }

        return $this->renderPartial('load_classify', array('model' => $model));
    }

    public function actionUpdategroupclassify()
    {
        if (isset($_GET['id']) && $_GET['id'] != '')
            $model = GroupClassify::findOne(['group_classify_id' => $_GET['id'],]);
        else
            $model = new GroupClassify();
        $arr_classify = array();
        $arr_classify_used = array();
        $list_classify = Classify::find()->select(['classify_id'])->where(['status' => 1])->all();
        if (!empty($list_classify)) {
            foreach ($list_classify as $value) {
                array_push($arr_classify, $value->classify_id);
            }
        }

        $list_classify_used = GroupClassify::find()->select(['classify_item'])->all();
        if (!empty($list_classify_used)) {
            foreach ($list_classify_used as $value) {
                if (!empty($value->classify_item)) {
                    $unserialize = unserialize($value->classify_item);
                    foreach ($unserialize as $k => $v) {
                        array_push($arr_classify_used, $v);
                    }
                }
            }
        }
        $arr_useful = array_diff($arr_classify, $arr_classify_used);

        if ($model->load(Yii::$app->request->post())) {
            if (!empty($_POST['GroupClassify']['multiple_value'])) {
                $arr_item = @explode(',', $_POST['GroupClassify']['multiple_value']);
                $arr_item = @array_filter($arr_item);
                $arr_item = @array_unique($arr_item);
                $model->classify_item = serialize($arr_item);
            } else
                $model->classify_item = '';
            if ($model->validate()) {
                if ($model->save()) {
                    echo 'success';
                    die;
                } else {
                    print_r($model->errors);
                    die;
                }
            } else {
                $get_error = $model->errors;
                $html = '';
                foreach ($get_error as $error) {

                    $html .= '<p>&#x25cf; ' . $error[0] . '</p>';
                }
                echo $html;
                die;
            }
        }
        $this->layout = 'null';
        return $this->render('load_group_classify', array('model' => $model, 'list_useful' => $arr_useful));
    }
}
