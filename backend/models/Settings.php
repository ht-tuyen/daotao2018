<?php

namespace backend\models;

use yii\base\DynamicModel;
use yii\base\InvalidParamException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use Yii;
use yii\helpers\Json;

/**
 * This is the model class for table "settings".
 *
 * @property integer $id
 * @property string $type
 * @property string $key
 * @property string $value
 * @property boolean $active
 * @property string $created
 * @property string $modified
 *
 */
class Settings extends ActiveRecord implements SettingsInterface
{
    public $order_status_0;
    public $order_status_1;
    public $order_status_2;
    public $order_status_4;
    public $order_status_5;
    public $order_status_6;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%settings}}';
    }

    /**
     * @param bool $forDropDown if false - return array or validators, true - key=>value for dropDown
     * @return array
     */
    public function getTypes($forDropDown = true)
    {
        $values = [
            'string' => ['value', 'string'],
            'integer' => ['value', 'integer'],
            'boolean' => ['value', 'boolean', 'trueValue' => "1", 'falseValue' => "0", 'strict' => true],
            'float' => ['value', 'number'],
            'email' => ['value', 'email'],
            'ip' => ['value', 'ip'],
            'url' => ['value', 'url'],
            'object' => ['value', function($attribute) {
                try {
                    $object = Json::decode($this->$attribute);
                } catch (InvalidParamException $e) {
                    $this->addError($attribute, \Yii::t('app', '"{attribute}" must be a valid JSON object', [
                        'attribute' => $attribute,
                    ]));
                    return;
                }
                if (!is_array($object)) {
                    $this->addError($attribute, \Yii::t('app', '"{attribute}" must be a valid JSON object', [
                        'attribute' => $attribute,
                    ]));
                }
            }],
        ];

        if (!$forDropDown) {
            return $values;
        }

        $return = [];
        foreach ($values as $key => $value) {
            $return[$key] = \Yii::t('app', $key);
        }

        return $return;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['value'], 'string'],
            [['key'], 'string', 'max' => 255],
            [
                ['key'],
                'unique',
                'targetAttribute' => ['key'],
                'message' =>
                    \Yii::t('app', '{attribute} "{value}" already exists.')
            ],
            ['type', 'in', 'range' => array_keys($this->getTypes(false))],
            [['type', 'created', 'modified'], 'safe'],
            [['active'], 'boolean'],
        ];
    }

    public function beforeSave($insert)
    {
        $validators = $this->getTypes(false);
        if (!array_key_exists($this->type, $validators)) {
            $this->addError('type', \Yii::t('app', 'Please select correct type'));
            return false;
        }

        $model = DynamicModel::validateData([
            'value' => $this->value
        ], [
            $validators[$this->type],
        ]);

        if ($model->hasErrors()) {
            $this->addError('value', $model->getFirstError('value'));
            return false;
        }

        if ($this->hasErrors()) {
            return false;
        }

        return parent::beforeSave($insert);
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        Yii::$app->settings->clearCache();
        Yii::$app->cacheFrontend->flush();
    }

    public function afterDelete()
    {
        parent::afterDelete();
        Yii::$app->settings->clearCache();
        Yii::$app->cacheFrontend->flush();
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => \Yii::t('app', 'ID'),
            'type' => \Yii::t('app', 'Type'),
            'key' => \Yii::t('app', 'Key'),
            'value' => \Yii::t('app', 'Value'),
            'active' => \Yii::t('app', 'Active'),
            'created' => \Yii::t('app', 'Created'),
            'modified' => \Yii::t('app', 'Modified'),
        ];
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => 'created',
                    ActiveRecord::EVENT_BEFORE_UPDATE => 'modified',
                ],
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function getSettings()
    {
        $settings = static::find()->where(['active' => true])->asArray()->all();
        return array_merge_recursive(
            ArrayHelper::map($settings, 'key', 'value'),
            ArrayHelper::map($settings, 'key', 'type')
        );
    }

    /**
     * @inheritdoc
     */
    public function setSetting($key, $value, $type = null)
    {
        $model = static::findOne(['key' => $key]);

        if ($model === null) {
            $model = new static();
            $model->active = 1;
        }
        $model->key = $key;
        $model->value = strval($value);

        if ($type !== null) {
            $model->type = $type;
        } else {
            $model->type = gettype($value);
        }

        return $model->save();
    }

    /**
     * @inheritdoc
     */
    public function activateSetting($key)
    {
        $model = static::findOne(['key' => $key]);

        if ($model && $model->active == 0) {
            $model->active = 1;
            return $model->save();
        }
        return false;
    }

    /**
     * @inheritdoc
     */
    public function deactivateSetting($key)
    {
        $model = static::findOne(['key' => $key]);

        if ($model && $model->active == 1) {
            $model->active = 0;
            return $model->save();
        }
        return false;
    }

    /**
     * @inheritdoc
     */
    public function deleteSetting($key)
    {
        $model = static::findOne(['key' => $key]);

        if ($model) {
            return $model->delete();
        }
        return true;
    }

    /**
     * @inheritdoc
     */
    public function deleteAllSettings()
    {
        return static::deleteAll();
    }
}
