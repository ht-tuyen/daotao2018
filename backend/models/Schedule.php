<?php

namespace backend\models;

use Yii;
use backend\models\User;
use yii\helpers\ArrayHelper;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "qli_schedule".
 *
 * @property integer $schedule_id
 * @property integer $order_info_id
 * @property string $title
 * @property string $time
 * @property integer $user_id
 * @property integer $status
 * @property integer $approval
 * @property string $create_time
 * @property string $update_time
 * @property string $feedback
 */
class Schedule extends \yii\db\ActiveRecord
{

    const CHUA_DUYET = 0, DA_DUYET = 1, PHAN_HOI = 2;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'qli_schedule';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'time', 'time_done', 'user_id'], 'required'],
            [['order_info_id', 'approval'], 'integer'],
            [['time', 'time_done','create_time', 'update_time'], 'safe'],
            [['title', 'feedback'], 'string', 'max' => 255],
            [['user_id'], 'string'],
        ];
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

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'schedule_id' => 'Schedule ID',
            'order_info_id' => 'Order Info ID',
            'title' => 'Title',
            'time' => 'Time',
            'time_done' => 'Time done',
            'user_id' => 'User ID',
            'status' => 'Status',
            'approval' => 'Approval',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
            'feedback' => 'Feedback',
        ];
    }

    public static function congDoanSanXuat() {

        $items = [
            0 => 'Nhập giấy chuyển tới xưởng in',
            1 => 'Bắt đầu công đoạn in',
            2 => 'Hoàn thành công đoạn in',
            3 => 'Bắt đầu gia công',
            4 => 'Hoàn thành gia công',
            5 => 'Tiếp nhận xử lý đơn hàng',
            6 => 'Thiết kê chế bản',
            7 => 'In',
            8 => 'Gia công thành phẩm',
            9 => 'Đóng gói',
            10 => 'Vận chuyển',
        ];

	    $settings = Yii::$app->settings;

	    $list = $settings->get('list_cong_doan', '');

	    if ($list != '') {
		    $items = [];

		    $list_cong_doan = explode(',', $list);
		    foreach ( $list_cong_doan as $cong_doan ) {
			    $items[$cong_doan] = $settings->get('cong_doan_' . $cong_doan .'_ten', '');
		    }
	    }


        return $items;

    }

    public static function getListNhanVien()
    {

        $all = User::find()->where('user_id != 1')->orderBy(['fullname' => SORT_ASC])->all();
        if (!empty($all)) {
            return ArrayHelper::map($all, 'user_id', 'fullname');
        }
        return array();
    }

    public static function getStatusOptions()
    {
        return Orders::definedOrderStatus();
    }

    public static function getStatusLabel_($value = null)
    {
        $array = self::getStatusOptions();
        if ($value === null || !array_key_exists($value, $array))
            return ' - ';
        return $array[$value];
    }

    public function getInfo()
    {
        return $this->hasOne(OrderInfo::className(), ['info_id' => 'order_info_id']);
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['user_id' => 'user_id']);
    }

    public static function getApprovalOptions()
    {
        return array(
            self::CHUA_DUYET => 'Chưa duyệt',
            self::DA_DUYET => 'Đã duyệt',
            self::PHAN_HOI => 'Phản hồi',
        );
    }

    public static function getApprovalLabel($value = null)
    {
        $array = self::getApprovalOptions();
        if ($value === null || !array_key_exists($value, $array))
            return ' - ';
        return $array[$value];
    }
}
