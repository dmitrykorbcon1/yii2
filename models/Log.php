<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "logs".
 *
 * @property int $id
 * @property string $ip IP
 * @property int $created_at Дата и время лога
 * @property string|null $url URL
 * @property string|null $os Операционная система
 * @property string|null $arch Архитектура
 * @property string|null $browser Браузер
 */
class Log extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'logs';
    }

    const SCENARIO_LOG = 'log';

    /**
     * @var array
     */
    protected $fillable = [
        'ip',
        'created_at',
        'url',
        'os',
        'arch',
        'browser'
    ];

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ip', 'created_at'], 'required'],
            [['created_at'], 'integer'],
            [['ip', 'url', 'os', 'arch', 'browser'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'ip' => 'IP',
            'created_at' => 'Дата и время лога',
            'url' => 'URL',
            'os' => 'Операционная система',
            'arch' => 'Архитектура',
            'browser' => 'Браузер',
        ];
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_LOG] = ['id', 'ip', 'created_at', 'url', 'os', 'arch', 'browser'];
        return $scenarios;
    }
}
