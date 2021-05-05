<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Department\DepartmentCard */
/* @var $modelDepartment \app\models\department\Department */

$this->title = 'Создание карточки сотрудника';
$this->params['breadcrumbs'][] = ['label' => 'Отделы', 'url' => ['/admin/department/index']];
$this->params['breadcrumbs'][] = ['label' => $modelDepartment->department_name, 'url' => ['/admin/department/view', 'id'=>$modelDepartment->id]];
$this->params['breadcrumbs'][] = ['label' => 'Структура отдела "' . $modelDepartment->department_name . '"', 'url' => ['index', 'idDepartment'=>$modelDepartment->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="department-card-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
