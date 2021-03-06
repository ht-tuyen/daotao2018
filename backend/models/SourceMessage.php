<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "{{%source_message}}".
 *
 * @property integer $id
 * @property string $category
 * @property string $message
 */
class SourceMessage extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%source_message}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['category', 'message'], 'required'],
            [['id'], 'integer'],
            [['message'], 'string'],
            [['category'], 'string', 'max' => 32],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'category' => 'Category',
            'message' => 'Message',
        ];
    }

    public function afterSave($insert, $changedAttributes)
    {
        $primaryKey = $this->primaryKey;

        if( !empty($_POST['Message']) ){
            foreach($_POST['Message'] as $language => $translation){
                if( empty($translation) )
                    continue;
                $message = Message::findOne([
                    'id' => $primaryKey,
                    'language' => $language,
                ]);
                if( !isset($message) ){
                    $message = new Message;
                }
                $message->attributes = array(
                    'language' => $language,
                    'translation' => $translation,
                    'id' => $primaryKey,
                );
                $message->save();
            }
        }

        parent::afterSave($insert, $changedAttributes); // TODO: Change the autogenerated stub
    }

}
