<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
// use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
// use backend\helpers\AcpHelper;

use backend\models\Tieuchuan;
use backend\models\Danhsachlayykien;
use backend\models\Duan;
use backend\models\SendmailSearch;

if(empty($tinhtrang)) $tinhtrang = 'saphop';

$ds = Danhsachlayykien::find()
                    ->select(['idmoihop','thamdu'])
                    ->andWhere(['idnguoinhan' => $idnguoinhan])
                    ->andWhere(['is not','idmoihop', null])
                    ->groupBy(['idmoihop'])
                    ->all();//Tra ve cac ban ghi cua nguoi nhan id cu the da moi hop
        if($ds){
            $list_moihop = ArrayHelper::getColumn($ds, 'idmoihop');
            //VD Array ( [0] => ["47","60"] [1] => ["69","70","71"] )   
            $list_idmoihop = [];
            foreach ($list_moihop as $k1 => $v_list) {                  
                $v_list = json_decode($v_list, true);
                foreach ($v_list as $k2 => $v_item) {   
                    if(!in_array($v_item,$list_idmoihop)) $list_idmoihop[] = $v_item;
                }//End forech $v                                    
            }//end foreach $list_idmoihop


            $searchModel = new SendmailSearch();
            if($tinhtrang == 'saphop'){
                $dataProvider = $searchModel->search_lichhop_saphop(Yii::$app->request->queryParams,$list_idmoihop);
            }elseif($tinhtrang == 'dahop'){
                $dataProvider = $searchModel->search_lichhop_dahop(Yii::$app->request->queryParams,$list_idmoihop);
            }

            $dataProvider->pagination->pageSize = 10;


            echo '<div class="lichhop-index">';            
            Pjax::begin(['id' => 'lichhop-pj','enablePushState' => false]);
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
                        'header' => 'Thời gian họp',
                        'attribute' => 'thoihan',                        
                        'headerOptions' => [
                            'style' => 'width:120px'
                        ],      
                        'format'=>'raw',          
                        'value' => function ($model){
                            return $model->thoihan;
                        },
                    ],


                    [
                        'header' => 'Nội dung',
                        'attribute' => 'tieude',                        
                        'headerOptions' => [
                            'style' => 'width:200px'
                        ],      
                        'format'=>'raw',          
                        'value' => function ($model){
                            return '<a href="javascript:;" onclick="openmodal(\'/acp/sendmail/viewm?type=moihop&id='.$model->mail_id.'\',\'13\');return false;">'.$model->tieude.'</a>';                            
                        },
                    ],



                    //  [
                    //     'header' => 'Nội dung',
                    //     'attribute' => 'noidung',                        
                    //     'headerOptions' => [
                    //         'style' => 'width:100px'
                    //     ],      
                    //     'format'=>'raw',          
                    //     'value' => function ($model){
                    //         $string = (strip_tags($model->noidung));
                    //         $string = str_replace('&nbsp;',' ',$string);
                    //         $string = explode(' ', $string);
                    //         $count = 0;
                    //         if (empty($string) == false) {
                    //             $string = array_chunk($string, 10);
                    //             $count = count($string);
                    //             $string = $string[0];
                    //         }
                    //         $xemthem = '... <a href="javascript:;" onclick="openmodal(\'/acp/sendmail/viewm?type=moihop&id='.$model->mail_id.'\',\'13\');return false;"><i>Xem thêm</i></a>';

                    //         $string = implode(' ', $string).$xemthem;
                    //         return $string;
                    //     },
                    // ],


                     [
                        'header' => 'Số hiệu / Tên dự án',
                        'attribute' => 'idduan',                        
                        'headerOptions' => [
                            'style' => 'width:200px'
                        ],      
                        'format'=>'raw',          
                        'value' => function ($model){                            
                            //Phân quyền trường ban mới thì bật lên
                            // return '<a target="_blank"  data-pjax ="0" href="/acp/duan/viewm?id='.$model->idduan.'&amp;showtab=2&amp;tabgiaidoan='.$model->giaidoan.'&amp;tabgda='.$model->groupduan.'">'.Duan::getSohieu($model->idduan) .' / '. Duan::getTen($model->idduan).'</a>';
                            return Duan::getSohieu($model->idduan) .' / '. Duan::getTen($model->idduan);
                        },
                    ],


                     [
                        'header' => 'Giai đoạn',
                        'attribute' => 'giaidoan',                        
                        'headerOptions' => [
                            'style' => 'width:80px'
                        ],      
                        'format'=>'raw',          
                        'value' => function ($model){                            
                            return Tieuchuan::getGiaidoanLabel($model->giaidoan);
                        },
                    ],


                    //  [
                    //     'header' => 'Tham dự',
                    //     'attribute' => 'mail_id',                        
                    //     'headerOptions' => [
                    //         'style' => 'width:80px'
                    //     ],      
                    //     'format'=>'raw',          
                    //     'value' => function ($model){
                    //         return '<div class="text-center"><input type="checkbox"  class="c_hop" data-idmh="'.$model->mail_id.'" /></div>';
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
    .lichhop-index .pagination{
        margin: 15px 0 0 0;
    }
    .lichhop-index .summary{
        margin: 25px 0 0 0;
    }
</style>