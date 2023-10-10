<?php

use app\modules\comment\widgets\CommentWidget;
use app\modules\like\widgets\LikeWidget;
use yii\bootstrap5\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\AutomationRoutine $model */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Автоматизация рутиных операций', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$instruction = $model->getInstruction();
?>
<div class="automation-routine-view">    

    <div class="card card-body">
        <p class="fs-5">
            <?= Yii::$app->formatter->asHtml($model->description) ?>
        </p>
        <hr />        
        <p>
            сылка на FTP: <kbd><?= $model->ftp_path ?></kbd>
        </p>
        <p>Дата изменения: <?= 
            Yii::$app->formatter->asDatetime($model->date_create) 
            . ($model->date_update ? ' (' . Yii::$app->formatter->asDatetime($model->date_update) . ')' : '')    
        ?></p>
    </div>

    <?php if ($instruction): ?>
    <div class="card mt-3">
        <div class="card-header fw-bold">
            <button class="btn btn-light border btn-toggle"><i class="fas fa-plus-circle"></i></button>
            <?= $model->getAttributeLabel('uploadInstruction') ?>
        </div>
        <div class="card-body" style="display: none;">
            <?php if (strpos(strtoupper($instruction), 'PDF')): ?> 
            <iframe height="700" src="<?= $instruction ?>" width="100%">
                <a href="<?= $instruction ?>">Скачать</a>
            </iframe>
            <?php else: ?>
            <div class="icon-addons">
                <a href="<?= $instruction ?>" data-filename="<?= $instruction ?>">Скачать</a>
            </div>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>

    <div class="card mt-3">
        <div class="card-header fw-bold"><?= $model->getAttributeLabel('uploadFiles') ?></div>
        <div class="card-body">
            <ul class="list-group">
            <?php foreach($model->getFiles() as $file): ?>
                <li class="icon-addons list-group-item list-group-item-action">
                    <?= Html::a(basename($file), ['download', 'id'=>$model->id, 'f'=>$file], ['data-filename' => basename($file)]) ?>
                </li>
            <?php endforeach; ?>
            </ul>
        </div>
    </div>

    <div class="mt-3">
        <?= LikeWidget::widget([
            'unique' => 'automation-routine-' . $model->id,
        ]) ?>
    </div>

    <div class="mt-3">
        <?= CommentWidget::widget([
            'modelName' => 'automation-routine',
            'modelId' => $model->id,
        ]) ?>
    </div>


</div>
<?php $this->registerJs(<<<JS
    $('.btn-toggle').on('click', function(){
        const body = $(this).parent('div').next('div.card-body')
        body.toggle()
        if (body.is(':visible')) {
            $(this).html('<i class="fas fa-minus-circle"></i>')
        }
        else {
            $(this).html('<i class="fas fa-plus-circle"></i>')
        }
    })

JS); ?>