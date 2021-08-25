<?php
/** @var yii\web\View $this */
/** @var app\models\department\Department $modelDepartment */
/** @var app\models\OP $model */


$this->title = 'Добавить запись';
$this->params['breadcrumbs'][] = ['label' => 'Отделы', 'url' => ['/department/index']];
$this->params['breadcrumbs'][] = ['label' => $modelDepartment->department_name, 'url' => ['/department/view', 'id'=>$modelDepartment->id]];
$this->params['breadcrumbs'][] = ['label' => 'Отраслевые проекты', 'url' => ['/department/op']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="department-op-create">

    <div class="col border-bottom mb-2">
        <p class="display-4">
            <?= $this->title ?>
        </p>
    </div>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
