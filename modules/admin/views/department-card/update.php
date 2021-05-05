<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Department\DepartmentCard */

$this->title = 'Изменение карточки сотрудника: ' . $model->user_fio;
$this->params['breadcrumbs'][] = ['label' => 'Отделы', 'url' => ['/admin/department/index']];
$this->params['breadcrumbs'][] = ['label' => $model->department->department_name, 'url' => ['/admin/department/view', 'id'=>$model->id_department]];
$this->params['breadcrumbs'][] = ['label' => 'Структура отдела "' . $model->department->department_name . '"', 'url' => ['index', 'idDepartment'=>$model->id_department]];
$this->params['breadcrumbs'][] = 'Изменение';
?>
<div class="department-card-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
