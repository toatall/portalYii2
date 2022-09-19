<?php

use kartik\grid\ActionColumn;
use yii\bootstrap5\Html;
use kartik\grid\GridView;
use kartik\grid\SerialColumn;
use yii\helpers\Url;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

?>
<div class="book-shelf-place-index">

    <?php Pjax::begin(['id' => 'pjax-book-shelf-place', 'timeout' => false, 'enablePushState' => false]) ?>

    <p>
        <?= Html::a('Добавить', ['create'], ['class' => 'btn btn-outline-success btn-sm']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => SerialColumn::class],

            'id',            
            'place',
            'username',
            'usernameModel.fio',
            'date_create:datetime',
            [
                'class' => ActionColumn::class,
                'template' => '{update} {delete}',
                'buttons' => [                    
                    'update' => function($url, $model) {
                        return Html::a('<i class="fas fa-pencil-alt"></i>', ['update', 'id'=>$model->id], ['pjax' => true]);
                    },
                    'delete' => function($url, $model) {
                        return Html::a('<i class="fas fa-trash"></i>', ['delete', 'id'=>$model->id], [
                            'data' => [
                                'confirm' => 'Вы уверены, что хотите удалить?',
                                'method' => 'post',     
                                'pjax' => true,
                            ],
                        ]);
                    },
                ],
            ],
        ],
        'toolbar' => [
            '{export}',
            '{toggleData}',
        ],
        'export' => [
            'showConfirmAlert' => false,
        ],
        'panel' => [
            'type' => GridView::TYPE_DEFAULT,       
        ],
    ]); ?>
<?php 
$url = Url::to(['index']);
$this->registerJs(<<<JS
    $(document).off('pjax:success', '#pjax-book-shelf-place');
    $(document).on('pjax:success', '#pjax-book-shelf-place', function(event, data) {
        if ((data.toLowerCase() == 'ok') || (data.toLowerCase() == '<ok/>')) {
            $.pjax({ url: '$url', container: '#pjax-book-shelf-place', push: false });
        }
    });
JS); ?>
    <?php Pjax::end(); ?>

</div>
