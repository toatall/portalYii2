<?php

use app\models\Tree;
use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Department\Department $model */
/** @var yii\bootstrap4\ActiveForm $form */
?>

<div class="department-form">

    <div class="card card-body">

        <?php $form = ActiveForm::begin(['id' => 'form-department']); ?>

        <div class="row">
            <div class="col-12">
                <?= $form->field($model, 'id_tree')->dropDownList(Tree::getTreeDropDownList()) ?>
            </div>
            
            <div class="col-3">
                <?= $form->field($model, 'department_index')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-9">
                <?= $form->field($model, 'department_name')->textInput(['maxlength' => true]) ?>
            </div>

            <?php if (Yii::$app->user->can('admin')): ?>            
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            Доступ
                        </div>

                        <div class="card-body">
                            <div id="content-permission">
                                <div class="row">
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
                    </div>
                </div>
            <?php endif; ?>

        </div>

        <hr />

        <div class="btn-group">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
            <?= Html::a('Отмена', ['/admin/department/index'], ['class' => 'btn btn-secondary']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

</div>
<?php

$idPermissionGroup = Html::getInputId($model, 'permissionGroup');
$idPermissionUser = Html::getInputId($model, 'permissionUser');

$this->registerJs(<<<JS
    
    // выбор группы
    $('#btn-add-group').on('click', function() {
        listGroup = $('#$idPermissionGroup');
        listGroup.children('option').prop('selected', true);
        groups = listGroup.val();    
        modalViewer.showModalManual($(this).attr('href'), false, 'get', 'groups=' + groups);
        return false;
    });

    $(modalViewer).on('onPortalSelectGroup', function(event, group) {
        $('#$idPermissionGroup').append('<option value=\"' + group.id + '\">' + group.name + '</option>');
        modalViewer.closeModal();
    });


    // удаление группы
    $('#btn-remove-group').on('click', function() {
        $('#$idPermissionGroup').children('option:selected').remove();
    });

    // выбор пользователя   
    $('#btn-add-user').on('click', function() {
        listUser = $('#$idPermissionUser');
        listUser.children('option').prop('selected', true);
        users = listUser.val();    
        modalViewer.showModalManual($(this).attr('href'), false, 'get', 'users=' + users);
        return false;
    });

    $(modalViewer).on('onPortalSelectUser', function(event, user) {
        $('#$idPermissionUser').append('<option value=\"' + user.id + '\">' + user.name + '</option>');
        modalViewer.closeModal();
    });


    // удаление пользователя
    $('#btn-remove-user').on('click', function() {
        $('#$idPermissionUser').children('option:selected').remove();
    });


    // перед сохранением выделить все группы и всех пользователей
    $('#form-department').on('submit', function() {
        $('#$idPermissionGroup').children('option').prop('selected', true);
        $('#$idPermissionUser').children('option').prop('selected', true);
    });

JS);

?>