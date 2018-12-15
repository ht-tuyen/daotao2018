<?php

use yii\helpers\Html;
use kartik\detail\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\Log */

$this->title = \Yii::t('app', 'Log');
$this->params['breadcrumbs'][] = ['label' => 'Logs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="log-view">
    <h1>Chi tiết</h1>
    <table class="table table-bordered table-striped">
        <tr>
            <td>
                <div class="col-md-2"><strong>Thời gian</strong></div>
                <div class="col-md-10"><?= $model->create_time?></div>
            </td>
        </tr>
        <tr>
            <td>
                <div class="col-md-2"><strong>Action</strong></div>
                <div class="col-md-10"><?= $model->action_?></div>
            </td>
        </tr>
        <tr>
            <td>
                <div class="col-md-2"><strong>Thông tin</strong></div>
                <div class="col-md-10"><?= $model->action_info?></div>
            </td>
        </tr>
        <tr>
            <td>
                <div class="col-md-2"><strong>Controller</strong></div>
                <div class="col-md-10"><?= $model->action_controller?></div>
            </td>
        </tr>
        <tr>
            <td>
                <div class="col-md-2"><strong>Model</strong></div>
                <div class="col-md-10"><?= $model->action_model?></div>
            </td>
        </tr>

        <tr>
            <td>
                <div class="col-md-2"><strong>Nội dung</strong></div>
                <div class="col-md-10">
                    <table class="table table-bordered table-hover">
                    <?php 
                        $thaydoi = $model->xulynoidungthaydoi();
                        $html = '';
                        $html .= '<b>'.$thaydoi['row_capnhat'].'</b>';
                        $html .= '<table class="table table-bordered table-striped">';
                        $html .=  $thaydoi['html'];
                        $html .= '</table>';
                        echo $html;
                    ?>    
                    </table>                    
                </div>
            </td>
        </tr>


        <tr>
            <td>
                <div class="col-md-2"><strong>IP</strong></div>
                <div class="col-md-10"><?= long2ip($model->remote_addr)?></div>
            </td>
        </tr>
    </table>

    <div class="clearfix"><br/></div>    
    <div class="form-group col-md-12 text-right"> 
         <button type="button" class="btn btn-default" data-dismiss="modal" aria-hidden="true">Đóng</button>
    </div>
</div>
