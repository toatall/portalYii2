<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\department\Department */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Departments', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="department-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'id_tree',
            'id_organization',
            'department_index',
            'department_name',
            'use_card',
            'general_page_type',
            'general_page_id_tree',
            'author',
            'log_change',
            'date_create',
            'date_edit',
        ],
    ]) ?>

</div>
