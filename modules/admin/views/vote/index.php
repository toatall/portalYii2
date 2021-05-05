<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Голосование';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="vote-main-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Создать', ['create'], ['class' => 'btn btn-success']) ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'name',
            'date_start',
            'date_end',
            'organizations',
            [
                'attribute' => 'multi_answer',
                'value' => function(\app\models\vote\VoteMain $model) {
                    return $model->multi_answer ? 'Да' : 'Нет';
                }
            ],
            //'on_general_page',
            //'description',
            //'date_create',
            //'date_edit',
            //'log_change',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
