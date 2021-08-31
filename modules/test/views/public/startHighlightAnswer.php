<?php
/** @var yii\web\View $this */
/** @var \app\modules\test\models\Test $model */
/** @var \app\modules\test\models\TestResult $modelResult */

use yii\bootstrap4\Html;
use yii\bootstrap4\Progress;
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
               

        <div id="question-container" data-url="<?= Url::to(['/test/public/highlight-answer', 'idResult'=>$modelResult->id]) ?>"></div>

        <div class="text-center mb-2">
            <button class="btn btn-outline btn-primary" id="btn-next">Далее</button>
        </div>

        <div class="mb-2 alert alert-danger" style="display: none;"></div>

    </div>
</div>


<?php 
$timeoutSeconds = 30;
$actionTimeoutUpdate = Url::to(['/test/public/partial-set-timeout', 'id'=>$modelResult->id, 'seconds'=>$timeoutSeconds]);
$actionFinihs = Url::to(['/test/public/finish', 'idResult'=>$modelResult->id]);
$csrfInput = Html::hiddenInput(Yii::$app->request->csrfParam, Yii::$app->request->getCsrfToken()); 
$this->registerJs(<<<JS
    
    function nextQuestion()
    {
        const contQuestion = $('#question-container')   ;
        const url = contQuestion.data('url');
        $.get(url)
        .done(function(data) {
            if (data == 'finish') {
                finishTest();
            }
            else {
                contQuestion.html(data);                
            }
        })
        .fail(function(err) {
            const errorContainer = $('.alert-danger');
            errorContainer.show();
            errorContainer.html(err.responseText);
        });
        $('#btn-next').hide();
    }

    nextQuestion();

    $(document).on('click', '.link-answer', function() {      
        const url = $(this).attr('href');
        const questionContainer = $('.form-answer');
        $.get(url)
        .done(function(data) {
            const template = '<div class="alert alert-{type-alert}"><strong>{title}</strong><br />{text}</div>';
            res = template.replace('{type-alert}', data.is_right ? 'success' : 'danger');
            res = res.replace('{title}', data.is_right ? 'Верно!' : 'Не верно!');
            if (data.is_right) {
                res = res.replace('{text}', '');                
            }
            else {
                res = res.replace('{text}', 'Правильный ответ: ' + data.right_answer.name);
            }
            
            questionContainer.html(res);
            $('#btn-next').show();
        })
        .fail(function(err) {
            const errorContainer = $('.alert-danger');
            errorContainer.show();
            errorContainer.html(err.responseText);
        });
        return false;
    });

    $('#btn-next').on('click', function() {
        $(this).hide();
        nextQuestion();        
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
            //updateProgressBarLeave(countSec);
            countSec--;
            if (countSec <= 0) {
                // click finish
                clearTimeout(timerLeave);
                //$('#form-test').submit();
                finishTest();
            }            
        }        

        timerLeave = setInterval(countDown, 1000);
    }

    function finishTest()
    {
        //document.location.reload();
        window.location.href='$actionFinihs';
    }
   
    
JS); ?>
