<?php
/* @var $this \yii\web\View */

use yii\helpers\Url;
use app\modules\test\assets\TestAsset;

TestAsset::register($this);
?>
<div class="test-container" data-href="<?= Url::to(['/test/default/view', 'id'=>5]) ?>"></div>
