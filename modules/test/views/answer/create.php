<?php

use yii\bootstrap5\Html;

/** @var yii\web\View $this */
/** @var app\modules\test\models\TestAnswer $model */
/** @var \app\modules\test\models\TestQuestion $modelQuestion */

$this->title = 'Создание ответа';
$this->params['breadcrumbs'][] = ['label' => 'Тесты', 'url' => ['/test']];
$this->params['breadcrumbs'][] = ['label' => $modelQuestion->test->name, 'url' => ['/test/test/view', 'id'=>$modelQuestion->id_test]];
$this->params['breadcrumbs'][] = ['label' => 'Вопросы', 'url' => ['/test/question/index', 'idTest' => $modelQuestion->id_test]];
$this->params['breadcrumbs'][] = ['label' => $modelQuestion->name, 'url' => ['/test/question/view', 'id'=>$modelQuestion->id]];
$this->params['breadcrumbs'][] = ['label' => 'Ответы', 'url' => ['/test/answer/index', 'idQuestion'=>$modelQuestion->id]];
$this->params['breadcrumbs'][] = $this->title;
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
