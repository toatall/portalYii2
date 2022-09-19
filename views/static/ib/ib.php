<?php
/** @var yii\web\View $this */

use yii\bootstrap5\Tabs;

$this->title = 'Основы информационной безопасности';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="col border-bottom mb-2">
    <p class="display-5">
        <?= $this->title ?>
    </p>    
</div>

<?= Tabs::widget([
    'id' => 'tab_id_index',
    'items' => [
        [
            'label' => 'Нормативные документы',
            'content' => $this->render('_normative'),
            'active' => true,
        ],
        [
            'label'=>'Заявки',
            'content'=>$this->render('_zayavki'),
        ],
        [
            'label'=>'Тестирование',
            'content'=>$this->render('_test'),
        ],
        [
            'label'=>'Ежеквартальные отчеты',
            'content'=>$this->render('_reports'),
        ],
        [
            'label'=>'Реестр разрешенного программного обеспечения',
            'content'=>$this->render('_reestrPO'),
        ],
    ],
    'options' => [
        'class' => 'mt-3',
    ],
]);

$this->registerJs(<<<JS
    $('.accordion .show').collapse('hide');
JS); ?>
