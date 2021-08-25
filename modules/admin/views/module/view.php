<?php

use app\helpers\Log\LogHelper;
use app\models\Module;
use yii\bootstrap4\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Module $model */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Модули', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="module-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Изменить', ['update', 'id' => $model->name], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->name], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены, что хотите удалить модуль?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'name',
            'description',
            'only_one',
            'children_node',
            'dop_action',
            'dop_action_right_admin',
            [
                'attribute' => 'log_change',
                'value' => function(Module $model) {
                    return LogHelper::getLog($model->log_change);
                },
                'format' => 'raw',
            ],
            'date_create:datetime',
            'author',
        ],
    ]) ?>

</div>
