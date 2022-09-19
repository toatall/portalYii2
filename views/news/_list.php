<?php
/** @var yii\web\View $this */
/** @var app\models\news\News $model */

use yii\helpers\Url;
use app\helpers\DateHelper;
use yii\bootstrap5\Html;

$url = Url::to(['news/view', 'id'=>$model->id]);

?>
<div data-id="<?= $model->id ?>">
    <div class="card mt-2">
        <div class="card-body bg-light vertical-align">
            <div class="row">
                <div class="col-2">                
                    <a href="<?= $url ?>" class="mv-link" data-pjax="false">
                        <img src="<?= $model->getThumbnail() ?>" class="img-thumbnail" style="width: 100%;" />
                    </a>                 
                </div>
                <div class="col-10" id="right-content">
                    <?php if ($model->date_top != ''): ?>
                    <div style="float:right; color:#777; font-size:20px; margin-top: 10px;">
                        <i class="fa fa-thumbtack" data-toggle="tooltip" title="Закреплена до <?= $model->date_top ?>"></i>
                    </div>
                    <?php endif; ?>
                    <div>
                        <a href="<?= $url ?>" class="mv-link text-decoration-none" data-pjax="false">
                            <h4 class="news-title">
                                <?php if (DateHelper::dateDiffDays($model->date_sort) <= 1): ?>
                                    <span class="badge badge-success">Новое</span>
                                <?php endif ?>
                                <?= $model->title ?>
                            </h4>
                        </a>
                        <div class="icon-group">
                            <span class="badge badge-secondary fa-sm"><?= $model->count_like ?> <i class="fa fa-heart"></i></span>
                            <span class="badge badge-secondary fa-sm"><?= $model->count_comment ?> <i class="fa fa-comments"></i></span>
                            <span class="badge badge-secondary fa-sm"><?= $model->count_visit ?> <i class="fa fa-eye"></i></span>
                        </div>
                        <p class="lead"><?= $model->message1 ?></p>                    
                        <hr />
                        <div style="color:#444;">
                            <i class="fa fa-building"></i> <?= $model->organization->name ?> 
                            <?= !empty($model->from_department) ? '(' . $model->from_department . ')' : '' ?><br />
                            <i class="fa fa-clock"></i> <?= Yii::$app->formatter->asDatetime($model->date_edit) ?><br />                                             
                            <?= Html::a('<i class="fas fa-user-alt"></i> ' . $model->modelAuthor->fio, '/@' . $model->author, ['class' => 'author mv-link']) ?>
                        </div>
                        <div class="clearfix"></div>
                    </div>                 
                </div>   
            </div>         
        </div>
    </div>   
</div>
