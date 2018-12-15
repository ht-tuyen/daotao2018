<?php

namespace common\modules\api\models\elearning;

use Yii;

/**
 * This is the model class for table "attachment_lesson".
 *
 * @property integer $id
 * @property integer $attachment_id
 * @property integer $lesson_id
 *
 * @property Attachments $attachment
 * @property CourseLessons $lesson
 */
class AttachmentLesson extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'attachment_lesson';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['attachment_id', 'lesson_id'], 'required'],
            [['attachment_id', 'lesson_id'], 'integer'],
            [['attachment_id'], 'exist', 'skipOnError' => true, 'targetClass' => Attachment::className(), 'targetAttribute' => ['attachment_id' => 'attachment_id']],
            [['lesson_id'], 'exist', 'skipOnError' => true, 'targetClass' => Lesson::className(), 'targetAttribute' => ['lesson_id' => 'lesson_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'attachment_id' => 'Attachment ID',
            'lesson_id' => 'Lesson ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAttachment()
    {
        return $this->hasOne(Attachment::className(), ['attachment_id' => 'attachment_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLesson()
    {
        return $this->hasOne(Lesson::className(), ['lesson_id' => 'lesson_id']);
    }
}
