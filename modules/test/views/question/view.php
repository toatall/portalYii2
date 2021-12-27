<?php

use yii\bootstrap4\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\modules\test\models\TestQuestion $model */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Тесты', 'url' => ['/test/test/index']];
$this->params['breadcrumbs'][] = ['label' => $model->test->name, 'url' => ['/test/test/view', 'id'=>$model->id_test]];
$this->params['breadcrumbs'][] = ['label' => 'Вопросы', 'url' => ['index', 'idTest' => $model->id_test]];

$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="test-question-view">
    <div class="card shadow mb-4">
        <div class="card-header">
            <h1><?= Html::encode($this->title) ?></h1>
        </div>
        <div class="card-body">
            <div class="btn-group mb-3">
                <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => 'Вы уверены, что хотите удалить?',
                        'method' => 'post',
                    ],
                ]) ?>
                <?php if (/*!$model->test->user_input*/true): ?>
                <?= Html::a('Управление ответами', ['/test/answer/index', 'idQuestion'=>$model->id], ['class' => 'btn btn-secondary']) ?>
                <?php endif; ?>
            </div>

            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'id',
                    'id_test',
                    'name',
                    'type_question',
                    'attach_file',
                    'weight',
                    'date_create:datetime',
                    'author',
                ],
            ]) ?>
        </div>
    </div>
</div>
