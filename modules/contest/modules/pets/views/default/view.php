<?php

use app\modules\like\widgets\LikeWidget;
use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\modules\contest\modules\pets\models\Pets $model */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Pets', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pets-view">
    
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [            
            'pet_name',
            'pet_owner',
            'pet_age',
        ],
    ]) ?>

<?= LikeWidget::widget([
        'unique' => 'contest-pets-' . $model->id,
        'showLikers' => false,
        // 'disabled' => true,
        // 'btnLikeText' => '',
        // 'btnUnlikeText' => '',
        // 'btnLikeIcon' => '',
        // 'btnUnlikeIcon' => '',
        // 'showZero' => true,
    ]) ?>

    <div class="mt-3 row">
        <?php foreach ($model->getFiles() as $file): ?>
            <div class="col-6 mb-3 d-flex align-self-stretch">
                <div class="card shadow-lg rounded-lg">
                    <img src="<?= $file ?>" class="card-img" />
                    <!-- <div class="card-header">
                        <?= $model->pet_name ?><br />
                        <?= $model->pet_age ?>
                    </div> -->
                </div>
            </div>
        <?php endforeach; ?>
    </div>

</div>
