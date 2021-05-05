<?php
/* @var $this \yii\web\View */
/* @var $modelDepartment \app\models\department\Department */
/* @var $model \app\models\OP */

use yii\helpers\Html;

$this->title = 'Изменить запись ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => $modelDepartment->department_name, 'url' => ['/departmnet/view', 'id'=>$modelDepartment->id]];
$this->params['breadcrumbs'][] = ['label' => 'Отраслевые проекты', 'url' => ['/departmnet/op']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="department-op-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
