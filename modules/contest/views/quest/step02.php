<?php

/** @var \yii\web\View $this */
/** @var array $data */
/** @var array|null $result */
/** @var array|null $resultData */
/** @var array|null $savedData */
/** @var array $words */


use yii\bootstrap4\Html;
use app\assets\FlipAsset;

FlipAsset::register($this);

$this->title = 'Станция «Занимательная»';

$stopQuest = true;
$this->registerCssFile("@web/vendor/crosswords/main.css", [
    'depends' => [app\assets\AppAsset::class]
]);

?>
<style type="text/css">
    table {
        display: table;
        border-collapse: collapse;
        /* box-sizing: border-box; */
        text-indent: initial;
        border-spacing: 2px;
        /* border:solid gray 1px; */
        border-radius:6px;
    }
  
    .classik_non {
        width: 30px;
        height: 30px;
        /* background-color: #eee; */
        
    }
    .td_key {
        width: 30px;
        height: 30px;
        text-align: center;
        border: 1px solid #aaa;
    }
    .input_key {
        background: transparent;
        width: 26px;
        cursor: pointer;
        font-family: Arial;
        font-size: 15px;
        color: #3887C7;
        text-align: center;
        text-transform: uppercase;
        font-weight: 900;
        border-radius: 5px;
        border: 1px solid #aaa;
    }
    .input_key:focus {
        color: red;
        border: 2px solid #F47E58;
        outline: none;
        background-color: #333;
    }
</style>

<?= Html::a('<i class="fas fa-arrow-circle-left"></i>', ['/contest/quest'], [
    'style' => 'position: fixed; left:2rem; top: 45%; font-size: 4rem;',   
    'class' => 'text-secondary',
    'title' => 'Назад',
]) ?>

<div class="row justify-content-center">
    <div class="col-6 text-center">
        <h3 class="text-muted mt-4">
            <?= $this->title ?>           
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
    <div class="col pt-5 pr-3">
        <?= Html::beginForm('', 'post', ['id' => 'form-save-crossword']) ?>
        <div class="text-center" style="z-index: 10; position: relative;">
            <table cellspacing="1" cellpadding="0">
                <?php foreach($data as $row => $a): ?>
                    <tr>
                    <?php for($i=0; $i<=23; $i++): ?>
                        <td class="<?= isset($a[$i]) && isset($a[$i]['char']) ? 'td_key' : 'classik_non' ?>">
                            <?php if (isset($a[$i])): ?>
                                <?php if (isset($a[$i]['placeholder'])): ?>
                                    <span class="p-1 text-muted lead fa-1x">
                                        <?= $a[$i]['placeholder'] ?>
                                    </span>
                                <?php else: ?>
                                    <input type="text" class="input_key bg-white" minlength="1" maxlength="1" 
                                        <?= isset($savedData[$row][$i]) ? ' value="' . $savedData[$row][$i] . '" ' : null ?>  
                                        name="<?= $a[$i]['name'] ?>" 
                                        data-id="<?= $a[$i]['numberQuestion'] ?>" data-dir="<?= $a[$i]['type'] ?>"
                                        autocomplete="off"<?= ($result || $stopQuest) ? ' disabled' : '' ?> />
                                <?php endif; ?>
                            <?php else: ?>
                                &nbsp;
                            <?php endif; ?>
                        </td>
                    <?php endfor; ?>
                    </tr>
                <?php endforeach; ?>        
            </table>
        </div>
        <?php if (!$result && !$stopQuest): ?>
        <div class="btn-group mt-5">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary', 'disabled0' => 'disabled', 'id' => 'btn-submit']) ?>
            <?= Html::a('Очистить', '', ['class' => 'btn btn-warning']) ?>
        </div>
        <?php endif; ?>
        <?= Html::endForm() ?>
    </div>
    <div class="col">        
        <h3>По горизонтали:</h3>
            <ol style="text-align: justify;">
                <?php foreach($words['horizontal'] as $num=>$word): ?>
                    <?php
                        $colorClass = '';
                        if (isset($resultData['check'])) {
                            if (isset($resultData['check']['horizontal'][$num])) {
                                if ($resultData['check']['horizontal'][$num]) {
                                    $colorClass = 'text-success';
                                }
                                else {
                                    $colorClass = 'text-danger';
                                }
                            }
                        }
                    ?>
                <li <?= $colorClass ? 'class="' . $colorClass . '"' : '' ?>><?= $word['question'] ?></li>
                <?php endforeach; ?>             
            </ol>
        <hr />
        <h3>По вертикали:</h3>
        <ol style="text-align: justify;">
            <?php foreach($words['vertical'] as $num=>$word): ?>
                    <?php
                        $colorClass = '';
                        if (isset($resultData['check'])) {
                            if (isset($resultData['check']['vertical'][$num])) {
                                if ($resultData['check']['vertical'][$num]) {
                                    $colorClass = 'text-success';
                                }
                                else {
                                    $colorClass = 'text-danger';
                                }
                            }
                        }
                    ?>
                <li <?= $colorClass ? 'class="' . $colorClass . '"' : '' ?>><?= $word['question'] ?></li>
                <?php endforeach; ?>          
        </ol>
    </div>
</div>
<?php 

$this->registerCss(<<<CSS
    input[disabled] {
        filter: brightness(0.9);
    }
CSS);

if (!$result) {
    $this->registerJs(<<<JS
               
        var inputOptions = {
            dir: null,
            id: 0
        };

        $('input.input_key').on('click', function() {
            inputOptions.dir = null;
            inputOptions.id = 0;
        });

        $('input.input_key').keyup(function() {            
            const keyNum = $(this).val();
            if (keyNum) {
                const names = $(this).attr('name').match(/\[(\d*)\]/g);
                const dir = $(this).data('dir');
                const id = $(this).data('id');
                
                var col = names[0].replace(/\[|\]/g, '');
                var row = names[1].replace(/\[|\]/g, '');
                let currentDir = dir;

                if (inputOptions.dir) {
                    currentDir = inputOptions.dir;
                }
                else {
                    inputOptions.dir = dir;                    
                }

                if (currentDir == 'horizontal') {
                    row++;
                }
                else {
                    col++;
                }
          
                const nextInput = $('input.input_key[name="answer[' + col + '][' + row + ']"]');                
                if (nextInput.length > 0) {
                    nextInput.focus().select();                    
                }
            }
        });



        var timer = 5 * 60;
        
        if (localStorage.getItem('timerStep2') != null && localStorage.getItem('timerStep2') > 0) {
            timer = localStorage.getItem('timerStep2');
        }
       
        function setTime() {
            const tick = $('#countdown');  
            var d = new Date(null);                
            d.setSeconds(timer);
            tick.attr('data-value', d.toISOString().substring(14, 19));        
            localStorage.setItem('timerStep2', timer);
            
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
            $('#form-save-crossword').submit();
        }      

        $('#btn-submit').on('click', function() {
            return confirm('Вы хотите завершить?');
        });
      
    JS);
}

?>