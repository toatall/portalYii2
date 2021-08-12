<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\test\models\Test */

$this->title = 'Редактирование теста: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Тесты', 'url' => ['/test/test/index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Редактирование';
?>
<div class="test-update">
    <div class="card shadow mb-4">
        <div class="card-header">
            <h1><?= Html::encode($this->title) ?></h1>
        </div>
        <div class="card-body">
            <?= $this->render('_form', [
                'model' => $model,
            ]) ?>
        </div>
        <div class="card-footer">
            <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Вы уверены, что хотите удалить?',
                    'method' => 'post',
                ],
            ]) ?>
        </div>
    </div>
</div>
