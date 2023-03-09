<?php

use app\modules\kadry\modules\beginner\models\Beginner;
use yii\bootstrap5\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;
/** @var yii\web\View $this */
/** @var app\modules\kadry\modules\beginner\models\BeginnerSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Давайте знакомиться';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="beginner-index">

    
    <p class="display-5 border-bottom"><?= $this->title ?></p>

    <?php if (Beginner::isRoleModerator()): ?>
        <?= Html::a('Добавить', ['create'], ['class' => 'btn btn-success mv-link']) ?>
    <?php endif; ?>

    <?php Pjax::begin(); ?>

    <div class="row row-cols-1 mt-4">
    <?php foreach($dataProvider->getModels() as $item): 
        /** @var Beginner $item */
        ?>        
            <div class="col-3 mb-3">
                <a href="<?= Url::to(['view', 'id' => $item->id]) ?>" class="text-decoration-none text-black mv-link">
                <div class="h-100">                  
                    <div class="col card h-100 shadow-sm">
                        <div class="card-header text-center">
                            <?= Html::img($item->getThumbImage(), ['class' => 'img-thumbnail', 'style' => 'height: 20vh; margin: 0 auto;']) ?>
                        </div>
                        <div class="card-body text-center">
                            <strong><?= $item->fio ?></strong><br />
                            <?php if ($item->date_employment): ?>
                            Работает с <?= Yii::$app->formatter->asDate($item->date_employment) ?>
                            <?php endif; ?>
                        </div>
                        
                    </div>
                </div>
                </a>
            </div>
        

    <?php endforeach; ?>
    </div>
    
    <?php Pjax::end(); ?>

</div>