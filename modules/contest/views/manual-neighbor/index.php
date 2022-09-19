<?php

/** @var yii\web\View $this */

use app\modules\contest\models\ManualNeighbor;
use kartik\grid\GridView;
use yii\bootstrap5\Html;

/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Методички для соседа';
?>

<p class="display-5 border-bottom"><?= $this->title ?></p>

<div class="mt-3">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'id',
            'name',
            'department',
            [
                'attribute' => 'file',
                'value' => function($model) {
                    /** @var app\modules\contest\models\ManualNeighbor $model */
                    return Html::a('<i class="far fa-file-pdf"></i> ' . basename($model->file), $model->file, ['target'=>'_blank']);
                },
                'format' => 'raw',
            ],
            [
                'label' => 'Голосов',
                'value' => function($model) {
                    /** @var app\modules\contest\models\ManualNeighbor $model */
                    $html = '<ul class="list-unstyled">';
                    $html .= '<li>Разберётся и ребенок - ' . $model->count_votes_1 . ' (голосов)</li>';
                    $html .= '<li>Охват аудитории - ' . $model->count_votes_2 . ' (голосов)</li>';
                    $html .= '<li>Глаза разбегаются - ' . $model->count_votes_3 . ' (голосов)</li>';
                    $html .= '</ul>';
                    return $html;
                },
                'format' => 'raw',
                'headerOptions' => ['style' => 'min-width: 20rem;'],
            ],            
        ],
    ]) ?>
</div>

<?php if (ManualNeighbor::isCanVoted()): ?>
    <?= Html::a('<i class="fas fa-vote-yea"></i> Проголосовать', ['/contest/manual-neighbor/vote'], ['class' => 'btn btn-primary mv-link']); ?>
<?php endif; ?>