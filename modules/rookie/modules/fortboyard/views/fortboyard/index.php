<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Fort Boyard';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container bg-light mt-4 rounded p-4">
    <div class="fort-boyard-index">

        <h1 class="font-weight-bolder border-bottom"><?= Html::encode($this->title) ?></h1>

        <p>
            <?= Html::a('Добавить', ['create'], ['class' => 'btn btn-success']) ?>
        </p>


        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                'id',
                'id_team',
                'date_show',
                'title',
                'text',
                //'date_create',

                ['class' => 'yii\grid\ActionColumn'],
            ],
        ]); ?>


    </div>
</div>