<?php
/** @var \yii\web\View $this */

use app\modules\dashboardEcr\assets\AppAsset;
use yii\bootstrap5\Html;
use yii\helpers\Url;

AppAsset::register($this);
?>

<div class="row pt-3">
    <div class="col d-flex justify-content-center">
        <div class="btn-group">
            <button class="btn btn-light border btn-migrate" data-migrate="to" data-url="<?= Url::to(['get-data', 'type' => 'in']) ?>">
                <i class="fas fa-arrow-up"></i> Миграция в округ
            </button>
            <button class="btn btn-light border btn-migrate" data-migrate="out" data-url="<?= Url::to(['get-data', 'type' => 'out']) ?>">
                Миграция из округа <i class="fas fa-arrow-down"></i>
            </button>
            <?php if (Yii::$app->user->can('admin')): ?>
            <?= Html::a('<i class="fas fa-pencil"></i> Редактировать', ['update'], ['class' => 'btn btn-primary mv-link']) ?>
            <?php endif; ?>
        </div>
    </div>
</div>
<div class="row">    
    <div class="col">    
        <?= $this->render('_map') ?>
    </div>
    <div class="col-3" id="container-table">
        
    </div>
</div>

<?php 
$this->registerJs(<<<JS
       
    $('.btn-migrate').on('click', function(){
        
        $('.popover').hide()
        let btn = $(this)
        btn.append(' <span class="spinner-border spinner-border-sm"></span>')
        btn.prop('disabled', true)

        const typeMigrate = $(this).data('migrate')
        $(this).addClass(['active', 'fw-bold'])
        $('.btn-migrate').not($(this)).removeClass(['active', 'fw-bold'])
        const url = $(this).data('url')
        $.get(url)
        .done(function(json){

            colorsMapReset()
            
            let max = 0
            let full = 0
            for(key in json) {
                let val = json[key]
                full += +val
                if (max == null) {
                    max = val
                }            
                else {
                    if (val > max) {
                        max = val
                    }
                }
            }

            createTable(json, typeMigrate, full)

            const colorsIn = { 'fill': [50, 200, 50], 'stroke': [10, 100, 10] }
            const colorsOut = { 'fill': [200, 50, 50], 'stroke': [100, 10, 10] }
            let colors = (typeMigrate == 'to') ? colorsIn : colorsOut            
            
            for(key in json) {                
                let opacity = json[key] / max               
                
                let fill = 'rgba(%r%, %g%, %b%, %opacity%)'
                    .replace('%r%', colors.fill[0])
                    .replace('%g%', colors.fill[1])
                    .replace('%b%', colors.fill[2])
                    .replace('%opacity%', opacity) 

                let stroke = 'rgb(%r%, %g%, %b%)'
                    .replace('%r%', colors.stroke[0])
                    .replace('%g%', colors.stroke[1])
                    .replace('%b%', colors.stroke[2])

                $('#R' + key).css('old-fill', $('#R' + key).css('fill'))    
                $('#R' + key).css('fill', fill)
                $('#R' + key).css('stroke', stroke)

                $('#R' + key).css('stroke-width', '1')
                $('#R' + key).attr('data-bs-content', 
                    '<span class="fs-6">Налогоплательщиков: <b>' + json[key] + '</b></span><br />' 
                    + '(<b>' + Math.round(json[key] / full * 100) + '%</b> от общего количества)')

                new bootstrap.Popover($('#R' + key))

            }

        })
        .always(() => {
            btn.find('span.spinner-border').remove()
            btn.prop('disabled', false)
            $('.popover').hide()
        })

        return false
    })

    function createTable(data, typeMigrate, full) {
        
        data2 = Object.keys(data).sort(function(a, b) { return data[b] - data[a] })      

        let t = '<table class="table table-bordered bg-white">'
        t += `
            <tr>
                <th>№</th>
                <th>Регион</th>
                <th>Количество НП</th>
            </tr>`

        let sum = 0
        let index = 0

        for(k in data2) {
            index += 1
            let key = data2[k]
            let val = data[data2[k]]
            sum += +val
            let arrowIcon = typeMigrate == 'to' ? '<i class="arr fas fa-arrow-up text-success"></i>' : '<i class="arr fas fa-arrow-down text-danger"></i>'
            t += `
            <tr>
                <td>` + index + `</td>
                <td>` + getRegionByCode(key) + `</td>
                <td>` + arrowIcon + ` ` + val + ` (` + Math.round(val / full * 100) + `%)</td>
            </tr>`
        }
        t += `
            <tr>
                <th colspan="2">Итого</th>
                <th>` + sum + ` (100%)</th>
            </tr>`

        // 
        t += '</table>'

        $('#container-table').html(t) 
    }

    $('.btn-migrate[data-migrate="to"]').trigger('click')

JS); 
?>




