<?php

use app\models\lifehack\Lifehack;
use yii\bootstrap4\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\lifehack\Lifehack $model */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Лайфхаки', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="lfehack-view">

    <h1 class="mv-hide"><?= Html::encode($this->title) ?></h1>

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
                        $res .= Html::a('#'.$tag, ['/lifehack/index', 'tag'=>$tag], ['target' => '_blank']) . ' ';
                    }
                    return $res;
                },
                'format' => 'raw',
            ],        
            'text',          
            'date_create:datetime:Добавлено',
            'username',          
        ],
    ]) ?>
       
    <?php if ($files = $model->getLifehackFiles()->all()): ?>
    <div class="card mt-2">
        <div class="card-header">
            <button data-toggle="collapse" data-target="#collapse-file" class="btn btn-light btn-sm">
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

</div>

<?php $this->registerJs(<<<JS

// настройки collapse для файлов
$('#collapse-file').collapse('show');
$('#collapse-file').on('show.bs.collapse', function() { $('#collapse-file-i').attr('class', 'fa fa-minus'); });
$('#collapse-file').on('hide.bs.collapse', function() { $('#collapse-file-i').attr('class', 'fa fa-plus'); });

JS);