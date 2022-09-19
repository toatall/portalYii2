<?php

/** @var yii\web\View $this */

use app\modules\test\models\TestQuestion;
use yii\bootstrap5\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;

/** @var \app\modules\test\models\TestResult $model */

?>


<div class="card shadow">
    <div class="card-header font-weight-bolder">
        Результаты теста
    </div>
    <div class="card-body text-dark">
        <?php Pjax::begin(['id' => 'pjax-view-result-checked-' . $model->id, 'enablePushState' => false, 'timeout' => false]) ?>
        <?= Html::beginForm(Url::to(['/test/result/save-checked', 'id' => $model->id]), 'post', [
            'data-pjax' => true,
            'id' => 'form-save-result-' . $model->id,
        ]) ?>
        <table class="table">
            <tr>
                <th>Вопрос</th>
                <th>Ответ(ы) пользователя</th>
                <th>&nbsp;</th>
            </tr>            
        <?php 
        foreach ($model->testResultQuestions as $question): 
            
            if ($question->is_right == true) {
                $alertClass = 'success';
            }
            elseif ($question->is_right == false) {
                $alertClass = 'danger';
            }
            
            if ($question->is_right === null) {
                $alertClass = 'warning';
            }

        ?>
            <tr class="table-<?= $alertClass ?>">
                <td>
                    <?= $question->testQuestion->name ?>
                </td>
                <td>
                    <?= $question->unpackUserInput() ?>
                </td>
                <td>
                    <?php if ($question->testQuestion->type_question == TestQuestion::TYPE_QUSTION_INPUT): ?>
                        <?= Html::checkbox("result_{$question->id}", $question->is_right, ['id'=>"Result_{$question->id}"]) ?>
                        <?= Html::label('Правильно', "Result_{$question->id}") ?>
                    <?php endif; ?>
                </td>
            </tr>
            
        <?php endforeach; ?>
        </table>
        <hr />
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
        <?= Html::endForm() ?>
        <?php Pjax::end() ?>                      
    </div>
</div>
