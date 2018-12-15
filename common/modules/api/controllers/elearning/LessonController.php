<?php

namespace common\modules\api\controllers\elearning;

use Yii;
use yii\helpers\HtmlPurifier;
use common\modules\api\models\elearning\Course;
use common\modules\api\models\elearning\Attachment;
use common\modules\api\models\elearning\AttachmentLesson;
use common\modules\api\models\elearning\Lesson;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Inflector;
use common\components\PaginationHelper;
use common\components\UploadImageHelper;
use yii\web\UploadedFile;

class LessonController extends Controller
{
    protected $per_page = 50;
    public function actionList()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
      
        $query = Lesson::find();
        $search_body = Yii::$app->request->getRawBody();
        $param = json_decode($search_body);
        if ($param->name !="") {
            $query->andFilterWhere(
                ['like', 'name',$param->name]
            );
        }
        if ($param->course !="") {
            $query->andFilterWhere(
                ['=', 'course_id',$param->course->course_id]
            );
        }
        if ($param->state !="") {
            $query->andFilterWhere(
                ['=', 'course_lessons.state',$param->state]
            );
        }
        if ($param->id !="") {
            $query->andFilterWhere(
                ['=', 'lesson_id',$param->id]
            );
        }
        if ($_GET['role_id'] == 963) {
            // Teacher
            $query->innerJoin('course_item', '`course_item`.`course_id` = `course_lessons`.`course_id`');

            $query->innerJoin('course_for_teacher', '`course_for_teacher`.`course_id` = `course_item`.`course_id`');
            $query->andFilterWhere(
                ['=', 'user_id',$_GET['user_id']]
            );
            $courses = Course::find()->select(['course_item.course_id', 'name'])->innerJoin('course_for_teacher', '`course_for_teacher`.`course_id` = `course_item`.`course_id`')->Where([
                'user_id'=>$_GET['user_id'],
                'state'=>1,
                
            ]
            )->all();
        }else {
            $courses = Course::find()->Where([
                'state'=>1,
                
            ]
            )->all();
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
            "courses"=> $courses,
           
            
            "attachments"=>Attachment::find()->select('attachment_id, name')->all()
        );
       
        return $result;
    }
    public function actionUploadfile()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $target_dir = Yii::getAlias('@anyname') ."/uploads/elearning/lesson/resource/";
        $name = $this->convert_vi_to_en(basename($_FILES["imageFile"]["name"]));
        $target_file = $target_dir .$name ;
        if (move_uploaded_file($_FILES["imageFile"]["tmp_name"], $target_file)) {
            return ['status'=>true, 'file_name'=>$name];
        } else {
            return ['status'=>false, 'message'=>'error'];
        }

        exit();
      

    }
	public function actionSet()
	{
		$connection = \Yii::$app->db;
		$connection->createCommand('DELETE FROM course_lessons WHERE lesson_id > 22')
        ->execute();
		echo "ok";
	}
    public function convert_vi_to_en($str) {
        $str = preg_replace("/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/", 'a', $str);
        $str = preg_replace("/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/", 'e', $str);
        $str = preg_replace("/(ì|í|ị|ỉ|ĩ)/", 'i', $str);
        $str = preg_replace("/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/", 'o', $str);
        $str = preg_replace("/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/", 'u', $str);
        $str = preg_replace("/(ỳ|ý|ỵ|ỷ|ỹ)/", 'y', $str);
        $str = preg_replace("/(đ)/", 'd', $str);
        $str = preg_replace("/(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)/", 'A', $str);
        $str = preg_replace("/(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)/", 'E', $str);
        $str = preg_replace("/(Ì|Í|Ị|Ỉ|Ĩ)/", 'I', $str);
        $str = preg_replace("/(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)/", 'O', $str);
        $str = preg_replace("/(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)/", 'U', $str);
        $str = preg_replace("/(Ỳ|Ý|Ỵ|Ỷ|Ỹ)/", 'Y', $str);
        $str = preg_replace("/(Đ)/", 'D', $str);
        $str = str_replace(" ","-",$str);
        //$str = str_replace(" ", "-", str_replace("&*#39;","",$str));
        return $str;
    }
    
    public function actionView()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $course = Lesson::find()
            ->where(['or',
            ['slug'=>$_GET['id']],
            ['lesson_id'=>$_GET['id']]
        ])->one();;
        return $course;
    }
    public function calculate_percent($course_id){
        $total_lesson = Lesson::find()->where(['course_id'=>$course_id, 'state'=>1])->count();
        $percentage = 100/$total_lesson;
        Lesson::updateAll(['percentage' => $percentage], ['=', 'course_id', $course_id]);

    }
    public function actionStore()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $body = Yii::$app->request->getRawBody();
        $param = json_decode($body);
        if ($param->lesson_id) {
            // update
            $model = Lesson::findOne($param->lesson_id);
            $model->modified_by = $param->user_id; 
        }else{
            // create
            $model = new Lesson();
            $model->created_by = $param->user_id;    
        }   
            // main data
            $model->name = $param->name;
            $model->short_desc = $param->short_desc;
            $purifier = new HtmlPurifier();

            $model->full_desc = $purifier->process($param->full_desc);
            $model->ordering = $param->ordering;
            $model->course_id = $param->course_id;
            $model->state = $param->state;
            $model->lesson_format = $param->lesson_format;
           
            $model->slug = Inflector::slug($param->name);

            if($param->resource_is_changed) {
               
                $model->lesson_resource = $param->lesson_resource;  
            }
          
            // save data
            if ($model->validate()) {
                $model->save();
                if (!$param->lesson_id) {
                    $model->lesson_code = "BH".sprintf('%05d', $model->lesson_id);
                    $model->save();
                }
                

                $this->calculate_percent($model->course_id);

                AttachmentLesson::deleteAll(['lesson_id' => $model->lesson_id]);
                $course = Course::findOne($param->course_id);
                if ($param->attachments) {
                    foreach ($param->attachments as $attach) {
                        $attless = new AttachmentLesson();
                        $attless->lesson_id = $model->lesson_id;
                        $attless->attachment_id = $attach->attachment_id;
                        $attless->save();

                        $updateAttach = Attachment::findOne($attach->attachment_id);
                        $updateAttach->category_id = $course->category_id;
                        $updateAttach->save();
                    }
                }
                $param->lesson_id =  $model->lesson_id;
                return  $param;
            }else {
                return ["error"=>$model->errors,"param"=> $param];
            }
    }
    public function actionDelete()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $course = Lesson::findOne($_GET['id']);
        $course->state = -1;
        $course->save();
        $this->calculate_percent($course->course_id);
        return $course;
    }
    public function actionBulkdelete()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $body = Yii::$app->request->getRawBody();
        Lesson::updateAll(['state' => -1], ['lesson_id' => json_decode($body)]);
        //$this->calculate_percent($model->course_id);
        //$courses = Lesson::deleteAll(['lesson_id' => json_decode($body)]);
        return $courses;

    }
    

  
}
