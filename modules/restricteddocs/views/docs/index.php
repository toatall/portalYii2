<?php

use yii\bootstrap5\Html;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Restricted Docs';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="restricted-docs-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Restricted Docs', ['create'], ['class' => 'btn btn-success']) ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'name',
            'doc_num',
            'doc_date',
            'privacy_sign_desc',
            //'owner',
            //'date_create',
            //'date_update',
            //'author',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
