<?php

namespace app\models;

use kartik\daterange\DateRangeBehavior;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\Query;

/**
 * Class LogsSearch
 * @package app\models\admin\search
 */
class LogsSearch extends Model
{
    public $os;
    public $arch;
    public $createdAt;
    public $createdAtStart;
    public $createdAtEnd;

    /**
     * @var int the default page size
     */
    public $pageSize = 10;

    public function behaviors()
    {
        return [
            [
                'class' => DateRangeBehavior::class,
                'attribute' => 'createdAt',
                'dateStartAttribute' => 'createdAtStart',
                'dateEndAttribute' => 'createdAtEnd'
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['os'], 'string'],
            [['os'], 'safe'],
            [['arch'], 'string'],
            [['arch'], 'safe'],
            [['createdAt'], 'match', 'pattern' => '/^.+\s\-\s.+$/']
        ];
    }

    public function attributeLabels()
    {
        return [
            'os' => 'Операционная система',
            'arch' => 'Архитектура'
        ];
    }

    /**
     * @return ActiveDataProvider
     */
    public function search()
    {
        $subQueryPopularUrl = (new Query())->select('url')
            ->from('logs')->groupBy('url')
            ->orderBy(["COUNT('url')" => SORT_DESC])
            ->limit(1);
        $subQueryPopularBrowser = (new Query())->select('browser')
            ->from('logs')->groupBy('browser')
            ->orderBy(["COUNT('browser')" => SORT_DESC])
            ->limit(1);
        $subQueryThreePopularBrowsers = (new Query())->select(["FROM_UNIXTIME(created_at, '%d-%m-%Y') as date", "browser", "count(browser) as count"])
            ->from('logs')
            ->groupBy(['date','browser'])
            ->orderBy(["COUNT('browser')" => SORT_DESC])
            ->limit(3);
        $subQueryBrowsers = (new Query())->select(["sum(count)"])
            ->from(['u' => $subQueryThreePopularBrowsers]);

        $query = (new Query())
            ->select(["FROM_UNIXTIME(created_at, '%d-%m-%Y') as date",
                "COUNT(created_at) as count",
                'url'=> $subQueryPopularUrl,
                'browser'=> $subQueryPopularBrowser,
                'percent' => $subQueryBrowsers])
            ->from('logs')
            ->groupBy('date');

        if ($this->arch) {
            $subQueryThreePopularBrowsers->andWhere(['arch' => $this->arch]);
        }
        if ($this->os) {
            $subQueryThreePopularBrowsers->andWhere(['os' => $this->os]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => $this->pageSize,
            ],
            'sort' => [
                'attributes' => [
                    'date' => [
                        'asc' => ['date' => SORT_ASC],
                        'desc' => ['date' => SORT_DESC],
                    ],
                    'count' => [
                        'asc' => ['count' => SORT_ASC],
                        'desc' => ['count' => SORT_DESC],
                    ],
                    'url' => [
                        'asc' => ['url' => SORT_ASC],
                        'desc' => ['url' => SORT_DESC],
                    ],
                    'browser' => [
                        'asc' => ['browser' => SORT_ASC],
                        'desc' => ['browser' => SORT_DESC],
                    ],
                ],
            ],
        ]);

        if (!$this->validate()) {
            return $dataProvider;
        }

//        if ($this->arch) {
//            $query->andWhere(
//                ['=', 'arch', $this->arch]
//            );
//        }
//        if ($this->os) {
//            $query->andWhere(
//                ['=', 'os', $this->os]
//            );
//        }

        if ($this->createdAt) {
            $query->andFilterWhere([
                'and',
                ['>=', 'created_at', $this->createdAtStart],
                ['<', 'created_at', $this->createdAtEnd]
            ]);
        }

        return $dataProvider;
    }
}
