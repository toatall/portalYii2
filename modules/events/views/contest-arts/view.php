<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\assets\fancybox\FancyboxAsset;

FancyboxAsset::register($this);

/* @var $this yii\web\View */
/* @var $model app\modules\events\models\ContestArts */

$this->title = $model->image_original_title;
$this->params['breadcrumbs'][] = ['label' => 'Конкурс "Навстречу искусству"', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => 'Управление', 'url' => ['admin']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="contest-arts-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="btn-group">
        <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены, что хотите удалить?',
                'method' => 'post',
            ],
        ]) ?>
        <?= Html::a('Управление', ['admin'], ['class' => 'btn btn-default']) ?>
    </div><br /><br />

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'date_show:date',
            'date_show_2:date',
            'department_name',
            'department_ad_group',
            [
                'attribute' => 'image_original',
                'value' => function($model) {
                    return Html::a(Html::img($model->image_original, ['style'=>'width:10em;', 'class'=>'thumbnail']), $model->image_original, ['class' => 'fancybox']);
                },
                'format' => 'raw',
            ],
            'image_original_author',            
            [
                'attribute' => 'image_reproduced',
                'value' => function($model) {
                    return Html::a(Html::img($model->image_reproduced, ['style'=>'width:10em;', 'class'=>'thumbnail']), $model->image_reproduced, ['class' => 'fancybox']);
                },
                'format' => 'raw',
            ],
            'description_original',
            'description_reproduced',            
            [
                'attribute' => 'qr_code_file',
                'value' => function($model) {
                    return Html::a(Html::img($model->qr_code_file, ['style'=>'width:10em;', 'class'=>'thumbnail']), $model->qr_code_file, ['class' => 'fancybox']);
                },
                'format' => 'raw',
            ],
            'date_create:datetime',
            'date_update:datetime',            
        ],
    ]) ?>

</div>
