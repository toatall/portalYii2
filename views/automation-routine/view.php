<?php

use app\models\History;
use app\modules\comment\widgets\CommentWidget;
use app\modules\like\widgets\LikeWidget;
use kartik\rating\StarRating;
use yii\bootstrap5\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var app\models\AutomationRoutine $model */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Автоматизация рутиных операций', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$instruction = $model->getInstruction();
?>
<div class="automation-routine-view">    

    <h1 class="display-5 border-bottom"><?= Html::encode($this->title) ?></h1>

    <div class="card card-body">
        <p class="fs-5">
            <?= Yii::$app->formatter->asHtml($model->description) ?>
        </p>
        <hr class="text-secondary" />    
        <?php if ($model->region_mail): ?>
            <p>Реквизиты письма Управления: <?= $model->region_mail ?></p>
        <?php endif; ?>
        <p>
            сылка на FTP: <kbd><?= $model->ftp_path ?></kbd>
        </p>
        <p>Дата изменения: <?= 
            Yii::$app->formatter->asDatetime($model->date_create) 
            . ($model->date_update ? ' (' . Yii::$app->formatter->asDatetime($model->date_update) . ')' : '')    
        ?></p>
        <hr class="text-secondary" />
        <p>
            <strong>Владелец(ы) (структурные подразделения): </strong>
            <?= $model->owners ?>
        </p>
        <?php if (Yii::$app->user->can('admin')): ?>
            <?php $statistic = $model->getRateStatictic(); ?>
            <?php print_r($statistic) ?>
            <hr class="text-secondary" />
            <p>Просмотров: <code><?= History::count(Url::current()) ?></code></p>
            <p>Средняя оценка: <code><?= $statistic['avg_rate'] ?? 0 ?></code></p>
            <p>Количество оценок: <code><?= $statistic['count_rate'] ?? 0 ?></code></p>
        <?php endif; ?>
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
        <div class="card">
            <div class="card-header">
                <strong>Пожалуйста поставьте Вашу оценку от 1 до 5!</strong>
                <br />Где:
                <ul class="list-unstyled">
                    <li>1 - программный модуль бесполезен (мне он не интересен)</li>                    
                    <li>5 - программный модуль очень полезен (буду пользоваться часто)</li>
                </ul>
            </div>
            <div class="card-body">
                <?php Pjax::begin(['id' => 'pjax-automation-routine-index', 'timeout'=>false, 'enablePushState'=>false]) ?>
                    <?= Html::beginForm('', 'post', ['id' => 'form-automation-ruotine-view', 'data' => ['pjax' => true]]) ?>
                        <?= StarRating::widget([
                            'name' => 'rate',
                            'value' => $model->getRate(),
                            'pluginOptions' => [
                                'step' => 1,
                            ],
                        ]) ?>
                        <?php $this->registerJs(<<<JS
                            $('[name="rate"]').on('change', function(){
                                $(this).parent('form').submit()
                            })
                        JS) ?>
                    <?= Html::endForm() ?>
                <?php Pjax::end() ?>
            </div>
        </div>
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