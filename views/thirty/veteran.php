<?php
/** @var \yii\web\View $this */
/** @var array $result */
/** @var \app\models\thirty\ThirtyVeteran $model */

use yii\bootstrap5\Html;
use app\assets\fancybox\FancyboxAsset;
FancyboxAsset::register($this);

$this->title = 'Поздравление ветеранов и заслуженных работников!';
$this->params['breadcrumbs'][] = ['label'=>'30-летие налоговых органов', 'url'=>['/thirty/index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="through-time">
    <div class="row mv-hide">
        <div class="col border-bottom mb-2">
            <p class="display-4">
            <?= $this->title ?>
            </p>    
        </div>    
    </div>

    <?php foreach ($result as $org => $item): ?>
    <div class="card mb-2 ">
        <div class="card-header"><?= $model->getOrgByCode($org) ?></div>
        <div class="card-body">
            <div class="row">
            <?php foreach ($item as $x): ?>
                <div class="col-2 mb-2 card-deck">
                    <div class="card">
                        <?= Html::a(Html::img($x['file_name_thumb'], ['class'=>'card-img']), $x['file_name'], [
                            'target'=>'_blank',
                            'data-fancybox' => 'gallery',
                            'data-caption' => $x['description'],
                        ]) ?>
                        <div class="card-footer">
                            <?= $x['description'] ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>