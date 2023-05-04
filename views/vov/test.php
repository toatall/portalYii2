<?php
/** @var \yii\web\View $this */

use yii\helpers\Url;
// $this->registerJsFile('@web/js/test.js', ['depends' => 'app\assets\AppAsset']);
?>
<div class="test-container" data-href="<?= Url::to(['/test/default/view', 'id'=>5]) ?>"></div>
