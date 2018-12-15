<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "qli_log_payment".
 *
 * @property integer $log_pay_id
 * @property integer $payment_id_root
 * @property integer $payment_id_use
 * @property integer $order_id
 * @property string $amount_before
 * @property string $amount_after
 * @property string $amount
 * @property string $create_time
 * @property string $update_time
 * @property string $created_by
 * @property string $updated_by
 */
class LogPayment extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'qli_log_payment';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['payment_id_root', 'payment_id_use', 'order_id'], 'integer'],
            [['amount_before', 'amount_after', 'amount'], 'number'],
            [['create_time', 'update_time'], 'safe'],
            [['created_by', 'updated_by'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'log_pay_id' => 'Log Pay ID',
            'payment_id_root' => 'Payment Id Root',
            'payment_id_use' => 'Payment Id Use',
            'order_id' => 'Order ID',
            'amount_before' => 'Amount Before',
            'amount_after' => 'Amount After',
            'amount' => 'Amount',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
        ];
    }
}
