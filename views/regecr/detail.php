<?php
/* @var $this yii\web\View */
/* @var $searchModel \app\models\regecr\RegEcrSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;

$this->title = 'Анкетирование по ГР (Детализация)';
$this->params['breadcrumbs'][] = $this->title;
?>

<h1><?= $this->title ?></h1>

<hr />
<div class="btn-group">
    <?= Html::a('Статистика', ['index'], ['class' => 'btn btn-default']) ?>
    <?= Html::a('В виде графика', ['chart'], ['class' => 'btn btn-default']) ?>
</div>
<hr />

<?php Pjax::begin(['id'=>'ajax-regecr-detail', 'timeout' => false, 'enablePushState'=>false]); ?>

<?= $this->render('_search', [
    'model' => $searchModel,
]); ?>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'summary' => false,
    'pager' => [
        'firstPageLabel' => 'Первая',
        'lastPageLabel' => 'Последняя',
    ],
    'columns' => [
        'code_org',
        'date_reg:date',
        'count_create',
        'count_vote',
        [
            'attribute' => 'avg_eval_a_1_1',
            'header' => 'Средняя оценка А 1.1 <span class="badge badge-info" data-toggle="popover" data-placement="left" data-content=\'Средняя оценка респондентами по показателю А 1.1 "Среднее время регистрации, юридических лиц", дней (среднее арифметическое от общего количества опрошенных респондентов)\'><i class="fa fa-info"></i></span>',
        ],
        [
            'attribute' => 'avg_eval_a_1_2',
            'header' => 'Средняя оценка А 1.2 <span class="badge badge-info" data-toggle="popover" data-placement="left" data-content=\'Средняя оценка респондентами по показателю А 1.2 "Среднее количество процедур, необходимых для регистрации юридических лиц", штук (среднее арифметическое от общего количества опрошенных респондентов)\'><i class="fa fa-info"></i></span>',
        ],
        [
            'attribute' => 'avg_eval_a_1_3',
            'header' => 'Средняя оценка А 1.3 <span class="badge badge-info" data-toggle="popover" data-placement="left" data-content=\'Средняя оценка респондентами по показателю А 1.3 "Оценка деятельности органов власти по регистрации, юридических лиц", баллов (среднее арифметическое от общего количества опрошенных респондентов)\'><i class="fa fa-info"></i></span>',
        ],
    ],
]); ?>

<?php Pjax::end(); ?>


<?php
$this->registerJs(<<<JS
    $('[data-toggle="popover"]').popover({trigger: 'hover'});
JS
);
?>