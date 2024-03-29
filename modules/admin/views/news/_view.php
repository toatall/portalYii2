<?php

use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\news\News $model */

?>

<?= DetailView::widget([
    'model' => $model,
    'attributes' => [
        'id',
        'id_tree',
        'id_organization',
        'title',
        'message1:raw',
        'message2:raw',
        'author',
        // 'general_page:boolean',
        'date_start_pub',
        'date_end_pub',
        'date_sort:datetime',
        'flag_enable:boolean',
        'thumbail_image:image',
        'date_create:datetime',
        'date_edit:datetime',
        'date_delete:datetime',
        // 'log_change',
        // 'on_general_page:boolean',
        'count_like',
        'count_comment',
        'count_visit',
        'tags',        
    ],
]) ?>
