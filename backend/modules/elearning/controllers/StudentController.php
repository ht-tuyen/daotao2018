<?php

namespace backend\modules\elearning\controllers;
use common\modules\api\models\elearning\CourseForUser;
use common\modules\api\models\elearning\Log;
use common\modules\api\models\elearning\Student;

use yii\db\Command;
use Yii;
use yii\web\Controller;


/**
 * MemberController implements the CRUD actions for Member model.
 */
class StudentController extends Controller
{
    public function actionIndex()
    {
        return $this->render('vue');
    }
    public function actionHistory($id)
    {
        $courses = CourseForUser::find()
        ->where(['user_id' =>$id])
        ->all();
        $logs = Log::find()->where(['user_id' =>$id])->orderBy(['id'=>SORT_DESC])
        ->all();
        //$connection = new \yii\db\Connection();
        
        $whereCreated = " and created_date <= '".date("Y-m-d H:i:s")."' and created_date >= '".date("Y-m-d H:i:s",strtotime('-30 day'))."'";
        $whereFinsihed = " and finished_date <= '".date("Y-m-d H:i:s")."' and finished_date >= '".date("Y-m-d H:i:s",strtotime('-30 day'))."'";
        $whereActived = " and approved_date <= '".date("Y-m-d H:i:s")."' and approved_date >= '".date("Y-m-d H:i:s",strtotime('-30 day'))."'";
        

        $chart1 = Yii::$app->db->createCommand('SELECT `date`, SUM(created) AS created, SUM(finished) AS finished FROM ( SELECT DATE(created_date) AS `date`, 0 AS finished, COUNT(*) AS created FROM course_for_user where user_id = '.$id.$whereCreated.' GROUP BY DATE(created_date) UNION ALL SELECT DATE(finished_date), COUNT(*), 0 FROM course_for_user where user_id ='.$id.$whereFinsihed.' and `finished_date` != "0000-00-00 00:00:00" GROUP BY DATE(finished_date) )x GROUP BY `date`')->queryAll();

        $chart2 = Yii::$app->db->createCommand('SELECT `date`, SUM(created) AS created, SUM(finished) AS finished, SUM(actived) AS actived FROM ( SELECT DATE(created_date) AS `date`, 0 AS actived, 0 AS finished, COUNT(*) AS created FROM course_for_user where user_id = '.$id.$whereCreated.' GROUP BY DATE(created_date) UNION ALL SELECT DATE(finished_date), 0, COUNT(*), 0 FROM course_for_user where user_id ='.$id.$whereFinsihed.' and `finished_date` != "0000-00-00 00:00:00" GROUP BY DATE(finished_date) UNION ALL SELECT DATE(approved_date), COUNT(*), 0, 0 FROM course_for_user where user_id ='.$id.$whereActived.' and `approved_date` != "0000-00-00 00:00:00" GROUP BY DATE(approved_date))x')->queryAll();

        $registrations = CourseForUser::find()->where([
            'user_id' =>$id,
        ])->count();
        $completed = CourseForUser::find()->where([
            'user_id' =>$id,
            'finished'=>1
        ])->count();
        $week = Log::find()->where([
            'user_id' =>$id,
            'type'=>3
        ])->andFilterWhere(['between','date',date("Y-m-d H:i:s", strtotime('-7 day')), date("Y-m-d H:i:s")])->count();
        $month = Log::find()->where([
            'user_id' =>$id,
            'type'=>3
           
        ])->andFilterWhere(['between','date',date("Y-m-d H:i:s", strtotime('-30 day')), date("Y-m-d H:i:s")])->count();
        $last_login = Log::find()->where([
            'user_id' =>$id,
            'type'=>3
        ])->orderBy(['id'=>SORT_DESC])->one();
        return $this->render('history',[
            'registrations'=>$registrations,
            'week'=>$week,
            'month'=>$month,
            'last_login'=>$last_login,
            'completed'=>$completed,
            'courses'=>$courses,
            'logs'=>$logs,
            'chart1'=>$chart1,
            'chart2'=>$chart2,
            'user'=>Student::findOne($id)
        ]);
    }
}
