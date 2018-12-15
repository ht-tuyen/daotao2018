<?php

namespace common\components;
// namespace app\components; // For Yii2 Basic (app folder won't actually exist)
use backend\modules\elearning\models\CourseCategory;
class MultiLevelsHelpers
{
    public static function getMenu($parent_id)
    {
       
        $result = static::getMenuRecrusive($parent_id, 1);
        return $result;
    }

    private static function getMenuRecrusive($parent, $level)
    {

        $items = CourseCategory::find()
            ->where(['parent_id' => $parent])
            ->orderBy('ordering')
            ->asArray()
            ->all();

        $result = []; 

        foreach ($items as $item) {
            $result[] = [
                    'label' => $item['name'],
                    'url' => ['#'],
                    'level'=>$level++,
                    'items' => static::getMenuRecrusive($item['category_id'], $level ),
                    '----',
                ];
        }
        return $result;
    }

}