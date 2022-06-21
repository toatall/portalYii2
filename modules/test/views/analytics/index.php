<?php

/** @var yii\web\View $this */

use app\assets\ApexchartsAsset;
use kartik\date\DatePicker;
use yii\bootstrap4\Html;

ApexchartsAsset::register($this);

/**
 * 1. Статистика по всем но (процент правильных ответов) сделать период
 * 
 */
$this->title = 'Аналитика';
?>

<h1 class="display-4 border-bottom">
    <i class="fas fa-chart-pie text-primary"></i> <?= $this->title ?>
</h1>

<?= Html::beginForm('/test/analytics/chart-right-total', 'get', ['id'=>'form-search', 'autocomplete' => 'off']) ?>
<div class="card">
    <div class="card-header">Период</div>
    <div class="card-body">
        <div class="row">
            <div class="col-4">
                
                <?= DatePicker::widget([
                    'name' => 'date1',
                    'value' => date('01.01.Y'),
                    'pluginOptions' => [
                        'todayHighlight' => true,
                        'todayBtn' => true,
                        'autoclose' => true,                        
                    ],
                ]) ?>
            </div>
            <div class="col-4">
                <?= DatePicker::widget([
                    'name' => 'date2',
                    'value' => date('d.m.Y'),
                    'pluginOptions' => [
                        'todayHighlight' => true,
                        'todayBtn' => true,
                        'autoclose' => true,
                    ],
                ]) ?>
            </div>
            <div class="col">
                <?= Html::submitButton('Показать', ['class' => 'btn btn-primary']) ?>
            </div>
        </div>
        <div class="row">
            <div class="col text-danger mt-3" id="alert-danger"></div>
        </div>
    </div>
</div>
<?= Html::endForm() ?>

<div class="row mt-2">
    <div class="col-6">
        <div class="card">           
            <div class="card-body">
                <div id="chart-total"></div>
                <div id="chart-total-detail" class="w-100 mt-4" style="display: none;"></div>
            </div>
        </div>            
    </div>
    <div class="col-6">
        <div class="card">            
            <div class="card-body">
                <div id="chart-right-total"></div>  
            </div>
        </div>
    </div>    
</div>
<div class="row">
    
</div>

<?php $this->registerJs(<<<JS
    $('#form-search').on('submit', function() {
        
        const date1 = $('input[name="date1"]');
        const date2 = $('input[name="date2"]');
        $('#alert-danger').html('');

        if (date1.val().trim() == "" || date2.val().trim() == "") {
            $('#alert-danger').html('Не заполнен период!');
            return false;
        }
       
        const url = $(this).attr('action');
        const data = $(this).serialize();
        $.ajax({
            url: url,
            method: 'get',
            data: data
        })
        .done(function(data) {    
            
            const dataTotal = [];
            const dataRight = [];
            const dataWrong = [];
            const categories = [];

            for (i in data.res) {
                dataTotal.push({
                    x: i,                   
                    y: data.res[i].total,
                    url: data.res[i].urlDetail,                                       
                });
                dataRight.push({
                    x: i,                   
                    y: data.res[i].right,
                    url: data.res[i].urlDetail,                                       
                });
                dataWrong.push({
                    x: i,                   
                    y: data.res[i].wrong,
                    url: data.res[i].urlDetail,                                       
                });
                categories.push(data.res[i].category);
            }         
            
            document.chartRightTotal.updateOptions({ xaxis: { categories: categories }});
            document.chartRightTotal.updateSeries([{ name: 'Правильные ответы', data: dataRight }, { name: 'Неправильные ответы', data: dataWrong }]);

            document.chartTotal.updateOptions({ xaxis: { categories: categories }});
            document.chartTotal.updateSeries([{ data: dataTotal }]);
        });
        
        return false;
    });   


    document.chartTotal = new ApexCharts(document.getElementById('chart-total'), {
        series: [{
            "name": 'Сдано тестов',
            data: []
        }],  
        title: {
            text: "Количество пройденных тестов за период (нажмите на столбец)"
        },       
        chart: {
            type: 'bar',
            height: 600,   
            events: {
                click: function(event, context, config) {
                                                                           
                    const divMain = $('#chart-total-detail');                                                                                   

                    if (config.globals.selectedDataPoints.length == 0) {
                        return false;
                    }
                    const indexSelected = config.globals.selectedDataPoints[0][0];                    
                    if (indexSelected !== undefined && config.config.series[config.seriesIndex] !== undefined) {                    
                     
                        divMain.show('slow');                        
                        
                        const url = config.config.series[config.seriesIndex].data[indexSelected].url;
                        
                        $.get(url)
                        .done(function(data) {

                            const dataRight = [];
                            const dataWrong = [];
                            const categories = [];
                            for (i in data.res) {
                                dataRight.push({                                 
                                    x: data.res[i].date,
                                    y: data.res[i].right                                                                      
                                });
                                dataWrong.push({
                                    x: data.res[i].date,
                                    y: data.res[i].wrong                                                                      
                                });
                                categories.push(data.res[i].date);
                            }                         

                            // document.chartTotalDetail.updateOptions({ title: { text: 'Правильные и неправильные ответы за период' }});
                            document.chartTotalDetail.updateSeries([{ name: 'Правильные ответы', data: dataRight }, { name: 'Неправильные ответы', data: dataWrong }]);
                                                       
                        })
                        .fail(function(err) {                 
                            console.log(err);
                        });
                        
                    }
                    else {
                        divMain.hide('slow');
                    }
                }
            }         
        },      
        plotOptions: {
            bar: {                
                borderRadius: 6
            }
        },
        yaxis: {
            labels: {
                maxWidth: '30%',                
            },            
        },        
    });
    document.chartTotal.render();


    document.chartRightTotal = new ApexCharts(document.getElementById('chart-right-total'), {
        series: [],  
        title: {
            text: "Процент правильных и неправильных ответов за период"
        },        
        chart: {
            type: 'bar',
            height: 600,
            stacked: true,
            stackType: '100%',
        },
        colors: ['#41cb08', '#ef3e3e'],
        plotOptions: {
            bar: {                
                borderRadius: 6
            }
        },
        yaxis: {
            labels: {
                maxWidth: '30%',                
            },            
        },        
    });
    document.chartRightTotal.render();


    document.chartTotalDetail = new ApexCharts(document.getElementById('chart-total-detail'), {
        series: [],         
        chart: {
            type: 'line',
            height: 450                                
        },
        colors: ['#41cb08', '#ef3e3e'],
        stroke: {
            curve: 'smooth',
        }        
    });
    document.chartTotalDetail.render();

JS); ?>