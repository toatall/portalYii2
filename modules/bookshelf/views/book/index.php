<?php

use app\modules\bookshelf\models\BookShelf;
use yii\bootstrap4\LinkPager;
use yii\bootstrap4\Html;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var app\modules\bookshelf\models\BookShelfSearch $searchModel */

$this->title = 'Что взять на книжной полке';
$this->params['breadcrumbs'][] = ['label' => 'Книжная полка', 'url' => ['/bookshelf/default/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="book-shelf-index">

    <p class="display-4 border-bottom">
        <?= Html::encode($this->title) ?>
    </p>

    <?php if (BookShelf::isEditor()): ?>
    <div class="btn-group mt-2 mb-2">
        <?= Html::a('Добавить', ['create'], ['class' => 'btn btn-outline-success btn-sm mv-link']) ?>
        <?= Html::a('Места размещения книг', ['/bookshelf/place/index'], ['class' => 'btn btn-outline-secondary btn-sm mv-link']) ?>        
    </div>
    <?php endif; ?>

    <?php Pjax::begin(['id' => 'pjax-bookshelf']) ?>
    <div>
        <?= $this->render('_search', [
            'model' => $searchModel,
        ]) ?>
    </div>

    <div class="row">
        <?php foreach ($dataProvider->getModels() as $model): ?>
            <?= $this->render('_item-book', [
                'model' => $model,
            ]) ?>
        <?php endforeach; ?>
    </div>

    <?= LinkPager::widget([
        'pagination' => $dataProvider->pagination,
        'firstPageLabel' => '<span title="Первая страница"><i class="fas fa-chevron-left"></i><i class="fas fa-chevron-left"></i></span>',
        'prevPageLabel' => '<i class="fas fa-chevron-left" title="Предыдущая страница"></i>',
        'lastPageLabel' => '<span title="Последняя страница"><i class="fas fa-chevron-right"></i><i class="fas fa-chevron-right"></i></span>',
        'nextPageLabel' => '<i class="fas fa-chevron-right" title="Следующая страница"></i>',        
    ]) ?>

    <?php Pjax::end() ?>

</div>
