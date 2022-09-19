<?php

use yii\grid\ActionColumn;
use yii\bootstrap5\Html;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\User $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Пользователи';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h1 class="display-5 border-bottom">
        <?= Html::encode($this->title) ?>
    </h1>

    <p>
        <?= Html::a('Добавить пользователя', ['create'], ['class' => 'btn btn-success']) ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
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
