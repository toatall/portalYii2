<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Телефонные справочники';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="telephone-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Добавить справочник', ['create'], ['class' => 'btn btn-success']) ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            //'id_tree',
            'id_organization',
            'dop_text',
            'telephone_file',
            //'sort',
            //'date_edit',
            'author',
            'date_create',
            //'log_change',
            //'count_download',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
