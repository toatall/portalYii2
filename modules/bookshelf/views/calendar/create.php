<?php

use yii\bootstrap5\Html;

/** @var yii\web\View $this */
/** @var app\modules\bookshelf\models\BookShelfCalendar $model */

$this->title = 'Create Book Shelf Calendar';
$this->params['breadcrumbs'][] = ['label' => 'Book Shelf Calendars', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="book-shelf-calendar-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
