<?php
namespace frontend\models;

use Yii;
use backend\models\Regions;
use backend\models\Countries;
use backend\models\Member;
use backend\models\Tieuchuan;
use yii\helpers\ArrayHelper;

class Order extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'qli_order';
    }

    public $create_time_old;

    const CHUAXULY = 1;
    const DANGXULY = 2;
    const HOANTHANH = 3;
    const HUY = 4;
    const DAXEM = 5;

    public function rules()
    {
        return [         
            [['hoten', 'sdt'], 'required',  'message' => 'Vui lòng nhập {attribute}'],
            [['anh_chi', 'country_id', 'province', 'member_id','tinhtrang'], 'integer'],
            [['create_time', 'item', 'total'], 'safe'],
            [['hoten', 'sdt', 'yck', 'sonha', 'time'], 'string', 'max' => 255],
            [['ghichu'],'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'order_id' => 'Order ID',
            'anh_chi' => 'Anh chị',
            'hoten' => 'Họ tên',
            'sdt' => 'Số điện thoại',
            'yck' => 'Yêu cầu khác',
            'country_id' => 'Đất nước',
            'province' => 'Thành phố',
            'sonha' => 'Số nhà',
            'time' => 'Thời gian giao hàng',
            'total' => 'Tổng thanh toán',
            'tinhtrang' => 'Tình trạng',
            'ghichu' => 'Ghi chú',
        ];
    }

    public function afterFind()
    {
        $this->create_time_old = $this->create_time;
        if(!empty($this->create_time)) $this->create_time = date("H:i d-m-Y", strtotime($this->create_time));
        if(!empty($this->time)) $this->time = date("d-m-Y", strtotime($this->time));
        parent::afterFind();
    }


    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }
              

        $thanh_tien = 0;
        $item = $this->item;
        $list_item = [];

        if(is_array($item)) foreach ($item as $k => $v) {
            $tc = Tieuchuan::find()->andWhere(['sohieu' => $v['sohieu']])->one();                 
            if($tc){
                if(!empty($v['soluong']) && !empty($v['dongia'])){
                    $thanh_tien +=  ($v['soluong'] * $v['dongia']);
                    $list_item[$tc->tc_id] = [
                        'dongia' => $v['dongia'],
                        'soluong' => $v['soluong'],
                    ];
                }
            }
            $this->total = $thanh_tien;
            $this->item = json_encode($list_item);
        }
     

        if (empty($this->tinhtrang)) {
            $this->tinhtrang = self::CHUAXULY;
        }
        
        if(!empty($this->create_time)){
            $this->create_time = $this->create_time_old;
        }else{
            $this->create_time = date('Y-m-d H:i:s',time());
        } 
        

        return true;
    }

    public function diachi()
    {
        $country = Countries::findOne($this->country_id);
        $province = Regions::getListLabel($this->province);

        return $this->sonha . (!empty($province) && $province != ' - ' ? " - " . $province : "") . (!empty($country) ? " - " . $country->title : "");
    }

    public static function getMemberOptions(){
        $member = Order::find()
                        ->select(['member_id','fullname'])
                        ->andWhere(['is not','member_id',null])
                        ->groupBy(['member_id'])
                        ->joinWith('member')
                        ->asArray()
                        ->all();
        $return = ArrayHelper::map($member, 'member_id','fullname');
        return $return;
    }

    public static function getMemberLabel($value = null) {
        $array = self::getMemberOptions();
        if ($value === null || !array_key_exists($value, $array))
            return '';
        return $array[$value];
    }




    public static function getXungdanhOptions() {
        return [
            0 => 'Anh',
            1 => 'Chị',
        ];
    }

    public static function getXungdanhLabel($value = null) {
        $array = self::getXungdanhOptions();
        if ($value === null || !array_key_exists($value, $array))
            return '';
        return $array[$value];
    }
   
    public static function getTinhtrangOptions() {
        return [
            self::CHUAXULY => 'Chưa xử lý',
            self::DANGXULY => 'Đang xử lý',
            self::HOANTHANH => 'Hoàn thành',
            self::HUY => 'Hủy',
            self::DAXEM => 'Đã xem',
            
        ];
    }

    public static function getTinhtrangLabel($value = null) {
        $array = self::getTinhtrangOptions();
        if ($value === null || !array_key_exists($value, $array))
            return '';
        return $array[$value];
    }
    public static function getTinhtrangClass($value = null) {
        $array = [
            self::CHUAXULY => 'bg-tieude',
            self::DANGXULY => '',
            self::HOANTHANH => '',
            self::HUY => '',
            self::DAXEM => '',
        ];        
        if ($value === null || !array_key_exists($value, $array))
            return '';
        return $array[$value];
    }

    
    public function getMember()
    {
        return $this->hasOne(Member::className(), ['user_id' => 'member_id']);
    }
}
