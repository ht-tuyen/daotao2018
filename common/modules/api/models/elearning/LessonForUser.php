<?php

namespace common\modules\api\models\elearning;
use common\modules\api\models\elearning\QuizResult;
use common\modules\api\models\elearning\Student;

use Yii;

/**
 * This is the model class for table "course_for_user".
 *
 * @property integer $id
 * @property integer $course_id
 * @property integer $user_id
 * @property integer $state
 * @property string $created_date
 * @property string $approved_date
 *
 * @property CourseItem $course
 */
class LessonForUser extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'lesson_for_user';
    }

    public function getStudent()
    {
        return Student::findOne($this->user_id);
    }
   
}
