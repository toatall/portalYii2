<?php

use yii\bootstrap4\Html;

/** @var yii\web\View $this */
/** @var app\models\Comment $model */
/** @var string $hash */
/** @var string $url */

$userModel = $model->usernameModel;
?>

<div class="row mb-4 pb-2">
    <div>
        <a href="/@<?= $userModel->username ?>" target="_blank">
            <img src="<?= $userModel->getPhotoProfile() ?>" class="img-thumbnail rounded-circle ml-3" style="max-height: 4rem;"
            data-content="<?= $userModel->fio ?>" data-toggle="popover" data-trigger="hover" />
        </a>
    </div>
    <div class="col border-bottom">                
        <strong>
            <?= Html::a($userModel->fio, '/@' . $userModel->username, [
                'data-content' => $userModel->organization_name, 
                'data-toggle' => 'popover',
                'data-trigger' => 'hover',
                'target' => '_blank',        
            ]) ?>
        </strong>
        <br />
        <?php if ($model->date_delete): ?>
            <div class="alert alert-danger">Комментарий был удален <?= Yii::$app->formatter->asDatetime($model->date_delete) ?></div>
        <?php else: ?>

            <?php if (($reply = $model->reply) != null): ?>
                <div class="reply">
                    <?= Html::a('<i class="fas fa-reply"></i> Показать родительский комментарий', '', ['class'=>'link-reply', 'style'=>'font-size: 0.8rem;']) ?>
                    <?php if ($reply->date_delete): ?>
                        <div class="alert alert-danger mb-2" style="display: none;">
                            <small>
                                Комментарий был удален <?= Yii::$app->formatter->asDatetime($reply->date_delete) ?>
                            </small>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-warning mb-2" style="display: none;">
                            <small>
                                <strong><?= $reply->usernameModel->fio ?>:</strong>
                                <?= $reply->text ?>
                            </small>
                        </div>
                    <?php endif;  ?>
                </div>
            <?php endif;  ?>
            <?= Yii::$app->formatter->asHtml($model->text) ?>
            <br />
            <span class="small">
                
                <span class="text-secondary"><?= Yii::$app->formatter->asDatetime($model->date_create) ?></span>
                &nbsp;&nbsp;
                
                <?= Html::a('<i class="fas fa-share"></i> Ответить', ['/comment/create', 'hash'=>$hash, 'url'=>$url, 'idParent'=>$model->id, 'container'=>'container-'.$model->id], 
                    ['class'=>'link-create', 'data-container'=>'container-'.$model->id]) ?>&nbsp;&nbsp;
                
                <?php if ($model->isAuthor() || Yii::$app->user->can('admin')): ?>
                    <?= Html::a('<i class="fas fa-pencil-alt"></i> Изменить', ['/comment/update', 'id'=>$model->id, 'container'=>'container-'.$model->id], 
                        ['class'=>'link-update', 'data-container'=>'container-'.$model->id]) ?>&nbsp;&nbsp;
                    <?= Html::a('<i class="fas fa-trash-alt"></i> Удалить', ['/comment/delete', 'id'=>$model->id, 'container'=>'container-'.$model->id], 
                        ['class'=>'link-delete', 'data-container'=>'container-comment-index-'.$model->bind_hash]) ?>
                <?php endif; ?>

            </span>
        
        <?php endif; ?>

        <div id="container-<?= $model->id ?>" class="mt-2"></div>
    </div>    
</div>    