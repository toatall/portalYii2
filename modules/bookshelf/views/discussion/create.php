<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\modules\bookshelf\models\BookShelfDiscussion $model */

$this->title = 'Создание дискуссии';
$this->params['breadcrumbs'][] = ['label' => 'Книжная полка', 'url' => ['/bookshelf/default/index']];
$this->params['breadcrumbs'][] = ['label' => 'Литературная дискуссия', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="book-shelf-discussion-create">

    <p class="display-4 border-bottom">
        <?= Html::encode($this->title) ?>
    </p>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
