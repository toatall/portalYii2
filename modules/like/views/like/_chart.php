<?php
/** @var \yii\web\View $this */
/** @var int $idLike */
/** @var array $groupDataByDate */

use app\assets\ApexchartsAsset;

ApexchartsAsset::register($this);

$idChart = 'apex-chart-'. $idLike;
$data = json_encode($groupDataByDate);
?>
<div id="<?= $idChart ?>"></div>

<?php 
$this->registerJs(<<<JS
    (function(){        
        const data = $data
        let seriesData = []
        data.map(function(val) { 
            seriesData.push({
                x: new Date(val.date).toLocaleDateString(),
                y: + val.count_likes,
            })
        })

        const chart = new ApexCharts(document.querySelector('#$idChart'), {
            title: {
                text: 'График по датам',
            },
            chart: {                 
                type: 'bar',
                height: 250,               
            },            
            yaxis: {
                labels: {
                    formatter: function(val) {
                        return val.toFixed(0)
                    },
                },
            },
            series: [{
                name: 'Количество лайков',              
                data: seriesData,
            }]
        })
        chart.render()
        
    }())

JS);