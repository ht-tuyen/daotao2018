<?php

namespace common\modules\api\models\elearning;
use common\modules\api\models\elearning\Category;

use Yii;

/**
 * This is the model class for table "attachments".
 *
 * @property integer $attachment_id
 * @property string $name
 * @property string $description
 * @property integer $state
 * @property string $source
 * @property integer $category_id
 * @property integer $modified_by
 * @property integer $created_by
 * @property string $created_date
 * @property string $modified_date
 * @property string $slug
 *
 * @property CourseCategory $category
 */
class Attachment extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'attachments';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name',  'state'], 'required','message' => '{attribute} không được để trống'],
            [['description'], 'string'],
            [['state', 'category_id', 'modified_by', 'created_by'], 'integer'],
            [['created_date', 'modified_date'], 'safe'],
            [['name', 'source', 'slug'], 'string', 'max' => 255],
            //[['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::className(), 'targetAttribute' => ['category_id' => 'category_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'attachment_id' => 'ID',
            'name' => 'Tên tài liệu',
            'description' => 'Thông tin',
            'state' => 'Trạng thái',
            'source' => 'Source',
            'category_id' => 'Chuyên mục',
            'modified_by' => 'Modified By',
            'created_by' => 'Created By',
            'created_date' => 'Created Date',
            'modified_date' => 'Modified Data',
            'slug' => 'Slug',
        ];
    }
    public function fields()
    {
        return [
            'attachment_id',
            'name',
            'description',
            'state' ,
            'source',
            'category_id' ,
            'modified_by',
            'created_by',
            'created_date' ,
            'modified_date',
            'slug'  ,
            'category' => function ($model) {
                return $model->category->name; // Return related model property, correct according to your structure
            },
            'att_less_id' =>function ($model){
                return $model->attachmentLesson;
            }
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['category_id' => 'category_id']);
    }
    public function getAttachmentLesson(){
        return $this->hasMany(AttachmentLesson::className(), ['attachment_id' => 'attachment_id']);
    }
    public function getLessons()
    {
       // return $this->hasMany(AttachmentLesson::className(), ['lesson_id' => 'lesson_id']);
            return $this->hasMany(Lesson::className(), ['lesson_id' => 'lesson_id'])
       ->via('attachmentLesson');
    }
}
