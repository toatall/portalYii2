<?php

use app\modules\kadry\models\education\Education;
use yii\bootstrap5\Html;
use kartik\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Образовательные программы';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="education-index">

    <h1 class="display-5 border-bottom">
        <?= Html::encode($this->title) ?>
    </h1>

    <p>
        <?= Html::a('Создать', ['create'], ['class' => 'btn btn-success']) ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            // ['class' => 'kartik\grid\SerialColumn'],

            'id',
            'title',
            'description',
            //'description_full',
            [
                'attribute' => 'thumbnail',
                'value' => function(Education $model) {
                    if ($model->thumbnail == null) {
                        return Yii::$app->formatter->nullDisplay;
                    }
                    return Html::a(Html::img($model->thumbnail, ['class' => 'w-100 img-thumbnail']), 
                        $model->thumbnail, ['target' => '_blank']);
                },
                'format' => 'raw',
            ],
            //'duration',
            'authorModel.fio:text:Автор',
            'date_create:datetime',
            'date_update:datetime',
            //'log_change',

            ['class' => 'kartik\grid\ActionColumn'],
        ],
    ]); ?>


</div>
