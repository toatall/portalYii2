<?php

/** @var yii\web\View $this */
/** @var \app\modules\test\models\TestResult $model */

?>


<div class="card shadow">
    <div class="card-header font-weight-bolder">
        Результаты теста
    </div>
    <div class="card-body text-dark">
        <?php foreach ($model->testResultQuestions as $question) : ?>
            <div class="alert <?= $question->is_right ? 'alert-success' : 'alert-danger' ?>">
                <div class="row">
                    <div class="col-sm-11">
                        <strong>
                            <?= $question->testQuestion->name ?>
                        </strong>
                    </div>
                    <div class="col-sm-1 text-right">
                        <button class="btn btn-light btn-toggle" data-target="#answers-<?= $question->id ?>"><i class="fas fa-arrow-down"></i></button>
                    </div>
                </div>
                <div class="row" id="answers-<?= $question->id ?>" style="display: none;">
                    <div class="col">
                        <?php $answers = $question->testResultAnswers; ?>
                        <?php if ($answers) : ?>
                            <?php if (count($answers) > 1) : ?>
                                <?php foreach ($answers as $answer) : ?>
                                    <div class="custom-control">
                                        <input type="checkbox" checked disabled="disabled" id="answ-<?= $answer->id ?>" />
                                        <label for="answ-<?= $answer->id ?>"><?= $answer->testAnswer->name ?></label>
                                    </div>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <?php $answer = array_shift($answers); ?>
                                <div class="custom-control">
                                    <input type="radio" checked disabled="disabled" id="answ-<?= $answer->id ?>" />
                                    <label for="answ-<?= $answer->id ?>"><?= $answer->testAnswer->name ?></label>
                                </div>
                            <?php endif; ?>
                        <?php else : ?>
                            <div class="custom-control">
                                Ответ не выбран
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>


    </div>
</div>

<?php $this->registerJs(<<<JS
    // сворачивание и разворачивание ответов
    $('.btn-toggle').on('click', function() {
        $($(this).data('target')).toggle();
        return false;
    });

    // сохранение результатов оценки
    $('#form-rating').on('submit', function(e) {
        e.preventDefault();
        
        const form = $(this);
        $.ajax({
            url: form.attr('action'),
            data: form.serialize(),
            method: 'post',
        })
        .done(function(data) {
            form.parents('div.alert-info').html('<strong>Спасибо за вашу оценку!</strong>');
        })
        .fail(function(err) {                        
            $('#container-rating-error').html(err.responseText);
            $('#container-rating-error').show();
        });
        return false;
    });

JS); ?>