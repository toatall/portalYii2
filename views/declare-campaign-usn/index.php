<?php

use app\assets\ApexchartsAsset;
use app\models\DeclareCampaignUsn;
use yii\bootstrap5\Tabs;
use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var DeclareCampaignUsn[][] $models */

ApexchartsAsset::register($this);

$this->title = 'Декларационная кампания по УСН';
$this->params['breadcrumbs'][] = $this->title;

$tabItems = [];
foreach($models as $date => $items) {
    $tabItems[] = [
        'label' => '<i class="far fa-clock"></i> ' . Yii::$app->formatter->asDate($date),
        'content' => $this->render('_grid', ['items' => $items]),
    ];
}
?>
<div class="declare-campaign-usn-index">

    <h1 class="title display-5 border-bottom"><?= Html::encode($this->title) ?></h1>

    <?php if (DeclareCampaignUsn::isRoleModerator()): ?>
    <p>
        <?= Html::a('Редактирование данных', ['change'], ['class' => 'btn btn-success btn-sm mv-link']) ?>
    </p>
    <?php endif; ?>

    <?= Tabs::widget([
        'items' => $tabItems,
        'headerOptions' => [
            'class' => 'fw-bold',
        ],
        'encodeLabels' => false,
        'options' => ['class' => 'mt-3'],
    ]) ?>

</div>

<?php 
$jsonUrl = Url::to(['data-chart']);
$this->registerJs(<<<JS

    $('.grid-view [data-bs-toggle="tooltip"]').tooltip();

    $('.btn-chart').on('click', function() {
        const tr = $(this).parents('tr')
        const containerChartId = 'container_chart_' + tr.data('id')
        const containerTableId = 'container_table_' + tr.data('id')
        const orgCode = tr.data('org-code')
        const deadline = tr.data('deadline')
        let next = tr.next('tr[data-chart]')
        if (next.length == 0) {            
            tr.after('<tr data-chart><td colspan="9"><div class="row"><div id="' + containerChartId + '" class="col"></div>'
                + '<div id="' + containerTableId + '" class="col table"></div>' + '</td></tr>')
            next = tr.next('tr')
            loadData(containerChartId, containerTableId, orgCode, deadline)
        }
        else {                        
            if (next.is(':visible')) {
                next.hide()
            }
            else {
                next.show()
                if ($('#' + containerChartId).html() == '') {
                    loadData(containerChartId, containerTableId, orgCode, deadline)
                }
            }
        }
    })

    function loadData(containerChartId, containerTableId, orgCode, deadline) {
        
        const url = UrlHelper.addParam('$jsonUrl', { org: orgCode, deadline: deadline })
        
        $.getJSON(url, function(response) {
            setTable(containerTableId, response)
            setChartData(containerChartId, {
                labels: response.labels,
                series: response.series,
            }, 'line')            
        })
    }
    
    function setChartData(container, data, chartType) {       
        $('#' + container).html('')
        let options = {
            chart: {
                type: chartType
            },
            series: data.series.slice(1),
            
            xaxis: {
                categories: data.labels,
            }
        }
        let chart = new ApexCharts(document.querySelector('#' + container), options)
        
        chart.render()
    }

    function setTable(container, data) {
        const table = $('#' + container)
        let names = []
        
        data.series.forEach(function(val) {
            names.push(val.name)
        })
    
        let t = '<table class="table table-bordered">'
            + '<tr><th>Дата</th><th>' + names.join('</th><th>') + '</th></tr>'
        for(i = 0; i < data.labels.length; i++) {
            t += '<tr>'
            t += '<td>' + data.labels[i] + '</td>'            
            data.series.forEach(function(val){
                t += '<td>' + val.data[i] + '</td>'
            })
            t += '</tr>'
        }        
        t += '</table>'

        table.html(t)
    }

JS);

$this->registerCss(<<<CSS
    tr[data-chart] div.table {
        height: 30rem;
        overflow-y: scroll;
    }
CSS);
?>

