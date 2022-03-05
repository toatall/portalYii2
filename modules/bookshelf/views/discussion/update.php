<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\modules\bookshelf\models\BookShelfDiscussion $model */

$this->title = 'Редактирование';
$this->params['breadcrumbs'][] = ['label' => 'Книжная полка', 'url' => ['/bookshelf/default/index']];
$this->params['breadcrumbs'][] = ['label' => 'Литературная дискуссия', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Редактирование';
?>
<div class="book-shelf-discussion-update">

    <p class="display-4 border-bottom">
        <?= Html::encode($this->title) ?>
    </p>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
