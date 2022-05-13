<?php

use app\assets\ApexchartsAsset;
use app\models\ExecuteTasks;
use kartik\select2\Select2;
use yii\bootstrap4\Html;

/** @var yii\web\View $this */
/** @var array $periods */

ApexchartsAsset::register($this);
$this->registerJsFile('@web/public/assets/portal/js/execute-tasks.index.js', [
    'depends' => 'app\assets\ApexchartsAsset',
]);

$this->title = 'Исполнение задач';
$this->params['breadcrumbs'][] = $this->title;
?>

<?php if (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false || strpos($_SERVER['HTTP_USER_AGENT'], 'Trident') !== false): ?>
    <div class="alert alert-danger display-4 font-weight-bolder text-center">Браузер Internet Explorer не поддерживается!</div>
<?php endif; ?>

<div class="execute-tasks-index">

    <div class="col border-bottom mb-2">
        <p class="display-4">
            <?= $this->title ?>
        </p>    
    </div>

    <?php if (ExecuteTasks::isModerator()): ?>
    <p>
        <?= Html::a('Управление данными по исполнению задач', ['manage'], ['class' => 'btn btn-outline-success']) ?>
    </p>
    <?php endif; ?>

    <?= Html::beginForm('/execute-tasks/data-chart-radar', 'get', ['id' => 'form-filter-radar']) ?>
    <div class="card card-body">
        <div class="row">
            <div class="col">               
                <?= Select2::widget([
                    'id' => 'select-period',
                    'name' => 'periodYear',
                    'data' => $periods,
                    'value' => array_key_first($periods),
                    'options' => [
                        'placeholder' => 'Выберите отчетный год',
                    ],
                ]) ?>
            </div>           
        </div>
    </div>
    <?= Html::endForm() ?>

    <div id="result-error" style="display: none;"></div>

    <div id="data-div" class="row">

        <div class="col-3">

            <div class="card mt-3">
                <div class="card-header lead text-uppercase text-center">
                    Выполнено задач 
                </div>
                <div class="card-body">
                    <div id="chart-total"></div>
                </div>
                <hr />
                <div class="card-body">
                    <div id="chart-total-with-indexes" class="row">                        
                    </div>
                </div>
                <div class="card-body">
                    
                </div>
            </div>  

            <div class="card mt-3">
                <div class="card-header lead text-uppercase text-center">
                    Лидеры (отделы Управления)
                </div>
                <div class="card-body">
                    <div id="leader-department"></div>
                </div>
            </div> 

            <div class="card mt-3">
                <div class="card-header lead text-uppercase text-center">
                    Лидеры (Инспекции)
                </div>
                <div class="card-body">
                    <div id="leader-organization"></div>
                </div>
            </div> 

        </div>

        <div class="col">
            
            <div class="card mt-3">
                <div class="card-header lead text-uppercase text-center">Исполнение задач в разрезе отделов</div>            
                <div class="card-body">
                    <div id="chart-departments"></div>  
                    <div id="chart-departments-detail" style="display: none;"></div>
                    <div id="chart-departments-detail-alert" style="display: none;"></div>
                </div>                
            </div>
            
            <div class="card mt-3">
                <div class="card-header lead text-uppercase text-center">Исполнение задач в разрезе инспекций</div>            
                <div class="card-body">
                    <div id="chart-organization"></div>
                    <div id="chart-organization-detail" style="display: none;"></div>
                    <div id="chart-organization-detail-alert" style="display: none;"></div>
                </div>
            </div>

            <!-- <div id="test-j"></div> -->

        </div>

    </div>

</div>


<?php
$this->registerJs(<<<JS
    /* */
    var chartJ = new ApexCharts(document.getElementById('test-j'), {
        series: [], 
        chart: {
            type: 'area',
            height: 300,
            animations: {
                enabled: true,
                easing: 'linear',
                // dynamicAnimation: {
                //     speed: 1000
                // },
            },
            toolbar: {
                show: false
            },
            zoom: {
                enabled: false
            },            
        },
        dataLabels: {
            enabled: false,
        },
        stroke: {
            curve: 'smooth'
        },
        xaxis: {
            range: 10,
            labels: {
                show: false,
            }
        },        
        yaxis: {
            min: 0,
            max: 100,
        },       
        brush: {
            enabled: true,
        },
        annotations: {
            yaxis: [
                {
                    y: 85,
                    y2: 100,
                    fillColor: 'red',
                    opacity: 0.1,
                },
                {
                    y: 70,
                    y2: 85,
                    fillColor: 'yellow',
                    opacity: 0.1,
                },
            ],
        },
    });
    chartJ.render();

    var d = [];
    const url = '/execute-tasks/j';

    function loadJ() {                       
        $.get(url)
        .done(function(data) {            
            setTimeout(() => { loadJ() }, 1);
            d.push(data);                    
            chartJ.updateSeries([{data: d}]);
        });
    }
    loadJ();

    // setInterval(() => {
    //     while (!finish) {
    //         setTimeout({}, 1000);
    //     }
    //     loadJ();
    // }, 1000);
        

    $('#select-period').on('change', function() {
        loadData();
    });

    function loadData() {
        const url = $('#select-period').val();        
        const errorDiv = $('#result-error');        

        errorDiv.hide();
        $('#chart-organization-detail').hide();
        $('#chart-organization-detail-alert').hide();
        $('#chart-departments-detail').hide();
        $('#chart-departments-detail-alert').hide();
        
        $.get(url)
        .done(function(data) {
                                  
            // total
            document.chartTotal.updateSeries(data.total);
        
            // departments
            const dataDep = [];
            for (i in data.departments) {
                dataDep.push({
                    x: i,                    
                    y: data.departments[i].finish,
                    url: data.departments[i].url, 
                    full_name: data.departments[i].full_name,
                    goals: [
                        {
                            name: "Всего задач",
                            value: data.departments[i].all,
                            strokeHeight: 5,
                            strokeColor: '#775DD0'
                        }
                    ]
                });
            }

            document.chartDepartment.updateSeries([{
                name: "Выполнено задач",
                data: dataDep
            }]);

            // organizations
            const dataOrg = [];
            for (i in data.organizations) {
                dataOrg.push({
                    x: i,                   
                    y: data.organizations[i].finish,
                    url: data.organizations[i].url,
                    full_name: data.organizations[i].full_name,
                    goals: [
                        {
                            name: "Всего задач",
                            value: data.organizations[i].all,
                            strokeHeight: 5,
                            strokeColor: '#775DD0'
                        }
                    ]
                });
            }            

            document.chartOrganization.updateSeries([{
                name: "Выполнено задач",
                data: dataOrg
            }]);
            

            // leader department

            const leaderDep = $('#leader-department');            
            
            var index = 1;
            var text = "";
            
            for (i in data.leadersDepartment) {
                if (index>5) {
                    break;
                }                
                text += '<div style="font-size: 1rem;">'
                    + '<span class="badge badge-warning">' + index + '</span> '
                    + '<i class="fas fa-blender text-warning"></i> '
                    + data.leadersDepartment[i].name + ' - ' + data.leadersDepartment[i].per + '%'
                    + '</div>';
                index++;
            }

            leaderDep.html(text);

            
            // leader organization

            const leaderOrg = $('#leader-organization');            
            
            var index = 1;
            var text = "";
            
            for (i in data.leadersOganization) {
                if (index>5) {
                    break;
                }              
                text += '<div style="font-size: 1rem;">'
                    + '<span class="badge badge-warning">' + index + '</span> '
                    + '<i class="fas fa-blender text-warning"></i> '
                    + data.leadersOganization[i].name + ' - ' + data.leadersOganization[i].per + '%'
                    + '</div>';
                index++;
            }

            leaderOrg.html(text);


            // total with indexes

            const divTotalWithIndexes = $('#chart-total-with-indexes');
            var index = 0;

            divTotalWithIndexes.html('');
            for (i in data.totalWithIndex) {

                divTotalWithIndexes.append('<div class="col-6"><p class="lead text-center">' + i + '</p><div id="chart-total-with-index-' + index + '"></div></div>');                
                const chartI = new ApexCharts(document.getElementById('chart-total-with-index-' + index), {
                    series: [
                        Math.round(data.totalWithIndex[i].finish / data.totalWithIndex[i].all * 100)
                    ],
                    chart: {
                        type: 'radialBar',
                        offsetY: -20,
                        sparkline: {
                            enabled: true
                        }
                    },
                    plotOptions: {
                        radialBar: {
                            startAngle: -90,
                            endAngle: 90,
                            track: {
                                background: "#e7e7e7",
                                strokeWidth: '100%',
                                margin: 5,                                
                            },
                            dataLabels: {
                                name: {
                                    show: false
                                },
                                value: {
                                    offsetY: -2,
                                    fontSize: '18px',
                                    fontFamily: 'Consolas',
                                    fontWeight: 'bold'
                                }
                            }
                        }
                    },                                        
                });
                chartI.render();
                
                index++;
            }               

        })
        .fail(function(err) {
            errorDiv.show();
            errorDiv.html('<div class="alert alert-danger mt-3">' + err.responseText + '</div>');
        });  
        
    }

    loadData();

JS); ?>