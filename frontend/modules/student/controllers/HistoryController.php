<?php

namespace frontend\modules\student\controllers;
use common\modules\api\models\elearning\CourseForUser;
use common\modules\api\models\elearning\Log;
use yii\db\Command;
use Yii;
use yii\web\Controller;

/**
 * Default controller for the `student` module
 */
class HistoryController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */

    /*
        Logtype
        1: View Lesson
        2: View Course
        3: Login
        4: Complete Lesson
        5: Complete Course
        6: Complete Quiz

    */

    public function actionIndex()
    { 
        $courses = CourseForUser::find()
        ->where(['user_id' => Yii::$app->user->id])
        ->all();
        $logs = Log::find()->where(['user_id' => Yii::$app->user->id])->orderBy(['id'=>SORT_DESC])
        ->all();
        //$connection = new \yii\db\Connection();
        
        $whereCreated = " and created_date <= '".date("Y-m-d H:i:s")."' and created_date >= '".date("Y-m-d H:i:s",strtotime('-30 day'))."'";
        $whereFinsihed = " and finished_date <= '".date("Y-m-d H:i:s")."' and finished_date >= '".date("Y-m-d H:i:s",strtotime('-30 day'))."'";
        $whereActived = " and approved_date <= '".date("Y-m-d H:i:s")."' and approved_date >= '".date("Y-m-d H:i:s",strtotime('-30 day'))."'";
        

        $chart1 = Yii::$app->db->createCommand('SELECT `date`, SUM(created) AS created, SUM(finished) AS finished FROM ( SELECT DATE(created_date) AS `date`, 0 AS finished, COUNT(*) AS created FROM course_for_user where user_id = '.Yii::$app->user->id.$whereCreated.' GROUP BY DATE(created_date) UNION ALL SELECT DATE(finished_date), COUNT(*), 0 FROM course_for_user where user_id ='.Yii::$app->user->id.$whereFinsihed.' and `finished_date` != "0000-00-00 00:00:00" GROUP BY DATE(finished_date) )x GROUP BY `date`')->queryAll();

        $chart2 = Yii::$app->db->createCommand('SELECT `date`, SUM(created) AS created, SUM(finished) AS finished, SUM(actived) AS actived FROM ( SELECT DATE(created_date) AS `date`, 0 AS actived, 0 AS finished, COUNT(*) AS created FROM course_for_user where user_id = '.Yii::$app->user->id.$whereCreated.' GROUP BY DATE(created_date) UNION ALL SELECT DATE(finished_date), 0, COUNT(*), 0 FROM course_for_user where user_id ='.Yii::$app->user->id.$whereFinsihed.' and `finished_date` != "0000-00-00 00:00:00" GROUP BY DATE(finished_date) UNION ALL SELECT DATE(approved_date), COUNT(*), 0, 0 FROM course_for_user where user_id ='.Yii::$app->user->id.$whereActived.' and `approved_date` != "0000-00-00 00:00:00" GROUP BY DATE(approved_date))x')->queryAll();

        $registrations = CourseForUser::find()->where([
            'user_id' => Yii::$app->user->id,
        ])->count();
        $completed = CourseForUser::find()->where([
            'user_id' => Yii::$app->user->id,
            'finished'=>1
        ])->count();
        $week = Log::find()->where([
            'user_id' => Yii::$app->user->id,
            'type'=>3
        ])->andFilterWhere(['between','date',date("Y-m-d H:i:s", strtotime('-7 day')), date("Y-m-d H:i:s")])->count();
        $month = Log::find()->where([
            'user_id' => Yii::$app->user->id,
            'type'=>3
           
        ])->andFilterWhere(['between','date',date("Y-m-d H:i:s", strtotime('-30 day')), date("Y-m-d H:i:s")])->count();
        $last_login = Log::find()->where([
            'user_id' => Yii::$app->user->id,
            'type'=>3
        ])->orderBy(['id'=>SORT_DESC])->one();
        return $this->render('index',[
            'registrations'=>$registrations,
            'week'=>$week,
            'month'=>$month,
            'last_login'=>$last_login,
            'completed'=>$completed,
            'courses'=>$courses,
            'logs'=>$logs,
            'chart1'=>$chart1,
            'chart2'=>$chart2
        ]);
    }
}
