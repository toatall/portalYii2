<?php
/** @var yii\web\View $this */
/** @var array $ifns */
/** @var string $date1 */
/** @var string $date2 */

use yii\bootstrap5\Html;
use kartik\widgets\DatePicker;
use app\assets\ChartJs;
use yii\helpers\Url;

// ChartJs::register($this);

$this->title = 'Анкетирование по ГР (график)';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="regecr-index">
    <div class="row">
        <div class="col border-bottom mb-2">
            <p class="display-4">
            <?= $this->title ?>
            </p>    
        </div>    
    </div>
   
    <div class="btn-group">
        <?= Html::a('Статистика', ['index'], ['class' => 'btn btn-secondary']) ?>
        <?= Html::a('Детализация', ['detail'], ['class' => 'btn btn-secondary']) ?>
    </div>

    <div class="row mt-2">
        <div class="col-6">
            <div class="card">
                <div class="card-header">Поиск</div>
                <div class="card-body">
                    <?= Html::beginForm(['chart'], 'get') ?>
                    <div class="row">
                        <div class="col-5">
                            <?= DatePicker::widget([
                                'name' => 'date1',
                                'value' => $date1,
                                'pluginOptions' => [
                                    'todayHighlight' => true,
                                    'todayBtn' => true,
                                    'autoclose' => true,
                                ],
                            ]) ?>
                        </div>
                        <div class="col-5">
                            <?= DatePicker::widget([
                                'name' => 'date2',
                                'value' => $date2,
                                'pluginOptions' => [
                                    'todayHighlight' => true,
                                    'todayBtn' => true,
                                    'autoclose' => true,

                                ],
                            ]) ?>
                        </div>
                        <div class="col-2">
                            <?= Html::submitButton('Поиск', ['class' => 'btn btn-primary col-sm-12']) ?>
                        </div>
                    </div>
                    <?= Html::endForm() ?>
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-2">
        <div class="card-header bg-primary text-white font-weight-bolder"><i class="fas fa-info-circle"></i> Описание</div>
        <div class="card-body">
            <strong>Дата</strong> - Дата регистрации<br />
            <strong>Кол-во вновь созданных ООО</strong> - Количество вновь созданных ООО<br />
            <strong>Кол-во опрошенных</strong> - Количество опрошенных представителей вновь созданных ООО (1 представитель в отношении 1 вновь созданного ООО)<br />
            <strong>Средняя оценка А 1.1</strong> - Средняя оценка респондентами по показателю А 1.1 "Среднее время регистрации, юридических лиц", дней (среднее арифметическое от общего количества опрошенных респондентов)<br />
            <strong>Средняя оценка А 1.2</strong> - Средняя оценка респондентами по показателю А 1.2 "Среднее количество процедур, необходимых для регистрации юридических лиц", штук (среднее арифметическое от общего количества опрошенных респондентов)<br />
            <strong>Средняя оценка А 1.3</strong> - Средняя оценка респондентами по показателю А 1.3 "Оценка деятельности органов власти по регистрации, юридических лиц", баллов (среднее арифметическое от общего количества опрошенных респондентов)<br />
        </div>
    </div>

    <?php foreach ($ifns as $code=>$name): ?>
        <div style="width:75%">
            <canvas id="canvas_<?= $code ?>" class="convas-chart" data-href="<?= Url::to(['regecr/chart-ajax', 'code'=>$code, 'date1'=>$date1, 'date2'=>$date2]) ?>"></canvas>
        </div>
    <?php endforeach; ?>


</div>

<?php
$this->registerJs(<<<JS
    
    function setChartData(chart_id, getData, orgName)
    {
        var config = {
            type: 'line',
            data: getData,
            options: {
                responsive: true,
                title: {
                    display: true,
                    text: orgName,
                },
                scales: {
                    xAxes: [{
                        display: true,
                        scaleLabel: {
                            display: true,
                            labelString: 'Даты'
                        }
                        }],
                    yAxes: [{                       
                        position: 'left',
                        display: true,
                        scaleLabel: {
                            display: true,
                            labelString: 'Значения'
                        },
                        ticks: {
                            //reverse: true
                            beginAtZero: true
                        }
                    }]
                }
            }
        };
        var ctx = document.getElementById(chart_id).getContext('2d');
        window.myLine = new Chart(ctx, config);
    }

    $('.convas-chart').each(function() {
        let chart = $(this);
        $.get($(this).data('href'))
        .done(function(data) {
            setChartData(chart.attr('id'), data.data, data.orgName);
        });
    });
    
JS
);
?>