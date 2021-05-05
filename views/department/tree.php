<?php
/* @var $this yii\web\View */
/* @var $departmentTree string */
/* @var $model \app\models\department\Department */

$this->params['breadcrumbs'][] = ['label' => $model->department_name, 'url' => ['view', 'id'=>$model->id]];
?>

<div class="news-index row">

    <h2 class="text-center" style="font-weight: bolder;"><?= $this->title ?></h2>

    <?= $departmentTree ?>
</div>
