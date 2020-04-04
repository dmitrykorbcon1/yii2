<?php

use kartik\daterange\DateRangePicker;
use yii\widgets\ActiveForm;

/* @var $this \yii\web\View */
/* @var $dataProvider \yii\data\ArrayDataProvider */
/* @var $searchModel \app\models\logsSearch */
/* @var array $issetOs */

$arch = [
    null => 'Все',
    'x86' => 'x86',
    'x64' => 'x64'
];
$this->registerJs("
$('body, html').on('change', 'form#filter-search input', function() {
    $('form#filter-search').submit();    
});
$('body, html').on('change', 'form#filter-search select', function() {
    $('form#filter-search').submit();    
});
");
?>
<?php $form = ActiveForm::begin([
    'method' => 'get',
    'id' => 'filter-search',
    'action' => ['site/index']
]); ?>

<div class="row">
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="">Дата создания</label>
                    <?= DateRangePicker::widget([
                        'model' => $searchModel,
                        'attribute' => 'createdAt',
                        'convertFormat' => true,
                        'startAttribute' => 'createdAtStart',
                        'endAttribute' => 'createdAtEnd',
                        'pluginOptions' => [
                            'timePicker' => false,
                            'autoApply' => true,
                            'locale' => [
                                'format' => 'Y-m-d'
                            ]
                        ],
                        'options' => [
                            'class' => 'form-control',
                            'autocomplete' => 'off'
                        ]
                    ]); ?>
                </div>
            </div>
            <div class="col-md-3">
                <?= $form->field($searchModel, 'arch')
                    ->dropDownList($arch); ?>
            </div>
            <div class="col-md-3">
                <?= $form->field($searchModel, 'os')
                    ->dropDownList($issetOs); ?>
            </div>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>
