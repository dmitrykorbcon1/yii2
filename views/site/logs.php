<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;
use dosamigos\chartjs\ChartJs;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $searchModel \app\models\logsSearch */
/* @var array $countRequest */
/* @var array $countBrowsers */

$this->title = 'Логи';
?>
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="col-md-9">
                    <h4 class="card-title"><?= Html::encode($this->title); ?></h4>
                </div>
                <?= $this->render('_filter', [
                    'searchModel' => $searchModel,
                ]); ?>
                <div class="row">
                    <div class="col-md-6">
                        <?= ChartJs::widget([
                            'type' => 'line',
                            'options' => [
                                'height' => 100,
                                'width' => 100
                            ],
                            'data' => [
                                'labels' => array_keys($countRequest),
                                'datasets' => [
                                    [
                                        'label' => "Число запросов",
                                        'backgroundColor' => "rgba(0,0,0,0.1)",
                                        'borderColor' => "rgba(179,181,198,1)",
                                        'pointBackgroundColor' => "rgba(179,181,198,1)",
                                        'pointBorderColor' => "#fff",
                                        'pointHoverBackgroundColor' => "#fff",
                                        'pointHoverBorderColor' => "rgba(179,181,198,1)",
                                        'data' => array_values($countRequest)
                                    ],
                                ]
                            ]
                        ]);
                        ?>
                    </div>
                    <div class="col-md-6">
                        <?= ChartJs::widget([
                            'type' => 'line',
                            'options' => [
                                'height' => 100,
                                'width' => 100
                            ],
                            'data' => [
                                'labels' => array_keys($countBrowsers),
                                'datasets' => [
                                    [
                                        'label' => "Популярность",
                                        'backgroundColor' => "rgba(255,99,132,0.2)",
                                        'borderColor' => "rgba(255,99,132,1)",
                                        'pointBackgroundColor' => "rgba(255,99,132,1)",
                                        'pointBorderColor' => "#fff",
                                        'pointHoverBackgroundColor' => "#fff",
                                        'pointHoverBorderColor' => "rgba(255,99,132,1)",
                                        'data' => array_values($countBrowsers)
                                    ]
                                ]
                            ]
                        ]);
                        ?>
                    </div>
                </div>

                <div class="table-responsive">
                    <?php Pjax::begin(['timeout' => 5000]); ?>
                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'pager' => [
                            'options' => ['class' => 'pagination flat pagination-primary'],
                            'linkContainerOptions' => ['class' => 'page-item'],
                            'linkOptions' => ['class' => 'page-link'],
                            'disabledListItemSubTagOptions' => ['tag' => 'a', 'class' => 'page-link'],
                            'nextPageLabel' => '>>>',
                            'prevPageLabel' => '<<<',
                        ],
                        'tableOptions' => ['class' => 'table table-hover'],
                        'columns' => [
                            [
                                'attribute' => 'date',
                                'format' => 'html',
                                'label' => 'Дата'
                            ],
                            [
                                'attribute' => 'count',
                                'format' => 'html',
                                'label' => 'Кол-во запросов'
                            ],
                            [
                                'attribute' => 'url',
                                'format' => 'raw',
                                'label' => 'Самый популярный URL'
                            ],
                            [
                                'attribute' => 'browser',
                                'format' => 'html',
                                'label' => 'Самый популярный браузер'
                            ],
                        ],
                    ]); ?>
                    <?php Pjax::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
