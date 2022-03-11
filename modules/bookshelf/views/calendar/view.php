<?php

use yii\bootstrap4\Html;

/** @var yii\web\View $this */
/** @var app\modules\bookshelf\models\BookShelfCalendar $model */

$this->title = $model->id;
?>
<div class="book-shelf-calendar-view">   

    <div class="card card-body">
        <div class="row">
            <div>
                <?= Html::img($model->getPhoto(), ['style' => 'width:15rem;', 'class' => 'img-thumbnail']); ?>                
            </div>
            <div class="col p-2 text-justify">
                <?= $model->description ?>
            </div>
        </div>
    </div>

</div>
