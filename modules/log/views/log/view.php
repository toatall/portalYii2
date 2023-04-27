<?php

use app\modules\log\models\Log;
use yii\bootstrap5\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\modules\log\models\Log $model */

?>
<div class="log-view p-4">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'level',
            'category',
            [
                'attribute' => 'url',
                'format' => 'raw',
                'value' => function(Log $model) {
                    return Html::a($model->url, Url::to($model->url), 
                    [
                        'target' => '_blank',                                                
                    ]);
                },
            
            ],
            [
                'attribute' => 'statusCode',
                'format' => 'raw',
                'value' => function(Log $model) {
                    $firstNum = substr($model->statusCode, 0, 1);
                    $bg = 'secondary';
                    if (in_array($firstNum, ['2', '3'])) {
                        $bg = 'success';
                    }
                    elseif ($firstNum == '4') {
                        $bg = 'warning';
                    }
                    elseif ($firstNum == '5') {
                        $bg = 'danger';
                    }

                    return Html::tag('span', $model->statusCode, ['class' => "badge bg-{$bg} fs-6"]);
                }
            ], 
            'statusText:ntext',
            'user',
            'userModel.fio:ntext:Full name',
            'log_time:datetime',
            'prefix:ntext',            
            [
                'attribute' => 'message',
                'format' => 'raw',
                'value' => function(Log $model) {
                    return Html::tag('code', Html::tag('pre', $model->message));
                },
            ],
        ],
    ]) ?>

</div>
