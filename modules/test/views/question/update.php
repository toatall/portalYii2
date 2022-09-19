<?php

use yii\bootstrap5\Html;

/** @var yii\web\View $this */
/** @var app\modules\test\models\TestQuestion $model */

$this->title = 'Изменение вопроса: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Тесты', 'url' => ['/test']];
$this->params['breadcrumbs'][] = ['label' => $model->test->name, 'url' => ['/test/index', 'id' => $model->id_test]];
$this->params['breadcrumbs'][] = ['label' => 'Вопросы', 'url' => ['index', 'idTest' => $model->id_test]];
$this->params['breadcrumbs'][] = 'Изменение';
?>
<div class="test-question-update">
    <div class="card shadow mb-4">
        <div class="card-header">
            <h1><?= Html::encode($this->title) ?></h1>
        </div>
        <div class="card-body">    
            <?= $this->render('_form', [
                'model' => $model,
            ]) ?>
        </div>
    </div>
</div>
