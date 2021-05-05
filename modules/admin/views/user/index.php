<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

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
            //'role_admin',
            'blocked',
            //'folder_path',
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

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
