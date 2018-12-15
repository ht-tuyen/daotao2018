<?php

namespace common\modules\api\models\elearning;

use Yii;


/**
 * This is the model class for table "course_category".
 *
 * @property integer $category_id
 * @property string $name
 * @property string $thumbnail
 * @property string $description
 * @property integer $parent_id
 * @property integer $state
 * @property integer $ordering
 * @property integer $created_by
 * @property string $created_date
 * @property integer $modified_by
 * @property string $modified_date
 *
 * @property CourseItem[] $courseItems
 */
class Category extends \yii\db\ActiveRecord
{
    public $_cats;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'course_category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required','message' => 'Tên chuyên mục không thể để trống'],
            [['description'], 'string'],
            [['parent_id', 'state', 'ordering', 'created_by', 'modified_by'], 'integer'],
            [['created_date', 'modified_date'], 'safe'],
            [['name'], 'string', 'max' => 110, 'message'=>'Tên chuyên mục không được dài quá 100 ký tự']
        ];
    }
    public function upload()
    {
        if ($this->validate()) {
            $this->thumbnail->saveAs(Yii::getAlias('@anyname') . '/uploads/elearning/category/' . $this->thumbnail->baseName . '.' . $this->thumbnail->extension, false);
            return true;
        } else {
            return false;
        }
    }
    public function build_list($parent = 0 , $level = 1, $sign="") {
        $categories = Category::find()->where(['state' => 1,'parent_id'=>$parent])->all();
        $level ++;
        $sign.="--";
        if ($categories) {
           
            foreach ($categories as $category) {
                if ($category->parent_id == 0) {
                    $level= 1;
                    $sign="";
                }
                $this->_cats[] = array(
                    // "level"=>$level,
                    // "state"=>$category->state,
                    // "description"=>$category->description,
                    // "ordering"=>$category->ordering,
                    // "category_id"=>$category->category_id,
                    "value"=>$category->category_id,
                    "name"=>$sign."".$category->name,
                    // "parent_id"=>$category->parent_id,
                    // "sign"=>$sign
                );
                self::build_list($category->category_id, $level,$sign);

            }
        }
        return $this->_cats;
        
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'category_id' => 'ID',
            'name' => 'Chuyên mục',
            'thumbnail' => 'Ảnh đại diện',
            'description' => 'Thông tin',
            'parent_id' => 'Chuyên mục cha',
            'state' => 'Trạng thái',
            'ordering' => 'Thứ tự',
            'created_by' => 'Tạo bởi',
            'created_date' => 'Ngày tạo',
            'modified_by' => 'Sửa bởi',
            'modified_date' => 'Ngày sửa',
            
        ];
    }
    public function fields()
    {
        return [
            'category_id',
            'name',
            'thumbnail',
            'description',
            'parent_id',
            'state' ,
            'ordering' ,
            'created_by',
            'created_date',
            'modified_by',
            'modified_date',
            'parent' => function ($model) {
                return $model->parent->name; // Return related model property, correct according to your structure
            },
            'courses' => function ($model) {
                return $model->courses; // Return related model property, correct according to your structure
            },
            
            
        ];
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCourses()
    {
        return (new \yii\db\Query())
            ->select('*')
            ->from('course_item')
          
            ->where(['category_id' => $this->category_id, 'state'=>1])
            ->all();
    }
    public function getParent()
    {
        return $this->hasOne(Category::className(), ['category_id' => 'parent_id']);
    }

    // public function relations()
    // {
    //     return array(
    //         'parent'=>array(self::BELONGS_TO, 'CourseCategory', 'parent_id'),
    //     );
    // }
}
