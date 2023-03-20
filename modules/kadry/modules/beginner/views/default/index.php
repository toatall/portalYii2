<?php

use app\modules\kadry\modules\beginner\models\Beginner;
use yii\bootstrap5\Html;
use yii\bootstrap5\LinkPager;
use yii\helpers\Url;
use app\helpers\ImageHelper;

/** @var yii\web\View $this */
/** @var app\modules\kadry\modules\beginner\models\BeginnerSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Давайте знакомиться';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="beginner-index">
    <p class="display-5 border-bottom"><?= $this->title ?></p>

    <?php if (Beginner::isRoleModerator()): ?>
        <?= Html::a('Добавить', ['create'], ['class' => 'btn btn-success']) ?>
    <?php endif; ?>

    <div class="row row-cols-1 mt-4">
    <?php foreach($dataProvider->getModels() as $item): 
        /** @var Beginner $item */
        ?>        
            <div class="col-3 mb-3">
                <a href="<?= Url::to(['view', 'id' => $item->id]) ?>" class="text-decoration-none text-black mv-link">
                <div class="h-100">                  
                    <div class="col card h-100 shadow-sm">
                        <div class="card-header text-center">
                            <?= Html::img(ImageHelper::findThumbnail($item->getThumbImage(), picImageNotFound: '/img/no_image_available.jpeg'), ['class' => 'img-thumbnail', 'style' => 'height: 20vh; margin: 0 auto;']) ?>
                        </div>
                        <div class="card-body text-center">
                            <strong><?= $item->fio ?></strong><br />
                            <?php if ($item->date_employment): ?>
                            Работает с <?= Yii::$app->formatter->asDate($item->date_employment) ?>
                            <?php endif; ?>
                        </div>
                        <?php if (Beginner::isRoleModerator()): ?>
                        <div class="card-footer">
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
                </div>
                </a>
            </div>        

    <?php endforeach; ?>
    </div>

    <div class="align-content-center">
        <?= LinkPager::widget([
            'pagination' => $dataProvider->pagination,            
        ]) ?>
    </div>
    
</div>
