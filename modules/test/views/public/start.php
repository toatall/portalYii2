<?php
/** @var yii\web\View $this */
/** @var \app\modules\test\models\Test $model */
/** @var \app\modules\test\models\TestResult $modelResult */

use yii\bootstrap4\Html;
use yii\bootstrap4\Progress;
use yii\bootstrap4\Tabs;
use yii\helpers\Url;

$this->params['breadcrumbs'][] = ['label' => $model->name]; 
$secondsTest = $modelResult->getTestLimitSeconds();
$secondsLeave = $secondsTest - $modelResult->seconds;

?>

<?php if ($secondsTest > 0): ?>
    <div class="card shadow mb-1 mt-1">
        <?= Progress::widget([
            'options' => [
                'id' => 'progress-bar-time-leave',
                'style' => ['height' => '2px'],                
            ],                
            'percent' => 0,
        ]) ?>
        <div class="card-header text-success">                   
            <div class="text-center">
                <h1 class="font-weight-bolder" id="time-seconds">00:00:00</h1>
            </div>
        </div>
    </div>

<?php endif; ?>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary"><?= $model->name ?></h6>
    </div>
    <div class="card-body">
        <?php if (!empty($model->description)): ?>
            <div class="card card-body"><?= $model->description ?></div>
        <?php endif; ?>

        <?php $questions = $modelResult->testResultQuestions; ?>

        <div class="mb-2">
            <?= Progress::widget([
                'options' => [
                    'id' => 'progress-bar',
                    'data-count' => count($questions)-1,
                ],
                'percent' => 0,
            ]) ?>
        </div>
        
        <?php     
            echo Html::beginForm(['/test/public/start', 'id'=>$model->id], 'post', ['id'=>'form-test']);            
            $items = [];
            for ($i=0; $i<count($questions); $i++) {
                $items[] = [
                    'label' => ''.($i+1), 
                    'content' => $this->render('_question', [
                        'model'=>$questions[$i],
                        'countQuestions'=>count($questions),
                        'indexQuestion'=>$i,
                    ]), 
                    'active'=>($i==0),
                    'headerOptions'=>['data-id' => 'tab_'.$i, 'data-progress-index' => $i],                        
                ];
            }
            
            echo Tabs::widget([
                'items' => $items,
                'id' => 'tabs-test',
            ]);

            echo Html::endForm();
        ?>
    </div>
</div>


<?php 
$timeoutSeconds = 30;
$actionTimeoutUpdate = Url::to(['/test/public/partial-set-timeout', 'id'=>$modelResult->id, 'seconds'=>$timeoutSeconds]);
$csrfInput = Html::hiddenInput(Yii::$app->request->csrfParam, Yii::$app->request->getCsrfToken()); 
$this->registerJs(<<<JS
    
    // отправка ответа для промежуточного сохранения результата
    $('.form-answer input').on('change', function() {
        const form = $(this).parents('div.form-answer');     
        form.append('$csrfInput');
        const data = form.find('input, select, textarea').serialize();
        form.find('input[type="hidden"]').remove();
        $.ajax({
            url: form.data('url'),            
            data: data,           
            method: 'post'
        });
    });

    // обновление прогресс бара
    function updateProgressBar(index)
    {
        if (index == undefined) {
            index = $('#tabs-test .active').parent('li').data('progress-index');        
        }
        const progressBar = $('#progress-bar');        
        const width = (index/progressBar.data('count'))*100;        
        progressBar.children('div').css('width', width + '%');
    }

    // кнопка вперед: переключение вкладки
    $('.btn-previous').on('click', function () {
        $('#tabs-test .active').parent('li').prev('li').find('a').trigger('click');
        updateProgressBar();
    });

    // кнопка назад: переключение вкладки
    $('.btn-next').on('click', function () {
        $('#tabs-test .active').parent('li').next('li').find('a').trigger('click');
        updateProgressBar();
    });



    // щелчок при переключении вкладки
    $('#tabs-test a[data-toggle="tab"]').on('click', function() {                
        updateProgressBar($(this).parent('li').data('progress-index'));
    });

    // сохранение промежуточного времени прохождения
    var timerTimeout; // таймер
    var timerTimeoutCount = 0; // счетчик для инкремента 
    var timerTimeoutMax = $secondsTest > 0 ? $secondsTest : 30*60; // предельное количество секунд (по умолчанию - 30 мин)    
    timerTimeout = setInterval(updateTimeoutInterval, 1000 * $timeoutSeconds);
    function updateTimeoutInterval() {

        // если превышено предельное время отправки информации о времени на сервер,
        // то завершаем это, чтобы не грузить сервер
        // возможно пользователь забыл закрыть тест
        timerTimeoutCount += $timeoutSeconds;
        if (timerTimeoutCount >= timerTimeoutMax) {
            clearTimeout(timerTimeout); 
        }

        $.ajax({
            url: '$actionTimeoutUpdate',
            method: 'get'
        })
        .done(function(data) {
            //console.log(data);
        });                
    }

    // если установлено ограниченное по времени прохождения теста
    if ($secondsTest > 0) {

        // timer
        var timerLeave;
        var countSec = $secondsLeave;

        function countDown() {
            var dt = new Date(0);
            dt.setSeconds(countSec);
            $('#time-seconds').html(dt.toISOString().substr(11, 8));
            updateProgressBarLeave(countSec);
            countSec--;
            if (countSec <= 0) {
                // click finish
                clearTimeout(timerLeave);                
                $('#form-test').submit();
            }            
        }
        // обновление прогресс бара показывающего шкалу ограничения по времени
        function updateProgressBarLeave(seconds)
        {
            const width = seconds / $secondsTest * 100;
            $('#progress-bar-time-leave').children('div').css('width', width + '%');            
        }

        timerLeave = setInterval(countDown, 1000);
    }

    // общая форма сохранения всех данных
    $('#form-test').on('submit', function() {        
        if (!confirm('Вы уверены, что хотите завершить?')) {
            return false;
        }
    });
    
JS); ?>
