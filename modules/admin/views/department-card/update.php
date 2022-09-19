<?php

use yii\bootstrap5\Html;

/** @var yii\web\View $this */
/** @var app\models\Department\DepartmentCard $model */

$this->title = 'Изменение карточки сотрудника: ' . $model->user_fio;
$this->params['breadcrumbs'][] = ['label' => 'Отделы', 'url' => ['/admin/department/index']];
$this->params['breadcrumbs'][] = ['label' => $model->department->department_name, 
    'url' => ['/admin/department/view', 'id'=>$model->id_department]];
$this->params['breadcrumbs'][] = ['label' => 'Структура отдела "' . $model->department->department_name . '"', 
    'url' => ['index', 'idDepartment'=>$model->id_department]];
$this->params['breadcrumbs'][] = 'Изменение';
?>
<div class="department-card-update">

    <h1 class="display-5 border-bottom">
        <?= Html::encode($this->title) ?>
    </h1>

    <?= $this->render('_form', [
        'model' => $model,
        'modelDepartment' => $model->department,
    ]) ?>

</div>
