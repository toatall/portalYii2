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
    'id' => 'ib',
    'items' => [
        [
            'label' => 'Нормативные документы',
            'content' => $this->render('_normative'),
            'active' => true,
            'options' => ['id' => 'normative-docs'],
        ],
        [
            'label'=>'Заявки',
            'content'=>$this->render('_zayavki'),
            'options' => ['id' => 'zayavki'],
        ],
        [
            'label'=>'Тестирование',
            'content'=>$this->render('_test'),
            'options' => ['id' => 'tests'],
        ],
        [
            'label'=>'Ежеквартальные отчеты',
            'content'=>$this->render('_reports'),
            'options' => ['id' => 'reports'],
        ],
        [
            'label'=>'Реестр разрешенного программного обеспечения',
            'content'=>$this->render('_reestrPO'),
            'options' => ['id' => 'reestr-po'],
        ],
    ],
    'options' => [
        'class' => 'mt-3',
    ],
]);

$this->registerJs(<<<JS
    
   $('.accordion .show').collapse('hide');
    
    function openTabById(params) {
        let parts = params.split('=');
        $('#' + parts[0] + ' a[href="#' + parts[1] + '"]').tab('show');
    }
        
    function openCollapseById(param) {        
        $('#' + param).collapse('show');
        console.log('#' + param);
    }
        
    let hash = window.location.hash;
    if (hash.match('#')) {
        let hashData = hash.split('#')[1];
        let parts = hashData.split('&');
        if (parts[0] != null) {
            openTabById(parts[0]);
        }
        if (parts[1] != null) {
            openCollapseById(parts[1]);
        }
    }
        
JS); ?>
