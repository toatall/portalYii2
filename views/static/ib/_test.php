<?php
/* @var $this \yii\web\View */

use yii\helpers\Url;
use app\modules\test\assets\TestAsset;
TestAsset::register($this);

$tests = [
    // Материалы для государственных служащих
    6 => Url::to(['/news/view', 'id'=>8526]),
    // Материалы для руководящего состава
    7 => Url::to(['/news/view', 'id'=>8527]),
    // Материалы для специалистов по информационным технологиям
    8 => Url::to(['/news/view', 'id'=>8528]),
    // Материалы для специалистов по информационой безопасности
    9 => Url::to(['/news/view', 'id'=>8529]),
];
?>

<div class="well">
    <?php foreach ($tests as $id=>$link): ?>
    <div class="test-container" data-href="<?= Url::to(['/test/default/view', 'id'=>$id]) ?>" data-material-url="<?= $link ?>"></div>
    <?php endforeach; ?>    
    <div class="test-container" data-href="<?= Url::to(['/test/default/view', 'id'=>18]) ?>"></div>
</div>
<?php $this->registerJS(<<<JS
    $(window.portalTest).on('onRequestDone', function(event, data, container) {
        container.find('div.btn-group').append('<a href="' + container.data('material-url') + '" class="btn btn-primary mv-link"><i class="fas fa-question-circle"></i> Материалы</a>');
    });
JS
);
?>