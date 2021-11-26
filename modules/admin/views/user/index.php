<?php

use kartik\grid\ActionColumn;
use yii\bootstrap4\Html;
use kartik\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Пользователи';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Добавить пользователя', ['create'], ['class' => 'btn btn-success']) ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],

            'id',
            'username',
            //'password',
            'username_windows',
            'fio',
            //'default_organization',
            'current_organization',
            'blocked:boolean',           
            //'telephone',
            //'post',
            //'rank',
            //'about',
            //'department',
            //'hash',
            //'organization_name',
            'date_create:datetime',
            'date_edit:datetime',
            //'date_delete',

            ['class' => ActionColumn::class],
        ],
    ]); ?>


</div>
