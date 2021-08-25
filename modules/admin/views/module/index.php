<?php

use app\models\Module;
use yii\bootstrap4\Html;
use kartik\grid\GridView;
use kartik\grid\ActionColumn;
use kartik\grid\SerialColumn;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Модули';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="module-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Создать модуль', ['create'], ['class' => 'btn btn-success']) ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => SerialColumn::class],

            'name',
            'description',
            [
                'attribute' => 'only_one',
                'value' => function(Module $model) {
                    return $model->only_one ? 'Да' : 'Нет';
                }
            ],
            'date_create:datetime',
            'author',

            ['class' => ActionColumn::class],
        ],
    ]); ?>


</div>
