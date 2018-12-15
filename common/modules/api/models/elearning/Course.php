<?php

namespace common\modules\api\models\elearning;
use common\modules\api\models\elearning\Category;
use common\modules\api\models\elearning\CourseForUser;
use common\modules\api\models\elearning\QuizResult;
use common\modules\api\models\elearning\Quiz;
use common\modules\api\models\elearning\LessonForUser;
use common\modules\api\models\elearning\TeacherCourse;


use Yii;

/**
 * This is the model class for table "course_item".
 *
 * @property integer $course_id
 * @property string $name
 * @property string $thumbnail
 * @property string $short_desc
 * @property string $full_desc
 * @property integer $category_id
 * @property integer $state
 * @property integer $ordering
 * @property integer $created_by
 * @property string $created_date
 * @property integer $modified_by
 * @property string $modified_date
 *
 * @property CourseForUser[] $courseForUsers
 * @property CourseCategory $category
 * @property CourseLessons[] $courseLessons
 * @property Quiz[] $quizzes
 */
class Course extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'course_item';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name',  'full_desc', 'category_id', 'state'], 'required','message' => '{attribute} không được để trống'],
            [[ 'full_desc'], 'string'],
            [['category_id', 'state', 'ordering', 'created_by', 'modified_by','ready'], 'integer'],
            [['created_date', 'modified_date'], 'safe'],
            [['name'], 'string', 'max' => 250],
          
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'course_id' => 'Course ID',
            'name' => 'Khóa học',
            'thumbnail' => 'Thumbnail',
            'short_desc' => 'Giới thiệu',
            'full_desc' => 'Thông tin khóa học',
            'category_id' => 'Chuyên mục',
            'state' => 'State',
            'ordering' => 'Ordering',
            'created_by' => 'Created By',
            'created_date' => 'Created Date',
            'modified_by' => 'Modified By',
            'modified_date' => 'Modified Date',
        ];
    }
    public function fields()
    {
        return [
            'course_id',
            'name',
            'thumbnail',
            'short_desc',
            'full_desc',
            'full_image',
            'category_id',
            'ready',
            'state',
            'slug',
            'ordering',
            'created_by',
            'created_date',
            'modified_by',
            'modified_date',
            'featured',
            'category' => function ($model) {
                return $model->category->name; // Return related model property, correct according to your structure
            },
            'teacher' => function ($model) {
                return $model->teacher->fullname; // Return related model property, correct according to your structure
            },
            'teachers' => function ($model) {
                return $model->teachers; // Return related model property, correct according to your structure
            },
            'students'=>function ($model){
                return $model->students;
            },
            'course_student'=>function ($model){
                return $model->courseForUsers;
            },
            'lessons'=>function ($model){
                return $model->lessons;
            },
            'quiz'=>function ($model){
                return $model->quiz;
            },
            'active_users'=>function ($model){
                return $model->activeUsers;
            },
            'inactive_users'=>function ($model){
                return $model->inactiveUsers;
            },
            'completed_quizes'=>function ($model){
                return $model->completedQuizes;
            },
            'main_teacher',
            'completed'=>function($model){
                $finished = 0;
                if (count($model->lessons)) {
                    foreach ($model->lessons as $lesson) {
                        if ($lesson->finished == 1)
                        $finished ++;
                    }
                    return $finished/count($model->lessons)*100;
                }
                return 0;
            }
        ];
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCompleted(){
        $percentage= LessonForUser::find()->where([
            'course_id'=>$this->course_id,
            'user_id'=>Yii::$app->user->id,
            'finished'=>1
        ])->sum('percentage');
        return ceil($percentage);
    }
    public function getTeacherCourse(){
        return $this->hasMany(TeacherCourse::className(), ['course_id' => 'course_id']);

    }
    public function getTeachers()
    {
       // return $this->hasMany(AttachmentLesson::className(), ['lesson_id' => 'lesson_id']);
            return $this->hasMany(User::className(), ['user_id' => 'user_id'])
       ->via('teacherCourse');
    }
    public function getCourseForUsers()
    {
        return $this->hasMany(CourseForUser::className(), ['course_id' => 'course_id']);
    }
    public function getActiveUsers()
    {
        return CourseForUser::find()->where(['state'=>1, 'course_id'=>$this->course_id])->select('user_id')->distinct()->count();

    }
    public function getMainTeacher()
    {
        return TeacherCourse::find()->where(['is_main'=>1, 'course_id'=>$this->course_id])->one();

    }
    public function getInactiveUsers()
    {
        return CourseForUser::find()->where(['state'=>-1, 'course_id'=>$this->course_id])->select('user_id')->distinct()->count();

    }
    public function getCompletedQuizes()
    {
        return (new \yii\db\Query())
            ->select(['user_id'])
            ->from('quiz_result')
            ->innerJoin('quiz', 'quiz.quiz_id = quiz_result.quiz_id')
            ->where(['quiz.quiz_type' => 2, 'quiz.item_id'=>$this->course_id])
            ->distinct()
            ->count();

    }
    public function getStudents()
    {
        return $this->hasMany(Student::className(), ['id' => 'user_id'])
            ->viaTable('course_for_user', ['course_id' => 'course_id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['category_id' => 'category_id']);
    }

    public function getTeacher()
    {
        return $this->hasOne(User::className(), ['user_id' => 'created_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLessons()
    {
        return $this->hasMany(Lesson::className(), ['course_id' => 'course_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQuiz()
    {
        return $this->hasOne(Quiz::className(), ['course_id' => 'course_id']);
        //return Quiz::find()->where(['quiz_type'=>2, 'item_id'=>$this->course_id])->one();
    }
}
