<?php
/** @var \yii\web\View $this */
/** @var \app\models\mentor\MentorPost $model */

use yii\helpers\Url;
use yii\bootstrap4\Html;

$url = Url::to(['mentor/view', 'id'=>$model->id]);

?>
<div data-id="<?= $model->id ?>">
    <div class="card mt-2">
        <div class="card bg-light vertical-align">
            <div class="card-header">
                <a href="<?= $url ?>" class="mv-link" data-pjax="false">
                    <h4><?= $model->title ?></h4>
                </a>
            </div>
            <div class="card-body">
                <div class="icon-group">
                    <span class="badge badge-secondary fa-sm"><?= $model->count_like ?> <i class="fa fa-heart"></i></span>
                    <span class="badge badge-secondary fa-sm"><?= $model->count_comment ?> <i class="fa fa-comments"></i></span>
                    <span class="badge badge-secondary fa-sm"><?= $model->count_visit ?> <i class="fa fa-eye"></i></span>
                </div>
                <p><?= $model->message1 ?></p>                                      
                <div style="color:#666;">
                    <i class="fa fa-building"></i> <?= $model->organization->name ?><br />
                    <i class="fa fa-clock"></i> <?= Yii::$app->formatter->asDatetime($model->date_update) ?>
                    <i class="fa fa-user-edit"></i> <?= $model->author ?>
                </div>
            </div>
            <?php if (\app\models\mentor\MentorPost::isModerator()): ?>              
            <div class="card-footer">                      
                <div class="btn-group ">
                    <?= Html::a('Изменить', ['/mentor/update-post', 'id'=>$model->id], ['class' => 'btn btn-primary']) ?>
                    <?= Html::a('Удалить', ['/mentor/delete-post', 'id'=>$model->id], ['class' => 'btn btn-danger',
                        'data' => [
                            'confirm' => 'Вы уверены, что хотите удалить?',
                            'method' => 'post',
                        ],
                    ]) ?>
                </div>                    
            </div>
            <?php endif; ?>
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
