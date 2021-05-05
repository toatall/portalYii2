<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $modelVoteMain \app\models\vote\VoteMain */

$this->title = 'Управление вопросами "' . $modelVoteMain->name . '"';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="vote-question-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Создать', ['create-question', 'idMain'=>$modelVoteMain->id], ['class' => 'btn btn-success']) ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [

            'id',
            'text_question',
            'date_create:datetime',
            'date_edit:datetime',

            [
                'class' => 'yii\grid\ActionColumn',
                'buttons' => [
                    'view' => function($url, $model, $key) {
                        return Html::a('', ['view-question', 'id'=>$model->id], ['class'=>'glyphicon glyphicon-eye-open']);
                    },
                    'update' => function($url, $model, $key) {
                        return Html::a('', ['update-question', 'id'=>$model->id], ['class'=>'glyphicon glyphicon-pencil']);
                    },
                    'delete' => function($url, $model, $key) {
                        return Html::a('', ['delete-question', 'id'=>$model->id], [
                            'class'=>'glyphicon glyphicon-trash',
                            'data' => [
                                'confirm' => 'Вы уверены, что хотите удалить?',
                                'method' => 'post',
                            ],
                        ]);
                    },
                ],
            ],
        ],
    ]); ?>


</div>
