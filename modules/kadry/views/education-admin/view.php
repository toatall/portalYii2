<?php

/** @var yii\web\View $this */
/** @var app\modules\kadry\models\education\Education $model */

use app\modules\kadry\models\education\Education;
use yii\bootstrap4\Html;
use yii\widgets\DetailView;
use app\modules\admin\assets\JsTreeAsset;

JsTreeAsset::register($this);

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Образовательные программы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="education-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="btn-group mb-2">
        <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены, что хотите удалить запись?',
                'method' => 'post',
            ],
        ]) ?>
        <?= Html::a('Назад', ['index'], ['class' => 'btn btn-secondary']) ?>
    </div>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'title',
            'description',
            'description_full:raw',
            
            [
                'attribute' => 'thumbnail',
                'value' => function(Education $model) {
                    if ($model->thumbnail == null) {
                        return Yii::$app->formatter->nullDisplay;
                    }
                    return Html::a(Html::img($model->thumbnail, ['class' => 'w-100 img-thumbnail']), 
                        $model->thumbnail, ['target' => '_blank']);
                },
                'format' => 'raw',
            ],
            'duration',
            'authorModel.fio:text:Автор',
            'date_create:datetime',
            'date_update:datetime',
            //'log_change',
        ],
    ]) ?>

    <div id="tree-view-left">
               
    </div>

</div>
<?php $this->registerJs(<<<JS
    var xx = $('#tree-view-left').jstree({'core': { 'multiple': false }});
JS);