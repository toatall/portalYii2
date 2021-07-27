<?php
/* @var $this \yii\web\View */
/* @var $modelDepartment \app\models\department\Department */
/* @var $model \app\models\OP */

use yii\helpers\Html;

$this->title = 'Добавить запись';
$this->params['breadcrumbs'][] = ['label' => $modelDepartment->department_name, 'url' => ['/department/view', 'id'=>$modelDepartment->id]];
$this->params['breadcrumbs'][] = ['label' => 'Отраслевые проекты', 'url' => ['/department/op']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="department-op-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
