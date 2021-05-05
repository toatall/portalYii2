<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\DatePicker;
use app\models\Organization;
use kartik\widgets\TouchSpin;

/* @var $this yii\web\View */
/* @var $model app\models\RegEcr */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="reg-ecr-form">

    <?php $form = ActiveForm::begin([
        'options'=> ['autocomplete'=>'off'],
    ]); ?>

    <?= $form->field($model, 'code_org')->dropDownList(Organization::getDropDownList(true)) ?>

    <?= $form->field($model, 'date_reg')->widget(DatePicker::class, [
        'pluginOptions' => [
            'todayHighlight' => true,
            'todayBtn' => true,
            'autoclose' => true,
        ],
    ]) ?>

    <?= $form->field($model, 'count_create')->widget(TouchSpin::class, []) ?>

    <?= $form->field($model, 'count_vote')->widget(TouchSpin::class, []) ?>

    <?= $form->field($model, 'avg_eval_a_1_1')->widget(TouchSpin::class, []) ?>

    <?= $form->field($model, 'avg_eval_a_1_2')->widget(TouchSpin::class, []) ?>

    <?= $form->field($model, 'avg_eval_a_1_3')->widget(TouchSpin::class, []) ?>

    <div class="panel panel-info">
        <div class="panel-heading">Описание</div>
        <div class="panel-body">
            <strong>Дата</strong> - Дата регистрации<br />
            <strong>Кол-во вновь созданных ООО</strong> - Количество вновь созданных ООО<br />
            <strong>Кол-во опрошенных</strong> - Количество опрошенных представителей вновь созданных ООО (1 представитель в отношении 1 вновь созданного ООО)<br />
            <strong>Средняя оценка А 1.1</strong> - Средняя оценка респондентами по показателю А 1.1 "Среднее время регистрации, юридических лиц", дней (среднее арифметическое от общего количества опрошенных респондентов)<br />
            <strong>Средняя оценка А 1.2</strong> - Средняя оценка респондентами по показателю А 1.2 "Среднее количество процедур, необходимых для регистрации юридических лиц", штук (среднее арифметическое от общего количества опрошенных респондентов)<br />
            <strong>Средняя оценка А 1.3</strong> - Средняя оценка респондентами по показателю А 1.3 "Оценка деятельности органов власти по регистрации, юридических лиц", баллов (среднее арифметическое от общего количества опрошенных респондентов)<br />
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
