<?php
/* @var $this \app\controllers\SiteController */
/* @var $dataProvider yii\data\ActiveDataProvider */

use yii\helpers\Html;
use yii\grid\GridView;

$this->title = 'Телефонные справочники';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="site-telephone">
    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'showHeader' => false,
        'summary' => false,
        'columns' => [
            [
                'value' => function($model) {
                    return Html::a('<i class="fas fa-file-word"></i> Скачать', $model->telephone_file, ['target'=>'_blank', 'class'=>'btn btn-primary']);
                },
                'format' => 'raw',
            ],
            'id_organization',
            'organization.name',
            'dop_text',
        ],
    ]); ?>

</div>
