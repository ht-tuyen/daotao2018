<?php

namespace frontend\modules\course\models;

use Yii;

class User extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'qli_user';
    }

   

}
