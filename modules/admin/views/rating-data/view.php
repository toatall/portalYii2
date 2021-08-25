<?php

use yii\bootstrap4\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\rating\RatingData $model */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Рейтинги', 'url' => ['index', 'idMain' => $model->id_rating_main]];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="rating-data-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="btn-group mb-2">
        <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены, что хотите удалить рейтинг?',
                'method' => 'post',
            ],
        ]) ?>
    </div>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'ratingMain.name',
            'note',
            'rating_year',
            'periodName',
            'log_change',
            'date_create:datetime',
            'author',
            [
                'attribute' => 'uploadFiles',
                'value' => function ($model) {
                    $result = '';
                    foreach ($model->getCheckListBoxUploadFiles() as  $file) {
                        $result .= Html::a(basename($file), $file, ['target' => '_blank']) . '<br />';
                    }
                    return $result;
                },
                'format' => 'raw',
            ],
        ],
    ]) ?>

</div>
