<?php

use app\modules\test\models\TestResultOpinion;
use kartik\rating\StarRating;
use yii\bootstrap4\Html;

/** @var yii\web\View $this */
/** @var \app\modules\test\models\TestResult $model */
/** @var \app\modules\test\models\Test $modelTest */
/** @var TestResultOpinion $modelRating */
/** @var int $countWrong */
/** @var int $countRight */

$this->title = 'Результаты теста "' . $modelTest->name . '"';
$this->params['breadcrumbs'][] = ['label'=>'Результаты тестов', 'url'=>['/test/result/index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="test-default-index">

    <div class="card shadow mb-4">
        <div class="card-header font-weight-bolder">Статистика</div>
        <div class="card-body">

            <table class="table col-6">
                <tr>
                    <th>Количество вопросов</th>
                    <td class="font-weight-bolder">
                        <h2><?= count($model->testResultQuestions) ?></h2>
                    </td>
                </tr>
                <tr>
                    <th>Правильных ответов</th>
                    <td>
                        <h2 class="text-success font-weight-bolder">
                            <?= $countRight ?>
                        </h2>
                    </td>
                </tr>
                <tr>
                    <th>Неправильных ответов</th>
                    <td>
                        <h2 class="text-danger font-weight-bolder">
                            <?= $countWrong ?>
                        </h2>
                    </td>
                </tr>
            </table>

            <?php // рейтинг только для тех, кто его сдевал
            if ($model->username === Yii::$app->user->identity->username): ?>
            <div class="alert alert-info">
                <?php if ($modelRating === null): ?>
                    <strong>
                        Пожалуйста, оцените качество обучения по данной теме
                    </strong>                                
                    <?= Html::beginForm(['/test/public/rating', 'id'=>$modelTest->id], 'post', ['id' => 'form-rating']) ?>
                    <?= StarRating::widget([
                        'id' => 'star-rating-' . $model->id,
                        'name' => 'rating',
                        'pluginOptions' => [
                            'step' => 1,
                            'required',
                        ],
                    ]) ?>
                    <label>Ваши предложения и замечания</label>
                    <?= Html::textarea('note', '', ['rows' => 5, 'class' => 'form-control', 'style' => 'width: 500px;']) ?>
                    <br />
                    <?= Html::submitButton('Оценить', ['class' => 'btn btn-primary']) ?>
                <?php else: ?>
                    <strong>Ваша оценка</strong>
                    <?= StarRating::widget([
                        'id' => 'star-rating-' . $model->id,
                        'name' => 'rating',
                        'pluginOptions' => [
                            'step' => 1,
                            'readonly' => true,                                               
                        ],
                        'value' => $modelRating->rating,
                    ]) ?>
                <?php endif; ?>
                <div id="container-rating-error" class="mt-2 alert alert-danger" style="display: none;"></div>
                <?= Html::endForm() ?>
            </div>
            <?php endif; ?>

        </div>                
    </div>    


    <div class="card shadow">
        <div class="card-header font-weight-bolder">            
            Результаты теста
        </div>
        <div class="card-body text-dark">
            <?php foreach ($model->testResultQuestions as $question): ?>
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
                        <?php if ($answers): ?>
                            <?php if (count($answers) > 1): ?>
                                <?php foreach ($answers as $answer): ?>
                                    <div class="custom-control">
                                        <input type="checkbox" checked disabled="disabled" id="answ-<?= $answer->id ?>" />
                                        <label for="answ-<?= $answer->id ?>"><?= $answer->testAnswer->name ?></label>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <?php $answer = array_shift($answers); ?>
                                <div class="custom-control">
                                    <input type="radio" checked disabled="disabled" id="answ-<?= $answer->id ?>" />
                                    <label for="answ-<?= $answer->id ?>"><?= $answer->testAnswer->name ?></label>
                                </div>
                            <?php endif; ?>
                        <?php else: ?>
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