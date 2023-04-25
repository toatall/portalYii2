<?php

use app\modules\admin\models\FooterData;
use yii\bootstrap5\Html;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var int $idType */

$this->title = 'Ссылки';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="footer-type-index mt-2 card card-body">

    <?php Pjax::begin(['id'=>'pjax-footer-data-gridview', 'timeout'=>false, 'enablePushState' => false]); ?>
   
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'id',
            [
                'format' => 'raw',
                'value' => function(FooterData $model) {
                    if ($model->target == '_blank') {
                        return Html::tag('span', '<i class="fas fa-window-restore"></i>', [
                            'class' => 'badge bg-primary',
                            'data-bs-toggle' => 'tooltip',
                            'title' => 'Открывать в новом окне',
                        ]);
                    }
                    return '';
                },
            ],
            [
                'attribute' => 'url',
                'format' => 'raw',
                'value' => function(FooterData $model) {
                    return Yii::$app->formatter->asUrl($model->url, [
                        'target' => '_blank',
                    ]);
                },
            ],
            'text',            
            'date_create:datetime',
            [
                'class' => ActionColumn::class,
                'header' => Html::a('<i class="fas fa-plus-circle"></i>', ['/admin/footer-data/create', 'idType' => $idType, 'pjax' => true], 
                    ['id' => 'btn-create', 'class' => 'btn btn-success btn-sm btn-create']),
                'template' => '{update} {delete}',                
                'buttons' => [
                    'update' => function($url, $model, $key) {
                        return Html::a('<i class="fas fa-pencil"></i>', ['update', 'id' => $model->id], ['pjax' => true]);
                    },
                    'delete' => function($url, $model, $key) {
                        return Html::a('<i class="fas fa-trash"></i>', ['delete', 'id' => $model->id], [
                            'class' => 'btn-delete',
                        ]);
                    },                    
                ],
            ],            
        ],
    ]); ?>

<?php $this->registerJs(<<<JS
    

    (function(){

        $('#pjax-footer-data-gridview [data-bs-toggle="tooltip"]').tooltip()

        $('#pjax-footer-data-gridview .btn-delete').on('click', function() {
            if (!confirm('Вы уверены, что хотите удалить ссылку?')) {
                return
            }
            const url = $(this).attr('href')
            $.post(url).done(function() {                 
                $('#select-type').trigger('change')
             })
            return false
        })
        
    }())

JS) ?>

    <?php Pjax::end() ?>

</div>
