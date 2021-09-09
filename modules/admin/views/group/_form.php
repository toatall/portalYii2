<?php

use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;
use app\assets\ModalViewerAsset;
use kartik\select2\Select2;
use yii\helpers\Url;

ModalViewerAsset::register($this);

/** @var yii\web\View $this */
/** @var app\models\Group $model */
/** @var yii\widgets\ActiveForm $form */

$idGroupListBox = Html::getInputId($model, 'groupUsers');
?>

<div class="group-form">

    <?php $form = ActiveForm::begin(['id' => 'form-group']); ?>

    <?= $form->errorSummary($model) ?>

    <?= ''/*$form->field($model, 'id_organization')->widget(Select2::class, [
        'data' => $model->dropDownListOrganizations(),
    ])*/ ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textInput() ?>

    <?= $form->field($model, 'is_global')->checkbox() ?>

    <?= $form->field($model, 'sort')->textInput() ?>

    <!--div class="card">
        <div class="card-header">Участники группы</div>
        <div class="card-body">
            <?= $form->field($model, 'groupUsers')->dropDownList($model->getListGroupUsers(), ['multiple'=>true, 'size'=>10])->label(false) ?>
            <div class="btn-group">
                <?= Html::a('Добавить', ['/admin/user/list'], ['class'=>'btn btn-primary mv-link', 'id'=>'btn-add', 'data-url'=>Url::to(['/admin/user/list'])]) ?>
                <?= Html::button('Удалить', ['class'=>'btn btn-danger', 'id'=>'btn-delete']) ?>
                <?php
                    $this->registerJs("
                        $('#btn-delete').on('click', function() {
                            $('#" . Html::getInputId($model, 'groupUsers') . " option:selected').remove();
                        });
                    ");
                ?>
             </div>
        </div>
    </div-->

    <div class="btn-group mt-2">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
        <?= Html::a('Назад', ['/admin/group/index'], ['class' => 'btn btn-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<?php
$this->registerJs(<<<JS

    $(modalViewer).on('onPortalSelectUser', function(event, data) {
        $('#$idGroupListBox').append('<option value="' + data.id + '">' + data.name + '</option>');
        modalViewer.closeModal();
    });
    
    $('#form-group').on('submit', function() {
        $('#$idGroupListBox option').prop('selected', true);
        return true;
    });
    
JS
);
?>
