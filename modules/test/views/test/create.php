<?php

use yii\bootstrap5\Html;

/** @var yii\web\View $this */
/** @var app\modules\test\models\Test $model */

$this->title = 'Создание теста';
$this->params['breadcrumbs'][] = ['label' => 'Тесты', 'url' => ['/test/test/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="test-create">
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
</div>
