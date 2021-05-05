<?php

use app\modules\test\assets\TestAsset;
use yii\helpers\Url;

TestAsset::register($this);

/* @var $this yii\web\View */
/* @var $result array */

$this->title = 'Тесты';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="test-default-index">
    <h1><?= $this->title ?></h1>

    <?php foreach ($result as $item): ?>
    <div class="test-container" data-href="<?= Url::to(['/test/default/view', 'id'=>$item['id']]) ?>"></div>
    <?php endforeach; ?>
</div>
