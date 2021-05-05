<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\assets\ModalViewerAsset;

ModalViewerAsset::register($this);

/* @var $this yii\web\View */
/* @var $model app\models\Group */
/* @var $form yii\widgets\ActiveForm */

$idGroupListBox = Html::getInputId($model, 'groupUsers');
?>

<div class="group-form">

    <?php $form = ActiveForm::begin(['id' => 'form-group']); ?>

    <?= $form->field($model, 'id_organization')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textInput() ?>

    <?= $form->field($model, 'sort')->textInput() ?>

    <div class="panel panel-default">
        <div class="panel-heading">Участники группы</div>
        <div class="panel-body">
            <?= $form->field($model, 'groupUsers')->dropDownList($model->getListGroupUsers(), ['multiple'=>true, 'size'=>10])->label(false) ?>
            <div class="btn-group">
                <?= Html::a('Добавить', ['/admin/user/list'], ['class'=>'btn btn-primary mv-link', 'id'=>'btn-add', 'data-url'=>\yii\helpers\Url::to(['/admin/user/list'])]) ?>
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
    </div>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
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
