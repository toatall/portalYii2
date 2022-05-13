
(function() {
    // объявление переменных
    const inputPeriod = $('[name="period"]');
    const inputPeriodYear = $('[name="periodYear"]');
    const inputDepartment = $('[name="department"]'); 

    inputPeriod.on('change', function() {
        sendForm();       
    });

    inputPeriodYear.on('change', function() {
        sendForm();
    });

    inputDepartment.on('change', function() {
        sendForm();
    });

    /**
     * Отправка данных формы-фильтра
     */
    function sendForm() {
        if (isFormValidate()) {           
            form = $('#form-filter-radar');
            $.get({
                url: form.attr('action'),
                data: form.serialize()
            })
            .done(function(data) {            
                if (data == null) {
                    setCanvasText('general', 'Нет данных', 30, 50);
                    setCanvasText('departments', 'Нет данных', 30, 50);
                    setCanvasText('organizations', 'Нет данных', 30, 50);
                }
                else {
                    setChartData(chartGeneral, data.general);
                    //setChartData('#general', data.general, 'radar', true);
                    //updateChart('#general', data.general);
                    //setChartData('#departments', data.departments, 'line', false);
                    // setCanvasText('departments', 'Выполняется доработка...', 30, 50);
                    // setCanvasText('organizations', 'Выполняется доработка...', 30, 50);
                }
            })
            .fail(function(err) {
                $('#result_error').html('<div class="alert alert-danger mt-3">' + err.responseText + '</div>');
            });        
            
        }
    }

    /**
     * Проверка выбора всех значений у фильтра
     * @returns {boolean}
     */
    function isFormValidate() {
        if (inputPeriod.val() != "" && inputPeriodYear.val() != "" && inputDepartment.val() != "") {
            return true;
        }
        else {
            return false;
        }
    }

    // создание грфиков    
    document.chartTotal = createChart('chart-total', {
        series: [0],
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
                    strokeWidth: '97%',
                    margin: 5, // margin is in pixels
                    dropShadow: {
                        enabled: true,
                        top: 2,
                        left: 0,
                        color: '#999',
                        opacity: 1,
                        blur: 2
                    }
                },
                dataLabels: {
                    name: {
                        show: false
                    },
                    value: {
                        offsetY: -2,
                        fontSize: '45px',
                        fontFamily: 'Consolas',
                        
                    }
                }
            }
        },
        grid: {
            padding: {
                top: -10
            }
        },
        fill: {
            type: 'gradient',
            gradient: {
                shade: 'light',
                shadeIntensity: 0.4,
                inverseColors: false,
                opacityFrom: 1,
                opacityTo: 1,
                stops: [0, 50, 53, 91]
            },
        },
        labels: ['-'],
    });
    document.chartTotal.render();

    // график по отделам
    document.chartDepartment = createChart('chart-departments', {
        series: [],  
        chart: {
            type: 'bar',
            height: 300,
            events: {
                click: function(event, context, config) {
                    
                    //console.log(config.config.series[config.seriesIndex].data[config.dataPointIndex]);                  
                    const div = $('#chart-departments-detail');        
                    const divAlert = $('#chart-departments-detail-alert');                                                    
                    const indexSelected = config.globals.selectedDataPoints[0][0];

                    divAlert.hide();
                    divAlert.html('');

                    if (indexSelected !== undefined) {
                        div.html('');
                        div.show();
                        const url = config.config.series[config.seriesIndex].data[indexSelected].url;
                        const chartSubDep = createChart('chart-departments-detail', {
                            series: [],  
                            chart: {
                                type: 'line',
                                height: 250                                
                            },
                            colors: ['#2b908f'],
                            dataLabels: {
                                formatter: function(val) {
                                    return val + '%';
                                },
                            },
                            tooltip: {
                                y: {
                                    formatter: function(val, opt) {
                                        return val + '%';
                                    },
                                },
                            },
                            // yaxis: {
                            //     min: 0
                            // }
                        });
                        chartSubDep.render();
                        
                        $.get(url)
                        .done(function(data) {
                            chartSubDep.updateSeries([{
                                name: "Выполнено задач",
                                data: data.data
                            }]);
                            chartSubDep.updateOptions({
                                xaxis: {
                                    categories: data.labels
                                }
                            });
                        })
                        .fail(function(err) {                 
                            divAlert.html('<div class="alert alert-danger mt-3">' + err.responseText + '</div>');
                            divAlert.show();
                        });
                        
                    }
                    else {                        
                        div.hide('slow');
                    }                   
                }
            }
        },
        colors: ['#00E396'],
        plotOptions: {
            bar: {               
                borderRadius: 6
            }
        },        
        yaxis: {
            labels: {
                maxWidth: '30%',                
            }
        },
        tooltip: {
            x: {
                formatter: function(val, { series, seriesIndex, dataPointIndex, w }) {                   
                    return w.config.series[seriesIndex].data[dataPointIndex].full_name;
                },
            },
        },
    });
    document.chartDepartment.render();
        
    // график по инспекциям
    document.chartOrganization = createChart('chart-organization', {
        series: [],  
        chart: {
            type: 'bar',
            height: 300,
            events: {                
                click: function(event, context, config) {
                                                        
                    const div = $('#chart-organization-detail');        
                    const divAlert = $('#chart-organization-detail-alert');                                                    
                    const indexSelected = config.globals.selectedDataPoints[0][0];

                    divAlert.hide();
                    divAlert.html('');

                    if (indexSelected !== undefined) {
                        div.html('');
                        div.show();
                        const url = config.config.series[config.seriesIndex].data[indexSelected].url;
                        const chartSubOrg = createChart('chart-organization-detail', {
                            series: [],  
                            chart: {
                                type: 'bar',
                                height: 250                                
                            },
                            plotOptions: {
                                bar: {                                    
                                    borderRadius: 2,                                    
                                }
                            },
                            //colors: ['#f48024']
                            colors: ['#00529c'],
                            dataLabels: {
                                formatter: function(val) {
                                    return val + '%';
                                },
                            }
                        });
                        chartSubOrg.render();
                        
                        $.get(url)
                        .done(function(data) {
                            chartSubOrg.updateSeries([{
                                name: "Выполнено задач",
                                data: data.data
                            }]);
                            chartSubOrg.updateOptions({
                                xaxis: {
                                    categories: data.labels
                                }
                            });
                        })
                        .fail(function(err) {                 
                            divAlert.html('<div class="alert alert-danger mt-3">' + err.responseText + '</div>');
                            divAlert.show();
                        });
                        
                    }
                    else {                        
                        div.hide('slow');
                    }                   
                }
            }           
        },        
        plotOptions: {
            bar: {
                //horizontal: true,
                borderRadius: 6
            }
        },
        yaxis: {
            labels: {
                maxWidth: '30%'              
            }
        },
        tooltip: {
            x: {
                formatter: function(val, { series, seriesIndex, dataPointIndex, w }) {                   
                    return w.config.series[seriesIndex].data[dataPointIndex].full_name;
                },
            },
        },
    });
    document.chartOrganization.render();    
    
    /**
     * Создание графика
     * @param {string} chartId 
     * @param {array} options 
     * @returns {ApexCharts}
     */
    function createChart(chartId, options) {              
        return new ApexCharts(document.getElementById(chartId), options);
    }

 })();




