<?php

use yii\bootstrap5\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\executetasks\models\ExecuteTasksDescriptionOrganization */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Execute Tasks Description Organizations', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="execute-tasks-description-organization-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'code_org',
            'photo',
            'fio',
            'telephone',
            'post',
            'rank',
            'description',
            'date_create',
            'date_update',
            'author',
        ],
    ]) ?>

</div>
