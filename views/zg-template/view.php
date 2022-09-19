<?php

use app\models\zg\ZgTemplate;
use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\zg\ZgTemplate $model */
/** @var boolean $isEditor */

$this->title = $model->kind;
$this->params['breadcrumbs'][] = ['label' => 'Шаблоны ответов на однотипные обращения', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="zg-template-view">

    <h1 class="display-5 border-bottom">
        <?= Html::encode($this->title) ?>
    </h1>

    <?php if ($isEditor): ?>
    <div class="btn-group mb-2">
        <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены, что хотите удалить?',
                'method' => 'post',
            ],
        ]) ?>
        <?= Html::a('Назад', ['index'], ['class' => 'btn btn-secondary']) ?>
    </div>
    <?php endif; ?>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
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
            'date_create:datetime',
            'date_update:datetime',
            'author',
        ],
    ]) ?>

</div>
