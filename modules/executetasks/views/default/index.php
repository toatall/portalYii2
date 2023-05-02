<?php

use app\assets\ApexchartsAsset;
use app\modules\executetasks\models\ExecuteTasks;
use kartik\select2\Select2;
use yii\bootstrap5\ButtonDropdown;
use yii\bootstrap5\Html;

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

<div class="execute-tasks-index p-4 rounded">

    <div class="col mb-3">
        <span class="display-5 border-bottom">
            <?= $this->title ?>
        </span>    
    </div>

    <?php if (ExecuteTasks::isModerator()): ?>
        <?= ButtonDropdown::widget([
            'label' => 'Управление',
            'dropdown' => [
                'items' => [
                    ['label'=>'Управление данными', 'url'=>['/executetasks/manage/index']],
                    ['label'=>'Настройка отделов', 'url'=>['/executetasks/department/index']],
                    ['label'=>'Настройка организаций', 'url'=>['/executetasks/organization/index']],
                ],
            ],
            'buttonOptions' => [
                'class' => 'btn btn-outline-success btn-sm',
            ],
            'options' => ['class' => 'ml-3']
        ]) ?>
    <?php endif; ?>

    <?= Html::beginForm('', 'get', ['id' => 'form-filter-radar']) ?>
    <div class="card card-body mt-2">
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

    <div class="row">
        <div class="col-4">
            <div class="card mt-3 shadow" style="height:350px;">
                <div class="card-header lead text-uppercase fa-1x text-center">
                    Выполнено задач (всего)
                </div>
                <div class="card-body">
                    <div id="chart-total" class=""></div>
                </div>                    
            </div> 
        </div>
        <div class="col">
            <div id="chart-total-with-indexes" class="row"></div>
        </div>        
    </div>


    <div class="row">
        <div class="col-6">
            <div class="card mt-3 shadow">
                <div class="card-header lead text-uppercase text-center">Исполнение задач в разрезе отделов</div>            
                <div class="card-body">
                    <div id="chart-departments" class="text-dark"></div>                      
                </div>                
            </div>
        </div>
        <div class="col-6">
           <div class="card mt-3 shadow">
                <div class="card-header lead text-uppercase text-center">Исполнение задач в разрезе налоговых органов</div>            
                <div class="card-body">
                    <div id="chart-organization" class="text-dark"></div>                      
                </div>                
            </div>         
        </div>
    </div>

    <div class="row">
        <div class="mt-3 col" id="organizations-detail-main" style="display: none;">
            <div class="card card-body shadow">
                <div>
                    <button type="button" class="btn-close btn-close-white float-end close-btn" aria-label="Close">
                        <!-- <span aria-hidden="true" class="text-light">&times;</span> -->
                    </button>
                </div>
                <div class="row col">
                    <p class="display-4" id="organizations-detail-title"></p>
                </div>
                <div class="row">
                    <div class="col-2">
                        <div id="organizations-detail-employee"></div>       
                    </div>
                    <div class="col">
                        <div id="organizations-detail-table"></div>                        
                    </div>
                </div>
                <div class="row">
                    <div id="organizations-detail-alert" style="display: none;"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="mt-3 col" id="departments-detail-main" style="display: none;">            
            <div class="card card-body shadow">
                <div>
                    <button type="button" class="btn-close btn-close-white float-end close-btn" aria-label="Close">
                        <!-- <span aria-hidden="true" class="text-light">&times;</span> -->
                    </button>
                </div>
                <div class="row col">
                    <p class="display-4" id="departments-detail-title"></p>
                </div>
                <div class="row">
                    <div class="col-2">
                        <div id="departments-detail-employee"></div>       
                    </div>
                    <div class="col">
                        <div id="departments-detail-table"></div>                        
                    </div>
                </div>
                <div class="row">
                    <div id="departments-detail-alert" style="display: none;"></div>
                </div>
            </div>
        </div>
    </div>

</div>


<?php
$this->registerJs(<<<JS
   
    $('#select-period').on('change', function() {
        loadData();
    });

    $(document).on('click', '.link-detail', function() {
        
        const divMain = $('#departments-detail-main');
        const divTable = $('#departments-detail-table');
        const divEmployee = $('#departments-detail-employee');
        const divTitle = $('#departments-detail-title');
        const divAlert = $('#departments-detail-alert'); 
      
        const url = $(this).attr('href');

        divMain.show();

        $.get(url)
        .done(function(data) {           
            divTable.html(data.table);           
            divTitle.html(data.deaprtmentName);
        })
        .fail(function(err) {                 
            divAlert.html('<div class="text-danger mt-3">' + err.responseText + '</div>');
            divAlert.show();
        });

        return false;
    });

    $('.close-btn').on('click', function() {
        $(this).parent('div').parent('div').parent('div').hide();
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

            // total with indexes
            const divTotalWithIndexes = $('#chart-total-with-indexes');
            var index = 0;
            
            divTotalWithIndexes.html('');
            for (i in data.totalWithIndex) {

                divTotalWithIndexes.append('<div class="col-6">' +
                                           '  <div class="card mt-3 shadow" style="height:350px;">' +
                                           '    <div class="card-header lead text-uppercase text-center fa-1x">Выполнено задач (' + i + ')</div>' +
                                           '    <div class="card-body"><div id="chart-total-with-index-' + index + '"></div></div>' +
                                           '  </div>' +
                                           '</div>');    

                var val = 0;
                if (data.totalWithIndex[i].all > 0) {
                    val = Math.round(data.totalWithIndex[i].finish / data.totalWithIndex[i].all * 100);
                }

                const chartI = new ApexCharts(document.getElementById('chart-total-with-index-' + index), {
                    series: [
                        val
                    ],
                    chart: {
                        type: 'radialBar',                      
                        height: 300
                    },
                    colors: ['#1c78d5'],
                    plotOptions: {
                        radialBar: {                          
                            dataLabels: {
                                name: {
                                    show: false
                                },
                                value: {
                                    offsetY: 10,
                                    fontSize: '2rem',
                                    // color: 'white'
                                }
                            }
                        }
                    },                                        
                });
                chartI.render();
                
                index++;
            }
        
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
            document.chartDepartment.updateOptions({ chart: { height: ((50 - dataDep.length) * dataDep.length) } });
           
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
            document.chartOrganization.updateOptions({ chart: { height: ((50 - dataOrg.length) * dataOrg.length) } });
           

            // leader department

            const leaderDep = $('#leader-department');            
            
            var index = 1;
            var text = "";
            
            for (i in data.leadersDepartment) {
                if (index>5) {
                    break;
                }                
                text += '<div style="font-size: 1rem;">'
                    + '<span class="badge bg-warning">' + index + '</span> '
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
                    + '<span class="badge bg-warning">' + index + '</span> '
                    + '<i class="fas fa-blender text-warning"></i> '
                    + data.leadersOganization[i].name + ' - ' + data.leadersOganization[i].per + '%'
                    + '</div>';
                index++;
            }

            leaderOrg.html(text);            

        })
        .fail(function(err) {
            errorDiv.show();
            errorDiv.html('<div class="alert alert-danger mt-3">' + err.responseText + '</div>');
        });  
        
    }

    loadData();

JS); ?>