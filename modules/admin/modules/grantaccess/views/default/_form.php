<?php
/** @var \yii\web\View $this */
/** @var string $unique */
/** @var \app\modules\admin\modules\grantaccess\models\GrantAccessGroup $model */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
?>

<?php $form = ActiveForm::begin([
    'options' => [
        'class' => 'mv-form',
    ],
]); ?>

<?= $form->errorSummary($model) ?>

<?= $form->field($model, 'unique')->textInput(['maxlength' => true, 'disabled' => true]) ?>

<?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'note')->textarea(['rows' => 5]) ?>

<div class="d-flex">
    <div class="form-group btn-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success btn-sm']) ?>
        <?php if (!$model->isNewRecord): ?>
        <?= Html::button('Отмена', ['class' => 'btn btn-secondary btn-sm', 'id' => 'btn-form-cancel']) ?>        
    </div>
    <div class="ms-auto">
        <?= Html::a('Удалить группу', ['/admin/grantaccess/default/delete', 'id' => $model->id], 
            ['id' => 'btn-group-delete', 'class' => 'btn btn-danger btn-sm']) ?>
        <?php endif; ?>
    </div>
</div>


<?php ActiveForm::end(); ?>
<?= $this->registerJs(<<<JS

        $('#btn-group-delete').on('click', function(){
            if (!confirm('Вы уверены, что хотите удалить группу?')) {
                return false
            }
            const idModal = $(this).parents('div.modal').attr('id')
            $.post($(this).attr('href'))
            .done(function(){
                $('#' + idModal).modal('hide')
            })            
            return false
        })

JS); ?>