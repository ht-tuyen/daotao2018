<?php

namespace backend\models;

use Yii;
use yii\web\UploadedFile;
use backend\helpers\AcpHelper;
use \yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "{{%attach}}".
 *
 * @property integer $a_id
 * @property string $title
 * @property string $file
 * @property string $link
 * @property integer $type
 * @property integer $sort
 * @property integer $status
 * @property string $create_time
 * @property string $update_time
 * @property integer $customer_id
 */
class Attach extends \yii\db\ActiveRecord
{
    public $file_hidden = null;
    private static $_all = null;

    const TYPE_FILE_DESIGN = 1;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%attach}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['type', 'sort', 'status', 'customer_id'], 'integer'],
            [['create_time', 'update_time'], 'safe'],
            [['title', 'file', 'link'], 'string', 'max' => 300],
        ];
    }

    public function custom($attribute, $params){
        if (!$this->file
            || !$this->link
        ) {
            $this->addError($attribute, 'Bạn phải tải tập tin hoặc điền vào đường dẫn ảnh');

            return false;
        }

        return true;
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'a_id' => 'A ID',
            'title' => 'Title',
            'file' => 'File',
            'link' => 'Link',
            'type' => 'Type',
            'sort' => 'Sort',
            'status' => 'Status',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
            'customer_id' => 'Customer ID',
        ];
    }

    public static function getAll($type = self::TYPE_FILE_DESIGN, $customer_id = 0) {
        $customer_id = (int) $customer_id;
        if (!isset(self::$_all)) {
            self::$_all = self::find()->where([
                'status' => 1,
                'type' => $type,
                'customer_id' => $customer_id,
            ])->orderBy(['sort' => SORT_ASC, 'create_time' => SORT_DESC])->all();
        }
        return self::$_all;
    }

    public static function getListOptions($type = self::TYPE_FILE_DESIGN, $customer_id = 0) {
        $attachs = self::getAll($type, $customer_id);
        $arr_attachs = [];
        if (!empty($attachs)) {
            foreach ($attachs as $attach) {
                $arr_attachs[$attach->primaryKey] = $attach->title;
            }
        }
        return $arr_attachs;
    }

    public static function getListJson() {
        $attachs = self::getAll();
        $arr_attachs = [];
        if (!empty($attachs)) {
            foreach ($attachs as $attach) {
                array_push($arr_attachs, $attach->attributes);
            }
        }
        return json_encode($arr_attachs);
    }

    public static function getById($a_id, $customer_id = 0) {
        $all = self::getAll(self::TYPE_FILE_DESIGN, $customer_id);
        if (!empty($all)) {
            foreach ($all as $c) {
                if ($c->a_id == $a_id) {
                    return $c;
                }
            }
        }
        return false;
    }

    public function beforeValidate()
    {
        Yii::$app->params['uploadPath'] = Yii::getAlias('@anyname') . '/uploads/attach/';
        $image = UploadedFile::getInstance($this, 'file');
        if (!empty($image)) {
            $pathinfo = pathinfo($image);
            $filename = AcpHelper::alias($pathinfo['filename']) . '.' . $pathinfo['extension'];
            $this->file = $filename;
        }
        if(empty($this->file) && empty($this->link)){
            $this->addError('file', 'Bạn phải tải tập tin hoặc nhập vào đường dẫn tập tin');
        }

        return parent::beforeValidate(); // TODO: Change the autogenerated stub
    }

    public function afterSave($insert, $changedAttributes)
    {
        $path = Yii::$app->params['uploadPath'] . $this->file;
        $image = UploadedFile::getInstance($this, 'file');
        if (!empty($image)) {
            $image->saveAs($path);
        }
        parent::afterSave($insert, $changedAttributes); // TODO: Change the autogenerated stub
    }

    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['create_time'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['update_time'],
                ],
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    public function getUploadDir()
    {
        $uploadDir = 'uploads' . DIRECTORY_SEPARATOR . 'attach' . DIRECTORY_SEPARATOR . $this->primaryKey;
        return $uploadDir;
    }
}