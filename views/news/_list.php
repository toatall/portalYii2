<?php
/* @var $this \yii\web\View */
/* @var $model \app\models\news\News */

use yii\helpers\Url;
use app\assets\ModalViewerAsset;

//ModalViewerAsset::register($this);
$url = Url::to(['news/view', 'id'=>$model->id]);

?>
<div class="left-panel" data-id="<?= $model->id ?>">
    <div class="panel panel-default">
        <div class="panel-body vertical-align" style="background: #fbfbfb">
            <div class="col-sm-2 col-md-2 col-lg-2 left-content">
                <div class="thumbnail">
                   <a href="<?= $url ?>" class="mv-link" data-pjax="false">
                        <img src="<?= $model->getThumbnail() ?>" style="width: 100%;" />
                    </a> 
                </div>
            </div>
            <div class="col-sm-10 col-md-10 col-lg-10" id="right-content">
                <?php if ($model->date_top != ''): ?>
                <div style="float:right; color:#777; font-size:20px; margin-top: 10px;">
                	<i class="fa fa-thumbtack" data-toggle="tooltip" title="Закреплена до <?= $model->date_top ?>"></i>
            	</div>
                <?php endif; ?>
                <div class="icerik-bilgi">
                    <a href="<?= $url ?>" class="mv-link" data-pjax="false">
                        <h4 style="color: #3B5998; font-weight: bold;">
                            <?php if (\app\helpers\DateHelper::dateDiffDays($model->date_sort) <= 0): ?>
                                <span class="label label-success">Новое</span>
                            <?php endif ?>
                            <?= $model->title ?>
                        </h4>
                    </a>
                    <div class="icon-group">
                        <span class="label label-default"><?= $model->count_like ?> <i class="fa fa-heart"></i></span>
                        <span class="label label-default"><?= $model->count_comment ?> <i class="fa fa-comments"></i></span>
                        <span class="label label-default"><?= $model->count_visit ?> <i class="fa fa-eye"></i></span>
                    </div>
                    <p><?= $model->message1 ?></p>                    
                    <hr />
                    <span style="color:#666; font-size:12px;">
                        <i class="fa fa-building"></i> <?= $model->organization->name ?><br />
                        <?= !empty($model->from_department) ? '(' . $model->from_department . ')' : '' ?><br />
                        <i class="fa fa-clock"></i> <?= $model->date_edit ?>
                        <i class="fa fa-user-edit"></i> <?= $model->author ?>
                    </span>
                    <div class="clearfix"></div>
                </div>                 
            </div>            
        </div>
    </div>   
</div>
<?php
$this->registerJs(<<<JS
    //$('[data-toggle="tooltip"]').tooltip(); 
    //modalViewer.bindLinks();         
JS
);
?>
