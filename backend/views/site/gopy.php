<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
// use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
// use backend\helpers\AcpHelper;

use backend\models\Tieuchuan;
use backend\models\Danhsachlayykien;
use backend\models\DanhsachlayykienSearch;
use backend\models\Duan;
use backend\models\Sendmail;
// use backend\models\SendmailSearch;


     
    $searchModel = new DanhsachlayykienSearch();

    if($type == 'chuagopy'){
        $dataProvider = $searchModel->search_chuagopy(Yii::$app->request->queryParams,$idnguoinhan);
    }else{
        $dataProvider = $searchModel->search_dagopy(Yii::$app->request->queryParams,$idnguoinhan);
    }
    
    $dataProvider->pagination->pageSize = 10;


    echo '<div class="gopy-index">';            
    Pjax::begin(['id' => 'gopy-pj','enablePushState' => false]);
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

            //  [
            //     'header' => 'Loại',
            //     'attribute' => 'idduan',                        
            //     'headerOptions' => [
            //         'style' => 'width:100px'
            //     ],      
            //     'format'=>'raw',          
            //     'value' => function ($model){
            //         return Duan::getTypeLabel(Duan::getType($model->idduan));
            //     },
            // ],


            [
                'header' => 'Tiêu chuẩn',
                'attribute' => 'idtieuchuan',                        
                'headerOptions' => [
                    'style' => 'width:200px'
                ],      
                'format'=>'raw',          
                'value' => function ($model){
                    $tc = Tieuchuan::findOne(['tc_id' => $model->idtieuchuan]);
                    if($tc){
                        //Trưởng ban thì bật lên
                        // return '<a href="javascript:;" onclick="openmodal(\'/acp/tieuchuan/viewm?id='.$model->idtieuchuan.'\');return false;">'.$tc->getTensuadoi($model->giaidoan,'vi').'</a>';
                        return $tc->getTensuadoi($model->giaidoan,'vi');
                    }
                    return '';
                },
            ],


              [
                'header' => 'Thư mời',
                'attribute' => 'idmoigopy',                        
                'headerOptions' => [
                    'style' => 'width:200px'
                ],      
                'format'=>'raw',          
                'value' => function ($model){
                    $sm = Sendmail::findOne(['mail_id' => $model->idmoigopy]);
                    if($sm){
                        return '<a href="javascript:;" onclick="openmodal(\'/acp/sendmail/viewm?type=gopy&id='.$model->idmoigopy.'\',\'13\');return false;">'.$sm->tieude.'</a>'; 
                    }
                    return '';
                },
            ],



               [
                'header' => 'Số hiệu / Tên dự án',
                'attribute' => 'idduan',                        
                'headerOptions' => [
                    'style' => 'width:200px'
                ],      
                'format'=>'raw',          
                'value' => function ($model){   
                    //Trưởng ban thì bật lên                         
                    // return '<a target="_blank"  data-pjax ="0" href="/acp/duan/viewm?id='.$model->idduan.'&amp;showtab=2&amp;tabgiaidoan='.$model->giaidoan.'&amp;tabgda='.$model->groupduan.'">'.Duan::getSohieu($model->idduan).' / '.Duan::getTen($model->idduan).'</a>';
                    return Duan::getSohieu($model->idduan).' / '.Duan::getTen($model->idduan);
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


            [
                'header' => 'Phương án',
                'attribute' => 'dapan',                        
                'visible' => ($type == 'dagopy'?1:0),
                'headerOptions' => [
                    'style' => 'width:200px'
                ],      
                'format'=>'raw',          
                'value' => function ($model){
                    if(Duan::getType($model->idduan) == 2){
                        return 'Xem chi tiết';
                    }else{
                        return Danhsachlayykien::getYkiengopyLabel($model->dapan);
                    }
                },
            ],

            [
                'header' => 'Nội dung góp ý cụ thể',
                'format'=>'raw',
                'visible' => ($type == 'dagopy'?1:0),
                'headerOptions' => [
                    'style' => 'width:300px'
                ], 
                'attribute' => 'noidung',
                'value' => function($model) use ($type) {
                        if(Duan::getType($model->idduan) == 2){
                            return 'Xem chi tiết';
                        }

                        $string = (strip_tags($model->noidung));
                        $string = str_replace('&nbsp;',' ',$string);
                        $string = explode(' ', $string);
                        $count = 0;
                        if (empty($string) == false) {
                            $string = array_chunk($string, 50);
                            $count = count($string);
                            $string = $string[0];
                        }
                        $string = implode(' ', $string);
                        $xemthem = '... <a href="javascript:;" onclick="openmodal(\'/acp/danhsachlayykien/viewm?type='.$type.'&idnn='.$model->idnguoinhan.'&tc='.$model->idtieuchuan.'&da='.$model->idduan.'&gd='.$model->giaidoan.'\',\'13\');return false;"><i>Xem thêm</i></a>';

                        if($count == 1){
                            return $string;
                        }else{
                            return $string . $xemthem;
                        }
                },                
            ],

             [
                'header' => 'File đính kèm',
                'attribute' => 'filedinhkem',                        
                'visible' => ($type == 'dagopy'?1:0),
                'headerOptions' => [
                    'style' => 'width:200px'
                ],      
                'format'=>'raw',          
                'value' => function ($model){
                    return '<a href="javascript:;" onclick="openmodal(\'/acp/sendmail/viewm?type=moihop&id=\',\'13\');return false;">'.$model->filedinhkem.'</a>';                            
                },
            ],

             [
                'header' => 'Ngày đến hạn góp ý',
                'attribute' => 'idmoigopy',                        
                'visible' => ($type == 'chuagopy'?1:0),
                'headerOptions' => [
                    'style' => 'width:150px'
                ],      
                'format'=>'raw',          
                'value' => function ($model){
                    return $model->idmoigopy0['thoihan'];
                    // return Sendmail::getThoihan($model->idmoigopy);
                },
            ],
           


              [
                // 'label' => false,
                'header' => 'Góp ý',
                'attribute' => 'idmoigopy',
                // 'visible' => ($type == 'chuagopy'?1:0),
                'headerOptions' => [
                    'style' => 'width:80px',
                ],      
                'contentOptions' => [
                    'class' => 'text-center',
                ],      
                'format'=>'raw',          
                'value' => function ($model) use ($type){

                    if(strtotime($model->idmoigopy0['thoihan']) < time()){
                        return 'Hết hạn góp ý';
                    }else{
                        return '<a href="javascript:;" title="'.($type == 'chuagopy'?'Góp ý nhanh':'Chỉnh sửa').'" onclick="openmodal(\'/acp/danhsachlayykien/updatem?type=dstieuchuan&amp;idnn='.$model->idnguoinhan.'&amp;tc='.$model->idtieuchuan.'&amp;da='.$model->idduan.'&amp;gd='.$model->giaidoan.'\',\'13\');return false;"><span class="text-blue"><i class="glyphicon '.($type == 'chuagopy'?'glyphicon-comment':'glyphicon-pencil').'"></i></span></a>';
                    }
                    return $model->idmoigopy0['thoihan'];
                },
            ],

             [
                'header' => 'Cập nhật',                
                'visible' => ($type == 'chuagopy'?1:0),
                'headerOptions' => [
                    'style' => 'width:120px'
                ],      
                'format'=>'raw',          
                'value' => function ($model){
                    return $model->update_at;
                    // return Sendmail::getThoihan($model->idmoigopy);
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

     
?>
<style type="text/css">
    .gopy-index .pagination{
        margin: 15px 0 0 0;
    }
    .gopy-index .summary{
        margin: 25px 0 0 0;
    }
</style>