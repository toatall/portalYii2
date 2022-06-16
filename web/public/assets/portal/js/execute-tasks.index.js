
(function() {
    // объявление переменных
    // const inputPeriod = $('[name="period"]');
    // const inputPeriodYear = $('[name="periodYear"]');
    // const inputDepartment = $('[name="department"]'); 

    // inputPeriod.on('change', function() {
    //     sendForm();       
    // });

    // inputPeriodYear.on('change', function() {
    //     sendForm();
    // });

    // inputDepartment.on('change', function() {
    //     sendForm();
    // });

    /**
     * Отправка данных формы-фильтра
     */
    // function sendForm() {
    //     if (isFormValidate()) {           
    //         form = $('#form-filter-radar');
    //         $.get({
    //             url: form.attr('action'),
    //             data: form.serialize()
    //         })
    //         .done(function(data) {            
    //             if (data == null) {
    //                 setCanvasText('general', 'Нет данных', 30, 50);
    //                 setCanvasText('departments', 'Нет данных', 30, 50);
    //                 setCanvasText('organizations', 'Нет данных', 30, 50);
    //             }
    //             else {
    //                 setChartData(chartGeneral, data.general);
    //                 //setChartData('#general', data.general, 'radar', true);
    //                 //updateChart('#general', data.general);
    //                 //setChartData('#departments', data.departments, 'line', false);
    //                 // setCanvasText('departments', 'Выполняется доработка...', 30, 50);
    //                 // setCanvasText('organizations', 'Выполняется доработка...', 30, 50);
    //             }
    //         })
    //         .fail(function(err) {
    //             $('#result_error').html('<div class="alert alert-danger mt-3">' + err.responseText + '</div>');
    //         });
            
    //     }
    // }

    /**
     * Проверка выбора всех значений у фильтра
     * @returns {boolean}
     */
    // function isFormValidate() {
    //     if (inputPeriod.val() != "" && inputPeriodYear.val() != "" && inputDepartment.val() != "") {
    //         return true;
    //     }
    //     else {
    //         return false;
    //     }
    // }

    // создание грфиков    
    document.chartTotal = createChart('chart-total', {
        series: [0],
        chart: {
            type: 'radialBar',
            // offsetY: -20,
            // sparkline: {
            //     enabled: true
            // }
            height: 300
        },
        colors: ['#1c78d5'],
        plotOptions: {
            radialBar: {
                // startAngle: -90,
                // endAngle: 90,
                // track: {
                //     background: "#e7e7e7",
                //     strokeWidth: '97%',
                //     margin: 5, // margin is in pixels
                //     dropShadow: {
                //         enabled: true,
                //         top: 2,
                //         left: 0,
                //         color: '#999',
                //         opacity: 1,
                //         blur: 2
                //     }
                // },
                dataLabels: {
                    name: {
                        show: false
                    },
                    value: {
                        offsetY: 10,
                        fontSize: '2rem',
                        //fontFamily: 'Consolas',
                        color: 'white'
                    }
                }
            }
        },
        grid: {
            padding: {
                top: -10
            }
        },
        // fill: {
        //     type: 'gradient',
        //     gradient: {
        //         shade: 'light',
        //         shadeIntensity: 0.4,
        //         inverseColors: false,
        //         opacityFrom: 1,
        //         opacityTo: 1,
        //         stops: [0, 50, 53, 91]
        //     },
        // },
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
                                                        
                    const divOtherMain = $('#organizations-detail-main');

                    const divMain = $('#departments-detail-main');
                    const divTable = $('#departments-detail-table');
                    const divEmployee = $('#departments-detail-employee');
                    const divTitle = $('#departments-detail-title');
                    const divAlert = $('#departments-detail-alert');                                                                        

                    const indexSelected = config.globals.selectedDataPoints[0][0];

                    divAlert.hide();
                    divOtherMain.hide();
                    divAlert.html('');

                    if (indexSelected !== undefined) {
                        
                        divTable.html('');
                        divEmployee.html('');
                        divTitle.html('');

                        divMain.show('slow');                        
                        
                        const url = config.config.series[config.seriesIndex].data[indexSelected].url;
                        
                        /*
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
                        chartSubDep.render(); */
                        
                        $.get(url)
                        .done(function(data) {
                            // chartSubDep.updateSeries([{
                            //     name: "Выполнено задач",
                            //     data: data.data
                            // }]);
                            // chartSubDep.updateOptions({
                            //     xaxis: {
                            //         categories: data.labels
                            //     }
                            // });
                            divTable.html(data.table);
                            divEmployee.html(data.employee);
                            divTitle.html(data.deaprtmentName);
                        })
                        .fail(function(err) {                 
                            divAlert.html('<div class="text-danger mt-3">' + err.responseText + '</div>');
                            divAlert.show();
                        });
                        
                    }
                    else {
                        divMain.hide('slow');
                    }
                }
            }
        },        
        colors: ['#1c78d5'],
        plotOptions: {
            bar: {               
                borderRadius: 6,
                horizontal: true,
            }
        },        
        yaxis: {
            labels: {
                maxWidth: '30%',  
                style: {
                    colors: ['#ccc']
                }              
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
                                                        
                    const divOtherMain = $('#departments-detail-main');

                    const divMain = $('#organizations-detail-main');
                    const divTable = $('#organizations-detail-table');
                    const divEmployee = $('#organizations-detail-employee');
                    const divTitle = $('#organizations-detail-title');
                    const divAlert = $('#departments-detail-alert');     

                    const indexSelected = config.globals.selectedDataPoints[0][0];

                    divAlert.hide();
                    divOtherMain.hide();
                    divAlert.html('');

                    if (indexSelected !== undefined) {
                        
                        divTable.html('');
                        divEmployee.html('');
                        divTitle.html('');
                        
                        divMain.show();

                        const url = config.config.series[config.seriesIndex].data[indexSelected].url;
                        
                        // const chartSubOrg = createChart('chart-organization-detail', {
                        //     series: [],  
                        //     chart: {
                        //         type: 'bar',
                        //         height: 250                                
                        //     },
                        //     plotOptions: {
                        //         bar: {                                    
                        //             borderRadius: 2,                                    
                        //         }
                        //     },
                        //     //colors: ['#f48024']
                        //     colors: ['#00529c'],
                        //     dataLabels: {
                        //         formatter: function(val) {
                        //             return val + '%';
                        //         },
                        //     }
                        // });
                        // chartSubOrg.render();
                        
                        $.get(url)
                        .done(function(data) {
                            // chartSubOrg.updateSeries([{
                            //     name: "Выполнено задач",
                            //     data: data.data
                            // }]);
                            // chartSubOrg.updateOptions({
                            //     xaxis: {
                            //         categories: data.labels
                            //     }
                            // });
                            divTable.html(data.table);
                            divEmployee.html(data.employee);
                            divTitle.html(data.organizationName);

                        })
                        .fail(function(err) {
                            divAlert.html('<div class="text-danger mt-3">' + err.responseText + '</div>');
                            divAlert.show();
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
                horizontal: true,
                borderRadius: 6
            }
        },
        yaxis: {
            labels: {
                maxWidth: '30%',
                style: {
                    colors: ['#ccc']
                },
            },            
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





