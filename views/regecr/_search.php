<?php

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;
use kartik\widgets\DatePicker;

/** @var yii\web\View $this */
/** @var app\models\regecr\RegEcrSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="news-search mt-2">

    <div class="card">
        <div class="card-header">Поиск</div>
        <div class="card-body">

            <?php $form = ActiveForm::begin([
                'action' => ['detail'],
                'method' => 'get',
                'options' => ['data-pjax' => true, 'autocomplete' => 'off'],
            ]); ?>

            <?= $form->field($model, 'code_org')->dropDownList(\app\models\Organization::getDropDownList(true, true)) ?>
            <div class="row">
                <div class="col-6">
                    <?= $form->field($model, 'searchDate1')->widget(DatePicker::class, [
                        'pluginOptions' => [
                            'todayHighlight' => true,
                            'todayBtn' => true,
                            'autoclose' => true,
                        ],
                    ]) ?>
                </div>
                <div class="col-6">
                    <?= $form->field($model, 'searchDate2')->widget(DatePicker::class, [
                        'pluginOptions' => [
                            'todayHighlight' => true,
                            'todayBtn' => true,
                            'autoclose' => true,
                        ],
                    ]) ?>
                </div>
            </div>

            <div class="form-group">
                <?= Html::submitButton('Поиск', ['class' => 'btn btn-primary']) ?>
            </div>

            <?php ActiveForm::end(); ?>

        </div>
    </div>

</div>
