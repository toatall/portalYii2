<?php

use yii\bootstrap5\Html;

/** @var yii\web\View $this */
/** @var app\modules\bookshelf\models\BookShelfCalendar $model */

$this->title = 'Update Book Shelf Calendar: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Book Shelf Calendars', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="book-shelf-calendar-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
