<?php

/** @var \yii\web\View $this */
/** @var array $groups */
/** @var array|null $result */
/** @var array $data */

use app\assets\FlipAsset;
use yii\bootstrap5\Html;

FlipAsset::register($this);

$this->title = 'Станция «Налоговое ориентирование»';

$stopQuest = true;

$this->registerCssFile("@web/public/vendor/jquery-ui/jquery-ui.min.css", [
    'depends' => [app\assets\AppAsset::class]
]);

$this->registerJsFile(
    '@web/public/vendor/jquery-ui/jquery-ui.min.js', [
        'depends' => [app\assets\AppAsset::class],
]);

$data = (isset($result['data']) ? $result['data'] : null);
$taxesJs = "'" . implode("', '", array_map(function($val) { return $val['name']; },  $groups)) . "'";

?>

<?= Html::a('<i class="fas fa-arrow-circle-left"></i>', ['/contest/quest'], [
    'style' => 'position: fixed; left:2rem; top: 45%; font-size: 4rem;',   
    'class' => 'text-secondary',
    'title' => 'Назад',
]) ?>

<div class="mb-5" style="z-index: 10; position: relative; margin-bottom: 5rem;">

    <div class="row justify-content-center">
        <div class="col-6 text-center">
            <h3 class="text-muted mt-4">
                <?= $this->title ?>
                <br />
                <small>Распределите виды налогов по группам</small>
            </h3>  
            <hr class="w-100" />
        </div>
    </div>    

    <?php if ($result || $stopQuest): ?>
    <!--div class="row col-10 offset-1 card card-body mt-2 fa-3x bg-secondary">
        <div class="text-center text-white">
            Вы заработали <span class="badge badge-info"><?= $result['balls'] ?? 0 ?></span>
            <?php switch ($result['balls'] ?? 0) {
                case 1: 
                    echo 'балл';
                    break;
                case 2:
                case 3:
                case 4:
                    echo 'балла';
                    break;
                default: 
                    echo 'баллов';
                } ?>        
        </div>
        <div class="text-center">
            <span style="font-size: 1rem;"">Вы проходили задание <?= isset($result['date_create']) ? Yii::$app->formatter->asDatetime($result['date_create']) : null ?></span>
        </div>
    </div-->
    <?php else: ?>
    <div class="row col-10 offset-1 align-content-center justify-content-center bg-secondary p-3 text-white rounded">      
        <div class="display-4">ОСТАЛОСЬ ВРЕМЕНИ</div>
        <div id="countdown" class="tick" data-value="--:--" style="font-size: 3rem;">
            <div data-layout="vertical">
                <span data-view="flip"></span>
            </div>
        </div>            
    </div>
    <?php endif; ?>

    <div class="row mt-4">
        <div class="col">
            <div class="card shadow-sm">
                <div class="card-header bg-warning text-white lead font-weight-bolder">Федеральные налоги</div>
                <div class="card-body box droppable bg-warning" data-id="1" style="opacity: .3;"></div>
            </div>
        </div>
        <div class="col">
            <div class="card shadow-sm">
                <div class="card-header bg-secondary text-white lead font-weight-bolder">Региональные налоги</div>
                <div class="card-body box droppable bg-secondary" data-id="2" style="opacity: .3;"></div>
            </div>
        </div>
        <div class="col">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white lead font-weight-bolder">Местные налоги</div>
                <div class="card-body box droppable bg-success" data-id="3" style="opacity: .3;"></div>
            </div>
        </div>
    </div>
    <div class="row col mt-4 justify-content-center">                

        <?php foreach($groups as $group): ?>
        <div class="drag bg-primary p-2 rounded draggable mr-2 mb-2 shadow" data-name="<?= $group['name'] ?>"
            style="<?= isset($group['position']) ? 'left: ' . $group['position']['left'] . 'px; top: ' . $group['position']['top'] . 'px; position: relative;' : '' ?>">
            <i class="fas fa-arrows-alt"></i> <?= $group['name'] ?>
        </div>
        <?php endforeach; ?>

    </div>

    <?php if (!$result && !$stopQuest): ?>
    <div class="row col">
        <hr class="w-100" />
        <div class="btn-group">
            <button id="btn-save" class="btn btn-primary" disabled>Сохранить</button>
            <?= Html::a('Очистить', '', ['class' => 'btn btn-warning']) ?>
        </div>
    </div>
    <?php endif; ?>

</div>

<?php 

if (!$result && !$stopQuest) {
    $this->registerJs(<<<JS
        
        var taxes = [$taxesJs];    
        var taxesGroup = {
            "1": [],
            "2": [],
            "3": [],
        };


        $('.draggable').draggable({});

        function dropIn(drop, ui) {
            const id = $(drop.target).data('id');
            const name = ui.draggable.data('name');
                
            if (!taxesGroup[id].includes(name)) {
                taxesGroup[id].push({ 'name': name, 'position': ui.position, groupId: id });
            }
            if (taxes.includes(name)) {
                taxes = taxes.filter(function(value, index, arr) {
                    return value != name;
                });
            }
            
            checkBtnSave();
        }

        function dropOut(drop, ui) {
            const id = $(drop.target).data('id');
            const name = ui.draggable.data('name');
                
            if (!taxes.includes(name)) {
                taxes.push({ 'name': name, 'position': ui.position, groupId: id });
            }
            if (taxesGroup[id].includes(name)) {
                taxesGroup[id] = taxesGroup[id].filter(function(value, index, arr) {
                    return value != name;
                });
            }
            
            checkBtnSave();
        }

        function checkBtnSave() {
            const flag = (taxes.length > 0);
            $('#btn-save').prop('disabled', flag);
        }


        $('.droppable').droppable({
            drop: function(event, ui) {
                dropIn(event, ui);                  
            },
            out: function(event, ui) {
                dropOut(event, ui);
            }
        });

        $('#btn-save').on('click', function() {
            if (!confirm('Вы уверены, что хотите завершить?')) {
                return false;
            }
            save();
        });

        function save() {        
            $.ajax({
                method: 'post',
                data: taxesGroup,
                async: false
            })
            .done(function() {
                location.reload();
            });
        }

        var timer = 5 * 60;
            
        if (localStorage.getItem('timerStep4') != null && localStorage.getItem('timerStep4') > 0) {
            timer = localStorage.getItem('timerStep4');
        }

        function setTime() {
            const tick = $('#countdown');  
            var d = new Date(null);                
            d.setSeconds(timer);
            tick.attr('data-value', d.toISOString().substring(14, 19));        
            localStorage.setItem('timerStep4', timer);
            
            if (timer <= 0) {                
                stopTime();              
            }

            timer--;
        }

        var interval = setInterval(() => {
            setTime(); 
        }, 1000);
        
        function stopTime() {            
            clearInterval(interval);
            save();
        } 
        
        
    JS);
}

$this->registerCss(<<<CSS
    .box {
        height: 15rem;
    }
    .drag {
        /* width: 10rem; */
        cursor: move;
        color: white;
    }    
CSS);


?>