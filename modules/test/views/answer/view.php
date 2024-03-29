<?php

use yii\bootstrap5\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\modules\test\models\TestAnswer $model */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Тесты', 'url' => ['/test/test/index']];
$this->params['breadcrumbs'][] = ['label' => $model->testQuestion->test->name, 'url' => ['/test/test/view', 'id'=>$model->testQuestion->id_test]];
$this->params['breadcrumbs'][] = ['label' => 'Вопросы', 'url' => ['/test/question/index', 'idTest' => $model->testQuestion->id_test]];
$this->params['breadcrumbs'][] = ['label' => $model->testQuestion->name, 'url' => ['/test/question/view', 'id'=>$model->testQuestion->id]];
$this->params['breadcrumbs'][] = ['label' => 'Ответы', 'url' => ['/test/answer/index', 'idQuestion'=>$model->id_test_question]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="test-answer-view">
    <div class="test-question-update">
        <div class="card shadow mb-4">
            <div class="card-header">
                <h1><?= Html::encode($this->title) ?></h1>
            </div>
            <div class="card-body">
                <div class="btn-group mb-4">
                    <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                    <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
                        'class' => 'btn btn-danger',
                        'data' => [
                            'confirm' => 'Вы уверены, что хотите удалить?',
                            'method' => 'post',
                        ],
                    ]) ?>
                    <?= Html::a('Отмена', ['/test/answer/index', 'idQuestion'=>$model->id_test_question], ['class'=>'btn btn-secondary']) ?>
                </div>

                <?= DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        'id',
                        'id_test_question',
                        'name',
                        'attach_file',
                        'weight',
                        'date_create:datetime',
                    ],
                ]) ?>
            </div>
        </div>
    </div>
</div>
