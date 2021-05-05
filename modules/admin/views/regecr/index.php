<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Анкетирование по ГР';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="reg-ecr-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Добавить', ['create'], ['class' => 'btn btn-success']) ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],

            'id',
            'code_org',
            'date_reg:date',
            'count_create',
            'count_vote',
            'avg_eval_a_1_1',
            'avg_eval_a_1_2',
            'avg_eval_a_1_3',
            //'author',
            //'date_create',
            //'date_update',
            //'date_delete',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
