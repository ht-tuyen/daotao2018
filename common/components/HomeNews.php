<?php 
namespace common\components;

use yii\base\Widget;
use yii\helpers\Html;
use frontend\modules\news\models\NewsItem;
class HomeNews extends Widget
{
    public $news;

    public function init()
    {
        parent::init();
        $this->news = NewsItem::find()->limit(4)->all();
    }

    public function run()
    {
        return $this->render('homenews',['news'=>$this->news]);;
    }
}
?>