<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\DatePicker;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model \app\models\regecr\RegEcrSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="news-search">

    <div class="panel panel-default">
        <div class="panel-heading">Поиск</div>
        <div class="panel-body">

            <?php $form = ActiveForm::begin([
                'action' => ['detail'],
                'method' => 'get',
                'options' => ['data-pjax' => true, 'autocomplete' => 'off'],
            ]); ?>

            <?= $form->field($model, 'code_org')->dropDownList(\app\models\Organization::getDropDownList(true, true)) ?>
            <div class="row">
                <div class="col-sm-6">
                    <?= $form->field($model, 'searchDate1')->widget(DatePicker::class, [
                        'pluginOptions' => [
                            'todayHighlight' => true,
                            'todayBtn' => true,
                            'autoclose' => true,
                        ],
                    ]) ?>
                </div>
                <div class="col-sm-6">
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
