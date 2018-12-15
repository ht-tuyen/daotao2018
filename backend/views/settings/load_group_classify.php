<?php

use yii\helpers\Html;
use yii\helpers\Url;
use backend\models\Classify;
use yii\widgets\ActiveForm;
use backend\assets\CustomAsset;
use kartik\dialog\Dialog;

/* @var $this yii\web\View */
/* @var $model backend\models\GroupClassify */
/* @var $form yii\widgets\ActiveForm */
$bundle = CustomAsset::register(Yii::$app->view);
echo Dialog::widget();
backend\assets\BackendAsset::register($this);
backend\assets\CustomAsset::register($this);
$this->registerCssFile($bundle->baseUrl . '/js/jquery-ui-1.9.2/_css/custom-theme/jquery-ui-1.9.2.css', ['depends' => [backend\assets\CustomAsset::className()]]);
$this->registerJsFile($bundle->baseUrl . '/js/jquery-ui-1.9.2/_js/jquery-ui-1.9.2.js', ['depends' => [backend\assets\CustomAsset::className()]]);
?>

    <div class="group-classify-form">
        <div class="get_error">

        </div>
        <input type="hidden" value="<?= $model->group_classify_id ?>" name="id"/>

        <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data', 'id' => 'group-classify-form-']]); ?>

        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

        <p><strong>Thêm phân loại người dùng</strong> <span style="color: blue"><br/>(Kéo các tiêu chí từ phải qua trái để thêm tiêu chí, kéo các tiêu chí lên hoặc xuống để sắp xếp, thứ tự ưu tiên từ trên xuống dưới)</span>
        </p>
        <div id="sortable-div">
            <div id="dragdrop">

                <div class="dragbleList">
                    <ul class="sortable-list">
                        <?php
                        if (!empty($list_useful)) {
                            $list_useful_full = Classify::find()->where(['IN', 'classify_id', $list_useful])->all();
                            if (!empty($list_useful_full)) {
                                foreach ($list_useful_full as $k => $v) {
                                    echo "<li class=\"sortable-item\" style='background-image;: url({'../../uploads/' . $v->symbol}) 5px 10px no-repeat #e4dfdf' data-id=\"{$v->classify_id}\">{$v->name}<table width='100%'><tr><th>Doanh thu (đ)</th><th>Số lượng (đơn)</th><th>Thời gian (tháng)</th></tr><tr><td>" . ($v->moc_doanh_thu > 0 && !empty($v->moc_doanh_thu) ? number_format($v->moc_doanh_thu) : '') . "</td><td>" . ($v->moc_so_luong > 0 && !empty($v->moc_so_luong) ? $v->moc_so_luong : '') . "</td><td>" . ($v->thoi_gian_tinh > 0 && !empty($v->thoi_gian_tinh) ? $v->thoi_gian_tinh : '') . "</td></tr></table></li>";
                                }
                            }
                        }
                        ?>
                    </ul>
                </div>
            </div>
            <div class="space"> <=></div>
            <div id="dragdrop">

                <?php
                $html = null;
                if (!$model->isNewRecord && $model->classify_item != "") {
                    $array_classify_item = unserialize($model->classify_item);
                    if (!empty($array_classify_item)) {
                        $list_useful_ = Classify::find()->where(['IN', 'classify_id', $array_classify_item])
                            ->orderBy([new \yii\db\Expression('FIELD (classify_id, ' . implode(',', $array_classify_item) . ')')])
                            ->all();
                        if (!empty($list_useful_)) {
                            foreach ($list_useful_ as $k => $v) {
                                $html .= "<li class=\"sortable-item\" data-id=\"{$v->classify_id}\">{$v->name}<table width='100%'><tr><th>Doanh thu (đ)</th><th>Số lượng (đơn)</th><th>Thời gian (tháng)</th></tr><tr><td>" . ($v->moc_doanh_thu > 0 && !empty($v->moc_doanh_thu) ? number_format($v->moc_doanh_thu) : '') . "</td><td>" . ($v->moc_so_luong > 0 && !empty($v->moc_so_luong) ? $v->moc_so_luong : '') . "</td><td>" . ($v->thoi_gian_tinh > 0 && !empty($v->thoi_gian_tinh) ? $v->thoi_gian_tinh : '') . "</td></tr></table></li>";
                            }
                        }
                    }
                }
                ?>
                <div class="sortList">
                    <ul class="sortable-list">
                        <?php echo $html; ?>
                    </ul>
                </div>
            </div>
        </div>
        <?php
        $multiple_value = '';
        if (!empty($model->classify_item)) {
            $array_classify_item = unserialize($model->classify_item);
            $multiple_value = implode(',', $array_classify_item);
        }
        ?>
        <?= Html::hiddenInput('GroupClassify[multiple_value]', $multiple_value, ['id' => 'GroupClassify_multiple_value']); ?>

        <?= $form->field($model, 'status')->checkbox(['label' => ''])->label(Yii::t('app', 'Status')); ?>

        <div class="form-group">
            <input type="button" value="<?= $model->isNewRecord ? "Tạo mới" : "Cập nhật" ?>"
                   class="save_group_classify_ajax <?= $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary' ?>"
                   data-id="<?= $model->isNewRecord ? "" : $model->group_classify_id ?>"/>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

<?php
$script = <<< XP
    $('#sortable-div .sortable-list').sortable({
        connectWith: '#sortable-div .sortable-list',
        placeholder: 'placeholder',
        update: function (event, ui) {
            if (this === ui.item.parent()[0]) {
                var id_sort = [];
                $('.sortList .sortable-list li').each(function (i) {
                    id_sort[i] = $(this).data('id');
                });
                $('#GroupClassify_multiple_value').val(id_sort.join(','));
            }
        }
    });
XP;
$this->registerJs($script, yii\web\View::POS_END);


$css = <<<XP

.group-classify-form {
  width: 100%;
  height: auto;
  padding: 20px 20px 10px 20px;
  margin: 0;
  border-radius: .3em;
  box-shadow: 0 0.1em 0.4em rgba(0,0,0,.3);
  overflow: hidden;
}
.add_classify, .add_group_classify {
    font-size: 16px;
    cursor: pointer;
    text-decoration: none;
}
#sortable-div{
        vertical-align: top;
    }
    .sortable-list {
        max-height: 380px;
    overflow: auto;
        background-color: #f3f3f3;
        color: #fff;
        list-style: none;
        margin-bottom:10px;
        min-height: 30px;
        padding: 5px;
        min-height: 200px;
    }
    #dragdrop{
        width: 396px;
        display: inline-block;
        vertical-align: top;
    }
    .space{display: inline-block; width: 60px; font-size: 24px; font-weight: bold; text-align: center; color: #0b559b; padding-top: 30px}
    .sortable-list:last-child{ margin-bottom: 0;}

    .dragbleList{ max-height: 400px; overflow: auto;}
    .sortable-item {
        background-color:#adadac;
        color: #fff;
        cursor: move;
        display: block;
        margin-bottom: 2px;
        padding: 6px 6px 6px 28px;        
        font-size: 12px;
        font-weight: bold;

    }
    .sortable-item th{
        font-weight: normal;
        text-align: right !important;
        border-top: 1px solid #fff;
    }
    .sortable-item td{background: none !important; text-align: right !important;}
    
XP;
$this->registerCss($css);
?>