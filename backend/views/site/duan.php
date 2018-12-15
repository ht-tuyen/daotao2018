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
use backend\models\DuanSearch;

$list_duan = BankythuatThanhvien::getBankythuatList($idthanhvien);//Tìm các bkt mà thành viên này có mặt trong đó
// print_r($list_duan);die;

        if($list_duan){
           
            $searchModel = new DuanSearch();
            $dataProvider = $searchModel->search_2(Yii::$app->request->queryParams,$list_duan);                   
            $dataProvider->pagination->pageSize = 10;


            echo '<div class="duan-pj-index">';            
            Pjax::begin(['id' => 'duan-pj','enablePushState' => false]);
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
                        'header' => 'Số hiệu / Tên dự án',
                        'attribute' => 'tenduan',                        
                        'headerOptions' => [
                            'style' => 'width:200px'
                        ],      
                        'format'=>'raw',          
                        'value' => function ($model){
                           return '<a target="_blank"  data-pjax ="0" href="/acp/duan/viewm?id='.$model->da_id.'&amp;showtab=2&amp;tabgiaidoan='.$model->giaidoan.'&amp;tabgda=1">'.$model->sohieu.' / '.Duan::getTen($model->da_id).'</a>';                           
                        },
                    ],



                     [
                        'header' => 'BKT',
                        'attribute' => 'coquanbiensoan',                        
                        'headerOptions' => [
                            'style' => 'width:200px'
                        ],      
                        'format'=>'raw',          
                        'value' => function ($model){                            
                          
                            $bkt = Bankythuat::findOne($model->coquanbiensoan);
                            if($bkt){
                                return '<a href="javascript:;" onclick="openmodal(\'/acp/bankythuat/viewm?id='.$bkt->bkt_id.'\');return false;"><span class="text-blue">'.$bkt->sohieu.' - '.$bkt->tenbankythuat.'</a>';
                            }else{
                                return '<span>'.$model->coquanxaydung.'</span> ';
                            }
                            
                        },
                    ],


                    //  [
                    //     'header' => 'Giai đoạn',
                    //     'attribute' => 'giaidoan',                        
                    //     'headerOptions' => [
                    //         'style' => 'width:80px'
                    //     ],      
                    //     'format'=>'raw',          
                    //     'value' => function ($model){                            
                    //         return Tieuchuan::getGiaidoanLabel($model->giaidoan);
                    //     },
                    // ],



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
    .duan-pj-index .pagination{
        margin: 15px 0 0 0;
    }
    .duan-pj-index .summary{
        margin: 25px 0 0 0;
    }
</style>