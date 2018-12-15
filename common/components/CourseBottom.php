<?php 
namespace common\components;

use yii\base\Widget;
use yii\helpers\Html;
use common\modules\api\models\elearning\Course;
use common\modules\api\models\elearning\Lesson;
use common\modules\api\models\elearning\Quiz;

class CourseBottom extends Widget
{
    public $course_id;
    public $course;
    public $lessons;
    public $lesson_id;
    public function init()
    {
        parent::init();
        $this->course = Course::findOne($this->course_id);
        $this->lessons = Lesson::find()->where(['course_id'=>$this->course_id])->all();
      

     
    }

    public function run()
    {
        return $this->render('coursebottom',[
            'course'=>$this->course,
            'course_id'=>$this->course_id,
            'lesson_id'=>$this->lesson_id,
           
           
        ]);;
    }
}
?>