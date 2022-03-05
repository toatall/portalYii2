<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\bookshelf\models\BookShelfCalendar */

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
