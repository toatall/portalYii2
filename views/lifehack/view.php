<?php

use app\models\lifehack\Lifehack;
use app\modules\comment\widgets\CommentWidget;
use yii\bootstrap5\Html;
use yii\widgets\DetailView;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var app\models\lifehack\Lifehack $model */
/** @var app\models\lifehack\LifehackLike $modelLike */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Лайфхаки', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lfehack-view">

    <h1 class="mv-hide display-5 border-bottom">
        <?= Html::encode($this->title) ?>
    </h1>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'title',
            [
                'attribute' => 'tags',
                'value' => function(Lifehack $model) {
                    $res = '';
                    foreach ($model->tagsArray as $tag) {
                        $res .= Html::a($tag, ['/lifehack/index', 'tag'=>$tag], ['target' => '_blank']) . ' ';
                    }
                    return $res;
                },
                'format' => 'raw',
            ],        
            'text:raw', 
            'author_name',         
            'date_create:datetime:Добавлено',
            'username',          
        ],
    ]) ?>
       
    <?php if ($files = $model->getLifehackFiles()->all()): ?>
    <div class="card mt-2">
        <div class="card-header">
            <button data-bs-toggle="collapse" data-bs-target="#collapse-file" class="btn btn-light btn-sm">
                <i class="fa fa-minus" id="collapse-file-i"></i>
            </button> Файлы
        </div>
        <div class="card-body collapse" id="collapse-file">
            <?php foreach ($files as $file): ?>
            <i class="far fa-file fa-1x"></i>&nbsp;&nbsp;<a href="<?= $file->filename ?>" target="_blank"><?= basename($file->filename) ?></a><br />
            <?php endforeach; ?>
        </div>
    </div>    
    <?php endif; ?>

    <div class="mt-3">
        <?php Pjax::begin(['timeout'=>false, 'enablePushState'=>false]) ?>
            <?= $this->render('_formLike', ['model' => $modelLike]) ?>
        <?php Pjax::end() ?>
    </div>

    <div class="mt-3">
        <?= CommentWidget::widget([
            'modelName' => 'lifehack',
            'modelId' => $model->id,
        ]) ?>
    </div>    

</div>

<?php $this->registerJs(<<<JS

// настройки collapse для файлов
$('#collapse-file').collapse('show');
$('#collapse-file').on('show.bs.collapse', function() { $('#collapse-file-i').attr('class', 'fa fa-minus'); });
$('#collapse-file').on('hide.bs.collapse', function() { $('#collapse-file-i').attr('class', 'fa fa-plus'); });

JS);