<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\assets\ModalViewerAsset;
use app\models\Module;

ModalViewerAsset::register($this);

/* @var $this yii\web\View */
/* @var $model app\models\Tree */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tree-form">

    <?php $form = ActiveForm::begin(['id' => 'form-tree']); ?>

    <?php
        $arrayParent = Yii::$app->user->can('admin') ? ['0' => 'Родитель'] : [];
    ?>
    <?= $form->field($model, 'id_parent')->dropDownList($arrayParent + \app\models\Tree::getTreeDropDownList()) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sort')->textInput() ?>

    <?php if (Yii::$app->user->can('admin')): ?>
        <?= $form->field($model,'allOrganization')->checkbox() ?>
    <?php endif; ?>

    <?= $form->field($model, 'use_material')->checkbox() ?>

    <div id="content-material" class="panel panel-default">
        <div class="panel-body">
            <?php if (Yii::$app->user->can('admin')): ?>
            <?= $form->field($model, 'module')->dropDownList(['' => ''] + ArrayHelper::map(Module::find()->all(), 'name', 'description')) ?>
            <?php endif; ?>
            <?= ''//$form->field($model, 'use_tape')->checkbox() ?>
        </div>
    </div>

    <?php if (Yii::$app->user->can('admin')): ?>
    <div class="panel panel-default">
        <div class="panel-heading">
            Доступ
        </div>

        <div class="panel-body">
            <div class="col-sm-12">
                <?= $form->field($model, 'useParentRight')->checkbox() ?>
            </div>
            <div id="content-permission">
                <div class="col-sm-6">
                    <?= $form->field($model, 'permissionGroup')->dropDownList($model->getPermissionGroups(), ['multiple'=>true, 'size'=>10]) ?>
                    <div class="btn-group">
                        <?= Html::a('Добавить', ['/admin/group/list'], ['class'=>'btn btn-success', 'id'=>'btn-add-group']) ?>
                        <?= Html::button('Удалить', ['class' => 'btn btn-danger', 'id'=>'btn-remove-group']) ?>
                    </div>
                </div>
                <div class="col-sm-6">
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

    <div class="panel panel-default">
        <div class="panel-heading">
            <button id="btn-add-parameters" class="btn btn-primary">Дополнительные параметры</button>
        </div>
        <div id="content-add-parameters" class="panel-body" style="display: none;">
            <div class="panel-body">
                <?= $form->field($model, 'param1')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'alias')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'view_static')->textInput(['maxlength' => true]) ?>
            </div>
        </div>
    </div>

    <?= $form->field($model, 'disable_child')->checkbox() ?>

    <hr />

    <div class="btn-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
        <?= Html::a('Отмена', ['/admin/tree/index'], ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<?php
// показать/скрыть выбор модуля
$this->registerJs("
$('#" . Html::getInputId($model, 'use_material') . "').change(function() {
    if ($(this).is(':checked')) { 
        $('#content-material').show(); 
    } 
    else { 
        $('#content-material').hide(); 
    }  
});
$('#" . Html::getInputId($model, 'use_material') . "').change();
");

// показать/скрыть наследование прав
$this->registerJs("
$('#" . Html::getInputId($model, 'useParentRight') . "').change(function() {
    if ($(this).is(':checked')) { 
        $('#content-permission').hide(); 
    } 
    else { 
        $('#content-permission').show(); 
    }  
});
$('#" . Html::getInputId($model, 'useParentRight') . "').change();
");


// кнопка показа доролнительных параметров
$this->registerJs("
$('#btn-add-parameters').on('click', function() {
    $('#content-add-parameters').toggle();   
    return false; 
});
");

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
$('#form-tree').on('submit', function() {
    $('#" . Html::getInputId($model, 'permissionGroup') . "').children('option').prop('selected', true);
    $('#" . Html::getInputId($model, 'permissionUser') . "').children('option').prop('selected', true);
});
");
?>