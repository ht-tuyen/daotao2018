<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
// use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
// use backend\helpers\AcpHelper;

use backend\models\Tieuchuan;
// use backend\models\Danhsachlayykien;
use backend\models\Duan;
use backend\models\BankythuatThanhvien;
use backend\models\Bankythuat;
use backend\models\BankythuatSearch;
// use backend\models\DuanSearch;

$list_bkt = BankythuatThanhvien::getBankythuatList($idthanhvien);//Tìm các bkt mà thành viên này có mặt trong đó
// print_r($list_duan);die;

        if($list_bkt){
           
            $searchModel = new BankythuatSearch();
            $dataProvider = $searchModel->search_2(Yii::$app->request->queryParams,$list_bkt);                   
            $dataProvider->pagination->pageSize = 10;


            echo '<div class="bkt-pj-index">';            
            Pjax::begin(['id' => 'bkt-pj','enablePushState' => false]);
            echo GridView::widget([
                'dataProvider' => $dataProvider,
                // 'filterModel' => $searchModel,   
                'layout' => '                  
                  <div>{items}</div>
                  <div>
                    <div class="pull-left">{summary}</div>
                    <div class="pull-right">{pager}</div>
                  </div>
                ',             
                'columns' => [
                    [
                        'class' => 'yii\grid\SerialColumn',
                        'contentOptions'=>[ 'style'=>'width: 50px'], 
                    ],
                 


                     [
                        'header' => 'BKT',
                        'attribute' => 'tenbankythuat',                        
                        'headerOptions' => [
                            'style' => 'width:200px'
                        ],      
                        'format'=>'raw',          
                        'value' => function ($model){ 
                            return '<a href="javascript:;" onclick="openmodal(\'/acp/bankythuat/viewm?id='.$model->bkt_id.'\');return false;"><span class="text-blue">'.$model->sohieu.' - '.$model->tenbankythuat.'</a>'; 
                        },
                    ],




                     [
                        'header' => 'Chức vụ',
                        'attribute' => 'bkt_id',                        
                        'headerOptions' => [
                            'style' => 'width:200px'
                        ],      
                        'format'=>'raw',          
                        'value' => function ($model) use ($idthanhvien){ 
                            $model->idthuky = unserialize($model->idthuky);
                        	if($model->idtruongban == $idthanhvien){
                        		return '<b>Trưởng ban</b>';
                        	}elseif (in_array($idthanhvien,$model->idthuky)){
                        		return '<b>Thư ký</b>';
                        	}else{
                        		return 'Thành viên';
                        	}
                        },
                    ],


                ],

                'pager' => [
                    'firstPageLabel' => '«',
                    'lastPageLabel' => '»',

                    'nextPageLabel' => '›',
                    'prevPageLabel'  => '‹',
                    
                    'maxButtonCount'=>5, // Số page hiển thị ví dụ: (First  1 2 3 Last)
                ],

            ]);
            Pjax::end();
            echo '</div>';

     
        }
?>
<style type="text/css">
    .bkt-pj-index .pagination{
        margin: 15px 0 0 0;
    }
    .bkt-pj-index .summary{
        margin: 25px 0 0 0;
    }
</style>