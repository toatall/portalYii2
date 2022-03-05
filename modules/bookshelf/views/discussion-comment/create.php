<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\bookshelf\models\BookShelfDiscussionComment */

$this->title = 'Create Book Shelf Discussion Comment';
$this->params['breadcrumbs'][] = ['label' => 'Book Shelf Discussion Comments', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="book-shelf-discussion-comment-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
