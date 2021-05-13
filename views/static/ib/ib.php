<?php
/** @var $this \yii\web\View */

use kartik\tabs\TabsX;
use app\assets\SpoilerAsset;
SpoilerAsset::register($this);

$this->title = 'Основы информационной безопасности';
$this->params['breadcrumbs'][] = $this->title;
?>
<h1 class="head"><?= $this->title ?></h1>

<div style="padding-right: 10px;">
    <?= TabsX::widget([
        'id' => 'tab_ib_index',
        'items' => [
            [
                'label' => 'Нормативные документы',
                'content' => $this->renderAjax('_normative'),
                'active' => true,
            ],
            [
                'label'=>'Заявки',
                'content'=>$this->renderAjax('_zayavki'),
            ],
            [
                'label'=>'Тестирование',
                'content'=>$this->render('_test'),
            ],
            [
                'label'=>'Ежеквартальные отчеты',
                'content'=>$this->render('_reports'),
            ],
        ],
    ])
    ?>
</div>

