<?php

namespace common\modules\api\controllers\elearning;

use Yii;
use common\modules\api\models\elearning\Student;
use common\modules\api\models\elearning\Lesson;
use common\modules\api\models\elearning\Course;
// use common\components\PHPExcel;
use common\modules\api\models\elearning\CourseForUser;
use common\modules\api\models\elearning\LessonForUser;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Inflector;
use common\components\PaginationHelper;
use common\components\SimpleXLSX;

class StudentController extends Controller
{
    protected $per_page = 50;
    public function actionList()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
      
        $query = Student::find();
        $search_body = Yii::$app->request->getRawBody();
        $param = json_decode($search_body);
        if ($param->username !="") {
            $query->andFilterWhere(
                ['like', 'username',$param->username]
            );
        }
        if ($param->full_name !="") {
            $query->andFilterWhere(
                ['like', 'full_name',$param->full_name]
            );
        }
        if ($param->email !="") {
            $query->andFilterWhere(
                ['like', 'email',$param->email]
            );
        }
        if ($param->course !="") {
            $query->leftJoin('course_for_user', '`course_for_user`.`user_id` = `user`.`id`');
            $query->andFilterWhere(
                ['=', 'course_id',$param->course->course_id]
            );
        }
        if ($param->mobile !="") {
            $query->andFilterWhere(
                ['like', 'mobile',$param->mobile]
            );
        }
        
        if ($param->status !="") {
            $query->andFilterWhere(
                ['=', 'status',$param->status]
            );
        }
        if ($param->id !="") {
            $query->andFilterWhere(
                ['=', 'id',$param->id]
            );
        }
        $countQuery = clone $query;
        $pagination = new PaginationHelper($countQuery->count(),$_GET['page'], $this->per_page );
        $models = $query->offset($pagination->from)
        ->limit($this->per_page)
        ->orderBy($_GET['sortBy'].' '.$_GET['sortType'])
        ->all();
        $result = array(
            "data"=>$models,
            "pagination"=> array(
                'total' =>  $countQuery->count(),
                'per_page' => $this->per_page,
                'current_page' => $pagination->current_page,
                'last_page' => $pagination->total_page,
                'from' => $pagination->from + 1,
                'to' => $pagination->to
            ),
            "courses"=>Course::find()->where(['state'=>1])->select('course_id, name')->all(),
           
        );
        return $result;
    }
	public function actionDownload(){
		// Create new PHPExcel object
		$objPHPExcel = new \PHPExcel();

		// Set document properties
		$objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
									 ->setLastModifiedBy("Maarten Balliauw")
									 ->setTitle("Office 2007 XLSX Test Document")
									 ->setSubject("Office 2007 XLSX Test Document")
									 ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
									 ->setKeywords("office 2007 openxml php")
									 ->setCategory("Test result file");

		

		// Add some data
		$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:L1');
		$objPHPExcel->getActiveSheet()->setCellValue('A1','Thống kê học viên');
		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('A2', 'STT')
					->setCellValue('B2', 'Họ và tên')
					->setCellValue('C2', 'Ngày sinh')
					->setCellValue('D2', 'Giới tính')
					->setCellValue('E2', 'Điện thoại')
					->setCellValue('F2', 'Email')
					->setCellValue('G2', 'Chức vụ')
					->setCellValue('H2', 'Đơn vị công tác')
					->setCellValue('I2', 'Địa chỉ đơn vị công tác')
					->setCellValue('J2', 'Tổng khóa học đã đăng ký')
					->setCellValue('K2', 'Tổng khóa học đã hoàn thành')
					->setCellValue('L2', 'Trạng thái');
		// Load data
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(35);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(10);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(30);
		$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(30);
		$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(50);
		$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(20);


		$students = Student::find()->all();
		$data = array();
		$i = 1;
		foreach ($students as $student){
			if ($student->gender == 1) {
				$student->gender = "Nam";
			}else{
				$student->gender = "Nữ";
			}
			if ($student->status == 10) {
				$student->status = "Hoạt động";
			}else{
				$student->status = "Khóa";
			}
			$data[] = array($i, $student->full_name, date("d-m-Y",strtotime($student->dob)), $student->gender , $student->mobile, $student->email, $student->position, $student->company, $student->company_address, $student->getCourses(), $student->getFinishedcourses(), $student->status );
			$i++;
		}
		
		$objPHPExcel->getActiveSheet()->fromArray($data, null, 'A3');

		

		// Rename worksheet
		$objPHPExcel->getActiveSheet()->setTitle('Simple');


		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$objPHPExcel->setActiveSheetIndex(0);


		// Redirect output to a client’s web browser (Excel2007)
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="thong_ke_hoc_vien.xlsx"');
		header('Cache-Control: max-age=0');
		// If you're serving to IE 9, then the following may be needed
		header('Cache-Control: max-age=1');

		// If you're serving to IE over SSL, then the following may be needed
		header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
		header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
		header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
		header ('Pragma: public'); // HTTP/1.0

		$objWriter = \
		PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->save('php://output');
	}
	public function actionDownloaddetail($id){
		// Create new PHPExcel object
		$objPHPExcel = new \PHPExcel();
		$student = Student::findOne($id);
		if ($student->gender == 1) {
			$gender = "Nam";
		}else {
			$gender = "Nữ";
		}
		// Set document properties
		$objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
									 ->setLastModifiedBy("Maarten Balliauw")
									 ->setTitle("Office 2007 XLSX Test Document")
									 ->setSubject("Office 2007 XLSX Test Document")
									 ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
									 ->setKeywords("office 2007 openxml php")
									 ->setCategory("Test result file");

		

		// Add some data
		$objPHPExcel->setActiveSheetIndex(0)->mergeCells('C1:F1');
		$objPHPExcel->getActiveSheet()->setCellValue('C1','Thống kê chi tiết học viên học viên');
		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('B2', 'Họ và tên:')
					->setCellValue('B3', 'Ngày sinh:')
					->setCellValue('B4', 'Giới tính:')
					->setCellValue('B5', 'Điện thoại:')
					->setCellValue('C2', $student->full_name)
					->setCellValue('C3', date("d-m-Y",strtotime($student->dob)))
					->setCellValue('C4', $gender)
					->setCellValue('C5', $student->mobile)
		
					->setCellValue('D2', 'Email:')
					->setCellValue('D3', 'Đơn vị công tác:')
					->setCellValue('D4', 'Chức vụ:')
					->setCellValue('E2', $student->email)
					->setCellValue('E3', $student->company)
					->setCellValue('E4', $student->position);
			
					
		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('A6', 'STT')
					->setCellValue('B6', 'Lĩnh vực')
					->setCellValue('C6', 'Khóa học')
					->setCellValue('D6', 'Ngày đăng ký')
					->setCellValue('E6', 'Thời gian làm bài thi')
					->setCellValue('F6', 'Thời gian nộp bài thi')
					->setCellValue('G6', 'Kết quả')
					->setCellValue('H6', 'Trạng thái');
					
		// Load data
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(35);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(25);
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(30);
		$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(30);
		$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(50);
		$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(20);


		
	
		
		$courses = (new \yii\db\Query())
            ->select(['course_for_user.*','course_category.name as catname','started_time','submitted_time','course_item.name as course_name'])
            ->from('course_for_user')
            ->innerJoin('quiz', 'quiz.course_id = course_for_user.course_id')
			->innerJoin('course_item', 'course_for_user.course_id = course_item.course_id')
			->innerJoin('course_category', 'course_category.category_id = course_item.category_id')
			->innerJoin('quiz_result', 'quiz.quiz_id = quiz_result.quiz_id')
            ->where(['course_for_user.user_id' => $id])
			->groupBy(['course_for_user.course_id'])
            ->all();
		$data = array();
		$i = 1;
		foreach ($courses as $course){
			
			if ($course['status'] == 1) {
				$course['status'] = "Đã duyệt";
			}else{
				$course['status'] = "Chưa duyệt";
			}
			if ($course['started_time']) {
				$started_time = date("H:i d-m-Y",strtotime($course['started_time']));
			}else {
				$started_time = 0;
			}
			if ($course['submitted_time']) {
				$submitted_time = date("H:i d-m-Y",strtotime($course['submitted_time']));
			}else {
				$submitted_time = 0;
			}
			 // date("H:i d-m-Y",strtotime($course->created_date));
			 // date("H:i d-m-Y",strtotime($course->created_date));
			 if ($course->reviewed) {
				 $result = $course->result."/".$course->total;
			 }else {
				 $result = 0;
			 }
			$data[] = array($i, $course['catname'], $course['course_name'], date("H:i d-m-Y",strtotime($course['created_date'])),$started_time,$submitted_time,$result,$course['status'] );
			$i++;
		}
		
		$objPHPExcel->getActiveSheet()->fromArray($data, null, 'A7');

		

		// Rename worksheet
		$objPHPExcel->getActiveSheet()->setTitle('Simple');


		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$objPHPExcel->setActiveSheetIndex(0);


		// Redirect output to a client’s web browser (Excel2007)
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="chi_tiet_hoc_vien_'.$student->username.'.xlsx"');
		header('Cache-Control: max-age=0');
		// If you're serving to IE 9, then the following may be needed
		header('Cache-Control: max-age=1');

		// If you're serving to IE over SSL, then the following may be needed
		header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
		header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
		header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
		header ('Pragma: public'); // HTTP/1.0

		$objWriter = \
		PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->save('php://output');
	}
    public function actionView()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $student = Student::findOne($_GET['id']);
        return $student;
    }
	public function actionUpload(){
		\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		$body = Yii::$app->request->getRawBody();
        // require_once('../../libs/simplexlsx.class.php');
		$image_parts = explode(";base64,", $body);
        $image_base64 = base64_decode($image_parts[1]);
		$finfo = finfo_open();
        $file_mime_type = finfo_buffer($finfo, $image_base64, FILEINFO_MIME_TYPE);
        $file_extension = $this->mime2ext($file_mime_type);
        $imageName = date("Y_m_d_H_i_s"). '.'.$file_extension;
        $file = Yii::getAlias('@anyname') . '/uploads/elearning/student/' . $imageName;
        file_put_contents($file, $image_base64);
		$data = array();
		if ( $xlsx = SimpleXLSX::parse($file)) {
			$rows = $xlsx->rows();
			array_shift($rows);
			foreach ($rows as $row) {
				if ($row[0] != "") {
					$data[] = $row;
				}
			}
			
		}else {
			$rows = SimpleXLSX::parse_error();
		}
		return [
			'data'=>$data,
			'file_name'=>$file
		];
	}
	public function actionAssignbulk(){
		\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		$body = Yii::$app->request->getRawBody();
		$data = json_decode($body);
		$file = $data->file;
		$course = Course::FindOne($data->course_id);
		if ( $xlsx = SimpleXLSX::parse($file)) {
			$rows = $xlsx->rows();
			array_shift($rows);
		}else {
			$rows = SimpleXLSX::parse_error();
		}
		$results = array();
		foreach ($rows as $key=> $item) {
			
			if ($item[4]) {
				$model = Student::find()->where(['email'=>$item[4]])->one();
				if ($model) {
					
					
					$check = CourseForUser::find()->where(['course_id'=>$data->course_id, 'user_id'=>$model->id])->one();
					if ($check->id) {
						$item[6] = 3;
					}else {
						$item[6] = 1;
						Yii::$app
						->mailer
						->compose(
							['html' => 'assignAccount-html'],
							['user' => $model, 'course'=>$course]
						)
						->setFrom([Yii::$app->params['supportEmail'] => 'TCVN E-learning'])
						->setTo($model->email)
						->setSubject('Bạn được mời tham gia vào khóa học trên TCVN E-learning')
						->send();
					}
					$results[$key] = $item;
					
				}else {
					$item[6] = 0;
					$results[$key] = $item;
					$model = new Student();
					$model->username = $item[4];
					$model->email = $item[4];
					$model->full_name = $item[1];
				
					$db = Yii::$app->db;
					$sql = $db->createCommand()->insert('user', [
						 'username' => $item[4],
						 'email' => $item[4],
						 'full_name' => $item[1],
						 'mobile' => $item[5],
						 'company' => $item[2],
						 'position' => $item[3],
						 'status' => 10,
						 'auth_key' => Yii::$app->security->generateRandomString(),
						 'password_hash' => '$2y$13$17DhcrQ1Ag2.uF9zEZVtMu7V3IvUP354T6tx1Gdr/FXJsd.yEGVmy',
						 'created_at' => time(),
						 'updated_at' => time(),
						])->execute();
					 $model->id = Yii::$app->db->getLastInsertID();

					Yii::$app
					->mailer
					->compose(
						['html' => 'createNAssignAccount-html', 'text' => 'createNAssignAccount-text'],
						['user' => $model, 'course'=>$course]
					)
					->setFrom([Yii::$app->params['supportEmail'] => 'TCVN E-learning'])
					->setTo($model->email)
					->setSubject('Tài khoản được tạo trên TCVN E-learning')
					->send();
				}
				if ($item[6] != 3) {
					$register = new CourseForUser();
					$register->user_id = $model->id;
					$register->course_id = $data->course_id;
					$register->state = 1;
					$register->approved_date = date("Y-m-d H:i:s");
					$register->save();
					// Save lesson for user
					$lessons = Lesson::find()->where(['state'=>1, 'course_id'=>$data->course_id])->all();
					foreach ($lessons as $lesson){
						$lesson_for_user = new LessonForUser();
						$lesson_for_user->course_id = $lesson->course_id;
						$lesson_for_user->lesson_id = $lesson->lesson_id;
						$lesson_for_user->user_id = $model->id;
						$lesson_for_user->percentage = $lesson->percentage;
						$lesson_for_user->created_date = date("Y-m-d H:i:s");
						$lesson_for_user->save();
					}
				}
				
			}else{
				$item[6] = 2;
				$results[$key] = $item;
			}
		}
		
		return $results;
	}
	public function actionStorebulk(){
		\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		$file = Yii::$app->request->getRawBody();
		// $list = json_decode($body);
		if ( $xlsx = SimpleXLSX::parse($file)) {
			$rows = $xlsx->rows();
			array_shift($rows);
		}else {
			$rows = SimpleXLSX::parse_error();
		}
		$results = array();
		$data = array();
		foreach ($rows as $key=> $item) {
			if ($item[0]) {
				if ($item[4]) {
					$student = Student::find()->where(['email'=>$item[4]])->one();
					if ($student) {
						$item[6] = 1;
						$results[$key] = $item;
						
					}else {
						
						$item[6] = 0;
						$results[$key] = $item;
						$model = new Student();
						$model->username = $item[4];
						$model->email = $item[4];
						$model->full_name = $item[1];
					
						$db = Yii::$app->db;
						$sql = $db->createCommand()->insert('user', [
							 'username' => $item[4],
							 'email' => $item[4],
							 'full_name' => $item[1],
							 'mobile' => $item[5],
							 'company' => $item[2],
							 'position' => $item[3],
							 'status' => 10,
							 'auth_key' => Yii::$app->security->generateRandomString(),
							 'password_hash' => '$2y$13$17DhcrQ1Ag2.uF9zEZVtMu7V3IvUP354T6tx1Gdr/FXJsd.yEGVmy',
							 'created_at' => time(),
							 'updated_at' => time(),
							])->execute();
						 $model->id = Yii::$app->db->getLastInsertID();
						 Yii::$app
						->mailer
						->compose(
							['html' => 'createAccount-html', 'text' => 'createAccount-text'],
							['user' => $model]
						)
						->setFrom([Yii::$app->params['supportEmail'] => 'TCVN E-learning'])
						->setTo($model->email)
						->setSubject('Tài khoản được tạo trên TCVN E-learning')
						->send();
						
						
					}
				}else{
					$item[6] = 2;
					$results[$key] = $item;
				}
			}
		}
		
		return $results;
	}
	public function mime2ext($mime){
        $all_mimes = '{"png":["image\/png","image\/x-png"],"bmp":["image\/bmp","image\/x-bmp","image\/x-bitmap","image\/x-xbitmap","image\/x-win-bitmap","image\/x-windows-bmp","image\/ms-bmp","image\/x-ms-bmp","application\/bmp","application\/x-bmp","application\/x-win-bitmap"],"gif":["image\/gif"],"jpeg":["image\/jpeg","image\/pjpeg"],"xspf":["application\/xspf+xml"],"vlc":["application\/videolan"],"wmv":["video\/x-ms-wmv","video\/x-ms-asf"],"au":["audio\/x-au"],"ac3":["audio\/ac3"],"flac":["audio\/x-flac"],"ogg":["audio\/ogg","video\/ogg","application\/ogg"],"kmz":["application\/vnd.google-earth.kmz"],"kml":["application\/vnd.google-earth.kml+xml"],"rtx":["text\/richtext"],"rtf":["text\/rtf"],"jar":["application\/java-archive","application\/x-java-application","application\/x-jar"],"zip":["application\/x-zip","application\/zip","application\/x-zip-compressed","application\/s-compressed","multipart\/x-zip"],"7zip":["application\/x-compressed"],"xml":["application\/xml","text\/xml"],"svg":["image\/svg+xml"],"3g2":["video\/3gpp2"],"3gp":["video\/3gp","video\/3gpp"],"mp4":["video\/mp4"],"m4a":["audio\/x-m4a"],"f4v":["video\/x-f4v"],"flv":["video\/x-flv"],"webm":["video\/webm"],"aac":["audio\/x-acc"],"m4u":["application\/vnd.mpegurl"],"pdf":["application\/pdf","application\/octet-stream"],"pptx":["application\/vnd.openxmlformats-officedocument.presentationml.presentation"],"ppt":["application\/powerpoint","application\/vnd.ms-powerpoint","application\/vnd.ms-office","application\/msword"],"docx":["application\/vnd.openxmlformats-officedocument.wordprocessingml.document"],"xlsx":["application\/vnd.openxmlformats-officedocument.spreadsheetml.sheet","application\/vnd.ms-excel"],"xl":["application\/excel"],"xls":["application\/msexcel","application\/x-msexcel","application\/x-ms-excel","application\/x-excel","application\/x-dos_ms_excel","application\/xls","application\/x-xls"],"xsl":["text\/xsl"],"mpeg":["video\/mpeg"],"mov":["video\/quicktime"],"avi":["video\/x-msvideo","video\/msvideo","video\/avi","application\/x-troff-msvideo"],"movie":["video\/x-sgi-movie"],"log":["text\/x-log"],"txt":["text\/plain"],"css":["text\/css"],"html":["text\/html"],"wav":["audio\/x-wav","audio\/wave","audio\/wav"],"xhtml":["application\/xhtml+xml"],"tar":["application\/x-tar"],"tgz":["application\/x-gzip-compressed"],"psd":["application\/x-photoshop","image\/vnd.adobe.photoshop"],"exe":["application\/x-msdownload"],"js":["application\/x-javascript"],"mp3":["audio\/mpeg","audio\/mpg","audio\/mpeg3","audio\/mp3"],"rar":["application\/x-rar","application\/rar","application\/x-rar-compressed"],"gzip":["application\/x-gzip"],"hqx":["application\/mac-binhex40","application\/mac-binhex","application\/x-binhex40","application\/x-mac-binhex40"],"cpt":["application\/mac-compactpro"],"bin":["application\/macbinary","application\/mac-binary","application\/x-binary","application\/x-macbinary"],"oda":["application\/oda"],"ai":["application\/postscript"],"smil":["application\/smil"],"mif":["application\/vnd.mif"],"wbxml":["application\/wbxml"],"wmlc":["application\/wmlc"],"dcr":["application\/x-director"],"dvi":["application\/x-dvi"],"gtar":["application\/x-gtar"],"php":["application\/x-httpd-php","application\/php","application\/x-php","text\/php","text\/x-php","application\/x-httpd-php-source"],"swf":["application\/x-shockwave-flash"],"sit":["application\/x-stuffit"],"z":["application\/x-compress"],"mid":["audio\/midi"],"aif":["audio\/x-aiff","audio\/aiff"],"ram":["audio\/x-pn-realaudio"],"rpm":["audio\/x-pn-realaudio-plugin"],"ra":["audio\/x-realaudio"],"rv":["video\/vnd.rn-realvideo"],"jp2":["image\/jp2","video\/mj2","image\/jpx","image\/jpm"],"tiff":["image\/tiff"],"eml":["message\/rfc822"],"pem":["application\/x-x509-user-cert","application\/x-pem-file"],"p10":["application\/x-pkcs10","application\/pkcs10"],"p12":["application\/x-pkcs12"],"p7a":["application\/x-pkcs7-signature"],"p7c":["application\/pkcs7-mime","application\/x-pkcs7-mime"],"p7r":["application\/x-pkcs7-certreqresp"],"p7s":["application\/pkcs7-signature"],"crt":["application\/x-x509-ca-cert","application\/pkix-cert"],"crl":["application\/pkix-crl","application\/pkcs-crl"],"pgp":["application\/pgp"],"gpg":["application\/gpg-keys"],"rsa":["application\/x-pkcs7"],"ics":["text\/calendar"],"zsh":["text\/x-scriptzsh"],"cdr":["application\/cdr","application\/coreldraw","application\/x-cdr","application\/x-coreldraw","image\/cdr","image\/x-cdr","zz-application\/zz-winassoc-cdr"],"wma":["audio\/x-ms-wma"],"vcf":["text\/x-vcard"],"srt":["text\/srt"],"vtt":["text\/vtt"],"ico":["image\/x-icon","image\/x-ico","image\/vnd.microsoft.icon"],"csv":["text\/x-comma-separated-values","text\/comma-separated-values","application\/vnd.msexcel"],"json":["application\/json","text\/json"]}';
        $all_mimes = json_decode($all_mimes,true);
        foreach ($all_mimes as $key => $value) {
          if(array_search($mime,$value) !== false) return $key;
        }
        return false;
    }
    public function actionAssign(){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $body = Yii::$app->request->getRawBody();
        $param = json_decode($body);
        
        $register = new CourseForUser();
        $model = Student::findOne($param->id);
        $register->user_id = $model->id;
        $register->course_id = $_GET['course_id'];
        $register->state = 1;
        $register->approved_date = date("Y-m-d H:i:s");
        $register->save();
        // Save lesson for user
        $lessons = Lesson::find()->where(['state'=>1, 'course_id'=>$_GET['course_id']])->all();
        foreach ($lessons as $lesson){
            $lesson_for_user = new LessonForUser();
            $lesson_for_user->course_id = $lesson->course_id;
            $lesson_for_user->lesson_id = $lesson->lesson_id;
            $lesson_for_user->user_id = $model->id;
            $lesson_for_user->percentage = $lesson->percentage;
            $lesson_for_user->created_date = date("Y-m-d H:i:s");
            $lesson_for_user->save();
        }
        Yii::$app
        ->mailer
        ->compose(
            ['html' => 'assignAccount-html'],
            ['user' => $model, 'course'=>Course::findOne($_GET['course_id'])]
        )
        ->setFrom([Yii::$app->params['supportEmail'] => 'TCVN E-learning'])
        ->setTo($model->email)
        ->setSubject('Bạn được mời tham gia vào khóa học trên TCVN E-learning')
        ->send();
        return $param;
    }
    public function actionStore()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $body = Yii::$app->request->getRawBody();
        $param = json_decode($body);
        $sendEmail = 0;
        if ($param->id) {
            // update
            $model = Student::findOne($param->id);
          
        }else{
            // create
            $model = new Student();
            $model->status = 10;
            $model->password_hash = Yii::$app->security->generatePasswordHash('ABC@123');
            $model->created_at = time();
            $model->auth_key = Yii::$app->security->generateRandomString();
            $sendEmail = 1;
        }   
            // main data
            $model->username = $param->username;
            $model->email = $param->email;
            $model->updated_at = time();
            $model->full_name = $param->full_name;
            $model->mobile = $param->mobile;
            $model->dob = date("Y-m-d",strtotime($param->dob));
            $model->address = $param->address;
            $model->gender = $param->gender;
            $model->company = $param->company;
            $model->company_address = $param->company_address;
            $model->academic = $param->academic;
            $model->degree = $param->degree;
            $model->position = $param->position;
         
            // save data
            $model->save();
            if ($_GET['course_id'] && $model->save()) {
                $register = new CourseForUser();
                $register->user_id = $model->id;
                $register->course_id = $_GET['course_id'];
                $register->state = 1;
                $register->approved_date = date("Y-m-d H:i:s");
                $register->save();
                // Save lesson for user
                $lessons = Lesson::find()->where(['state'=>1, 'course_id'=>$_GET['course_id']])->all();
                foreach ($lessons as $lesson){
                    $lesson_for_user = new LessonForUser();
                    $lesson_for_user->course_id = $lesson->course_id;
                    $lesson_for_user->lesson_id = $lesson->lesson_id;
                    $lesson_for_user->user_id = $model->id;
                    $lesson_for_user->percentage = $lesson->percentage;
                    $lesson_for_user->created_date = date("Y-m-d H:i:s");
                    $lesson_for_user->save();
                }
                Yii::$app
                ->mailer
                ->compose(
                    ['html' => 'createNAssignAccount-html', 'text' => 'createNAssignAccount-text'],
                    ['user' => $model, 'course'=>Course::findOne($_GET['course_id'])]
                )
                ->setFrom([Yii::$app->params['supportEmail'] => 'TCVN E-learning'])
                ->setTo($model->email)
                ->setSubject('Tài khoản được tạo trên TCVN E-learning')
                ->send();
            }
            if ($model->save() && $sendEmail && !$_GET['course_id']) {
                Yii::$app
                ->mailer
                ->compose(
                    ['html' => 'createAccount-html', 'text' => 'createAccount-text'],
                    ['user' => $model]
                )
                ->setFrom([Yii::$app->params['supportEmail'] => 'TCVN E-learning'])
                ->setTo($model->email)
                ->setSubject('Tài khoản được tạo trên TCVN E-learning')
                ->send();

            }
            $param->id =  $model->id;
            return  $param;
    }
    public function actionDelete()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $student = Student::findOne($_GET['id']);
        $student->status = 0;
        $student->save();
        return $student;
    }
    public function actionBulkdelete()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $body = Yii::$app->request->getRawBody();
        //$students = Student::deleteAll(['id' => json_decode($body)]);
        Student::updateAll(['status' => 0], ['id' => json_decode($body)]);

        return $students;

    }
    

  
}
