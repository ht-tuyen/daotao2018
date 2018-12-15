<?php

namespace common\modules\api\models\elearning;
use backend\models\User;
use Yii;

/**
 * This is the model class for table "messages".
 *
 * @property integer $message_id
 * @property integer $user_id
 * @property string $message
 * @property string $created_date
 * @property integer $lesson_id
 * @property integer $status
 */
class Message extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'messages';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'message', 'lesson_id'], 'required'],
            [['user_id', 'lesson_id', 'status','parent_id','is_admin'], 'integer'],
            [['message'], 'string'],
            [['created_date'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'message_id' => 'Message ID',
            'user_id' => 'User ID',
            'message' => 'Message',
            'created_date' => 'Created Date',
            'lesson_id' => 'Lesson ID',
            'status' => 'Status',
        ];
    }
    public function fields()
    {
        return [
            'message_id' ,
            'user_id' ,
            'message' ,
            'created_date' ,
            'lesson_id' ,
            'status',
            'user' => function ($model) {
                return $model->user;
            },
            'replies' => function ($model) {
                return $model->replies;
            },
            'is_admin'
        ];

    }
    public function getUser()
    {
        if ($this->is_admin) {
            return User::find()->where(['user_id'=>$this->user_id])->one();
        }
        return $this->hasOne(Student::className(), ['id' => 'user_id']);
    }
    public function getReplies()
    {
        return $this->hasMany(Message::className(), ['parent_id' => 'message_id']);
    }
}
