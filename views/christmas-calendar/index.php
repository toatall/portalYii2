<?php
/* @var $this \yii\web\View */
/* @var $model \app\models\christmascalendar\ChristmasCalendar */
/* @var $data \app\models\christmascalendar\ChristmasCalendarQuestion[] */
/* @var $today \app\models\christmascalendar\ChristmasCalendarQuestion */
/* @var $listUsers array */

use app\models\christmascalendar\ChristmasCalendarQuestion;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Проект "SUPER STAЖ"';
$this->params['breadcrumbs'][] = $this->title;
$dateStart = $model->getDateStart();
$dateEnd = $model->getDateEnd();
$dateNow = $model->getDateNow();

?>
<div class="crismas-calendar-index">
    <h1><?= $this->title ?></h1>
    <hr />

    <main>
        <table class="calendar">
            <caption class="calendar__banner--month">
                <h1>Декабрь</h1>
            </caption>
            <caption class="calendar__banner--month">
                <?php if ($today != null): ?>
                <div class="alert alert-warning">
                    <h4 style="font-weight: bolder;">Задание на сегодня!</h4>
                    <?= $today->description ?>
                </div>
                <?php if (!$today->isAnswered()): ?>
                <div class="answer">
                    <?= Html::a('Я знаю кто это', ['/christmas-calendar/guess'], ['class'=>'btn btn-primary', 'id'=>'btn-guess']) ?>
                </div>
                <div id="container-guess" style="display:none;">
                    <?= Html::beginForm(['/christmas-calendar/guess'], 'post', ['id'=>'form-guess']) ?>
                    <div class="col-sm-10">
                        <?= Select2::widget([
                            'name' => 'answer',
                            'data' => $listUsers,
                            'options' => [
                                'placeholder' => 'Выберите сотрудника',
                            ],
                        ]) ?>
                    </div>
                    <div class="col-sm-2">
                        <?= Html::submitButton('Сохранить', ['class'=>'btn btn-primary col-sm-12']) ?>
                    </div>
                    <?= Html::endForm(); ?>
                    <?php
$this->registerJs(<<<JS
    $('#btn-guess').on('click', function() {
        $('.answer').hide();
        $('#container-guess').show();
        return false;
    });

    $('#form-guess').on('submit', function(e) {
        e.preventDefault();
        let sel = $(this).find('select').first().val();
        let cont = $('#container-guess');
        let form = $(this);
        
        if (sel === '') {
            alert('Выберите сотрудника');
            return false;
        }
        
        $(this).find('[type="submit"]').prop('disabled', true);
        
        $.ajax({
            url: form.attr('action'),
            method: 'post',
            data: form.serialize()
        })
        .fail(function() {
            cont.html('<div class="alert alert-dander">Что то пошло не так! Попробуйте снова!</div>');
        })
        .done(function() {
            cont.html('<div class="alert alert-info">Ваш голос принят!</div>');
        });
        
        return false;
    });
JS
)
                    ?>
                    </div>
                    <?php else: ?>
                    <div class="alert alert-info">Вы уже ответили!</div>
                    <?php endif; ?>
                <?php endif; ?>
            </caption>
            <thead>
            <tr>
                <th class="calendar__day__header">Понедельник</th>
                <th class="calendar__day__header">Вторник</th>
                <th class="calendar__day__header">Среда</th>
                <th class="calendar__day__header">Четверг</th>
                <th class="calendar__day__header">Пятница</th>
                <th class="calendar__day__header">Суббота</th>
                <th class="calendar__day__header">Воскресение</th>
            </tr>
            </thead>
            <tbody>
                <?php for ($i=0; $i<=($model->countWeeks()); $i++): ?>
                <tr>
                    <?php for ($w=1; $w<=7; $w++): ?>
                    <td class="calendar__day__cell">
                        <?php if ($dateStart <= $dateEnd && $dateStart->format('N') == $w): ?>
                            <div style="height: 160px; display: inline-block; vertical-align: middle; text-align: center;">
                                <div style="display: inline-block; vertical-align: middle; min-height: 130px; min-width: 130px;" class="thumbnail">
                            <?php
                            // если дата прошла, то показать фотку
                            if (true/*$dateStart->format('j') < $dateNow->format('j')*/): ?>
                                <?php if (isset($data[$dateStart->format('j')]) && $data[$dateStart->format('j')] != null): ?>
                                    <a href="<?= Url::to(['/christmas-calendar/statistic', 'day'=>$dateStart->format('j')]) ?>" class="mv-link" title="Смотреть статистику">
                                        <img src="<?= $data[$dateStart->format('j')]->photo ?>" style="max-width: 128px; max-height: 128px;" />
                                        <div class="label label-default" style="font-size: small; display: block; margin-top: 5px; max-width: 128px; white-space: normal;">
                                            <?= $data[$dateStart->format('j')]->user['fio'] ?>
                                        </div>
                                    </a>
                                <? else: ?>
                                    <?php if ($dateStart->format('j') == '31'): ?>
                                        <video controls="" style="max-width: 128px; max-height: 128px;">
                                            <source src="/files_static/christmas-calendar/Итоги СуперStaЖ.mp4">
                                        </video>
                                        <div class="label label-default" style="font-size: small; display: block; margin-top: -10px; max-width: 128px; white-space: normal;">
                                            Итоги СуперStaЖ
                                        </div>
                                    <?php else: ?>
                                        <img src="<?= ChristmasCalendarQuestion::pickRandom() ?>" />
                                    <?php endif; ?>
                                <? endif; ?>
                            <? else: ?>
                                <?php if ($dateStart->format('j') == $dateNow->format('j') && !empty($data[$dateStart->format('j')])): ?>
                                    <div>
                                        <i class="fas fa-question text-muted text-today" style="font-size: 120px;"></i>
                                    </div>
                                <?php else: ?>
                                <img src="<?= ChristmasCalendarQuestion::pickRandom() ?>" />
                                <?php endif; ?>
                            <? endif; ?>
                                </div>
                            </div>
                            <div class="clear" style="margin-top: 5px;"></div>

                            <span class="<?= $dateStart == $dateNow ? 'label label-default' : '' ?>"><?= $dateStart->format('j') ?></span>

                        <?php $dateStart = $dateStart->modify('+1 day');
                        endif;
                        ?>
                    </td>
                    <?php endfor; ?>
                </tr>
                <?php endfor; ?>
            </tbody>
        </table>

        <div style="margin-top: 20px;">
            <div class="calendar__banner--month">
                <h1>Правильно ответили</h1>
            </div>
            <ul class="list-group">
            <?php foreach ($model->getRating() as $rating): ?>
                <li class="list-group-item">
                    <?= $rating['#'] . '. ' . $rating['fio'] ?>
                    (<?= $rating['count_answers'] ?>)
                    <!--span class="label label-success" style="font-size: large"><?= $rating['count_answers'] ?></span-->
                </li>
            <?php endforeach; ?>
            </ul>
        </div>
    </main>

</div>
<link href="/css/Montserrat/stylesheet.css" rel="stylesheet">
<style type="text/css">

    /* animashka */
    .text-today {
        animation-name: vibro;
        animation-timing-function: cubic-bezier(1, 1, 1, 1);
        animation-iteration-count: 10;
        animation-duration: 2.5s;
    }
    @keyframes vibro {
        50% {
            transform: scale(0.95); filter: brightness(150%);
        }
    }

    main {
        background-color: #F6E9DC;
        box-shadow: 0px 0px 0px 2px #e66053, 10px 10px 20px 10px rgba(78, 79, 74, 0.5);
        flex-basis: 980px;
        font-family: 'Montserrat';
        font-weight: 700;
    }

    .calendar {
        table-display: fixed;
        border: 2px solid #e66053;
        width: 100%;
    }

    .calendar__day__header,
    .calendar__day__cell {
        border: 2px solid #e66053;
        text-align: center;
        width: calc(100% / 7);
        vertical-align: middle;
    }
    .calendar__day__cell {
        height: 20vh;
    }

    .calendar__day__header:first-child,
    .calendar__day__cell:first-child {
        border-left: none;
    }
    .calendar__day__header:last-child,
    .calendar__day__cell:last-child {
        border-right: none;
    }

    .calendar__day__header,
    .calendar__day__cell {
        padding: .75rem 0 1.5rem;
    }

    .calendar__banner--month {
        border: 2px solid #e66053;
        border-bottom: none;
        text-align: center;
        padding: .75rem;
    }
    .calendar__banner--month h1 {
        background-color: #4E4F4A;
        color: #F6E9DC;
        display: inline-block;
        font-size: 3rem;
        font-weight: 700;
        letter-spacing: 0.1em;
        padding: .5rem 2rem;
        text-transform: uppercase;
    }

    .calendar__day__header {
        font-size: 1rem;
        letter-spacing: 0.1em;
        text-transform: uppercase;
    }

    .calendar__day__cell {
        font-size: 4rem;
        position: relative;
    }

    /*
    tr:nth-child(odd) > .calendar__day__cell:nth-child(odd) {
        color: #e66053;
    }

    tr:nth-child(even) > .calendar__day__cell:nth-child(even) {
        color: #e66053;
    }
     */
    td.holiday {
        color: #e66053;
    }

    .calendar__day__cell[data-moon-phase] {
        background-color: #e66053;
        color: #4E4F4A;
    }
    .calendar__day__cell[data-moon-phase]:after {
        content: attr(data-moon-phase);
        color: #F6E9DC;
        display: block;
        font-weight: 400;
        font-size: .75rem;
        position: absolute;
        bottom: 0;
        width: 100%;
        height: 1rem;
        text-transform: uppercase;
    }

    .calendar__day__cell[data-bank-holiday] {
        background-color: #4E4F4A;
        border-color: #4E4F4A;
        border-bottom: none;
        color: #F6E9DC;
    }
    .calendar__day__cell[data-bank-holiday]:after {
        content: attr(data-bank-holiday);
        color: #F6E9DC;
        display: block;
        font-size: .75rem;
        font-weight: 400;
        position: absolute;
        bottom: 0;
        width: 100%;
        height: 1rem;
        text-transform: uppercase;
    }
</style>