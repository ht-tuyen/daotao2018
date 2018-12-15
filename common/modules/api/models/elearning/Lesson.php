<?php

namespace common\modules\api\models\elearning;
use common\modules\api\models\elearning\Log;

use Yii;

/**
 * This is the model class for table "course_lessons".
 *
 * @property integer $lesson_id
 * @property string $name
 * @property integer $course_id
 * @property integer $lesson_format
 * @property string $lesson_resource
 * @property string $thumbnail
 * @property string $short_desc
 * @property string $full_desc
 * @property integer $state
 * @property integer $ordering
 * @property integer $created_by
 * @property string $created_date
 * @property integer $modified_by
 * @property string $modified_date
 *
 * @property Attachments[] $attachments
 * @property CourseItem $course
 */
class Lesson extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'course_lessons';
    }
  

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'course_id', 'lesson_format', 'short_desc', 'full_desc', 'state'], 'required','message' => '{attribute} không được để trống'],
            [['course_id', 'lesson_format', 'state'], 'integer'],
            [['short_desc', 'full_desc','lesson_code'], 'string'],
            [['created_date', 'modified_date'], 'safe'],
            [['name'], 'string', 'max' => 255],
            [['course_id'], 'exist', 'skipOnError' => true, 'targetClass' => Course::className(), 'targetAttribute' => ['course_id' => 'course_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'lesson_id' => 'Lesson ID',
            'name' => 'Tên bài học',
            'course_id' => 'Khóa học',
            'lesson_format' => 'Lesson Format',
            'lesson_resource' => 'Lesson Resource',
            'thumbnail' => 'Thumbnail',
            'short_desc' => 'Giới thiệu',
            'full_desc' => 'Thông tin khóa học',
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
            'lesson_id' ,
            'name',
            'course_id' ,
            'lesson_format',
            'lesson_resource',
            'lesson_code',
            'thumbnail' ,
            'short_desc',
            'full_desc',
            'state',
            'slug',
            'ordering',
            'created_by' ,
            'created_date',
            'modified_by',
            'modified_date',
            'finished',
            'percentage',
            'main_teacher',
            'category_id'=>function ($model) {
                return $model->course->category_id;
            },
            'course' => function ($model) {
                return $model->course->name; // Return related model property, correct according to your structure
            },
            'attachments'=> function ($model){
                return $model->attachments;
            },
            'quizes'=> function($model){
                return $model->quizes;
            },
            'students'=> function($model){
                return $model->students;
            },
            'completed'=> function($model){
                return $model->completed;
            },
            'student_detail'=> function($model){
                return $model->studentDetail;
            },
            
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAttachmentLesson(){
        return $this->hasMany(AttachmentLesson::className(), ['lesson_id' => 'lesson_id']);

    }
    public function getQuizes()
    {
        return $this->hasMany(Quiz::className(), ['lesson_id' => 'lesson_id']);

    }
    
    
    public function getAttachments()
    {
       // return $this->hasMany(AttachmentLesson::className(), ['lesson_id' => 'lesson_id']);
            return $this->hasMany(Attachment::className(), ['attachment_id' => 'attachment_id'])
       ->via('attachmentLesson');
    }
   
    public function getCompleted()
    {
        return LessonForUser::find()->where(['finished'=>1, 'lesson_id'=>$this->lesson_id])->select('user_id')->distinct()->count();

    }
    public function getStudents()
    {
        return LessonForUser::find()->where(['lesson_id'=>$this->lesson_id])->select('user_id')->distinct()->count();

    }
    
    public function getStudentDetail()
    {
        return $this->hasMany(Student::className(), ['id' => 'user_id'])
            ->viaTable('lesson_for_user', ['lesson_id' => 'lesson_id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCourse()
    {
        return $this->hasOne(Course::className(), ['course_id' => 'course_id']);
    }
    public function checkCompleted()
    {
        return LessonForUser::find()->where(['finished'=>1, 'lesson_id'=>$this->lesson_id,'user_id'=> Yii::$app->user->id])->one();

    }
 
    
}
