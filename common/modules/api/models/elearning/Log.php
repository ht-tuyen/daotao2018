<?php

namespace common\modules\api\models\elearning;

use Yii;

class Log extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'log';
    }
    /*
        Logtype
        1: View Lesson
        2: View Course
        3: Login
        4: Complete Lesson
        5: Complete Course
        6: Complete Quiz

    */
    public function getLabel(){
        switch($this->type) {
            case 1:
            case 2:
                $label = "Xem";
                break;
            case 3:
                $label = "Đăng nhập";
                break;
            case 4:
            case 5:
            case 6:
                $label = "Hoàn thành";
                break;
            case 7:
                $label = "Đăng ký";
                break;
            default:
                $label = "Xem";
        }
        return $label;

    }
    public function getText(){
        
        return "";
    }
}   
