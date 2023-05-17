<?php
/** @var \yii\web\View $this */
/** @var int $idGroup */
/** @var string $unique */
/** @var \app\modules\admin\modules\grantaccess\models\GrantAccessGroup $model */
/** @var \yii\data\ActiveDataProvider $dataProvider */
/** @var \app\models\UserSearch $searchModel */

use yii\bootstrap5\Html;
use yii\bootstrap5\LinkPager;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\Pjax;

?>

<?php Pjax::begin(['id' => 'pjax-admin-grantaccess-index-users','timeout' => false, 'enablePushState' => false]) ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            // 'id',
            'default_organization:text:Код НО',
            'username',
            'fio',
            'department',
            [
                'header' => Html::a('<i class="fas fa-plus"></i>', ['/admin/user/index'], 
                    ['class'=>'btn btn-success btn-sm', 'id'=>'btn-user-add']),

                'format' => 'raw',
                'value' => function(\app\models\User $modelUser) use ($idGroup) {
                    return Html::button('<i class="fas fa-trash"></i>',                         
                        [
                            'class' => 'btn btn-danger btn-sm btn-user-delete',
                            'data-url' => Url::to(['/admin/grantaccess/default/revoke-user', 'idUser' => $modelUser->id, 'idGroup' => $idGroup]),                        
                        ]);
                },                    
            ],
        ],
        'pager' => ['class' => LinkPager::class],
    ]) ?>
    

<?php
    $urlAddUser = Url::to(['/admin/grantaccess/default/assign-user', 'idGroup' => $idGroup]);
    $urlUsers = Url::to(['/admin/grantaccess/default/users', 'unique' => $unique]);
    $this->registerJs(<<<JS
        
        // users
        (function(){

            function updateGridViewUsers() {
                $.pjax.reload({ container: '#pjax-admin-grantaccess-index-users', url: '$urlUsers', push: false, replace: false, timeout: false })
            }

            const modalViewerGrantAccessUsers = new ModalViewer({  
                enablePushState: false,
            });
            $(modalViewerGrantAccessUsers).on('onPortalSelectUser', function(event, data) {            
                $.post(UrlHelper.addParam('$urlAddUser', { idUser: data.id }))
                .done(function() {
                    updateGridViewUsers()
                    modalViewerGrantAccessUsers.closeModal()
                })            
            })
            $('#btn-user-add').on('click', function() {
                modalViewerGrantAccessUsers.showModal($(this).attr('href'), 'get', { 'UserSearch[excludeIdGroup]': $idGroup }, true)
                return false
            })
            $('.btn-user-delete').on('click', function() {
                if (!confirm('Вы уверены, что хотите исключить пользователя из группы?')) {
                    return false
                }
                $(this).prop('disabled', true)
                $(this).append(' <span class="spinner-border spinner-border-sm"></span>')
                $.post($(this).data('url'))
                .done(function() {
                    updateGridViewUsers()
                })
                .always(function(){
                    $(this).children('span.spinner-border').remove()
                    $(this).prop('disabled', false)
                })
                return false
            })

        }())       
                
    JS);
    ?>    
<?php Pjax::end() ?>