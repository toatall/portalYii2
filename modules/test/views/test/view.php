<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\test\models\Test */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Тесты', 'url' => ['/test/test/index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="test-view">
    <div class="card shadow mb-4">
        <div class="card-header">
            <h1><?= Html::encode($this->title) ?></h1>
        </div>
        <div class="card-body">
            <div class="btn-group">
                <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => 'Вы уверены, что хотите удалить?',
                        'method' => 'post',
                    ],
                ]) ?>
                <?= Html::a('Управление вопросами', ['/test/question/index', 'idTest'=>$model->id], ['class' => 'btn btn-secondary']) ?>
                </div>
            <hr />
            
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'id',
                    'name',
                    'date_start',
                    'date_end',
                    'count_attempt',
                    'count_questions',
                    'description',
                    'time_limit:time',
                    'date_create:datetime',
                    'author',
                ],
            ]) ?>
        </div>
    </div>
</div>

