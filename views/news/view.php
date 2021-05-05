<?php

use yii\helpers\Html;
use app\models\news\News;
use yii\helpers\Url;
use dosamigos\gallery\Gallery;
use yii\bootstrap\Tabs;


/* @var $this yii\web\View */
/* @var $model News */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Новости', 'url' => ['/news/index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

?>
<div class="news-view">
    <div class="panel panel-default">
        <div class="panel-heading">
            <?php if (!\Yii::$app->request->isAjax): ?>
            <h1><?= Html::encode($this->title) ?></h1><hr />
            <?php endif; ?>

            <?php if (false): // @todo возможно сделать возможность редактирования новостей ?>
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
            <?php endif; ?>

            <div class="">
                <i class="fa fa-calendar-alt"></i> <?= \Yii::$app->formatter->asDatetime($model->date_create) ?>,
                <i class="fa fa-user"></i> <?= $model->modelAuthor->fio ?>,
                <i class="fa fa-heart"></i> <?= $model->count_like ?>,
                <i class="fa fa-comments"></i> <?= $model->count_comment ?>,
                <i class="fa fa-eye"></i> <?= $model->count_visit ?>,
                <br /><i class="fa fa-building"></i> <?= $model->organization->name ?>
                <?= !empty($model->from_department) ? '(' . $model->from_department . ')' : '' ?>
            </div>
        </div>
        <div class="panel-body">
            <?= $model->message2 ?>
        </div>
    </div>
    
    <?php if ($model->getCheckListBoxUploadFilesGallery()): ?>
    <div class="panel panel-default">
        <div class="panel-heading">
            <button data-toggle="collapse" data-target="#collapse-file" class="btn btn-default btn-sm">
                <i class="fa fa-minus" id="collapse-file-i"></i>
            </button> Файлы
        </div>
        <div class="panel-body collapse" id="collapse-file">
            <?php foreach ($model->getCheckListBoxUploadFilesGallery() as $file): ?>
            <i class="fa fa-file"></i> <a href="<?= $file ?>" target="_blank"><?= basename($file) ?></a><br />
            <?php endforeach; ?>
        </div>
    </div>
    
    <?php endif; ?>
    
    <?php if ($model->getCheckListBoxUploadImagesGallery()): ?>
    <div class="panel panel-default">
        <div class="panel-heading">
            <button data-toggle="collapse" data-target="#collapse-image" class="btn btn-default btn-sm">
                <i class="fa fa-minus" id="collapse-image-i"></i>
            </button> Изображения
        </div>
        <div class="panel-body collapse" id="collapse-image">                
            <?php 
            $items = array();
            foreach ($model->getCheckListBoxUploadImagesGallery() as $image)
            {
                $imageFile = \Yii::$app->storage->getFileUrl($image);
                $items[] = [
                    'url' => $imageFile,
                    'src' => \Yii::$app->storage->addFileNamePrefix($imageFile, 'thumb'),
                    'imageOptions' => [
                        'class' => 'img-thumbnail',
                        'style' => 'width:200px;',
                    ],
                ];
            }
            // виджет галереи
            echo Gallery::widget(['items' => $items]);
            ?>
        </div>
    </div>      
    <?php endif; ?>
    
    <div class="panel panel-default">
        <div id="container-like" class="panel-body" data-ajax-url="<?= Url::to(['news/like', 'idNews'=>$model->id]) ?>"></div>
    </div>
         
    <?= Tabs::widget([
        'id' => 'tab-comments',
        'encodeLabels' => false,
        'items' => [
            [
                'label' => 'Комментарии <button class="btn btn-default btn-xs" id="btn-comment-refresh" title="Обновить" alt="Обновить"><i class="fa fa-sync"></i></button>',                
                'content' => '<div id="container-comment" data-ajax-url="' . Url::to(['news-comment/index', 'idNews'=>$model->id]) . '"></div>',
                'linkOptions' => ['data-tab' => 'index'],
            ],
            [
                'label' => 'Добавить комментарий',
                'content' => '<div id="container-comment-form" data-ajax-url="' . Url::to(['news-comment/create', 'idNews'=>$model->id]) . '"></div>',
                'linkOptions' => ['data-tab' => 'form'],
            ]
        ],
    ]) ?>
        
</div>
<?php $this->registerJS(<<<JS
    
     function runAjaxGetRequest(container) 
     {
        container.html('<img src="/img/loader_fb.gif" style="height: 100px;">');
        $.get(container.attr('data-ajax-url'))
        .done(function(data) {
            container.html(data);
        })
        .fail(function (jqXHR) {
            container.html('<div class="alert alert-danger">' + jqXHR.status + ' ' + jqXHR.statusText + '</div>');
        });    
    }
    
    // настройки collapse для файлов
    $('#collapse-file').collapse('show');
    $('#collapse-file').on('show.bs.collapse', function() { $('#collapse-file-i').attr('class', 'fa fa-minus'); });
    $('#collapse-file').on('hide.bs.collapse', function() { $('#collapse-file-i').attr('class', 'fa fa-plus'); });

    // настройки collapse для изображений
    $('#collapse-image').collapse('show');
    $('#collapse-image').on('show.bs.collapse', function() { $('#collapse-file-i').attr('class', 'fa fa-minus'); });
    $('#collapse-image').on('hide.bs.collapse', function() { $('#collapse-file-i').attr('class', 'fa fa-plus'); });

    // для корректного отображения изображения из галереии при просмотре 
    $('#blueimp-gallery').prependTo($('body'));

    // привязка к кнопке обновить коментарии
    $('#btn-comment-refresh').on('click', function() {
        //ajaxHtmlAuto('container-comment');
        runAjaxGetRequest($('#container-comment'));
    });

    runAjaxGetRequest($('#container-like'));
    runAjaxGetRequest($('#container-comment'));
    runAjaxGetRequest($('#container-comment-form'));
    
JS
);
?>