<?php
/** @var \yii\web\View $this */


$this->title = 'Геральдический знак';
$this->params['breadcrumbs'][] = ['label' => 'Проекты', 'url' => ['/project']];
$this->params['breadcrumbs'][] = ['label'=>'30-летие налоговых органов', 'url'=>['/project/thirty/default/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="through-time">
<h1 class="head mv-hide"><?= $this->title ?></h1>

<div class="text-justify">

    <p>Приказом Министерства финансов Российской Федерации от 29.10.2019 № 169н учреждены геральдический знак – эмблема и флаг
        Федеральной налоговой службы и утверждено Положение о геральдическом знаке – эмблеме и флаге Федеральной налоговой службы.</p>
    <a href="/files_static/thirty/Приказ Минфина России от 29.10.2019 №169Н.pdf" target="_blank" class="btn btn-default">
        <i class="fas fa-download"></i> Открыть приказ Минфина России от 29.10.2019 № 169Н
    </a>

    <hr />
    <div class="row mt-2">
        <div class="col">
            <a href="/files_static/thirty/gerbText0001.jpg" data-fancybox="gallery">
                <img src="/files_static/thirty/gerbText0001.jpg" class="img-thumbnail" style="width: 400px;" data-caption="" />
            </a>
        </div>
        <div class="col">
            <a href="/files_static/thirty/gerbText0002.jpg" data-fancybox="gallery">
                <img src="/files_static/thirty/gerbText0002.jpg" class="img-thumbnail" style="width: 400px;" data-caption="" />
            </a>
        </div>        
    </div>
</div>
<?php $this->registerJs(<<<JS
    Fancybox.bind('[data-fancybox]', {});
JS); ?>