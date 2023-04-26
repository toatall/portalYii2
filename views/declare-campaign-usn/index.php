<?php

use app\assets\ApexchartsAsset;
use app\models\DeclareCampaignUsn;
use yii\data\ArrayDataProvider;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var DeclareCampaignUsn[] $models */


ApexchartsAsset::register($this);

$this->title = 'Декларационная компания ' . date('Y') . ' года по УСН';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="declare-campaign-usn-index">

    <h1 class="title display-5 border-bottom"><?= Html::encode($this->title) ?></h1>

    <?php if (DeclareCampaignUsn::isRoleModerator()): ?>
    <p>
        <?= Html::a('Редактирование данных', ['change'], ['class' => 'btn btn-success btn-sm mv-link']) ?>
    </p>
    <?php endif; ?>

    
    <?= GridView::widget([
        'tableOptions' => ['class' => 'table table-bordered'],
        'summary' => false,
        'dataProvider' => new ArrayDataProvider([
            'allModels' => $models,
        ]),
        'columns' => [
            'org_code',
            'date:date',
            'count_np:integer',
            'count_np_ul:integer',
            'count_np_ip:integer', 
            
            [
                'attribute' => 'count_np_provides_reliabe_declare',
                'format' => 'raw',
                'value' => function(DeclareCampaignUsn $model) {
                    $result = Yii::$app->formatter->asInteger($model->count_np_provides_reliabe_declare);
                    if (($val = $model->previous_count_np_provides_reliabe_declare) !== false && $model->count_np_provides_reliabe_declare > 0) {
                        $newValue = $newValue = $model->count_np_provides_reliabe_declare - $val;;
                        
                        $color = 'primary';
                        if ($newValue > 0) {
                            $color = 'success';
                        }
                        elseif ($newValue < 0) {
                            $color = 'danger';
                        }

                        $result .= '<br /><small>' . Html::tag('span', Yii::$app->formatter->asInteger($newValue, [], [
                            NumberFormatter::POSITIVE_PREFIX => '+',
                            NumberFormatter::NEGATIVE_PREFIX => '-',
                        ]), [
                            'class' => 'badge bg-' . $color,
                            'data-bs-toggle' => 'tooltip',
                            'title' => 'По сравнению с ' . $model->previous_date . ' (' . Yii::$app->formatter->asInteger($val) . ')',
                        ]) . '</small>';                        
                    }
                    return $result;
                }
            ],
            [
                'attribute' => 'count_np_provides_not_required',
                'format' => 'raw',
                'value' => function(DeclareCampaignUsn $model) {
                    $result = Yii::$app->formatter->asInteger($model->count_np_provides_not_required);
                    if (($val = $model->previous_count_np_provides_not_required) !== false && $model->count_np_provides_not_required > 0) {                        
                        
                        $newValue = $newValue = $model->count_np_provides_not_required - $val;
                        
                        $color = 'primary';
                        if ($newValue > 0) {
                            $color = 'success';
                        }
                        elseif ($newValue < 0) {
                            $color = 'danger';
                        }
                        
                        $result .= '<br /><small>' . Html::tag('span', Yii::$app->formatter->asInteger($newValue, [], [
                            NumberFormatter::POSITIVE_PREFIX => '+',
                            NumberFormatter::NEGATIVE_PREFIX => '-',
                        ]), [                            
                            'class' => 'badge bg-' . $color,
                            'data-bs-toggle' => 'tooltip',
                            'title' => 'Прирост по сравнению с ' . $model->previous_date . ' (' . Yii::$app->formatter->asInteger($val) . ')',
                        ]) . '</small>';
                    }
                    return $result;
                }
            ],
            
            [
                'label' => 'Процент налогоплательщиков, представивших либо обосновано не представивших',               
                'value' => function(DeclareCampaignUsn $model) {
                    return Yii::$app->formatter->asPercent(($model->count_np_provides_reliabe_declare + $model->count_np_provides_not_required) / $model->count_np, 2);
                }
            ],    
            [
                'format' => 'raw',
                'value' => function($model) {
                    return Html::button('<i class="fas fa-chart-line"></i>', [
                        'class' => 'btn btn-primary btn-sm btn-chart',
                        'data-org' => $model->org_code,
                    ]);
                },
            ],              
        ],
        'rowOptions' => function($model) {
            $result = ['data-id' => $model->id, 'data-org-code' => $model->org_code];
            if ($model->org_code == '8600') {
                $result['class'] = 'fw-bold';
            }
            return $result;
        }
    ]); ?>    
    
</div>
<?php 
$jsonUrl = Url::to(['data-chart']);
$this->registerJs(<<<JS

    $('.grid-view [data-bs-toggle="tooltip"]').tooltip();

    $('.btn-chart').on('click', function() {

        const tr = $(this).parents('tr')
        const containerId = 'container_chart_' + tr.data('id')
        const orgCode = tr.data('org-code')
        let next = tr.next('tr')
        if (next.length == 0 || next.attr('data-chart') == null) {            
            tr.after('<tr data-chart><td colspan="9"><div id="' + containerId + '" class="w-50"></div></td></tr>')
            next = tr.next('tr')
            loadChart(containerId, orgCode)
        }
        else {                        
            if (next.is(':visible')) {
                next.hide()
            }
            else {
                next.show()
                loadChart(containerId, orgCode)
            }
        }

    })    

    function loadChart(element, orgCode) {        
        
        const url = UrlHelper.addParam('$jsonUrl', { org: orgCode })
        
        $.getJSON(url, function(response) {
            setChartData(element, {
                labels: response.labels,
                series: response.series
            }, 'line')
        })

    }
    
    function setChartData(container, data, chartType) {           
        $('#' + container).html('')
        let options = {
            chart: {
                type: chartType
            },
            series: data.series,
            
            xaxis: {
                categories: data.labels
            }
        }
        let chart = new ApexCharts(document.querySelector('#' + container), options)
        chart.render()
    }


JS) ?>