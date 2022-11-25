<?php
/** @var yii\web\View $this */
/** @var app\models\conference\AbstractConference $model */
/** @var string $action */

use yii\widgets\DetailView;

?>

<div class="mt-2">
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'organization.fullName:text:Налоговый орган',
            'date_start:datetime',   
            'duration',  
            'theme',     
            'date_create:datetime',
            'date_edit:datetime',
            'author',       
        ],
    ]) ?>
</div>