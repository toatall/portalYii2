<?php
/* @var $this yii\web\View */

use kartik\tabs\TabsX;
use yii\helpers\Url;

$this->title = 'Проект "Помним! Гордимся!"';
$this->params['breadcrumbs'][] = $this->title;

?>
<h1 class="display-5 border-bottom"><?= $this->title ?></h1>

<?= TabsX::widget([
    'id' => 'tabs_general',
    'items' => [
        [
            'label' => 'Летопись Войны',
            'content' => '<div class="container-vov" data-href="' . Url::to(['vov/news']) . '"></div>',
            'active' => true,
        ],
        [
            'label' => 'Война в лицах',
            'items' => [
                [
                    'label' => 'Новости',
                    'content' => '<div class="container-vov" data-href="' . Url::to(['vov/face-news']) . '"></div>',
                ],
                [
                    'label' => 'Фотографии',
                    'content' => '<div class="container-vov" data-href="' . Url::to(['vov/face-carousel']) . '"></div>',
                ],
            ],
        ],
        // [
        //     'label' => 'Тестирование',
        //     'content' => '<div class="container-vov" data-href="' . Url::to(['vov/test']) . '"></div>',
        // ],
        [
            'label' => 'Живые строки войны',
            'content' => '<div class="container-vov" data-href="' . Url::to(['vov/live-rows-war']) . '"></div>',
        ],
    ],
    //'position' => TabsX::POS_LEFT,
]) ?>
<?php
$this->registerJs(<<<JS
    $('.container-vov').each(function() {
        let cont = $(this);
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
