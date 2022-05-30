<?php

/** @var \yii\web\View $this */
/** @var array $questions */
/** @var array $result */
/** @var array $data */

use yii\bootstrap4\Html;
use app\assets\FlipAsset;

FlipAsset::register($this);
$this->title = 'Станция «Любознательная»';

?>

<?= Html::a('<i class="fas fa-arrow-circle-left"></i>', ['/contest/quest'], [
    'style' => 'position: fixed; left:2rem; top: 45%; font-size: 4rem;',   
    'class' => 'text-secondary',
    'title' => 'Назад',
]) ?>


<div class="d-none d-xl-block" style="position: absolute; bottom: 35vh; left: 10vw; z-index: 200;">
    <img src="/public/assets/contest/quest/img/person2.png" style="height: 35vh;" />
</div>

<div class="mb-5" style="z-index: 10; position: relative; margin-bottom: 5rem;">
    
    <div class="row justify-content-center">
        <div class="col-6 text-center">
            <h3 class="text-muted mt-4">
                <?= $this->title ?>
                <br />
                <small>Викторина</small>
            </h3>  
            <hr class="w-100" />
        </div>
    </div>


    <?php if ($result): ?>
    <div class="row col-10 offset-2 card card-body mt-2 fa-3x bg-secondary">
        <div class="text-center text-white">
            Вы заработали <span class="badge badge-info"><?= $result['balls'] ?></span>
            <?php switch ($result['balls']) {
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
            <span style="font-size: 1rem;"">Вы проходили задание <?= Yii::$app->formatter->asDatetime($result['date_create']) ?></span>
        </div>
    </div>
    <?php else: ?>
    <div class="row col-10 offset-2 align-content-center justify-content-center bg-secondary p-3 text-white rounded">      
        <div class="display-4">ОСТАЛОСЬ ВРЕМЕНИ</div>
        <div id="countdown" class="tick" data-value="--:--" style="font-size: 3rem;">
            <div data-layout="vertical">
                <span data-view="flip"></span>
            </div>
        </div>            
    </div>
    <?php endif; ?>


    <div class="row col-10 offset-2 mt-4">
        <div class="col">
            
            <?= Html::beginForm('', 'post', ['id'=>'form-step5']) ?>            
            <?php foreach($questions as $question): ?>
                <div class="card mb-2">
                    <div class="card-header">
                        <p style="font-size: 1.2rem;">
                            <?= $question['name'] ?>
                        </p>
                    </div>
                    <div class="card-body">
                        <div class="row form-group">
                            <div class="col-4 text-right">
                                <?= Html::label($question['label'], '', ['class' => '']) ?>
                            </div>
                            <div class="col-6">
                                <?= Html::textInput('answer[' . $question['id'] . ']', 
                                    isset($data['answer'][$question['id']]) ? $data['answer'][$question['id']] : '', 
                                    [
                                        'class' => 'form-control', 
                                        'autocomplete' => 'off',
                                    ]
                                    + 
                                    ($result ? ['disabled' => 'disabled'] : [])) ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>    
            
            <?if (!$result): ?>
            <div class="btn-group mt-3">
                <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary', 'id'=>'btn-save']) ?>
                <?= Html::a('Очистить', '', ['class' => 'btn btn-warning']) ?>
            </div>
            <?php endif; ?>
            <?= Html::endForm() ?>
            
        </div>
    </div>

</div>

<?php 

if (!$result) {
    $this->registerJs(<<<JS


        function save() {        
            $.ajax({
                method: 'post',
                data: $('#form-step5').serialize(),
                async: false
            })
            .done(function() {
                location.reload();
            });
        }

        $('#btn-save').on('click', function() {
            if (confirm('Вы увреены, что хотите завершить?')) {
                save();
            }
            return false;
        });


        var timer;
            
        if (localStorage.getItem('timerStep5') == null || localStorage.getItem('timerStep5') <= 0) {
            timer = 5 * 60;
        }
        else {
            timer = localStorage.getItem('timerStep5');
        }
        function setTime() {
            const tick = $('#countdown');  
            var d = new Date(null);                
            d.setSeconds(timer);
            tick.attr('data-value', d.toISOString().substring(14, 19));        
            localStorage.setItem('timerStep5', timer);
            
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

?>


