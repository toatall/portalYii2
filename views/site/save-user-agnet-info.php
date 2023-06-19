<?php
/** @var \yii\web\View $this */

use yii\helpers\Url;

$this->title = 'Вход';
$this->registerJsFile('/public/assets/portal/js/urlHelper.js');
$url = Url::to(['', 'forward' => 1]);
?>
<div class="alert alert-info lead">
    Вход выполнен!
    <br />
    <a href="<?= $url ?>" class="btn btn-secondary mt-3">На главную страницу</a>
</div>
<?php
$this->registerJs(<<<JS

    const url = UrlHelper.addParam('$url', {
        width: screen.width ?? null,
        height: screen.height ?? null,
    })    
    window.location = url    

JS);