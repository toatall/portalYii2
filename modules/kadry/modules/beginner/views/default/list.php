<?php
/** @var \app\modules\kadry\modules\beginner\models\Beginner[] $models */
/** @var \yii\web\View $this */

use app\helpers\DateHelper;
use app\helpers\ImageHelper;
use app\modules\kadry\modules\beginner\models\Beginner;
use yii\bootstrap5\Html;
use yii\helpers\Url;

?>

<?php if ($models): ?>
<div class="row mt-4">
    <?php foreach($models as $item): ?>           
        <div class="col-3 mb-3">
            <a href="<?= Url::to(['view', 'id' => $item->id]) ?>" class="text-decoration-none text-black mv-link">
                <div class="card shadow-sm position-relative d-flex align-self-stretch h-100">
                    <?php /* if (DateHelper::dateDiffDays($item->date_employment) < 30): ?>                        
                        <span class="position-absolute translate-middle p-2 bg-success badge" style="top: 1rem; right: 0rem;">
                            Новый сотрудник
                        </span>
                    <?php endif;*/ ?>
                    <div class="card-header text-center">
                        <?= Html::img(ImageHelper::findThumbnail($item->getThumbImage(), picImageNotFound: '/img/no_image_available.jpeg'), [
                            'class' => 'img-thumbnail', 'style' => 'height: 20vh; margin: 0 auto;'
                        ]) ?>
                    </div>
                    <div class="card-body text-center">
                        <p class="lead"><?= $item->departmentModel->organization->fullName ?></p>
                        <p><?= $item->departmentModel->getConcatened() ?></p>                            
                        <hr />
                        <strong><?= $item->fio ?></strong><br />
                        <?php if ($item->date_employment): ?>
                        Работает с <?= Yii::$app->formatter->asDate($item->date_employment) ?>
                        <?php endif; ?>
                    </div>
                    <?php if (Beginner::isRoleModerator()): ?>
                    <div class="card-footer h-100">
                        <div class="btn-group">
                            <?= Html::a('Изменить', ['update', 'id'=>$item->id], ['class' => 'btn btn-primary btn-sm']) ?>
                            <?= Html::a('Удалить', ['delete', 'id'=>$item->id], [
                                'class' => 'btn btn-danger btn-sm',
                                'data' => [
                                    'confirm' => 'Вы уверены, что хотите удалить?',
                                    'method' => 'post',
                                ],
                            ]) ?>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </a>
        </div>        
    <?php endforeach; ?>
</div>
<?php else: ?>
<div class="alert alert-info border">
    Нет данных
</div>
<?php endif; ?>