<?php

use app\modules\test\models\TestResultOpinion;
use kartik\rating\StarRating;
use yii\bootstrap5\Html;

/** @var yii\web\View $this */
/** @var \app\modules\test\models\TestResult $model */
/** @var \app\modules\test\models\Test $modelTest */
/** @var TestResultOpinion $modelRating */
/** @var int $countWrong */
/** @var int $countRight */
/** @var bool $statistsic */

$this->title = 'Результаты теста "' . $modelTest->name . '"';
$this->params['breadcrumbs'][] = ['label'=>'Результаты тестов', 'url'=>['/test/result/index']];
$this->params['breadcrumbs'][] = $this->title;
$statistsic = isset($statistsic) ? true : false;
?>

<div class="test-default-index">

    <?php if ($modelTest->user_input && $model->is_checked): ?>
    <div class="card shadow mb-4">
        <div class="card-header font-weight-bolder">Статистика</div>
        <div class="card-body">

            <?php if ($modelTest->finish_text): ?>
                <div class="alert alert-info">
                    <?= $modelTest->finish_text ?>
                </div>
            <?php endif; ?>

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
    <?php endif; ?>


    <?php 
    if ($modelTest->user_input) {

        if ($statistsic && $model->test->canStatisticTest()) {
            echo $this->render('_viewResultChecked', [
                'model' => $model,
            ]);
        }
        else {
            echo $this->render('_viewResultInputUser', [
                'model' => $model,
            ]);
        }
    }
    else {
        echo $this->render('_viewResultGeneral', [
            'model' => $model,
        ]);
    }
    ?>

</div>