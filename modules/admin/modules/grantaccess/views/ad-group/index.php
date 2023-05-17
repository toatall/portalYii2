<?php
/** @var \yii\web\View $this */
/** @var \yii\data\ActiveDataProvider $dataProvider */
/** @var int $idGroup */

use app\modules\admin\modules\grantaccess\models\GrantAccessGroupAdGroup;
use yii\bootstrap5\Html;
use yii\bootstrap5\LinkPager;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\Pjax;

?>
<?php Pjax::begin(['id' => 'pjax-admin-grantaccess-index-adgroups','timeout' => false, 'enablePushState' => false]) ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'id',
            'group_name',
            'date_create:datetime',
            [
                'header' => Html::a('<i class="fas fa-plus"></i>', ['/admin/grantaccess/ad-group/create', 'idGroup' => $idGroup], 
                    ['class'=>'btn btn-success btn-sm', 'id'=>'btn-adgroup-add']),

                'format' => 'raw',
                'value' => function(GrantAccessGroupAdGroup $modelGroup) {
                    return 
                    Html::beginTag('div', ['class' => 'btn-group']) .
                        Html::a('<i class="fas fa-pencil"></i>', ['/admin/grantaccess/ad-group/update', 'id'=>$modelGroup->id], [
                            'class' => 'btn btn-primary btn-sm btn-adgroup-update',
                        ]) .
                        Html::a('<i class="fas fa-trash"></i>', 
                            ['/admin/grantaccess/ad-group/delete', 'id' => $modelGroup->id],
                            ['class' => 'btn btn-danger btn-sm btn-adgroup-delete']) .
                    Html::endTag('div');
                },
            ],
        ],
        'pager' => ['class' => LinkPager::class],
    ]) ?>
    

    <?php   
    $url = Url::to(['/admin/grantaccess/ad-group/index', 'unique' => $unique]);
    $this->registerJs(<<<JS
        
        // ad-groups
        (function(){

            function updateGridViewAdGroup() {
                $.pjax.reload({ container: '#pjax-admin-grantaccess-index-adgroups', url: '$url', push: false, replace: false, timeout: false })
            }

            const modalViewerGrantAccessAdGroup = new ModalViewer({  
                enablePushState: false,
            })

            $(modalViewerGrantAccessAdGroup).on('onRequestJsonAfterAutoCloseModal', function() {            
                updateGridViewAdGroup()        
            })

             $('#btn-adgroup-add').on('click', function() {
                modalViewerGrantAccessAdGroup.showModal($(this).attr('href'), 'get', {}, true)
                return false
            })

            $('.btn-adgroup-update').on('click', function() {
                modalViewerGrantAccessAdGroup.showModal($(this).attr('href'), 'get', {}, true)
                return false
            })

            $('.btn-adgroup-delete').on('click', function() {
                if (!confirm('Вы уверены, что хотите удалить?')) {
                    return false
                }
                $.post($(this).attr('href'))
                .done(function() {
                    updateGridViewAdGroup()
                })
                return false
            })

        }())       
                
    JS);
    ?>     
<?php Pjax::end() ?>
