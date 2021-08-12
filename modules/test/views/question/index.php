<?php

use kartik\grid\ActionColumn;
use yii\bootstrap4\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $modelTest \app\modules\test\models\Test */

$this->title = 'Вопросы';
$this->params['breadcrumbs'][] = ['label' => 'Тесты', 'url' => ['/test']];
$this->params['breadcrumbs'][] = ['label' => $modelTest->name, 'url' => ['/test/test/view', 'id'=>$modelTest->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="test-question-index">

    <div class="card shadow mb-4">
        <div class="card-header">
            <h1><?= Html::encode($this->title) ?></h1>
        </div>
        <div class="card-body">
            <p>
                <?= Html::a('Добавить вопрос', ['create', 'idTest' => $modelTest->id], ['class' => 'btn btn-success']) ?>
            </p>
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' => [
                    //['class' => 'yii\grid\SerialColumn'],

                    'id',
                    'id_test',
                    [
                        'attribute' => 'name',
                        'value' => function(\app\modules\test\models\TestQuestion $model) {
                            return \yii\helpers\StringHelper::truncateWords($model->name, 10);
                        },
                    ],
                    'type_question',
                    //'attach_file',
                    'weight',
                    'date_create:datetime',
                    //'author',

                    ['class' => ActionColumn::class],
                ],
            ]); ?>
        </div>
    </div>
</div>
