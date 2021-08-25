<?php

use yii\bootstrap4\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Menu\Menu $model */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Меню', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="menu-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="btn-group" style="margin-bottom: 10px;">
        <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены, что хотите удалить?',
                'method' => 'post',
            ],
        ]) ?>
    </div>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'id_parent',
            'type_menu',
            'name',
            'link',
            'submenu_code',
            'target',
            'blocked',
            'sort_index',
            'key_name',
            'author',
            'date_create:datetime',
            'date_edit:datetime',
            'log_change',
        ],
    ]) ?>

</div>
