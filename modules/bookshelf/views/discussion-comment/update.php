<?php

use yii\bootstrap5\Html;

/** @var yii\web\View $this */
/** @var app\modules\bookshelf\models\BookShelfDiscussionComment $model */

$this->title = 'Update Book Shelf Discussion Comment: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Book Shelf Discussion Comments', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="book-shelf-discussion-comment-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
