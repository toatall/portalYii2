<?php

use yii\helpers\Url;
use yii\bootstrap\Tabs;

/* @var $this yii\web\View */
/* @var $model \app\modules\test\models\Test */

?>

<div class="test-statistic">
    <?= Tabs::widget([
        'items' => [
            [
                'label' => 'Статистика по организациям',
                'content' => '<div class="container-statistic" data-href="' . Url::to(['/test/default/statistic-organizations', 'id'=>$model->id]) . '"></div>',
                'active' => true,
            ],
            [
                'label' => 'Статистика по сотрудникам',
                'content' => '<div class="container-statistic" data-href="' . Url::to(['/test/default/statistic-users', 'id'=>$model->id]) . '"></div>',
            ],
            [
                'label' => 'Статистика по ответам',
                'content' => '<div class="container-statistic" data-href="' . Url::to(['/test/default/statistic-answers', 'id'=>$model->id]) . '"></div>',
            ],
        ],
    ]) ?>
</div>
<?php
$this->registerJs(<<<JS
    $('.container-statistic').each(function() {
        var cont = $(this);
        cont.html('<i class="fas fa-spin fa-spinner"></i>');
        $.get($(this).data('href'))
        .done(function(data) {
            cont.html(data);
        })
        .fail(function (jqXHR) {
            cont.html('<div class="alert alert-danger">' + jqXHR.status + ' ' + jqXHR.statusText + '</div>');
        });
    });
JS
);
?>