<?php

namespace backend\controllers;


use backend\models\MemberSearch;

use frontend\models\Order;
use frontend\models\OrderSearch;

use backend\models\Regions;

use backend\models\User;
use Yii;

use yii\data\Pagination;
use yii\helpers\Html;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use backend\helpers\AcpHelper;


/**
 * LogController implements the CRUD actions for Log model.
 */
class ExportController extends AcpController
{
    public function getControllerLabel() {
        return 'Xuất dữ liệu';
    }
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [

        ];
    }

    public function actionDsGopY($tc = '', $gd = '') {

    	$tieuChuan = Tieuchuan::findOne($tc);
    	if($tieuChuan){
            if($tieuChuan->type == Tieuchuan::TYPE_TCQT){
                $data = [
                    'tc' => $tc,
                ];
                return Yii::$app->runAction('export/ds-gop-y-qt',$data);
            }
        }

	    $ds = Danhsachlayykien::find()
	                          ->andWhere(
		                          ['idtieuchuan' => $tc]
	                          )
	                          ->andWhere(
		                          ['giaidoan' => $gd]
	                          )
	                          ->andWhere(['IS NOT','idmoigopy', null])->all();

	    $tiento = '';	    
	    $tiento = Tieuchuan::getGiaidoanLabel($gd);
	    
	    if (count($ds)) {
		    $OpenTBS = new \hscstudio\export\OpenTBS; // new instance of TBS
		    // Change with Your template kaka
		    $template = dirname(__FILE__) . "/../templates_excel/ds_y_kien.docx";
		    $OpenTBS->LoadTemplate($template, OPENTBS_ALREADY_UTF8); // Also merge some [onload] automatic fields (depends of the type of document).

		    $OpenTBS->VarRef['sohieu'] = $tieuChuan->sohieu.'/'.$tieuChuan->getTensuadoi($gd,'vi');
		    $items = [];
		    foreach ( $ds as $count => $d ) {
		    	$ngoinhan = Nguoinhan::getNguoinhan($d->idnguoinhan);
		    	$ykien = Danhsachlayykien::getYkiengopyLabel($d->dapan);
			    $nd = (strip_tags($d->noidung));
			    $nd = str_replace('&nbsp;',' ',$nd);
			    $nd = html_entity_decode($nd);
			    $items[] = ['count' => ($count + 1), 'nguoigop' => $ngoinhan['hoten'], 'ykien' => $ykien, 'noidung' => $nd];
			}

		    $OpenTBS->MergeBlock('data', $items);
	    }

	    $OpenTBS->Show(OPENTBS_DOWNLOAD, $tiento. '_'.$tieuChuan->tentiengviet.'.doc'); // Also merges all [onshow] automatic fields.
	    exit;

    }

    public function actionDsGopYQt($tc = '', $gd = '') {
	    $ds = Danhsachlayykien::find()
	                          ->andWhere(['idtieuchuan' => $tc])
	                          // ->andWhere(['giaidoan' => $gd])
	                          ->andWhere(['IS NOT','idmoigopy', null])->all();
        
	    $tiento = '';	    
	    $tieuChuan = Tieuchuan::findOne($tc);
	    $tiento = Tieuchuan::getGiaidoanQTLabel($tieuChuan->giaidoan);
	    
	    if (count($ds)) {
		    $OpenTBS = new \hscstudio\export\OpenTBS; 
		    $template = dirname(__FILE__) . "/../templates_excel/ds_y_kien_quocte.docx";
		    $OpenTBS->LoadTemplate($template, OPENTBS_ALREADY_UTF8); 
		    $OpenTBS->VarRef['sohieu'] = $tieuChuan->sohieu;
		    $items = [];
		    foreach ( $ds as $count => $d ) {
		    	$ngoinhan = Nguoinhan::getNguoinhan($d->idnguoinhan);
		    	$ykien = Danhsachlayykien::getYkiengopyLabel($d->dapan);

		    	$traloi = json_decode($d->noidung, true);
		    	$nd = $this->tieuchuanquocte($tieuChuan->giaidoan,$traloi,$d);
		    	// die;
		    	$nd = (strip_tags($nd));
			    // $nd = (strip_tags($d->noidung));
			    $nd = str_replace('&nbsp;',' ',$nd);
			    $nd = html_entity_decode($nd);
			    $items[] = [
			    	'count' => ($count + 1),
			    	'nguoigop' => $ngoinhan['hoten'],
			    	// 'ykien' => $ykien,
			    	'noidung' => $nd
			    ];
			}
		    $OpenTBS->MergeBlock('data', $items);
	    }
	    $OpenTBS->Show(OPENTBS_DOWNLOAD, $tiento. '_'.$tieuChuan->tentiengviet.'.doc');
	    exit;
    }

    /**
     * Lists all thanh vien models.
     * @return mixed
     */
    public function actionThanhVien()
    {
    	if(!AcpHelper::check_role('export','Thanhvien')){
    		return $this->redirect(['/']);
    	}

        $searchModel = new ThanhvienSearch();
	    $request = Yii::$app->request->queryParams;
        $dataProvider = $searchModel->search($request);
	    $pages = new Pagination();
		$pages->setPageSize(-1);
        $dataProvider->setPagination($pages);
		$items = $dataProvider->getModels();
		$labels = json_decode($request['filter_texts'], true);
		$cols = $request['cols'];
		// print_r($cols);die;
	    $objReader = \PHPExcel_IOFactory::createReader('Excel5');
	    $objPHPExcel = $objReader->load(dirname(__FILE__) . "/../templates_excel/ds_thanh_vien.xls" );

		$base = 10;
	    $row = $base;

	    $attachs = [];

	    foreach ( range('A', 'Z') as $count => $item ) {
		    $attachs['_'.$count] = $item;
	    }
	    if (count($items)) {
	    
		    foreach ( $items as $count => $item ) {
		    	$row = $base + $count;
		    	if ($count == 0) {
			    } else {
				    // $objPHPExcel->getActiveSheet()->insertNewRowBefore($row, 1);
			    }

			    $objPHPExcel->getActiveSheet()->setCellValue('B'. $row, ($count + 1));

			    $base_col = 2;
			    $col_count = 0;
			    if ($count == 0) {
				    $objPHPExcel->getActiveSheet()->insertNewColumnBefore('C', count($cols) - 1);
			    }

			    foreach ( $cols as $field => $col ) {
				    $cols_a = $base_col + $col_count;
				    if (isset($attachs['_'.$cols_a])) {
				    	if ($row == $base) {
						    $objPHPExcel->getActiveSheet()->setCellValue($attachs['_'.$cols_a] . ($row - 1), $col);
					    }

					    // if ($field == 'idbankythuat') {
				    	// 	$value = $col;
					    // }
					    if($field == 'gioitinh'){
					    	if($item->$field == 1){
								$value = 'Nam';
							}else{
								$value = 'Nữ';
							}
					    }
					    elseif ($field == 'noicap') {
						    $value = Regions::getListLabel($item->$field);
				        }elseif ($field == 'idbankythuat') {
				        	$value = '';
			        		$bkt = Bankythuat::find()
			        					->joinwith('idthanhviens')
			        					->andWhere(['qli_thanhvien.tv_id' => $item['tv_id']])
			        					->all();
			        		if($bkt){
			        			foreach ($bkt as $k_bkt => $v_bkt) {				        				
			        				$value .= $v_bkt->sohieu . ' - ' . $v_bkt->tenbankythuat . "\n";
			        			}
			        		}
					    }
					    else {
						    $value = $item->$field;
					    }
					    $objPHPExcel->getActiveSheet()->setCellValue($attachs['_'.$cols_a] . $row, $value);

					    $objPHPExcel->getActiveSheet()->getColumnDimension($attachs['_'.$cols_a])->setAutoSize(true);
				    }
				    $col_count++;
			    }

	    	}

	    }

		$thanhVienSearch = isset($request['ThanhvienSearch']) ? $request['ThanhvienSearch'] : [];
	    if (count($thanhVienSearch)) {

		    $ac = $row + 5;
		    $count_new = 0;

		    foreach ( $request['ThanhvienSearch'] as $key => $tv ) {
			    $new = $ac + $count_new;
			    if ($tv != '') {
				    $filter_text = '';
				    if ( isset( $labels[ 'thanhviensearch-' . $key ] ) ) {
					    $filter_text = $labels[ 'thanhviensearch-' . $key ];
				    }
				    if ( $key == 'hoten' ) {

				    } else if ($key == 'gioitinh') {
						if ($tv == 1) {
							$tv = 'Nam';
						} else {
							$tv = 'Nữ';
						}
			        } else if ($key == 'noicap') {
					    $tv = Regions::getListLabel($tv);
			        } else if ($key == 'listbankythuat') {
					    if (count($tv)) {
					    	$bk = $tv;
						    $tv = '';
						    foreach ( $bk as $item ) {
						    	$btk = Bankythuat::findOne($item);
							    $tv .= $btk->tenbankythuat . ', ';
						    }

					    } else {
						    $tv = '';
					    }
				    }
				    if ($count_new == 0) {

				    } else {
					    // $objPHPExcel->getActiveSheet()->insertNewRowBefore( $new, 1 );
				    }

				    $objPHPExcel->getActiveSheet()->setCellValue( 'B' . $new, $filter_text . ': ' . $tv )
				    ;
				    $count_new++;
			    }

		    }
	    }

	    $filename = 'ds-thanh-vien';
	    header('Content-Type: application/vnd.ms-excel');
	    header('Content-Disposition: attachment;filename="' . $filename . '.xls"');
	    header('Cache-Control: max-age=0');

	    $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');

	    $objWriter->save('php://output');
	    exit();

    }


    protected  function tieuchuanquocte($form_gopy = '', $traloi = '',$model = '')
    {
    	$settings = Yii::$app->settings;
    	$list_cau_hoi = $settings->get('list_cau_hoi');
    	$s_gd = Tieuchuan::getGiaidoanQTLabel($form_gopy);

    	$print = '';
    	if(isset($list_cau_hoi[$s_gd]) && is_array($list_cau_hoi[$s_gd])){
    		foreach ($list_cau_hoi[$s_gd] as $k => $v) {
    			$k = strtolower($k); 
    			$print .= "\n";                                               
    			$print .= '<b>'.$v['name'].'</b>'."\n";                 
        if(is_array($v['pa'])){//Nếu có các phương án trả lời 
        	foreach ($v['pa'] as $k_pa => $pa) { 
        		$data_list[$k_pa] = $pa['tieude'];
        		$custom_data[$k_pa] = [
        			'data-c' => $k.'-option-'.$k_pa,
        			'data-p' => $k.'-option',
        		];
        	}   
        	if(!empty($traloi['pa'][$k]['noidung'])) $model->pa[$k]['noidung'] = $traloi['pa'][$k]['noidung'];
        	$print .= '<p><b>Ý kiến: </b>' .($data_list[$model->pa[$k]['noidung']]).'</p>'."\n";
        	foreach ($v['pa'] as $k_pa => $pa) {                                                             
        		if(!empty($pa['comment'])){

        			if(!empty($pa['comment_batbuoc'])){  
        				if(!empty($traloi['comment_batbuoc'][$k][$k_pa]['noidung']) && ($traloi['comment_batbuoc'][$k][$k_pa]['noidung'] != '-')){
        					$model->comment_batbuoc[$k][$k_pa]['noidung'] = $traloi['comment_batbuoc'][$k][$k_pa]['noidung'];
        					$print .= '<b>Nội dung: </b>' .$model->comment_batbuoc[$k][$k_pa]['noidung']."\n";
        				}
        			}else{        
        				if(!empty($traloi['comment'][$k][$k_pa]['noidung']) && ($traloi['comment'][$k][$k_pa]['noidung'] == '-')){
        					$model->comment[$k][$k_pa]['noidung'] = $traloi['comment'][$k][$k_pa]['noidung'];
        					$print .= '<b>Nội dung: </b>' .$model->comment[$k][$k_pa]['noidung']."\n";
        				}
        			}
        		}
        		if(!empty($pa['file'])){                
        			if(!empty($pa['file_batbuoc'])){  
        				if(!empty($traloi['file_batbuoc'][$k][$k_pa]['noidung'])){                
        					$print .= '<a class="danhsachlayykien-file_batbuoc-'.$k.'-'.$k_pa.'-noidung" target="_blank" href="/acp/'.$traloi['file_batbuoc'][$k][$k_pa]['noidung'].'">Tải về</a>';
        				}else{
        					if($view == 'view') echo '<i>Không có file</i>';
        				} 
        			}else{                       
        				if(!empty($traloi['file'][$k][$k_pa]['noidung'])){                
        					$print .= '<a class= target="_blank" href="/acp/'.$traloi['file'][$k][$k_pa]['noidung'].'">Tải về</a>';
        				}else{
        					if($view == 'view') echo '<i>Không có file</i>';
        				}
        			}
        		}  

        		if(!empty($pa['child'])){            
        			foreach ($pa['child'] as $k_child => $child) {

        				$print .= '<div><b>'.$child['name'].'</b></div>';
        				if(is_array($child['pa'])){       
        					foreach ($child['pa'] as $k_pa_child => $pa_child) { 
        						$data_list_child[$k_pa_child] = $pa_child['tieude'];
        						$custom_data_child[$k_pa_child] = [
        							'data-c' => $k.'_'.$k_pa.'-option-'.$k_pa_child,
        							'data-p' => $k.'_'.$k_pa.'-option',
        						];
        					}

        					if(!empty($traloi['pa'][$k]['child'][$k_child]['noidung'])) $model->pa[$k]['child'][$k_child]['noidung'] = $traloi['pa'][$k]['child'][$k_child]['noidung'];
        					$print .= $data_list_child[$model->pa[$k]['child'][$k_child]['noidung']]."\n";

        					foreach ($child['pa'] as $k_pa_child => $pa_child) { 
        						if(!empty($pa_child['comment'])){                                               
        							if(!empty($pa_child['comment_batbuoc'])){ 

        								if(!empty($traloi['comment_batbuoc'][$k]['child'][$k_pa_child]['noidung'])) $model->comment_batbuoc[$k]['child'][$k_pa_child]['noidung'] = $traloi['comment_batbuoc'][$k]['child'][$k_pa_child]['noidung'];
        								$print .= $model->comment_batbuoc[$k]['child'][$k_pa_child]['noidung']."\n";
        							}else{
        								if(!empty($traloi['comment'][$k]['child'][$k_pa_child]['noidung'])) $model->comment[$k]['child'][$k_pa_child]['noidung'] = $traloi['comment'][$k]['child'][$k_pa_child]['noidung'];
        								$print .= $model->comment[$k]['child'][$k_pa_child]['noidung']."\n";
        							}
        						}

        						if(!empty($pa_child['file'])){
        							if(!empty($pa_child['file_batbuoc'])){ 
        								if(!empty($traloi['file_batbuoc'][$k]['child'][$k_pa_child]['noidung'])){
        									$print .= '<a class="danhsachlayykien-file_batbuoc-'.$k.'-child-'.$k_pa_child.'-noidung"  target="_blank" href="/acp/'.$traloi['file_batbuoc'][$k]['child'][$k_pa_child]['noidung'].'">Tải về</a>';
        								}else{
        									if($view == 'view') echo '<i>Không có file</i>';
        								}
        							}else{
        								if(!empty($traloi['file'][$k]['child'][$k_pa_child]['noidung'])){
        									$print .= '<a target="_blank" href="/acp/'.$traloi['file'][$k]['child'][$k_pa_child]['noidung'].'">Tải về</a>';
        								}else{
        									if($view == 'view') echo '<i>Không có file</i>';
        								}
        							}
        						}
        					}
        				}
        			}
        		}
        	}
        }
    }
	}
	return $print;
	}



	public function actionBanKyThuat()
	{
		if(!AcpHelper::check_role('export','Bankythuat')){
    		return $this->redirect(['/']);
    	}

		$searchModel = new BankythuatSearch();
		$request = Yii::$app->request->queryParams;
		$dataProvider = $searchModel->search($request);
		$pages = new Pagination();
		$pages->setPageSize(-1);
		$dataProvider->setPagination($pages);
		$items = $dataProvider->getModels();
		$labels = json_decode($request['filter_texts'], true);
		$cols = $request['cols'];

		$objReader = \PHPExcel_IOFactory::createReader('Excel5');
		$objPHPExcel = $objReader->load(dirname(__FILE__) . "/../templates_excel/ds_ban_ky_thuat.xls" );
		$base = 2;
		$row = $base;


		$attachs = [];

		foreach ( range('A', 'Z') as $count => $item ) {
			$attachs['_'.$count] = $item;
		}

		if (count($items)) {
			foreach ( $items as $count => $item ) {
				$row = $base + $count;
				if ($count == 0) {
				} else {
					// $objPHPExcel->getActiveSheet()->insertNewRowBefore($row, 1);
				}

				$objPHPExcel->getActiveSheet()->setCellValue('A'. $row, ($count + 1));

				$base_col = 1;
				$col_count = 0;
				if ($count == 0) {
					$objPHPExcel->getActiveSheet()->insertNewColumnBefore('B', count($cols) - 1);
				}

				foreach ( $cols as $field => $col ) {
					$cols_a = $base_col + $col_count;
					if (isset($attachs['_'.$cols_a])) {
						if ($row == $base) {
							$objPHPExcel->getActiveSheet()->setCellValue($attachs['_'.$cols_a] . ($row - 1), $col);
						}

						if ($field == 'idtruongban') {
							$truongban = Thanhvien::findOne($item->idtruongban);
							$tentruongban = $truongban ? $truongban->hoten : '';
							$objPHPExcel->getActiveSheet()->setCellValue($attachs['_'.$cols_a] . $row, $tentruongban);
						} else if ($field == 'idthuky') {
						    if(!empty($item->idthuky)){
						        $list_thu_ky = unserialize($item->idthuky);
                                $tenthuky = '';
                                if(is_array($list_thu_ky) && !empty($list_thu_ky)) {
                                    foreach ($list_thu_ky as $v) {
                                        $thuky = Thanhvien::findOne($v);
                                        $tenthuky .= $thuky ? $thuky->hoten . ';' : '';
                                    }
                                }
                            }
                            $objPHPExcel->getActiveSheet()->setCellValue($attachs['_'.$cols_a] . $row, substr($tenthuky,0,-1));

                        } else if ($field == 'banquocte') {
							if($item->banquocte !=''){

								$nv = [];
								if ($item->banquocte != 1 && $item->banquocte != '') {
									$df = json_decode( $item->banquocte, true );
									if(!empty($df)) {
                                        foreach ($df as $item) {
                                            $btk = Bankythuat::findOne($item);
                                            if($btk){
                                            	$nv[] = $btk->sohieu .' - '. $btk->tenbankythuat;
                                            }
                                        }
                                    }
									$bankythuat = implode(', ', $nv);
								} else {
									$bankythuat = '';
								}

							} else {
								$bankythuat = $item->banquocte;
							}

							$objPHPExcel->getActiveSheet()->setCellValue($attachs['_'.$cols_a] . $row, $bankythuat);
						} else {
							$objPHPExcel->getActiveSheet()->setCellValue($attachs['_'.$cols_a] . $row, $item->$field);
						}

						$objPHPExcel->getActiveSheet()->getColumnDimension($attachs['_'.$cols_a])->setAutoSize(true);
					}
					$col_count++;
				}

			}

		}

		$bktSearch = isset($request['BankythuatSearch']) ? $request['BankythuatSearch'] : [];

		if (count($bktSearch)) {
			$ac = $row + 5;
			$count_new = 0;
			foreach ( $request['BankythuatSearch'] as $key => $bkt ) {
				$new = $ac + $count_new;
				$filter_text = '';
				if ( isset( $labels[ 'bankythuatsearch-' . $key ] ) ) {
					$filter_text = $labels[ 'bankythuatsearch-' . $key ];
				}

				if ($bkt != '') {
					if ($key == 'idtruongban') {
						$tv = Thanhvien::findOne($bkt);
						$bkt = $tv->hoten;

					} else if ($key == 'idthuky') {
						$tv = Thanhvien::findOne($bkt);
						$bkt = $tv->hoten;
					}

					if ($count_new == 0) {

					} else {
						// $objPHPExcel->getActiveSheet()->insertNewRowBefore( $new, 1 );
					}

					$objPHPExcel->getActiveSheet()->setCellValue( 'A' . $new, $filter_text . ': ' . $bkt )
					;
					$count_new++;
				}

			}
		}


		$filename = 'ds-ban-ky-thuat';
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="' . $filename . '.xls"');
		header('Cache-Control: max-age=0');

		$objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');

		$objWriter->save('php://output');
		exit();
	}

	public function actionBanKyThuatQt()
	{
		if(!AcpHelper::check_role('export_qt','Bankythuat')){
    		return $this->redirect(['/']);
    	}

		$searchModel = new BankythuatSearch();
		$request = Yii::$app->request->queryParams;
		$dataProvider = $searchModel->search_quocte($request);
		$pages = new Pagination();
		$pages->setPageSize(-1);
		$dataProvider->setPagination($pages);
		$items = $dataProvider->getModels();
		$labels = json_decode($request['filter_texts'], true);
		$cols = $request['cols'];

		$objReader = \PHPExcel_IOFactory::createReader('Excel5');
		$objPHPExcel = $objReader->load(dirname(__FILE__) . "/../templates_excel/export_bkt_qt.xls" );
		$base = 2; //Dòng mặc định ban đầu
		$row = $base;


		$attachs = [];

		foreach ( range('A', 'Z') as $count => $item ) {
			$attachs['_'.$count] = $item;
		}

		if (count($items)) {
			foreach ( $items as $count => $item ) {
				$row = $base + $count;
				if ($count == 0) {
				} else {
					// $objPHPExcel->getActiveSheet()->insertNewRowBefore($row, 1);
				}

				$objPHPExcel->getActiveSheet()->setCellValue('A'. $row, ($count + 1));

				$base_col = 1; //Cột mặc định
				$col_count = 0;
				if ($count == 0) {
					$objPHPExcel->getActiveSheet()->insertNewColumnBefore('B', count($cols) - 1);
				}

				foreach ( $cols as $field => $col ) {
					$cols_a = $base_col + $col_count;
					if (isset($attachs['_'.$cols_a])) {
						if ($row == $base) {
							$objPHPExcel->getActiveSheet()->setCellValue($attachs['_'.$cols_a] . ($row - 1), $col);
						}

						if ($field == 'idtruongban') {
							$truongban = Thanhvien::findOne($item->idtruongban);
							$tentruongban = $truongban ? $truongban->hoten : '';
							$objPHPExcel->getActiveSheet()->setCellValue($attachs['_'.$cols_a] . $row, $tentruongban);
						}
						elseif ($field == 'tucach') {
							$tucach = Bankythuat::getTucachLabelExport($item->tucach);
							$objPHPExcel->getActiveSheet()->setCellValue($attachs['_'.$cols_a] . $row, $tucach);
						} 
						else {
							$objPHPExcel->getActiveSheet()->setCellValue($attachs['_'.$cols_a] . $row, $item->$field);
						}

						$objPHPExcel->getActiveSheet()->getColumnDimension($attachs['_'.$cols_a])->setAutoSize(true);
					}
					$col_count++;
				}

			}

		}

		$bktSearch = isset($request['BankythuatSearch']) ? $request['BankythuatSearch'] : [];

		if (count($bktSearch)) {
			$ac = $row + 5;
			$count_new = 0;
			foreach ( $request['BankythuatSearch'] as $key => $bkt ) {
				$new = $ac + $count_new;
				$filter_text = '';
				if ( isset( $labels[ 'bankythuatsearch-' . $key ] ) ) {
					$filter_text = $labels[ 'bankythuatsearch-' . $key ];
				}

				if ($bkt != '') {				

					if ($count_new == 0) {

					} else {
						// $objPHPExcel->getActiveSheet()->insertNewRowBefore( $new, 1 );
					}

					$objPHPExcel->getActiveSheet()->setCellValue( 'A' . $new, $filter_text . ': ' . $bkt )
					;
					$count_new++;
				}

			}
		}


		$filename = 'export-ban-ky-thuat-qt';
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="' . $filename . '.xls"');
		header('Cache-Control: max-age=0');

		$objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');

		$objWriter->save('php://output');
		exit();
	}


	public function actionIcs()
	{
		if(!AcpHelper::check_role('export','Ics')){
    		return $this->redirect(['/']);
    	}

		$searchModel = new IcsSearch();
		$request = Yii::$app->request->queryParams;
		$dataProvider = $searchModel->search($request);

		$dataProvider->setSort([
			'defaultOrder' => ['maphanloai'=>SORT_ASC]
		 ]);

		$pages = new Pagination();
		$pages->setPageSize(-1);
		$dataProvider->setPagination($pages);
		$items = $dataProvider->getModels();
		$labels = json_decode($request['filter_texts'], true);
		$cols = $request['cols'];

		$objReader = \PHPExcel_IOFactory::createReader('Excel5');
		$objPHPExcel = $objReader->load(dirname(__FILE__) . "/../templates_excel/export_ics.xls" );
		$base = 2; //Dòng mặc định ban đầu
		$row = $base;


		$attachs = [];

		foreach ( range('A', 'Z') as $count => $item ) {
			$attachs['_'.$count] = $item;
		}

		if (count($items)) {
			foreach ( $items as $count => $item ) {
				$row = $base + $count;
				if ($count == 0) {
				} else {
					// $objPHPExcel->getActiveSheet()->insertNewRowBefore($row, 1);
				}

				// $objPHPExcel->getActiveSheet()->setCellValue('A'. $row, ($count + 1));

				$base_col = 0; //Cột mặc định
				$col_count = 0;
				if ($count == 0) {
					// $objPHPExcel->getActiveSheet()->insertNewColumnBefore('B', count($cols) - 1);
				}

				foreach ( $cols as $field => $col ) {
					$cols_a = $base_col + $col_count;
					if (isset($attachs['_'.$cols_a])) {
						if ($row == $base) {
							$objPHPExcel->getActiveSheet()->setCellValue($attachs['_'.$cols_a] . ($row - 1), $col);
						}
						
						// $objPHPExcel->getActiveSheet()->setCellValue($attachs['_'.$cols_a] . $row, ''.(string)$item->$field.'' );
						// $objPHPExcel->getActiveSheet()->getStyle($attachs['_'.$cols_a] . $row)->getNumberFormat()->setFormatCode('s');

						$objPHPExcel->getActiveSheet()->setCellValueExplicit($attachs['_'.$cols_a] . $row, $item->$field, 's');

						$objPHPExcel->getActiveSheet()->getColumnDimension($attachs['_'.$cols_a])->setAutoSize(true);
					}
					$col_count++;
				}

			}

		}

		$bktSearch = isset($request['IcsSearch']) ? $request['IcsSearch'] : [];

		if (count($bktSearch)) {
			$ac = $row + 5;
			$count_new = 0;
			foreach ( $request['IcsSearch'] as $key => $bkt ) {
				$new = $ac + $count_new;
				$filter_text = '';
				if ( isset( $labels[ 'IcsSearch-' . $key ] ) ) {
					$filter_text = $labels[ 'IcsSearch-' . $key ];
				}

				if ($bkt != '') {				

					if ($count_new == 0) {

					} else {
						// $objPHPExcel->getActiveSheet()->insertNewRowBefore( $new, 1 );
					}

					$objPHPExcel->getActiveSheet()->setCellValue( 'A' . $new, $filter_text . ': ' . $bkt )
					;
					$count_new++;
				}

			}
		}


		$filename = 'export-ics';
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="' . $filename . '.xls"');
		header('Cache-Control: max-age=0');

		$objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');

		$objWriter->save('php://output');
		exit();
	}

	public function actionTieuChuanPopup() {
		if(!AcpHelper::check_role('export','Tieuchuan')){
    		return $this->redirect(['/']);
    	}
		return $this->renderAjax('tieuchuan-filter', []);
	}

	public function actionBanKyThuatPopup() {
		if(!AcpHelper::check_role('export','Bankythuat')){
    		return $this->redirect(['/']);
    	}
		return $this->renderAjax('bankythuat-filter', []);
	}

	public function actionBanKyThuatQtPopup() {
		if(!AcpHelper::check_role('export_qt','Bankythuat')){
    		return $this->redirect(['/']);
    	}
		return $this->renderAjax('bankythuat-qt-filter', []);
	}

	public function actionIcsPopup() {
		if(!AcpHelper::check_role('export','Ics')){
    		return $this->redirect(['/']);
    	}
		return $this->renderAjax('ics-filter', []);
	}

	public function actionThanhVienPopup() {
		if(!AcpHelper::check_role('export','Thanhvien')){
    		return $this->redirect(['/']);
    	}
		return $this->renderAjax('thanhvien-filter', []);
	}


	public function actionDuAnPopup() {
		if(!AcpHelper::check_role('export','Duan')){
    		return $this->redirect(['/']);
    	}
		return $this->renderAjax('duan-filter', []);
	}

	public function actionDuAn()
	{
		if(!AcpHelper::check_role('export','Duan')){
    		return $this->redirect(['/']);
    	}

		$searchModel = new DuanSearch(
			// [
			// 	'tinhtrangsuadoi' => '0',
			// 	// 'nambanhanh' => NULL,
			// ]
		);
		$request = Yii::$app->request->queryParams;
		$dataProvider = $searchModel->search_duan($request);
		$dataProvider->setSort([
			'defaultOrder' => ['da_id'=>SORT_DESC]
		]);

		$pages = new Pagination();
		$pages->setPageSize(-1);
		$dataProvider->setPagination($pages);
		$items = $dataProvider->getModels();
		$labels = json_decode($request['filter_texts'], true);
		$cols = $request['cols'];

		$objReader = \PHPExcel_IOFactory::createReader('Excel5');
		$objPHPExcel = $objReader->load(dirname(__FILE__) . "/../templates_excel/export_du_an.xls" );
		$base = 3;
		$row = $base;

		$attachs = [];

		foreach ( range('A', 'Z') as $count => $item ) {
			$attachs['_'.$count] = $item;
		}

		if (count($items)) {
			foreach ( $items as $count => $item ) {

				$row = $base + $count;
				if ( $count == 0 ) {
				} else {
					// $objPHPExcel->getActiveSheet()->insertNewRowBefore( $row, 1 );
				}

				$objPHPExcel->getActiveSheet()->setCellValue( 'A' . $row, ( $count + 1 ));

				$base_col = 1;
				$col_count = 0;
				if ($count == 0) {
					$objPHPExcel->getActiveSheet()->insertNewColumnBefore('B', count($cols) - 1);
				}

				foreach ( $cols as $field => $col ) {
					$cols_a = $base_col + $col_count;
					if (isset($attachs['_'.$cols_a])) {
						$giatri = '';
						if ($row == $base) {
							$col = mb_strtoupper($col, 'UTF-8');
							$objPHPExcel->getActiveSheet()->setCellValue($attachs['_'.$cols_a] . ($row - 1), $col);
							$objPHPExcel->getActiveSheet()->getColumnDimension($attachs['_'.$cols_a])->setAutoSize(true);
						}
						if ($field == 'kehoachnam') {
							$giatri = Kehoachnam::getTen($item->kehoachnam);
						}
						elseif ($field == 'coquanbiensoan') {
							$giatri = Tieuchuan::getCoquanbiensoanLabel($item->coquanbiensoan);
						}

						elseif ($field == 'quyetdinh') {
							$qd = json_decode($item->quyetdinh,true);
                    		if(!empty($qd['soquyetdinh'])) $giatri = $qd['soquyetdinh'];
						}

						elseif ($field == 'hopdong') {
							$hd = json_decode($item->hopdong,true);
                    		if(!empty($hd['sohopdong'])) $giatri = $hd['sohopdong'];
						}

						elseif ($field == 'tiendo') {
							$giatri = Duan::getTiendoLabel($item->da_id);
							// $giatri = 'Tien do';
						}

						else {
							$giatri = $item->$field;
						}

						$styleArray = array(
					    'font'  => array(
					        'name'  => 'Arial'
					    ));
						
						$objPHPExcel->getActiveSheet()->setCellValue($attachs['_'.$cols_a] . $row, $giatri);
						
						// $objPHPExcel->getDefaultStyle()->applyFromArray($styleArray);
						$objPHPExcel->getActiveSheet()->getStyle($attachs['_'.$cols_a] . $row)->applyFromArray($styleArray);
						$objPHPExcel->getActiveSheet()->getStyle($attachs['_'.$cols_a] . $row)->getAlignment()->setWrapText(true);
						
					}
					$col_count++;
				}

			}

		}

		// die;

		$tcSearch = isset($request['DuanSearch']) ? $request['DuanSearch'] : [];

		if (count($tcSearch)) {
			$ac = $row + 6;
			$count_new = 0;
			foreach ( $request['DuanSearch'] as $key => $duan_search ) {
				$new = $ac + $count_new;
				if ($duan_search != '') {

					$filter_text = '';
					if ( isset( $labels[ 'duansearch-' . $key ] ) ) {
						$filter_text = $labels[ 'duansearch-' . $key ];
					}

					if ($key == 'kehoachnam') {
						$duan_search = Kehoachnam::getTen($item->kehoachnam);
					}
					elseif ($key == 'coquanbiensoan') {
						$duan_search = Tieuchuan::getCoquanbiensoanLabel($item->coquanbiensoan);
					}


					if ($count_new == 0) {

					} else {
						// $objPHPExcel->getActiveSheet()->insertNewRowBefore( $new, 1 );
					}

					$objPHPExcel->getActiveSheet()->setCellValue( 'A' . $new, $filter_text . ': ' . $duan_search )
						;
					$count_new++;
				}

			}
		}

		$filename = 'export-du-an';
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="' . $filename . '.xls"');
		header('Cache-Control: max-age=0');

		$objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');

		ob_end_clean();
		$objWriter->save('php://output');
		exit();

	}


	public function actionHoSoPopup() {
		if(!AcpHelper::check_role('export_hoso','Duan')){
    		return $this->redirect(['/']);
    	}
		return $this->renderAjax('hoso-filter', []);
	}

	public function actionHoSo()
	{
		if(!AcpHelper::check_role('export_hoso','Duan')){
    		return $this->redirect(['/']);
    	}

		$searchModel = new DuanSearch();
		$request = Yii::$app->request->queryParams;
		$dataProvider = $searchModel->search_hoso($request);
		$dataProvider->setSort([
			'defaultOrder' => ['da_id'=>SORT_DESC]
		]);

		$pages = new Pagination();
		$pages->setPageSize(-1);
		$dataProvider->setPagination($pages);
		$items = $dataProvider->getModels();
		$labels = json_decode($request['filter_texts'], true);
		$cols = $request['cols'];

		$objReader = \PHPExcel_IOFactory::createReader('Excel5');
		$objPHPExcel = $objReader->load(dirname(__FILE__) . "/../templates_excel/export_ho_so.xls" );
		$base = 3;
		$row = $base;

		$attachs = [];

		foreach ( range('A', 'Z') as $count => $item ) {
			$attachs['_'.$count] = $item;
		}

		if (count($items)) {
			foreach ( $items as $count => $item ) {

				$row = $base + $count;
				if ( $count == 0 ) {
				} else {
					// $objPHPExcel->getActiveSheet()->insertNewRowBefore( $row, 1 );
				}

				$objPHPExcel->getActiveSheet()->setCellValue( 'A' . $row, ( $count + 1 ));

				$base_col = 1;
				$col_count = 0;
				if ($count == 0) {
					$objPHPExcel->getActiveSheet()->insertNewColumnBefore('B', count($cols) - 1);
				}

				foreach ( $cols as $field => $col ) {
					$cols_a = $base_col + $col_count;
					if (isset($attachs['_'.$cols_a])) {
						$giatri = '';
						if ($row == $base) {
							$col = mb_strtoupper($col, 'UTF-8');
							$objPHPExcel->getActiveSheet()->setCellValue($attachs['_'.$cols_a] . ($row - 1), $col);
							$objPHPExcel->getActiveSheet()->getColumnDimension($attachs['_'.$cols_a])->setAutoSize(true);
						}
						
						if ($field == 'coquanbiensoan') {
							$giatri = Tieuchuan::getCoquanbiensoanLabel($item->coquanbiensoan);
						}

						elseif ($field == 'nam') {
							$arr = explode('-',$item->sohieu);
							$giatri = $arr[0];
						}

						elseif ($field == 'sohieutieuchuan') {
							$tc = Tieuchuan::find()
                                    ->select(['idduan','sohieu','tc_id'])
                                    ->andWhere(['idduan' => $item->da_id])->all();
		                    $html = '';
		                    if($tc){
		                        foreach ($tc as $k => $v) {
		                            if(!empty($v->sohieu)){
		                                $html .= Html::encode($v->sohieu). "\n";
		                            }
		                        }
		                    }
							$giatri = $html;
						}

						elseif ($field == 'quyetdinh') {

							$tc = Tieuchuan::find()
                                    ->select(['idduan','quyetdinhbanhanh','tc_id'])
                                    ->andWhere(['idduan' => $item->da_id])->all();
		                    $html = '';
		                    $arr_qd = [];
		                    if($tc){
		                        foreach ($tc as $k => $v) {
		                            if(!empty($v->quyetdinhbanhanh)){
		                                $qd = json_decode($v->quyetdinhbanhanh,true);                                
		                                if(!empty($qd['soquyetdinh'])) if(!in_array($qd['soquyetdinh'],$arr_qd)){
		                                    $arr_qd[] = $qd['soquyetdinh'];
		                                    $html .= $qd['soquyetdinh'] . ' ' .$qd['ngaycongbo'] . "\n";
		                                }
		                            }
		                        }
		                    }
		                    $giatri = $html;
						}

						else {
							$giatri = $item->$field;
						}

						$styleArray = array(
					    'font'  => array(
					        'name'  => 'Arial'
					    ));
						
						$objPHPExcel->getActiveSheet()->setCellValue($attachs['_'.$cols_a] . $row, $giatri);
						
						// $objPHPExcel->getDefaultStyle()->applyFromArray($styleArray);
						$objPHPExcel->getActiveSheet()->getStyle($attachs['_'.$cols_a] . $row)->applyFromArray($styleArray);
						$objPHPExcel->getActiveSheet()->getStyle($attachs['_'.$cols_a] . $row)->getAlignment()->setWrapText(true);
						
					}
					$col_count++;
				}

			}

		}

		// die;

		$tcSearch = isset($request['DuanSearch']) ? $request['DuanSearch'] : [];

		if (count($tcSearch)) {
			$ac = $row + 6;
			$count_new = 0;
			foreach ( $request['DuanSearch'] as $key => $duan_search ) {
				$new = $ac + $count_new;
				if ($duan_search != '') {

					$filter_text = '';
					if ( isset( $labels[ 'duansearch-' . $key ] ) ) {
						$filter_text = $labels[ 'duansearch-' . $key ];
					}

					if ($key == 'kehoachnam') {
						$duan_search = Kehoachnam::getTen($item->kehoachnam);
					}
					elseif ($key == 'coquanbiensoan') {
						$duan_search = Tieuchuan::getCoquanbiensoanLabel($item->coquanbiensoan);
					}


					if ($count_new == 0) {

					} else {
						// $objPHPExcel->getActiveSheet()->insertNewRowBefore( $new, 1 );
					}

					$objPHPExcel->getActiveSheet()->setCellValue( 'A' . $new, $filter_text . ': ' . $duan_search )
						;
					$count_new++;
				}

			}
		}

		$filename = 'export-du-an';
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="' . $filename . '.xls"');
		header('Cache-Control: max-age=0');

		$objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');

		ob_end_clean();
		$objWriter->save('php://output');
		exit();

	}


	public function actionTieuChuan()
	{
		if(!AcpHelper::check_role('export','Tieuchuan')){
    		return $this->redirect(['/']);
    	}

		$searchModel = new TieuchuanSearch(
			[
				'tinhtrangsuadoi' => '0',
				// 'nambanhanh' => NULL,
			]
		);
		$request = Yii::$app->request->queryParams;
		$dataProvider = $searchModel->search($request);
		$dataProvider->setSort([
			'defaultOrder' => ['tc_id'=>SORT_DESC]
		]);

		$pages = new Pagination();
		$pages->setPageSize(-1);
		$dataProvider->setPagination($pages);
		$items = $dataProvider->getModels();
		$labels = json_decode($request['filter_texts'], true);
		$cols = $request['cols'];

		$objReader = \PHPExcel_IOFactory::createReader('Excel5');
		$objPHPExcel = $objReader->load(dirname(__FILE__) . "/../templates_excel/ds_tieu_chuan.xls" );
		$base = 2;
		$row = $base;

		$attachs = [];

		foreach ( range('A', 'Z') as $count => $item ) {
			$attachs['_'.$count] = $item;
		}

		if (count($items)) {
			foreach ( $items as $count => $item ) {

				$row = $base + $count;
				if ( $count == 0 ) {
				} else {
					// $objPHPExcel->getActiveSheet()->insertNewRowBefore( $row, 1 );
				}

				$objPHPExcel->getActiveSheet()->setCellValue( 'A' . $row, ( $count + 1 ) );

				$base_col = 1;
				$col_count = 0;
				if ($count == 0) {
					$objPHPExcel->getActiveSheet()->insertNewColumnBefore('B', count($cols) - 1);
				}

				foreach ( $cols as $field => $col ) {
					$cols_a = $base_col + $col_count;
					if (isset($attachs['_'.$cols_a])) {
						if ($row == $base) {
							$objPHPExcel->getActiveSheet()->setCellValue($attachs['_'.$cols_a] . ($row - 1), $col);
						}

						if ($field == 'tinhtrang') {
							$objPHPExcel->getActiveSheet()->setCellValue($attachs['_'.$cols_a] . $row, Tieuchuan::getTinhTrangLabel( $item->tinhtrang ));
						} if ($field == 'coquanxaydung') {
						    $bkt = Bankythuat::findOne($item->coquanxaydung);
						    if(isset($bkt) && !empty($bkt))
							    $objPHPExcel->getActiveSheet()->setCellValue($attachs['_'.$cols_a] . $row, $bkt->sohieu);
						    else
                                $objPHPExcel->getActiveSheet()->setCellValue($attachs['_'.$cols_a] . $row, $item->coquanxaydung);
						} elseif ($field == 'sohieu') {
							$sohieu =  $item->sohieu;
							// $sohieu =  $item->sohieu .':'.$item->nambanhanh;
							// if(empty($item->nambanhanh)){
							// 	$sohieu = $item->sohieu;
							// }
							$objPHPExcel->getActiveSheet()->setCellValue($attachs['_'.$cols_a] . $row, $sohieu);
						}
						elseif ($field == 'gia'){ 							
							$gia =  $item->setGia($item->tc_id);
							$objPHPExcel->getActiveSheet()->setCellValue($attachs['_'.$cols_a] . $row, $gia);
							$objPHPExcel->getActiveSheet()->getStyle($attachs['_'.$cols_a] . $row)->getNumberFormat()->setFormatCode('@');
                        }
                        elseif ($field == 'mucdo'){ 							
							$mucdo =  $item->getMucdoLabel($item->tc_id);
                            $objPHPExcel->getActiveSheet()->setCellValue($attachs['_'.$cols_a] . $row, $mucdo);
                        }

                        elseif ($field == 'quyetdinhbanhanh'){
                        	$qd = json_decode($item->quyetdinhbanhanh);
                        	$return_qd = '';
                        	if(!empty($qd->ngaycongbo)) $return_qd .= date('Y-m-d',strtotime($qd->ngaycongbo)).',';
                        	if(!empty($qd->soquyetdinh)) $return_qd .= $qd->soquyetdinh;
							// $mucdo =  $item->getMucdoLabel($item->tc_id);
                            $objPHPExcel->getActiveSheet()->setCellValue($attachs['_'.$cols_a] . $row, $return_qd);
                        }

						elseif ($field == 'chisophanloai') {
							if ($item->chisophanloai == '') {
								$chiso = '';
							} else {
								$test = [];
								$chisophanloai = json_decode($item->chisophanloai, true);
								if(is_array($chisophanloai)){							
									foreach ($chisophanloai as $ac ) {
										$test[] = $ac;
										// echo $ac.'<br/>';
									}
								}			
								$chiso = implode('* ', $test);
							}
							$chiso = $chiso . ' ';

							$objPHPExcel->getActiveSheet()->getStyle($attachs['_'.$cols_a] . $row)->getNumberFormat()->setFormatCode('@');

							$objPHPExcel->getActiveSheet()->setCellValue($attachs['_'.$cols_a] . $row, $chiso);

						} else {
							$objPHPExcel->getActiveSheet()->setCellValue($attachs['_'.$cols_a] . $row, $item->$field);
						}

						$objPHPExcel->getActiveSheet()->getColumnDimension($attachs['_'.$cols_a])->setAutoSize(true);
					}
					$col_count++;
				}

			}

		}

		// die;

		$tcSearch = isset($request['TieuchuanSearch']) ? $request['TieuchuanSearch'] : [];

		if (count($tcSearch)) {
			$ac = $row + 6;
			$count_new = 0;
			foreach ( $request['TieuchuanSearch'] as $key => $tieuchuan_search ) {
				$new = $ac + $count_new;
				if ($tieuchuan_search != '') {

					$filter_text = '';
					if ( isset( $labels[ 'tieuchuansearch-' . $key ] ) ) {
						$filter_text = $labels[ 'tieuchuansearch-' . $key ];
					}

					if ($key == 'chisophanloai') {
						$bk = $tieuchuan_search;
						$tieuchuan_search = '';
						if (count($bk)) {
							$it = [];
							foreach ($bk as $item ) {
								$it[] = $item;
							}
							$tieuchuan_search = implode(',', $it);
						}
					} else if ($key == 'idbankythuat') {
						$tieuchuan_search = Thanhvien::findOne($tieuchuan_search)->hoten;
					}
					if ($count_new == 0) {

					} else {
						// $objPHPExcel->getActiveSheet()->insertNewRowBefore( $new, 1 );
					}

					$objPHPExcel->getActiveSheet()->setCellValue( 'B' . $new, $filter_text . ': ' . $tieuchuan_search )
						;
					$count_new++;
				}

			}
		}

		$filename = 'ds-tieu-chuan';
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="' . $filename . '.xls"');
		header('Cache-Control: max-age=0');

		$objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');

		$objWriter->save('php://output');
		exit();

	}

//Dơn hang
	public function actionDonHangPopup() {
		if(!AcpHelper::check_role('export_donhang','Member')){
    		return $this->redirect(['/']);
    	}
		return $this->renderAjax('donhang-filter', []);
	}

		public function actionDonHang()
	{
		if(!AcpHelper::check_role('export_donhang','Member')){
    		return $this->redirect(['/']);
    	}
		$searchModel = new OrderSearch();
		$request = Yii::$app->request->queryParams;
		$dataProvider = $searchModel->search($request);
		$dataProvider->setSort([
			'defaultOrder' => ['order_id'=>SORT_DESC]
		]);

		$pages = new Pagination();
		$pages->setPageSize(-1);
		$dataProvider->setPagination($pages);
		$items = $dataProvider->getModels();
		$labels = json_decode($request['filter_texts'], true);
		$cols = $request['cols'];

		$objReader = \PHPExcel_IOFactory::createReader('Excel5');
		$objPHPExcel = $objReader->load(dirname(__FILE__) . "/../templates_excel/export_don_hang.xls" );
		$base = 3;
		$row = $base;

		$attachs = [];

		foreach ( range('A', 'Z') as $count => $item ) {
			$attachs['_'.$count] = $item;
		}

		

		if (count($items)) {
			foreach ( $items as $count => $item ) {
				$row = $base + $count;
				if ( $count == 0 ) {
				} else {
					// $objPHPExcel->getActiveSheet()->insertNewRowBefore( $row, 1 );
				}
				$objPHPExcel->getActiveSheet()->setCellValue( 'A' . $row, ( $count + 1 ) );

		// 		echo '<pre>';
		// print_r($item);
		// echo '</pre>';


				$base_col = 1;
				$col_count = 0;
				if ($count == 0) {
					$objPHPExcel->getActiveSheet()->insertNewColumnBefore('B', count($cols) - 1);
				}
				
				foreach ( $cols as $field => $col ) {
					$cols_a = $base_col + $col_count;
					if (isset($attachs['_'.$cols_a])) {
						if ($row == $base) {
							$objPHPExcel->getActiveSheet()->setCellValue($attachs['_'.$cols_a] . ($row - 1), $col);
						}
						$giatri = $item->$field;
							
						if ($field == 'order_id') {
							$giatri = '#' . $item->$field;
						}
						elseif ($field == 'item') {
							// $ds_item = json_decode($giatri,true);
							// $giatri = '';
							// if(is_array($ds_item)) foreach ($ds_item as $k => $v_item) {
							// 	$tc = Tieuchuan::find()->andWhere(['tc_id' => $k])->one();
							// 	if($tc) if(!empty($v_item['dongia']) && !empty($v_item['soluong']))
							// 	$giatri .= $tc->sohieu . '    ' . number_format($v_item['dongia']) . 'đ x '. $v_item['soluong'] . "\n";
							// }							
						}
						elseif ($field == 'total') {
							$giatri = number_format($item->$field). 'đ';
						}
						elseif ($field == 'tinhtrang') {
							$giatri = Order::getTinhtrangLabel($item->$field);
						}
						

                        $objPHPExcel->getActiveSheet()->setCellValue($attachs['_'.$cols_a] . $row, $giatri);

						$objPHPExcel->getActiveSheet()->getColumnDimension($attachs['_'.$cols_a])->setAutoSize(true);
					}
					$col_count++;
				}

			}

		}

		// die;

		$tcSearch = isset($request['MemberSearch']) ? $request['MemberSearch'] : [];

		if (count($tcSearch)) {
			$ac = $row + 6;
			$count_new = 0;
			foreach ( $request['MemberSearch'] as $key => $tieuchuan_search ) {
				$new = $ac + $count_new;
				if ($tieuchuan_search != '') {

					$filter_text = '';
					if ( isset( $labels[ 'membersearch-' . $key ] ) ) {
						$filter_text = $labels[ 'membersearch-' . $key ];
					}

					if ($count_new == 0) {

					} else {
						// $objPHPExcel->getActiveSheet()->insertNewRowBefore( $new, 1 );
					}

					$objPHPExcel->getActiveSheet()->setCellValue( 'A' . $new, $filter_text . ': ' . $tieuchuan_search )
						;
					$count_new++;
				}

			}
		}

		$filename = 'ds-don-hang';
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="' . $filename . '.xls"');
		header('Cache-Control: max-age=0');

		$objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');

		$objWriter->save('php://output');
		exit();

	}




//Member
	public function actionKhachHangPopup() {
		if(!AcpHelper::check_role('export','Member')){
    		return $this->redirect(['/']);
    	}
		return $this->renderAjax('khachhang-filter', []);
	}

	public function actionKhachHang()
	{
		if(!AcpHelper::check_role('export','Member')){
    		return $this->redirect(['/']);
    	}
		$searchModel = new MemberSearch();
		$request = Yii::$app->request->queryParams;
		$dataProvider = $searchModel->search($request);
		$dataProvider->setSort([
			'defaultOrder' => ['user_id'=>SORT_DESC]
		]);

		$pages = new Pagination();
		$pages->setPageSize(-1);
		$dataProvider->setPagination($pages);
		$items = $dataProvider->getModels();
		$labels = json_decode($request['filter_texts'], true);
		$cols = $request['cols'];

		$objReader = \PHPExcel_IOFactory::createReader('Excel5');
		$objPHPExcel = $objReader->load(dirname(__FILE__) . "/../templates_excel/export_khach_hang.xls" );
		$base = 3;
		$row = $base;

		$attachs = [];

		foreach ( range('A', 'Z') as $count => $item ) {
			$attachs['_'.$count] = $item;
		}

		if (count($items)) {
			foreach ( $items as $count => $item ) {
				$row = $base + $count;
				if ( $count == 0 ) {
				} else {
					// $objPHPExcel->getActiveSheet()->insertNewRowBefore( $row, 1 );
				}
				$objPHPExcel->getActiveSheet()->setCellValue( 'A' . $row, ( $count + 1 ) );

				$base_col = 1;
				$col_count = 0;
				if ($count == 0) {
					$objPHPExcel->getActiveSheet()->insertNewColumnBefore('B', count($cols) - 1);
				}

				foreach ( $cols as $field => $col ) {
					$cols_a = $base_col + $col_count;
					if (isset($attachs['_'.$cols_a])) {
						if ($row == $base) {
							$objPHPExcel->getActiveSheet()->setCellValue($attachs['_'.$cols_a] . ($row - 1), $col);
						}
						$giatri = $item->$field;
									

                        $objPHPExcel->getActiveSheet()->setCellValue($attachs['_'.$cols_a] . $row, $giatri);

						$objPHPExcel->getActiveSheet()->getColumnDimension($attachs['_'.$cols_a])->setAutoSize(true);
					}
					$col_count++;
				}

			}

		}

		// die;

		$tcSearch = isset($request['MemberSearch']) ? $request['MemberSearch'] : [];

		if (count($tcSearch)) {
			$ac = $row + 6;
			$count_new = 0;
			foreach ( $request['MemberSearch'] as $key => $tieuchuan_search ) {
				$new = $ac + $count_new;
				if ($tieuchuan_search != '') {

					$filter_text = '';
					if ( isset( $labels[ 'membersearch-' . $key ] ) ) {
						$filter_text = $labels[ 'membersearch-' . $key ];
					}

					if ($count_new == 0) {

					} else {
						// $objPHPExcel->getActiveSheet()->insertNewRowBefore( $new, 1 );
					}

					$objPHPExcel->getActiveSheet()->setCellValue( 'A' . $new, $filter_text . ': ' . $tieuchuan_search )
						;
					$count_new++;
				}

			}
		}

		$filename = 'ds-khach-hang';
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="' . $filename . '.xls"');
		header('Cache-Control: max-age=0');

		$objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');

		$objWriter->save('php://output');
		exit();

	}














//Quoc te

	public function actionTieuChuanQuocTePopup() {
		if(!AcpHelper::check_role('export','Tieuchuanquocte')){
    		return $this->redirect(['/']);
    	}
		return $this->renderAjax('tieuchuanquocte-filter', []);
	}

	public function actionTieuChuanQuocTe()
	{
		if(!AcpHelper::check_role('export','Tieuchuanquocte')){
    		return $this->redirect(['/']);
    	}
		$searchModel = new TieuchuanSearch();
		$request = Yii::$app->request->queryParams;
		$dataProvider = $searchModel->search_tcqt($request);
		$dataProvider->setSort([
			'defaultOrder' => ['tc_id'=>SORT_DESC]
		]);

		$pages = new Pagination();
		$pages->setPageSize(-1);
		$dataProvider->setPagination($pages);
		$items = $dataProvider->getModels();
		$labels = json_decode($request['filter_texts'], true);
		$cols = $request['cols'];

		$objReader = \PHPExcel_IOFactory::createReader('Excel5');
		$objPHPExcel = $objReader->load(dirname(__FILE__) . "/../templates_excel/ds_tieu_chuan_qt.xls" );
		$base = 2;
		$row = $base;

		$attachs = [];

		foreach ( range('A', 'Z') as $count => $item ) {
			$attachs['_'.$count] = $item;
		}

		if (count($items)) {
			foreach ( $items as $count => $item ) {
				$row = $base + $count;
				if ( $count == 0 ) {
				} else {
					// $objPHPExcel->getActiveSheet()->insertNewRowBefore( $row, 1 );
				}
				$objPHPExcel->getActiveSheet()->setCellValue( 'A' . $row, ( $count + 1 ) );

				$base_col = 1;
				$col_count = 0;
				if ($count == 0) {
					$objPHPExcel->getActiveSheet()->insertNewColumnBefore('B', count($cols) - 1);
				}

				foreach ( $cols as $field => $col ) {
					$cols_a = $base_col + $col_count;
					if (isset($attachs['_'.$cols_a])) {
						if ($row == $base) {
							$objPHPExcel->getActiveSheet()->setCellValue($attachs['_'.$cols_a] . ($row - 1), $col);
						}
						$giatri = $item->$field;

						if ($field == 'idduan') {							
							$giatri = Duan::getSohieu($item->idduan);
						}
						elseif ($field == 'giaidoan') {
						    $giatri = Tieuchuan::getGiaidoanQTLabel($item->groupduan);
						}
						elseif ($field == 'bktqt') {
							$da = Duan::findOne(['da_id' => $item->idduan]);
							if($da) $bktqt = Bankythuat::getSohieu($da->tenduan);							       
							$giatri = $bktqt;							
						}						
                        elseif ($field == 'bktvn'){							
							$da = Duan::findOne(['da_id' => $item->idduan]);
							$giatri = '';
							if($da) $giatri = Tieuchuan::getCoquanbiensoanLabel($da->coquanbiensoan);					
						}
						elseif ($field == 'gopy'){							
							$ds_gopy = Danhsachlayykien::find()
											->andWhere(['idtieuchuan' => $item->tc_id])
											->andWhere(['idduan' => $item->idduan])
											->andWhere(['giaidoan' => $item->giaidoan])
											->andWhere(['groupduan' => $item->groupduan])
											->andWhere(['is not','idmoigopy',null])
											->count();
							if($ds_gopy == 0){
								$giatri = 'Chưa gửi xin góp ý';
							}else{
								$ds_dagopy = Danhsachlayykien::find()
											->andWhere(['idtieuchuan' => $item->tc_id])
											->andWhere(['idduan' => $item->idduan])
											->andWhere(['giaidoan' => $item->giaidoan])
											->andWhere(['groupduan' => $item->groupduan])
											->andWhere(['is not','noidung',null])
											->andWhere(['is not','idmoigopy',null])
											->count();
								if($ds_dagopy){
									$giatri = 'Đã gửi xin góp ý.<br/>Đã trả lời: ' .  $ds_dagopy .'/'. $ds_gopy;
								}else{
									$giatri = '';
								}
							}
                        }


                        $objPHPExcel->getActiveSheet()->setCellValue($attachs['_'.$cols_a] . $row, $giatri);

						$objPHPExcel->getActiveSheet()->getColumnDimension($attachs['_'.$cols_a])->setAutoSize(true);
					}
					$col_count++;
				}

			}

		}

		// die;

		$tcSearch = isset($request['TieuchuanSearch']) ? $request['TieuchuanSearch'] : [];

		if (count($tcSearch)) {
			$ac = $row + 6;
			$count_new = 0;
			foreach ( $request['TieuchuanSearch'] as $key => $tieuchuan_search ) {
				$new = $ac + $count_new;
				if ($tieuchuan_search != '') {

					$filter_text = '';
					if ( isset( $labels[ 'tieuchuansearch-' . $key ] ) ) {
						$filter_text = $labels[ 'tieuchuansearch-' . $key ];
					}

					if ($count_new == 0) {

					} else {
						// $objPHPExcel->getActiveSheet()->insertNewRowBefore( $new, 1 );
					}

					$objPHPExcel->getActiveSheet()->setCellValue( 'A' . $new, $filter_text . ': ' . $tieuchuan_search )
						;
					$count_new++;
				}

			}
		}

		$filename = 'ds-gop-y-tcqt';
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="' . $filename . '.xls"');
		header('Cache-Control: max-age=0');

		$objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');

		$objWriter->save('php://output');
		exit();

	}


	public function actionListError()
	{	
		$items = $_GET['file'];
		if(!is_array($items)) return false;
		$cols = 2;

		$objReader = \PHPExcel_IOFactory::createReader('Excel5');
		$objPHPExcel = $objReader->load(dirname(__FILE__) . "/../templates_excel/ds_error.xls" );
		$base = 2;
		$row = $base;

		$attachs = [];
		foreach ( range('A', 'Z') as $count => $item ) {
			$attachs['_'.$count] = $item;
		}
		if (count($items)) {
			foreach ( $items as $count => $item ) {
				$row = $base + $count;				
				$objPHPExcel->getActiveSheet()->setCellValue( 'A'.$row, ( $count + 1 ) );
				$base_col = 1;							
				$cols_a = 1;
				if (isset($attachs['_'.$cols_a])) {					
					$objPHPExcel->getActiveSheet()->setCellValue($attachs['_'.$cols_a] . $row, $item);
				}				
			}
		}		

		$filename = 'danh-sach-file-loi';
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="' . $filename . '.xls"');
		header('Cache-Control: max-age=0');

		$objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');

		$objWriter->save('php://output');
		exit();

	}
}
