<?php

namespace frontend\modules\news\controllers;
use frontend\modules\news\models\NewsCategory;
use frontend\modules\news\models\NewsItem;
use yii\web\Controller;
use yii\data\Pagination;
/**
 * Default controller for the `news` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        $items = NewsItem::find()->where([
            'trangthai' => 1,
        ])->orderBy(['tt_id'=>'desc']);
        $countQuery = clone $items;
        $pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 18  ]);
        $models = $items->offset($pages->offset)
            ->limit($pages->limit)
            ->all();

        return $this->render('index', [
            'items' => $models,
            'pages' => $pages,
        ]);
        //return $this->render('index', ['items' => $items]);
    }
    public function actionTest() {
        echo "ok";
    }
    public function actionViewCategory($id)
    {

    }
    public function actionViewitem($id)
    {
        return $this->render('viewitem', [
            'item' => NewsItem::findOne($id)
        ]);

    }
    
}
