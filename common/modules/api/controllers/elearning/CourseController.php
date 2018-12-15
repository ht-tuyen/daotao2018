<?php

namespace common\modules\api\controllers\elearning;

use Yii;
use common\modules\api\models\elearning\Course;
use common\modules\api\models\elearning\Lesson;
use common\modules\api\models\elearning\CourseHistory;
use common\modules\api\models\elearning\CourseForUser;
use common\modules\api\models\elearning\QuizResult;
use common\modules\api\models\elearning\LessonForUser;
use common\modules\api\models\elearning\TeacherCourse;
use common\modules\api\models\elearning\Student;

use backend\models\User;
use common\modules\api\models\elearning\Category;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Inflector;
use common\components\PaginationHelper;
use common\components\UploadImageHelper;

class CourseController extends Controller
{
    protected $per_page = 50;
    public function actionList()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $category = new Category();
        $category->build_list();    
        $query = Course::find();
        $search_body = Yii::$app->request->getRawBody();
        $param = json_decode($search_body);
        if ($param->name !="") {
            $query->andFilterWhere(
                ['like', 'name',$param->name]
            );
        }
        if ($param->category !="") {
            $query->andFilterWhere(
                ['=', 'category_id',$param->category->value]
            );
        }
        if ($param->state !="") {
            $query->andFilterWhere(
                ['=', 'state',$param->state]
            );
        }
        if ($param->id !="") {
            $query->andFilterWhere(
                ['=', 'course_id',$param->id]
            );
        }
        if ($_GET['role_id'] == 963) {
            // Teacher
            $query->innerJoin('course_for_teacher', '`course_for_teacher`.`course_id` = `course_item`.`course_id`');
            $query->andFilterWhere(
                ['=', 'user_id',$_GET['user_id']]
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
            "categories"=>$category->_cats,
            "teachers"=> User::find()->where(['role_id'=>963, 'status'=>1])->all()
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
		$objPHPExcel->setActiveSheetIndex(0)->mergeCells('F2:G2');
		$objPHPExcel->setActiveSheetIndex(0)->mergeCells('H2:I2');
		$objPHPExcel->getActiveSheet()->setCellValue('A1','Thống kê khóa học');
		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('A2', 'STT')
					->setCellValue('B2', 'Khóa học')
					->setCellValue('C2', 'Lĩnh vực')
					->setCellValue('D2', 'Tổng số học viên đăng ký khóa học')
					->setCellValue('E2', 'Tổng số học viên tham gia thi')
					->setCellValue('F2', 'Tổng số học viên đạt')
					->setCellValue('H2', 'Tổng số học viên trượt');
		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('A3', '')
					->setCellValue('B3', '')
					->setCellValue('C3', '')
					->setCellValue('D3', '')
					->setCellValue('E3', '')
					->setCellValue('F3', 'Số lượng')
					->setCellValue('G3', 'Tỷ lệ %')
					->setCellValue('H3', 'Số lượng')
					->setCellValue('I3', 'Tỷ lệ %');
					
		// Load data
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(35);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(30);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(30);
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(10);
		$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(10);
		$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(10);
		$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(10);



		$courses = Course::find()->where(['state'=>1])->all();
		$data = array();
		$i = 1;
		foreach ($courses as $course){
			$done_test = $passed = $fail = $passed_percent = $fail_percent = 0;
			$course_for_users = CourseForUser::find()->where(['course_id' => $course->course_id])->all();
			foreach ($course_for_users as $student) {
				if ($student->reviewed) {
					$done_test++;
					if ($student->result >= $student->minimum_points) {
						$passed++;
					}else {
						$fail++;
					}
				}
					
			}
			if ($done_test) {
				$passed_percent = number_format($passed/$done_test*100);
				$fail_percent = 100 - $passed_percent;
			}
			$data[] = array($i, $course->name, $course->category->name, count($course_for_users),$done_test,$passed,$passed_percent,$fail,$fail_percent );
			$i++;
		}
		
		$objPHPExcel->getActiveSheet()->fromArray($data, null, 'A4');

		

		// Rename worksheet
		$objPHPExcel->getActiveSheet()->setTitle('Simple');


		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$objPHPExcel->setActiveSheetIndex(0);


		// Redirect output to a client’s web browser (Excel2007)
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="thong_ke_khoa_hoc.xlsx"');
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
	public function url_slug($str, $options = array()) {
	// Make sure string is in UTF-8 and strip invalid UTF-8 characters
	$str = mb_convert_encoding((string)$str, 'UTF-8', mb_list_encodings());
	
	$defaults = array(
		'delimiter' => '-',
		'limit' => null,
		'lowercase' => true,
		'replacements' => array(),
		'transliterate' => false,
	);
	
	// Merge options
	$options = array_merge($defaults, $options);
	
	$char_map = array(
		// Latin
		'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'A', 'Å' => 'A', 'Æ' => 'AE', 'Ç' => 'C', 
		'È' => 'E', 'É' => 'E', 'Ê' => 'E', 'Ë' => 'E', 'Ì' => 'I', 'Í' => 'I', 'Î' => 'I', 'Ï' => 'I', 
		'Ð' => 'D', 'Ñ' => 'N', 'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O', 'Ö' => 'O', 'Ő' => 'O', 
		'Ø' => 'O', 'Ù' => 'U', 'Ú' => 'U', 'Û' => 'U', 'Ü' => 'U', 'Ű' => 'U', 'Ý' => 'Y', 'Þ' => 'TH', 
		'ß' => 'ss', 
		'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a', 'å' => 'a', 'æ' => 'ae', 'ç' => 'c', 
		'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i', 
		'ð' => 'd', 'ñ' => 'n', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o', 'ö' => 'o', 'ő' => 'o', 
		'ø' => 'o', 'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ü' => 'u', 'ű' => 'u', 'ý' => 'y', 'þ' => 'th', 
		'ÿ' => 'y',
		// Latin symbols
		'©' => '(c)',
		// Greek
		'Α' => 'A', 'Β' => 'B', 'Γ' => 'G', 'Δ' => 'D', 'Ε' => 'E', 'Ζ' => 'Z', 'Η' => 'H', 'Θ' => '8',
		'Ι' => 'I', 'Κ' => 'K', 'Λ' => 'L', 'Μ' => 'M', 'Ν' => 'N', 'Ξ' => '3', 'Ο' => 'O', 'Π' => 'P',
		'Ρ' => 'R', 'Σ' => 'S', 'Τ' => 'T', 'Υ' => 'Y', 'Φ' => 'F', 'Χ' => 'X', 'Ψ' => 'PS', 'Ω' => 'W',
		'Ά' => 'A', 'Έ' => 'E', 'Ί' => 'I', 'Ό' => 'O', 'Ύ' => 'Y', 'Ή' => 'H', 'Ώ' => 'W', 'Ϊ' => 'I',
		'Ϋ' => 'Y',
		'α' => 'a', 'β' => 'b', 'γ' => 'g', 'δ' => 'd', 'ε' => 'e', 'ζ' => 'z', 'η' => 'h', 'θ' => '8',
		'ι' => 'i', 'κ' => 'k', 'λ' => 'l', 'μ' => 'm', 'ν' => 'n', 'ξ' => '3', 'ο' => 'o', 'π' => 'p',
		'ρ' => 'r', 'σ' => 's', 'τ' => 't', 'υ' => 'y', 'φ' => 'f', 'χ' => 'x', 'ψ' => 'ps', 'ω' => 'w',
		'ά' => 'a', 'έ' => 'e', 'ί' => 'i', 'ό' => 'o', 'ύ' => 'y', 'ή' => 'h', 'ώ' => 'w', 'ς' => 's',
		'ϊ' => 'i', 'ΰ' => 'y', 'ϋ' => 'y', 'ΐ' => 'i',
		// Turkish
		'Ş' => 'S', 'İ' => 'I', 'Ç' => 'C', 'Ü' => 'U', 'Ö' => 'O', 'Ğ' => 'G',
		'ş' => 's', 'ı' => 'i', 'ç' => 'c', 'ü' => 'u', 'ö' => 'o', 'ğ' => 'g', 
		// Russian
		'А' => 'A', 'Б' => 'B', 'В' => 'V', 'Г' => 'G', 'Д' => 'D', 'Е' => 'E', 'Ё' => 'Yo', 'Ж' => 'Zh',
		'З' => 'Z', 'И' => 'I', 'Й' => 'J', 'К' => 'K', 'Л' => 'L', 'М' => 'M', 'Н' => 'N', 'О' => 'O',
		'П' => 'P', 'Р' => 'R', 'С' => 'S', 'Т' => 'T', 'У' => 'U', 'Ф' => 'F', 'Х' => 'H', 'Ц' => 'C',
		'Ч' => 'Ch', 'Ш' => 'Sh', 'Щ' => 'Sh', 'Ъ' => '', 'Ы' => 'Y', 'Ь' => '', 'Э' => 'E', 'Ю' => 'Yu',
		'Я' => 'Ya',
		'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' => 'e', 'ё' => 'yo', 'ж' => 'zh',
		'з' => 'z', 'и' => 'i', 'й' => 'j', 'к' => 'k', 'л' => 'l', 'м' => 'm', 'н' => 'n', 'о' => 'o',
		'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't', 'у' => 'u', 'ф' => 'f', 'х' => 'h', 'ц' => 'c',
		'ч' => 'ch', 'ш' => 'sh', 'щ' => 'sh', 'ъ' => '', 'ы' => 'y', 'ь' => '', 'э' => 'e', 'ю' => 'yu',
		'я' => 'ya',
		// Ukrainian
		'Є' => 'Ye', 'І' => 'I', 'Ї' => 'Yi', 'Ґ' => 'G',
		'є' => 'ye', 'і' => 'i', 'ї' => 'yi', 'ґ' => 'g',
		// Czech
		'Č' => 'C', 'Ď' => 'D', 'Ě' => 'E', 'Ň' => 'N', 'Ř' => 'R', 'Š' => 'S', 'Ť' => 'T', 'Ů' => 'U', 
		'Ž' => 'Z', 
		'č' => 'c', 'ď' => 'd', 'ě' => 'e', 'ň' => 'n', 'ř' => 'r', 'š' => 's', 'ť' => 't', 'ů' => 'u',
		'ž' => 'z', 
		// Polish
		'Ą' => 'A', 'Ć' => 'C', 'Ę' => 'e', 'Ł' => 'L', 'Ń' => 'N', 'Ó' => 'o', 'Ś' => 'S', 'Ź' => 'Z', 
		'Ż' => 'Z', 
		'ą' => 'a', 'ć' => 'c', 'ę' => 'e', 'ł' => 'l', 'ń' => 'n', 'ó' => 'o', 'ś' => 's', 'ź' => 'z',
		'ż' => 'z',
		// Latvian
		'Ā' => 'A', 'Č' => 'C', 'Ē' => 'E', 'Ģ' => 'G', 'Ī' => 'i', 'Ķ' => 'k', 'Ļ' => 'L', 'Ņ' => 'N', 
		'Š' => 'S', 'Ū' => 'u', 'Ž' => 'Z',
		'ā' => 'a', 'č' => 'c', 'ē' => 'e', 'ģ' => 'g', 'ī' => 'i', 'ķ' => 'k', 'ļ' => 'l', 'ņ' => 'n',
		'š' => 's', 'ū' => 'u', 'ž' => 'z'
	);
	
	// Make custom replacements
	$str = preg_replace(array_keys($options['replacements']), $options['replacements'], $str);
	
	// Transliterate characters to ASCII
	if ($options['transliterate']) {
		$str = str_replace(array_keys($char_map), $char_map, $str);
	}
	
	// Replace non-alphanumeric characters with our delimiter
	$str = preg_replace('/[^\p{L}\p{Nd}]+/u', $options['delimiter'], $str);
	
	// Remove duplicate delimiters
	$str = preg_replace('/(' . preg_quote($options['delimiter'], '/') . '){2,}/', '$1', $str);
	
	// Truncate slug to max. characters
	$str = mb_substr($str, 0, ($options['limit'] ? $options['limit'] : mb_strlen($str, 'UTF-8')), 'UTF-8');
	
	// Remove delimiter from ends
	$str = trim($str, $options['delimiter']);
	
	return $options['lowercase'] ? mb_strtolower($str, 'UTF-8') : $str;
}
	public function actionDownloaddetail($id){
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
		$objPHPExcel->setActiveSheetIndex(0)->mergeCells('C1:F1');
		$objPHPExcel->setActiveSheetIndex(0)->mergeCells('C2:F2');
	$model = Course::findOne($id);
        $students = CourseForUser::find()->where(['course_id' => $id])->all();
        $users = (new \yii\db\Query())
        ->select('s.*')
        ->from('user as s')
       
        ->all();
		$objPHPExcel->getActiveSheet()->setCellValue('C1','Thống kê chi tiết khóa học');
		$objPHPExcel->getActiveSheet()->setCellValue('C2','Khóa học: '.$model->name);
		
		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('A3', 'STT')
					->setCellValue('B3', 'Học viên')
					->setCellValue('C3', 'Ngày đăng ký')
					->setCellValue('D3', 'Thời gian làm bài thi')
					->setCellValue('E3', 'Thời gian nộp bài thi')
					->setCellValue('F3', 'Kết quả')
					->setCellValue('G3', 'Trạng thái');
					
					
		// Load data
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(35);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(30);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(30);
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(10);
		$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(10);
		$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(10);
		$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(10);



		

       
		$data = array();
		$i = 1;
		foreach ($students as $student){
			$started_time = $student->getQuizResult()['started_time'] ? date("H:i d-m-Y",strtotime($student->getQuizResult()['started_time'])) : "";
			$submitted_time = $student->getQuizResult()['submitted_time'] ? date("H:i d-m-Y",strtotime($student->getQuizResult()['submitted_time'])) : "";
			$result = "";
			if ($student->result) {
				if ($student->getQuizResult()['reviewed']) {
				$result = $student->getQuizResult()['result'] . '/' . $student->getQuizResult()['total'];
				}else {
					$result = "Đang chấm thi";
				}
			}
			
			$status =  $student->state == 1 ? "Đã duyệt" : "Chưa chuyệt";
			$data[] = array($i, $student->student->full_name, date("d-m-Y",strtotime($student->created_date)),$started_time, $submitted_time, $result,$status );
			$i++;
		}
		
		$objPHPExcel->getActiveSheet()->fromArray($data, null, 'A4');

		

		// Rename worksheet
		$objPHPExcel->getActiveSheet()->setTitle('Simple');


		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$objPHPExcel->setActiveSheetIndex(0);


		// Redirect output to a client’s web browser (Excel2007)
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="thong_ke_chi_tiet_khoa_hoc_'.$this->url_slug($model->name,array('transliterate' => true)).'.xlsx"');
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
    public function actionStudents()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        
        $query = CourseForUser::find()->where(['course_id' => $_GET['course_id']]);
        
        
        
        $countQuery = clone $query;
        $pagination = new PaginationHelper($countQuery->count(),$_GET['page'], $this->per_page );
        $models = $query->offset($pagination->from)
        ->limit($this->per_page)
        ->orderBy('state asc')
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
            
        );
        return $result;
    }
    public function actionResult()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        
        $query = QuizResult::find()->where(['quiz_id' => $_GET['quiz_id']]);
         
        $countQuery = clone $query;
        $pagination = new PaginationHelper($countQuery->count(),$_GET['page'], $this->per_page );
        $models = $query->offset($pagination->from)
        ->limit($this->per_page)
        ->orderBy('result desc')
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
            
        );
        return $result;
    }
    
    public function actionActive()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $student = Student::findOne($_GET['user_id']);
        $course = Course::findOne($_GET['course_id']);
        $active = CourseForUser::find()
        ->where(['user_id' => $_GET['user_id'], 'course_id' => $_GET['course_id']])
        ->one();
        $active->state = 1;
        $active->approved_date = date("Y-m-d H:i:s");
        $active->save();

        // Save lesson for user
        $lessons = Lesson::find()->where(['state'=>1, 'course_id'=>$_GET['course_id']])->all();
        foreach ($lessons as $lesson){
            $lesson_for_user = new LessonForUser();
            $lesson_for_user->course_id = $lesson->course_id;
            $lesson_for_user->lesson_id = $lesson->lesson_id;
            $lesson_for_user->user_id = $_GET['user_id'];
            $lesson_for_user->percentage = $lesson->percentage;
            $lesson_for_user->created_date = date("Y-m-d H:i:s");
            $lesson_for_user->save();
        }

        // Save course history
        $history = new CourseHistory();
        $history->course_id = $_GET['course_id'];
        $history->user_id = Yii::$app->user->id;
        $history->type = 1;
        $history->date = date("Y-m-d");
        $history->save();

        // Send email to user
        Yii::$app
                ->mailer
                ->compose(
                    ['html' => 'noticeStudentActivedAccount-html.php', 'text' => 'noticeStudentActivedAccount-txt.php'],
                    ['user' => $student, 'course'=>$course]
                )
                ->setFrom([Yii::$app->params['supportEmail'] => 'TCVN E-learning'])
                ->setTo($student->email)
                ->setSubject('Khóa học được kích hoạt trên TCVN E-learning')
                ->send();

        return ['active'=>$active, 'lessons'=>$lessons];
    }
    public function actionDeactive()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $student = Student::findOne($_GET['user_id']);
        $course = Course::findOne($_GET['course_id']);
        $active = CourseForUser::find()
        ->where(['user_id' => $_GET['user_id'], 'course_id' => $_GET['course_id']])
        ->one();
        $active->state = -1;
        $active->approved_date = date("Y-m-d H:i:s");
        $active->save();
        // Send email to user
        Yii::$app
                ->mailer
                ->compose(
                    ['html' => 'noticeStudentDeactivedAccount-html.php', 'text' => 'noticeStudentDeactivedAccount-txt.php'],
                    ['user' => $student, 'course'=>$course]
                )
                ->setFrom([Yii::$app->params['supportEmail'] => 'TCVN E-learning'])
                ->setTo($student->email)
                ->setSubject('Khóa học bị khóa trên TCVN E-learning')
                ->send();
        return $active;
    }
    public function actionView()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        
      
        $course = Course::find()
            ->where(['or',
            ['slug'=>$_GET['id']],
            ['course_id'=>$_GET['id']]
        ])->one();;
        
        return $course;
    }
    public function actionStore()
    {
        /*
            Role id 
            1: Admin
            963 : Teacher
        */
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $body = Yii::$app->request->getRawBody();
        $param = json_decode($body);
        if ($param->course_id) {
            // update
            $model = Course::findOne($param->course_id);
            $model->modified_by = $param->user_id; 
        }else{
            // create
            $model = new Course();
            $model->created_by = $param->user_id;    
        }   
            // main data
            $model->name = $param->name;
            $model->short_desc = $param->short_desc;
            $model->full_desc = $param->full_desc;
            $model->ordering = $param->ordering;
            $model->category_id = $param->category_id;
            $model->featured = $param->featured;
            $model->state = $param->state;
            $model->ready = $param->ready;
            $model->slug = Inflector::slug($param->name);
            $model->main_teacher = $param->mainTeacher;
            if($param->thumbnail_is_changed) {
                $thumb = new UploadImageHelper($param->thumbnail, $model->slug, 'course' );
                $model->thumbnail = $thumb->imageName;
            }
            if($param->full_image_is_changed) {
                $full_image = new UploadImageHelper($param->full_image, "full-".$model->slug, 'course' );
                $model->full_image = $full_image->imageName;
            }
            // save data
            if ($model->validate()) {
                $model->save();

                TeacherCourse::deleteAll(['course_id' => $model->course_id]);

                if ($param->teachers) {
                    foreach ($param->teachers as $teacher) {
                        $attless = new TeacherCourse();
                        $attless->course_id = $model->course_id;
                        $attless->user_id = $teacher->user_id;
                        if ($teacher->user_id == $param->mainTeacher) {
                            $attless->is_main =1;
                        }
                        $attless->save();
                    }
                }
                if ($param->mainTeacher) {
                    $lessons = Lesson::find()->where(['course_id'=>$model->course_id])->all();
                    $lesson_column = [];
                    foreach ($lessons as $lesson) {
                        $lesson_column[] = $lesson->lesson_id;
                    }
                    Lesson::updateAll(['main_teacher' => $param->mainTeacher], ['lesson_id' => $lesson_column]);

                    $course_users = CourseForUser::find()->where(['course_id'=>$model->course_id])->all();
                    $course_users_column = [];
                    foreach ($course_users as $item) {
                        $course_users_column[] = $item->course_id;
                    }
                    CourseForUser::updateAll(['main_teacher' => $param->mainTeacher], ['course_id' => $course_users_column]);
                }
                $param->course_id =  $model->course_id;
                return  ["success"=>$param]; 
            }else {
                return ["error"=>$model->errors];
            }
    }
    public function actionDelete()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $course = Course::findOne($_GET['id']);
        $course->state = -1;
        $course->save();
        return $course;
    }
    public function actionBulkdelete()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $body = Yii::$app->request->getRawBody();
        Course::updateAll(['state' => -1], ['course_id' => json_decode($body)]);

        //$courses = Course::deleteAll(['course_id' => json_decode($body)]);
        return $courses;

    }
    

  
}
