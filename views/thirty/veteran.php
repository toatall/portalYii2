<?php
/* @var $this \yii\web\View */
/* @var $result array */
/* @var $model \app\models\thirty\ThirtyVeteran */

use yii\helpers\Html;
use app\assets\fancybox\FancyboxAsset;
FancyboxAsset::register($this);

$this->title = 'Поздравление ветеранов и заслуженных работников!';
$this->params['breadcrumbs'][] = ['label'=>'30-летие налоговых органов', 'url'=>['/thirty/index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="through-time">
    <h1 class="head mv-hide"><?= $this->title ?></h1>
    <hr />

    <?php foreach ($result as $org => $item): ?>
    <div class="panel panel-default">
        <div class="panel-heading"><?= $model->getOrgByCode($org) ?></div>
        <div class="panel-body">
            <?php foreach ($item as $x): ?>
                <div class="thumbnail" style="float:left;">
                    <?= Html::a(Html::img($x['file_name_thumb'], ['style'=>'height:200px;']), $x['file_name'], [
                        'target'=>'_blank',
                        'data-fancybox' => 'gallery',
                        'data-caption' => $x['description'],
                    ]) ?>
                    <div class="caption"><?= $x['description'] ?></div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endforeach; ?>
</div>