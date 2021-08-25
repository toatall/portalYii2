<?php
/** @var yii\web\View $this */

use yii\bootstrap4\Html;

$this->title = 'Наставничество';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="row mv-hide">
    <div class="col border-bottom mb-2">
        <p class="display-4">
        <?= $this->title ?>
        </p>    
    </div>    
</div>

<div class="list-group">
    <?= Html::a('Нормативно-правовая база', ['mentor/normative'], ['class'=>'list-group-item list-group-item-action']) ?>
    <?= Html::a('Рейтинг Инспекций по результатам осуществления наставничества', ['/tree/view', 'id'=>269], ['class'=>'list-group-item list-group-item-action']) ?>
    <?= Html::a('Доска почета Наставников', ['/tree/view', 'id'=>270], ['class'=>'list-group-item list-group-item-action']) ?>    
</div>
