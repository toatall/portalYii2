<?php

use yii\helpers\Html;
use yii\bootstrap4\LinkPager;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Литературная дискуссия';
$this->params['breadcrumbs'][] = ['label' => 'Книжная полка', 'url' => ['/bookshelf/default/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="book-shelf-discussion-index">

    <p class="display-4 border-bottom">
        <?= Html::encode($this->title) ?>
    </p>

    <p>
        <?= Html::a('Создать', ['create'], ['class' => 'btn btn-outline-success btn-sm']) ?>
    </p>

    <?php foreach($dataProvider->getModels() as $model): ?>
        <?= $this->render('_item', [
            'model' => $model,
        ]) ?>
    <?php endforeach; ?>
    
    <?= LinkPager::widget([
        'pagination' => $dataProvider->pagination,
        'firstPageLabel' => '<span title="Первая страница"><i class="fas fa-chevron-left"></i><i class="fas fa-chevron-left"></i></span>',
        'prevPageLabel' => '<i class="fas fa-chevron-left" title="Предыдущая страница"></i>',
        'lastPageLabel' => '<span title="Последняя страница"><i class="fas fa-chevron-right"></i><i class="fas fa-chevron-right"></i></span>',
        'nextPageLabel' => '<i class="fas fa-chevron-right" title="Следующая страница"></i>',        
    ]) ?>

</div>
