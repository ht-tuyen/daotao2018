<?php

namespace common\modules\api\models\elearning;

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
