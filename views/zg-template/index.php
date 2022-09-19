<?php

use app\models\zg\ZgTemplate;
use yii\bootstrap5\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var string $kind */
/** @var array $kindList */
/** @var boolean $isEditor */

$this->title = 'Шаблоны ответов на однотипные обращения';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="zg-template-index">

    <div class="col border-bottom mb-2">
        <p class="display-5">
            <?= $this->title ?>
        </p>    
    </div>

    <?php if ($isEditor): ?>
    <p>
        <?= Html::a('Добавить', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?php endif; ?>

    <div class="card">
        <div class="card-body">
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
                        'value' => function(ZgTemplate $model) {
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
                'toolbar' => [
                    '{export}',
                    '{toggleData}',
                ],
                'export' => [
                    'showConfirmAlert' => false,
                ],
                'panel' => [
                    'type' => GridView::TYPE_DEFAULT,       
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
