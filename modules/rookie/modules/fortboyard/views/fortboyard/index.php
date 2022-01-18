<?php

use kartik\grid\ActionColumn;
use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\grid\SerialColumn;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

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
                //['class' => SerialColumn::class],

                'id',
                'teamName:text:Команда',
                'date_show_1',
                'date_show_2',
                'title',
                'text',
                //'date_create',

                ['class' => ActionColumn::class],
            ],
        ]); ?>


    </div>
</div>