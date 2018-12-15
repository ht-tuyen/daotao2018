<?php 
namespace common\components;

use yii\base\Widget;
use yii\helpers\Html;
use common\modules\api\models\elearning\Course;
class HomeCourses extends Widget
{
    public $courses;

    public function init()
    {
        parent::init();
        $this->courses = Course::find()->where(['state'=>1 ,'featured'=>1])->orderBy(['ordering'=>SORT_ASC, 'created_date'=>SORT_DESC])->limit(9)->all();
    }

    public function run()
    {
        return $this->render('homecourse',['courses'=>$this->courses]);;
    }
}
?>