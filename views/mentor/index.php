<?php
/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = 'Наставничество';
$this->params['breadcrumbs'][] = $this->title;

?>
<h1 style="font-weight: bolder;"><?= $this->title ?></h1>
<ul>
    <li>
        <?= Html::a('Нормативно-правовая база', ['mentor/normative']) ?>
    </li>
    <li>
        <?= Html::a('Рейтинг Инспекций по результатам осуществления наставничества', ['/tree/view', 'id'=>269]) ?>
    </li>
    <li>
        <?= Html::a('Доска почета Наставников', ['/tree/view', 'id'=>270]) ?>
    </li>
</ul>
