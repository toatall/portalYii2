<?php

/** @var yii\web\View $this */
/** @var app\models\ExecuteTasks[] $models */

use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;
use yii\widgets\Pjax;

?>

<?php Pjax::begin(['timeout' => false, 'enablePushState' => false]) ?>

    <?php $form = ActiveForm::begin([
        'id' => 'form-tasks',
        'options' => [
            'data-pjax' => true,
        ],
    ]); ?>

    <?php foreach (Yii::$app->session->getAllFlashes() as $key => $message): ?>
        <div class="alert alert-<?= $key ?> mt-3"><?= $message ?></div>
    <?php endforeach; ?>

    <?= $form->errorSummary($models) ?>

    <table class="table table-bordered mt-2">
        <tr>
            <th>Код налогового органа</th>
            <th>Количество задач</th>
            <th>Количество завершенных задач</th>
            <th class="w-25">Достижение</th>
        </tr>    
        

        <?php foreach($models as $index => $model): ?>
            <?php
                $width = 0;
                if (is_numeric($model->count_tasks) && is_numeric($model->finish_tasks)
                    && $model->count_tasks > 0) {
                    $width = $model->finish_tasks / $model->count_tasks * 100;
                }
            ?>
            <tr data-id="<?= $index ?>">
                <td><?= $model->orgModel->fullName ?></td>
                <td>
                    <?= $form->field($model, "[$index]count_tasks")->label(false) ?>
                </td>
                <td>
                    <?= $form->field($model, "[$index]finish_tasks")->label(false) ?>
                </td>
                <td>
                    <div class="progress">
                        <div class="progress-bar" role="progressbar" id="progress_<?= $index ?>" style="width: <?= $width ?>%;"></div>                        
                    </div>
                    <p class="text-center">
                        <code id="progress_text_<?=$index ?>"><?= round($width, 2) ?>%</code>
                    </p>
                </td>
            </tr>        
        <?php endforeach; ?>
    </table>

    <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>

<?php ActiveForm::end(); ?>

<?php
$this->registerJs(<<<JS
    $('#form-tasks input').off('change');
    $('#form-tasks input').on('change', function() {
        var id = $(this).parents('tr').data('id');
        var fieldCount = parseInt($('input[name="ExecuteTasks[' + id + '][count_tasks]"]').val());
        if (!Number.isInteger(fieldCount)) {
            fieldCount = 0;
        }
        var fieldTasks = parseInt($('input[name="ExecuteTasks[' + id + '][finish_tasks]"]').val());
        if (!Number.isInteger(fieldTasks)) {
            fieldTasks = 0;
        }
        var width = 0;
        if (fieldCount > 0) {
            width = fieldTasks / fieldCount * 100;
        }       
        $('#progress_' + id).css('width', width + '%');
        $('#progress_text_' + id).html(width.toFixed(2) + '%');
    });
JS); ?>

<?php Pjax::end() ?>