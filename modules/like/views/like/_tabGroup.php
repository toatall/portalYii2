<?php
/** @var \yii\web\View $this */
/** @var int $idLike */
/** @var array $groupDataByOrg */
/** @var array $groupDataByDate */

use kartik\grid\GridView;
?>

<div class="row mt-2">
    <div class="col">
        <?= GridView::widget([
            'dataProvider' => new \yii\data\ArrayDataProvider([
                'allModels' => $groupDataByOrg,
            ]),
            'columns' => [
                'org:text:Код НО',
                'count_likes:integer:Количество лайков',
            ],
        ]) ?>
    </div>
    <div class="col">
        <?= $this->render('_chart', [
            'groupDataByDate' => $groupDataByDate,
            'idLike' => $idLike,
        ]) ?>
    </div>
</div>