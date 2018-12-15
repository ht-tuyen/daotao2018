<?php 
namespace common\components;

use yii\base\Widget;
use yii\helpers\Html;
use common\modules\api\models\elearning\Course;
use common\modules\api\models\elearning\Lesson;
use common\modules\api\models\elearning\Quiz;
use common\modules\api\models\elearning\QuizResult;
use Yii;
class CourseSideBar extends Widget
{
    public $course_id;
    public $active_type;
    public $active_id;
    public $course;
    public $lessons;
    public $quiz;
    public $completed_quiz;
    public function init()
    {
        parent::init();
        $this->course = Course::findOne($this->course_id);
        $this->lessons = Lesson::find()->where(['course_id'=>$this->course_id])->orderBy(['ordering'=>SORT_ASC, 'created_date'=>SORT_DESC])->all();
        $this->quiz = Quiz::find()->where(['course_id'=>$this->course_id])->one();
        $this->completed_quiz = QuizResult::find()->where([
            'quiz_id'=>$this->quiz->quiz_id,
            'user_id'=> Yii::$app->user->id
        ])->one();
    }

    public function run()
    {
        return $this->render('coursesidebar',[
            'course'=>$this->course,
            'course_id'=>$this->course_id,
            'lessons'=>$this->lessons,
            'quiz'=>$this->quiz,
            'active_type'=>$this->active_type,
            'active_id'=>$this->active_id,
            'completed_quiz'=> $this->completed_quiz
        ]);;
    }
}
?>