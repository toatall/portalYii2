<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\bookshelf\models\BookShelfCalendar */

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
