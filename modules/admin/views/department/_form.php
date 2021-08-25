<?php

use app\models\Tree;
use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Department\Department $model */
/** @var yii\bootstrap4\ActiveForm $form */
?>

<div class="department-form">

    <?php $form = ActiveForm::begin(['id' => 'form-department']); ?>

    <?= $form->field($model, 'id_tree')->dropDownList(Tree::getTreeDropDownList()) ?>

    <?= $form->field($model, 'department_index')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'department_name')->textInput(['maxlength' => true]) ?>

    <?php if (Yii::$app->user->can('admin')): ?>
        <div class="card">
            <div class="card-header">
                Доступ
            </div>

            <div class="card-body">
                <div id="content-permission">
                    <div class="col-6">
                        <?= $form->field($model, 'permissionGroup')->dropDownList($model->getPermissionGroups(), ['multiple'=>true, 'size'=>10]) ?>
                        <div class="btn-group">
                            <?= Html::a('Добавить', ['/admin/group/list'], ['class'=>'btn btn-success', 'id'=>'btn-add-group']) ?>
                            <?= Html::button('Удалить', ['class' => 'btn btn-danger', 'id'=>'btn-remove-group']) ?>
                        </div>
                    </div>
                    <div class="col-6">
                        <?= $form->field($model, 'permissionUser')->dropDownList($model->getPermissionUsers(), ['multiple'=>true, 'size'=>10]) ?>
                        <div class="btn-group">
                            <?= Html::a('Добавить', ['/admin/user/list'], ['class'=>'btn btn-success', 'id'=>'btn-add-user']) ?>
                            <?= Html::button('Удалить', ['class' => 'btn btn-danger', 'id'=>'btn-remove-user']) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <div class="btn-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
        <?= Html::a('Отмена', ['/admin/department/index'], ['class' => 'btn btn-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<?php


// выбор группы
$this->registerJs("
$('#btn-add-group').on('click', function() {
    listGroup = $('#" . Html::getInputId($model, 'permissionGroup') . "');
    listGroup.children('option').prop('selected', true);
    groups = listGroup.val();    
    modalViewer.showModalManual($(this).attr('href'), false, 'get', 'groups=' + groups);
    return false;
});

$(modalViewer).on('onPortalSelectGroup', function(event, group) {
    $('#" . Html::getInputId($model, 'permissionGroup') . "').append('<option value=\"' + group.id + '\">' + group.name + '</option>');
    modalViewer.closeModal();
});

");

// удаление группы
$this->registerJs("
$('#btn-remove-group').on('click', function() {
    $('#" . Html::getInputId($model, 'permissionGroup') . "').children('option:selected').remove();
});
");

// выбор пользователя
$this->registerJs("
$('#btn-add-user').on('click', function() {
    listUser = $('#" . Html::getInputId($model, 'permissionUser') . "');
    listUser.children('option').prop('selected', true);
    users = listUser.val();    
    modalViewer.showModalManual($(this).attr('href'), false, 'get', 'users=' + users);
    return false;
});

$(modalViewer).on('onPortalSelectUser', function(event, user) {
    $('#" . Html::getInputId($model, 'permissionUser') . "').append('<option value=\"' + user.id + '\">' + user.name + '</option>');
    modalViewer.closeModal();
});

");

// удаление пользователя
$this->registerJs("
$('#btn-remove-user').on('click', function() {
    $('#" . Html::getInputId($model, 'permissionUser') . "').children('option:selected').remove();
});
");

// перед сохранением выделить все группы и всех пользователей
$this->registerJs("
$('#form-department').on('submit', function() {
    $('#" . Html::getInputId($model, 'permissionGroup') . "').children('option').prop('selected', true);
    $('#" . Html::getInputId($model, 'permissionUser') . "').children('option').prop('selected', true);
});
");

?>