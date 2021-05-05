<?php
/* @var $this \yii\web\View */
/* @var $model \app\models\mentor\MentorPost */

use yii\helpers\Url;
use app\assets\ModalViewerAsset;
use yii\helpers\Html;

//ModalViewerAsset::register($this);

$url = Url::to(['mentor/view', 'id'=>$model->id]);

?>
<div class="left-panel" data-id="<?= $model->id ?>">    
    <div class="panel panel-default">
        <div class="panel-body vertical-align" style="background: #fbfbfb">
            <div class="col-sm-12" id="right-content">
                <div class="icerik-bilgi">
                    <a href="<?= $url ?>" class="mv-link" data-pjax="false">
                        <h4 style="color: #3B5998; font-weight: bold;"><?= $model->title ?></h4>
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
                        <i class="fa fa-clock"></i> <?= Yii::$app->formatter->asDatetime($model->date_update) ?>
                        <i class="fa fa-user-edit"></i> <?= $model->author ?>
                    </span>
                </div>
                <div>
                    <?php if (\app\models\mentor\MentorPost::isModerator()): ?>
                    <hr />
                    <div class="btn-group ">
                        <?= Html::a('Изменить', ['/mentor/update-post', 'id'=>$model->id], ['class' => 'btn btn-primary']) ?>
                        <?= Html::a('Удалить', ['/mentor/delete-post', 'id'=>$model->id], ['class' => 'btn btn-danger',
                            'data' => [
                                'confirm' => 'Вы уверены, что хотите удалить?',
                                'method' => 'post',
                            ],
                        ]) ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>   
</div>
<?php
$this->registerJs(<<<JS
    $('[data-toggle="tooltip"]').tooltip(); 
    modalViewer.bindLinks();
JS
);
?>
