<?php

use yii\bootstrap4\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\test\models\TestAnswer */

$this->title = 'Изменение ответа: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Тесты', 'url' => ['/test/test/index']];
$this->params['breadcrumbs'][] = ['label' => $model->testQuestion->test->name, 'url' => ['/test/test/view', 'id'=>$model->testQuestion->id_test]];
$this->params['breadcrumbs'][] = ['label' => 'Вопросы', 'url' => ['/test/question/index', 'idTest' => $model->testQuestion->id_test]];
$this->params['breadcrumbs'][] = ['label' => $model->testQuestion->name, 'url' => ['/test/question/view', 'id'=>$model->testQuestion->id]];
$this->params['breadcrumbs'][] = ['label' => 'Ответы', 'url' => ['/test/answer/index', 'idQuestion'=>$model->id_test_question]];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="test-answer-update">
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
