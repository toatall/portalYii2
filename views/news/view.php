<?php
/** @var yii\web\View $this */
/** @var app\models\news\News $model */

use app\assets\FancyappsUIAsset;
use app\models\History;
use app\modules\comment\models\Comment;
use yii\bootstrap5\Html;
use app\modules\comment\widgets\CommentWidget;
use app\modules\like\widgets\LikeWidget;
use yii\helpers\Url;

FancyappsUIAsset::register($this);

$url = Url::current();
$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Новости', 'url' => ['/news/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="news-view">
    <div class="card">
        <div class="card-header">
            <?php if (!\Yii::$app->request->isAjax): ?>                
                <div class="col border-bottom mb-2">
                    <p class="display-4">
                        <?= $this->title ?>
                    </p>    
                </div>
            <?php endif; ?>

            <?php if (false): // @todo возможно сделать возможность редактирования новостей
                /* ?>
            <p>
                <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => 'Вы уверены, что хотите удалить?',
                        'method' => 'post',
                    ],
                ]) ?>
            </p>
            <?php 
                */
            endif; ?>

            <div class="fs-5">
                <i class="far fa-clock text-muted"></i> 
                    <?= \Yii::$app->formatter->asDatetime($model->date_create) ?>
                    <?= ($model->date_create != $model->date_edit) 
                        ? ' (изменено: ' . Yii::$app->formatter->asDatetime($model->date_edit) . ')' : '' ?>,
                <i class="fas fa-user-alt text-muted"></i> <?= Html::a(($model->modelAuthor == null ?: $model->modelAuthor->fio), '/@' . $model->author, ['class' => 'author', 'target' => '_blank']) ?>,
                <br /><i class="far fa-building text-muted"></i> <?= $model->organization->name ?>
                    <?= !empty($model->from_department) ? '(' . $model->from_department . ')' : '' ?>                
            </div>
            <div class="icon-group border-top mt-2 pt-2">                                
                <span class="badge bg-light border text-dark fs-5"><?= Comment::count('comment-news-' . $model->id) ?> <i class="far fa-comments"></i></span>                            
                <span class="badge bg-light border text-dark fs-5"><?= History::count($url) ?> <i class="far fa-eye"></i></span>
            </div>
        </div>
        <div class="card-body">
            <div style="font-size:20px;">
                <?= $model->message2 ?>
            </div>
        </div>
    </div>
    
    <?php if ($model->getCheckListBoxUploadFilesGallery()): ?>
    <div class="card mt-2">
        <div class="card-header">
            <button data-bs-toggle="collapse" data-bs-target="#collapse-file" class="btn btn-light">
                <i class="fas fa-minus" id="collapse-file-i"></i> Файлы
            </button> 
        </div>
        <div class="card-body collapse" id="collapse-file">
            <div class="list-group">
            <?php foreach ($model->getCheckListBoxUploadFilesGallery() as $file): ?>
                <?php $exists = file_exists(Yii::getAlias('@webroot') . $file); ?>
                <a href="<?= $file ?>" class="list-group-item list-group-item-action<?= $exists ?: ' disabled' ?>" target="_blank">
                    <div class="d-flex justify-content-between">
                        <h5 class="icon-addons fw-normal">
                            <span data-filename="<?= $file ?>">
                                <?= basename($file) ?>
                            </span>
                        </h5>    
                        <small class="text-muted">                
                            <?= $exists ? 
                                Yii::$app->storage->sizeText(filesize(Yii::getAlias('@webroot') . $file)) 
                                    : '<span class="text-danger"><i class="fas fa-times-circle"></i> файл не найден</span>' ?>
                        </small>
                    </div>
                </a>
            <?php endforeach; ?>
            </div>
        </div>
    </div>
    
    <?php endif; ?>
    
    <?php if ($model->getCheckListBoxUploadImagesGallery()): ?>
    <div class="card mt-2">
        <div class="card-header">
            <button data-bs-toggle="collapse" data-bs-target="#collapse-image" class="btn btn-light">
                <i class="fas fa-minus" id="collapse-image-i"></i> Изображения
            </button> 
        </div>
        <div class="card-body collapse row" id="collapse-image">                
            <?php
            foreach ($model->getCheckListBoxUploadImagesGallery() as $image) {
                if (file_exists(Yii::getAlias('@webroot') . $image)) {
                    echo Html::beginTag('div', ['class' => 'col-2']);
                        echo Html::a(Html::img(\Yii::$app->storage->addFileNamePrefix($image, 'thumb'), [
                            'class' => 'img-thumbnail',
                            'style' => 'height: 10rem',
                        ]), $image, [
                            'data-fancybox' => 'gallery',
                            'data-src' => $image,
                            'data-caption' => $model->title,
                        ]);
                    echo Html::endTag('div');
                }
                else {                   
                    echo Html::beginTag('div', ['class' => 'col-2 mb-5 text-center', 'style' => 'height: 10rem']);
                        echo Html::tag('i', '', ['class' => 'far fa-image text-muted', 'style'=>'font-size:8rem;']);
                        echo Html::tag('p', '<i class="fas fa-exclamation text-danger"></i> Изображение `' . basename($image) . '` не найдено!', 
                            ['class' => 'text-muted']);
                    echo Html::endTag('div');
                }
            }
            ?>
        </div>
    </div>      
    <?php endif; ?>
    
    <div class="card card-body mt-2">
        <div>
        <?= LikeWidget::widget([
            'unique' => 'like-news-' . $model->id,
        ]) ?>
        </div>
    </div>

    <?= CommentWidget::widget([
        'modelName' => 'news',
        'modelId' => $model->id,
        'hash' => 'comment-news-' . $model->id,     
        'options' => [
            'class' => 'mt-2',
        ],
    ]) ?>
        
</div>
<?php 
$this->registerJS(<<<JS
    
    // настройки collapse для файлов
    $('#collapse-file').collapse('show');
    $('#collapse-file').on('show.bs.collapse', function() { $('#collapse-file-i').toggleClass('fas fa-minus'); });
    $('#collapse-file').on('hide.bs.collapse', function() { $('#collapse-file-i').toggleClass('fas fa-plus'); });

    // настройки collapse для изображений
    $('#collapse-image').collapse('show');
    $('#collapse-image').on('show.bs.collapse', function() { $('#collapse-image-i').toggleClass('fas fa-minus'); });
    $('#collapse-image').on('hide.bs.collapse', function() { $('#collapse-image-i').toggleClass('fas fa-plus'); });

JS
);
?>