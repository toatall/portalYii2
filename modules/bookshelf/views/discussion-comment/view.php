<?php

use yii\bootstrap5\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\modules\bookshelf\models\BookShelfDiscussionComment $model */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Book Shelf Discussion Comments', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="book-shelf-discussion-comment-view">

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
            'id_parent',
            'id_book_shelf_discussion',
            'comment',
            'username',
            'date_create',
            'date_update',
        ],
    ]) ?>

</div>
