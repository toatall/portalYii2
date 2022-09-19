<?php

use app\models\Module;
use yii\bootstrap5\Html;
use kartik\grid\GridView;
use kartik\grid\ActionColumn;
use kartik\grid\SerialColumn;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Модули';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="module-index">

    <h1 class="display-5 border-bottom">
        <?= Html::encode($this->title) ?>
    </h1>

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

            [
                'class' => ActionColumn::class,
                'dropdown' => true,
            ],
        ],
        'toolbar' => [
            '{export}',
            '{toggleData}',
        ],
        'export' => [
            'showConfirmAlert' => false,
        ],
        'panel' => [
            'type' => GridView::TYPE_DEFAULT,       
        ],
    ]); ?>


</div>
