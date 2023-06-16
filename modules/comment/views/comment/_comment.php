<?php
use yii\bootstrap5\Html;

/** @var yii\web\View $this */
/** @var app\modules\comment\models\Comment $model */
/** @var string $hash */
/** @var string $url */
/** @var string $modelName */
/** @var int $modelId */

$userModel = $model->usernameModel;
?>

<div class="row mb-4 pb-2">
    <div style="max-width: 7rem;">
        <a href="/@<?= $userModel->username ?>" target="_blank">
            <img src="<?= $userModel->getPhotoProfile() ?>" class="img-thumbnail w-100"
            data-content="<?= $userModel->fio ?>" data-toggle="popover" data-trigger="hover" />
        </a>
    </div>
    <div class="col">  
        <div class="card">
            <div class="card-header">                
                <?= Html::a($userModel->fio . " (@{$userModel->username})", '/@' . $userModel->username, [
                    'data-content' => $userModel->organization_name, 
                    'data-toggle' => 'popover',
                    'data-trigger' => 'hover',
                    'target' => '_blank',        
                    'class' => 'author fw-bold',
                ]) ?>
                <small>
                <br /><?= $userModel->organization_name ?? '' ?>        
                <?= $userModel->department ? ' (' . $userModel->department . ')' : '' ?> 
                </small>
            </div>
            <div class="card-body">
                <?php if ($model->date_delete): ?>
                    <div class="text-danger pt-2" title="Дата удаления: <?= Yii::$app->formatter->asDatetime($model->date_delete) ?>">
                        <i class="fas fa-ban"></i> Комментарий был удален 
                    </div>
                    <?php else: ?>
                        <?php if (($reply = $model->reply) != null): ?>
                            <div class="reply">
                                <?= Html::a('<i class="fas fa-reply"></i> Показать родительский комментарий', '', ['class'=>'link-reply', 'style'=>'font-size: 0.8rem;']) ?>
                                <?php if ($reply->date_delete): ?>
                                    <div class="alert alert-light text-danger mb-2" style="display: none;">
                                        <small title="<?= Yii::$app->formatter->asDatetime($reply->date_delete) ?>">
                                            Комментарий был удален
                                        </small>
                                    </div>
                                <?php else: ?>
                                    <div class="alert alert-warning mb-2" style="display: none;">
                                        <small>
                                            <strong><?= $reply->usernameModel->fio ?>:</strong>
                                            <?= $reply->text ?>
                                        </small>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endif;  ?>
                        <?= Yii::$app->formatter->asHtml($model->text) ?>
                        <br />
                        <span class="small">                
                            <span class="text-secondary">
                                <i class="far fa-clock"></i>
                                <?= Yii::$app->formatter->asDatetime($model->date_create) ?>                          
                                <?php if ($model->date_create != $model->date_update): ?>
                                    (изменено: <?= Yii::$app->formatter->asDatetime($model->date_update) ?>)
                                    &nbsp;&nbsp;
                                <?php endif; ?>
                            </span>  
                            
                            <?= Html::a('<i class="fas fa-share"></i> Ответить', 
                                ['/comment/create', 'hash'=>$hash, 'url'=>$url, 'idParent'=>$model->id, 'container'=>'container-'.$model->id, 'modelName'=>$modelName, 'modelId'=>$modelId], 
                                ['class'=>'link-create', 'data-container'=>'container-'.$model->id]) ?>&nbsp;&nbsp;
                            
                            <?php if ($model->isAuthor() || Yii::$app->user->can('admin')): ?>
                                <?= Html::a('<i class="fas fa-pencil-alt"></i> Изменить', ['/comment/update', 'id'=>$model->id, 'container'=>'container-'.$model->id], 
                                    ['class'=>'link-update', 'data-container'=>'container-'.$model->id]) ?>&nbsp;&nbsp;
                                <?= Html::a('<i class="fas fa-trash-alt"></i> Удалить', ['/comment/delete', 'id'=>$model->id, 'container'=>'container-'.$model->id], 
                                    ['class'=>'link-delete', 'data-container'=>'container-comment-index-'.$model->bind_hash]) ?>
                            <?php endif; ?>

                        </span>
                    
                    <?php endif; ?>
                </div>
            </div>                      
        <div id="container-<?= $model->id ?>" class="mt-2"></div>
    </div>    
</div>    