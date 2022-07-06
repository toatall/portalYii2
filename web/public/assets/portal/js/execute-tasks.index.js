
(function() {
    
    // создание грфиков    
    document.chartTotal = createChart('chart-total', {
        series: [0],
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
        labels: ['-'],
    });
    document.chartTotal.render();

    // график по отделам
    document.chartDepartment = createChart('chart-departments', {
        series: [],  
        chart: {
            type: 'bar',        
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
                        
                        $.get(url)
                        .done(function(data) {                          
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
                barHeight: '80%',  
                height: '100px'              
            }
        },           
        yaxis: {
            labels: {
                style: {
                    colors: '#ccc'
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
    document.chartDepartment.render();
        
    // график по инспекциям
    document.chartOrganization = createChart('chart-organization', {
        series: [],  
        chart: {
            type: 'bar',
            // height: 300,
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
                        
                        $.get(url)
                        .done(function(data) {                            
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
                    colors: '#ccc'
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





