<?php
/** @var \yii\web\View $this */
/** @var string $url */
/** @var string $container */


$this->registerJs(<<<JS
    $.pjax({ url: '$url', container: '$container', replaceRedirect: false, push: false });
JS);

?>
<div class="spinner-border text-secondary" role="status">
    <span class="sr-only">Загрузка...</span>
</div>
