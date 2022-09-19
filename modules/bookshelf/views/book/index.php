<?php

use app\modules\bookshelf\models\BookShelf;
use yii\bootstrap5\LinkPager;
use yii\bootstrap5\Html;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var app\modules\bookshelf\models\BookShelfSearch $searchModel */

$this->title = 'Книги';
$this->params['breadcrumbs'][] = ['label' => 'Книжная полка', 'url' => ['/bookshelf/default/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="book-shelf-index card card-body bg-dark animate__animated animate__fadeInUp">

    <p class="display-5 text-white font-weight-bolder">
        <?= Html::a('Книжная полка', ['/bookshelf'], ['class' => 'text-white']) ?>
        &rsaquo;
        <span class="font-weight-normal text-secondary"><?= Html::encode($this->title) ?></span>
    </p>
    <hr class="border-white" />

    <?php if (BookShelf::isEditor()): ?>     
        <div>  
            <div class="btn-group mt-2 mb-4">
                <?= Html::a('Добавить', ['create'], ['class' => 'btn btn-success mv-link']) ?>
                <?= Html::a('Места размещения книг', ['/bookshelf/place/index'], ['class' => 'btn btn-secondary mv-link']) ?>        
            </div>
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

    <div class="align-content-center">
        <?= LinkPager::widget([
            'pagination' => $dataProvider->pagination,
            'firstPageLabel' => '<span title="Первая страница"><i class="fas fa-chevron-left"></i><i class="fas fa-chevron-left"></i></span>',
            'prevPageLabel' => '<i class="fas fa-chevron-left" title="Предыдущая страница"></i>',
            'lastPageLabel' => '<span title="Последняя страница"><i class="fas fa-chevron-right"></i><i class="fas fa-chevron-right"></i></span>',
            'nextPageLabel' => '<i class="fas fa-chevron-right" title="Следующая страница"></i>',       
        ]) ?>
    </div>

    <?php Pjax::end() ?>

</div>
