<?php

use yii\bootstrap5\Html;

/** @var yii\web\View $this */
/** @var app\models\department\DepartmentCard $model */
/** @var app\models\department\Department $modelDepartment */

$this->title = 'Создание карточки сотрудника';
$this->params['breadcrumbs'][] = ['label' => 'Отделы', 'url' => ['/admin/department/index']];
$this->params['breadcrumbs'][] = ['label' => $modelDepartment->department_name, 
    'url' => ['/admin/department/view', 'id'=>$modelDepartment->id]];
$this->params['breadcrumbs'][] = ['label' => 'Структура отдела "' . $modelDepartment->department_name . '"', 
    'url' => ['index', 'idDepartment'=>$modelDepartment->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="department-card-create">

    <h1 class="display-5 border-bottom">
        <?= Html::encode($this->title) ?>
    </h1>

    <?= $this->render('_form', [
        'model' => $model,
        'modelDepartment' => $modelDepartment,
    ]) ?>

</div>
