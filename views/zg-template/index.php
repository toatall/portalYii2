<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;

/* @var $this \yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $kind string */
/* @var $kindList array */
/* @var $isEditor boolean */

$this->title = 'Шаблоны ответов на однотипные обращения';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="zg-template-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php if ($isEditor): ?>
    <p>
        <?= Html::a('Добавить', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?php endif; ?>

    <div class="panel panel-default">
        <div class="panel-body">
            <?php Pjax::begin(['id'=>'ajax-zg-template-index', 'timeout' => false, 'enablePushState'=>false]); ?>
            <?= Html::beginForm(['index'], 'get', ['data-pjax' => true, 'autocomplete' => 'off', 'id'=>'form-zg-template']) ?>
                <?= Html::label('Вид обращений', 'kindList') ?>
                <?= Html::dropDownList('kind', $kind, ArrayHelper::merge([''=>''], $kindList), ['class'=>'form-control', 'id'=>'kindList']) ?>
            <?= Html::endForm() ?>
            <hr />

            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    'kind',
                    [
                        'attribute' => 'files',
                        'value' => function(\app\models\zg\ZgTemplate $model) {
                            $result = '';
                            foreach ($model->zgTemplateFiles as $file) {
                                $result .= Html::a('<i class="fas fa-file-word"></i> ' . basename($file->filename), $file->filename) . Html::tag('br');
                            }
                            return $result;
                        },
                        'format' => 'raw',
                    ],
                    'description',
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'visibleButtons' => [
                            'view' => $isEditor,
                            'update' => $isEditor,
                            'delete' => $isEditor,
                        ],
                    ],
                ],
            ]); ?>
<?php
$this->registerJs(<<<JS
    $('#kindList').on('change', function() {
        $('#form-zg-template').submit();
    });
JS
);
?>

        <?php Pjax::end() ?>
        </div>
    </div>
</div>
