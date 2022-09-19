<?php
/** @var yii\web\View $this */
/** @var app\models\lifehack\LifehackTags $model */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

?>
<?php $form = ActiveForm::begin([
    'options' => [
        'data-pjax' => true,
    ],
]); ?>

<?= $form->errorSummary($model) ?>

<?= $form->field($model, 'tag')->textInput(['maxlength' => true]) ?>

<div class="btn-group pt-2">
    <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    <?= Html::a('Отмена', ['lifehack/index-tags'], ['class' => 'btn btn-secondary', 'pjax' => 1]) ?>
</div>

<?php ActiveForm::end(); ?>




