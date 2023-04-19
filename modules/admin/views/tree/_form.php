<?php

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Module;
use app\modules\admin\models\tree\Tree;
use app\modules\admin\models\tree\TreeBuild;
use kartik\select2\Select2;

/** @var yii\web\View $this */
/** @var app\modules\admin\models\tree\Tree $model */
/** @var yii\bootstrap4\ActiveForm $form */

$arrayParent = Yii::$app->user->can('admin') ? ['0' => 'Родитель'] : [];
?>

<div class="tree-form">

    <div class="card card-body">

        <?php $form = ActiveForm::begin(['id' => 'form-tree']); ?>

        <?= $form->field($model, 'id_parent')->widget(Select2::class, [
            'data' => $arrayParent + Tree::generateDropDownTree(TreeBuild::buildingTree()),
        ]) ?>

        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'is_url')->checkbox() ?>

        <div id="div-url">
            <?= $form->field($model, 'url') ?>
        </div>

        <div id="div-general">
            <?php if (Yii::$app->user->can('admin')): ?>
                <?= $form->field($model,'allOrganization')->checkbox() ?>
            <?php endif; ?>

            <?= $form->field($model, 'use_material')->checkbox() ?>

            <div id="content-material" class="card">
                <div class="card-body">
                    <?php if (Yii::$app->user->can('admin')): ?>
                    <?= $form->field($model, 'module')->dropDownList(['' => ''] + ArrayHelper::map(Module::find()->all(), 'name', 'description')) ?>
                    <?php endif; ?>            
                </div>
            </div>
        </div>

        <?php if (Yii::$app->user->can('admin')): ?>
        <div class="card mt-2">
            <div class="card-header">
                Права доступа
            </div>

            <div class="card-body">
                <div class="row col">
                    <?= $form->field($model, 'useParentRight')->checkbox() ?>
                </div>
                <div id="content-permission">
                    <div class="row">
                        <div class="col">
                            <?= $form->field($model, 'permissionGroup')->dropDownList($model->getPermissionGroups(), ['multiple'=>true, 'size'=>10]) ?>
                            <div class="btn-group">
                                <?= Html::a('Добавить', ['/admin/group/list'], ['class'=>'btn btn-success btn-sm', 'id'=>'btn-add-group']) ?>
                                <?= Html::button('Удалить', ['class' => 'btn btn-danger btn-sm', 'id'=>'btn-remove-group']) ?>
                            </div>
                        </div>
                        <div class="col">
                            <?= $form->field($model, 'permissionUser')->dropDownList($model->getPermissionUsers(), ['multiple'=>true, 'size'=>10]) ?>
                            <div class="btn-group">
                                <?= Html::a('Добавить', ['/admin/user/list'], ['class'=>'btn btn-success btn-sm', 'id'=>'btn-add-user']) ?>
                                <?= Html::button('Удалить', ['class' => 'btn btn-danger btn-sm', 'id'=>'btn-remove-user']) ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <?= $form->field($model, 'sort')->textInput(['maxlength' => true]) ?>        

        <?= $form->field($model, 'disable_child')->checkbox() ?>

        <hr />

        <div class="btn-group">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
            <?= Html::a('Отмена', ['/admin/tree/index'], ['class' => 'btn btn-secondary mv-hide']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

</div>
<?php
$idIsUrl = Html::getInputId($model, 'is_url');
$idUseMaterial = Html::getInputId($model, 'use_material');
$idUseParentRight = Html::getInputId($model, 'useParentRight');
$idPermissionGroup = Html::getInputId($model, 'permissionGroup');
$idPermissionUser = Html::getInputId($model, 'permissionUser');

$this->registerJs(<<<JS
    
    // показать/скрыть при выборе ссылки
    $('#$idIsUrl').change(function() {       
        $('#div-general').toggle(!$(this).is(':checked'));
        $('#div-url').toggle($(this).is(':checked'));
    });
    $('#$idIsUrl').change();

    // показать/скрыть выбор модуля
    $('#$idUseMaterial').change(function() {      
        $('#content-material').toggle($(this).is(':checked'));
    });
    $('#$idUseMaterial').change();


    // показать/скрыть наследование прав
    $('#$idUseParentRight').change(function() {
        $('#content-permission').toggle(!$(this).is(':checked'));
    });
    $('#$idUseParentRight').change();

    
    var modalViewerAdd = new ModalViewer({ enablePushState: false });
    
    // выбор группы    
    $('#btn-add-group').on('click', function() {
        listGroup = $('#$idPermissionGroup');
        listGroup.children('option').prop('selected', true);
        groups = listGroup.val();            
        modalViewerAdd.showModal($(this).attr('href'), 'get', 'groups=' + groups);
        return false;
    });
    $(modalViewerAdd).on('onPortalSelectGroup', function(event, group) {
        $('#$idPermissionGroup').append('<option value=\"' + group.id + '\">' + group.name + '</option>');
        modalViewerAdd.closeModal();
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
        modalViewerAdd.showModal($(this).attr('href'), 'get', 'users=' + users);
        return false;
    });
    $(modalViewerAdd).on('onPortalSelectUser', function(event, user) {
        $('#$idPermissionUser').append('<option value=\"' + user.id + '\">' + user.name + '</option>');
        modalViewerAdd.closeModal();
    });

    // удаление пользователя
    $('#btn-remove-user').on('click', function() {
        $('#$idPermissionUser').children('option:selected').remove();
    });


    // перед сохранением выделить все группы и всех пользователей
    $('#form-tree').on('submit', function() {
        $('#$idPermissionGroup').children('option').prop('selected', true);
        $('#$idPermissionUser').children('option').prop('selected', true);
    });

JS);

?>