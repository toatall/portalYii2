<?php
/** @var \yii\web\View $this */
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
<h2 class="display-5 title mv-hide">Управление пользователями `<?= $model->title ?>`</h2>

<div id="div-form" class="card card-body mb-3" <?= $model->isNewRecord ?: 'style="display:none;"' ?>>

    <?php if ($model->isNewRecord): ?>
        <div class="alert alert-info">
            Роль "<?= $unique ?>" еще не создана!
        </div>
    <?php endif; ?>

    <?= $this->render('_form', ['model' => $model]) ?>

</div>

<?php if (!$model->isNewRecord): ?>

    <?php Pjax::begin(['id' => 'pjax-admin-grantaccess-index','timeout' => false, 'enablePushState' => false]) ?>
    
    <button class="btn btn-primary btn-sm" id="btn-update-form">
        <i class="fas fa-pencil"></i>
        Редактировать роль
    </button>

    <div class="mt-3">

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
                    'header' => Html::a('Добавить пользователя', ['/admin/user/index'], 
                        ['class'=>'btn btn-success btn-sm', 'id'=>'btn-add-user']),

                    'format' => 'raw',
                    'value' => function(\app\models\User $modelUser) use ($model) {
                        return Html::a('<i class="fas fa-trash"></i> Исключить', 
                            ['/admin/grantaccess/default/revoke-user', 'idUser' => $modelUser->id, 'idGroup' => $model->id],
                            ['class' => 'btn btn-danger btn-sm btn-user-delete']);
                    },                    
                ],
            ],
            'pager' => ['class' => LinkPager::class],
        ]) ?>       
    </div>

    <?php
    $groupId = $model->id;
    $urlAddUser = Url::to(['/admin/grantaccess/default/assign-user', 'idGroup' => $model->id]);
    $url = Url::to(['/admin/grantaccess/default/index', 'unique' => $model->unique]);
    $this->registerJs(<<<JS
        
        (function(){

            $('#btn-update-form').on('click', function(){
                $('#div-form').toggle()
            })
            $('#btn-form-cancel').on('click', function() {
                $('#div-form').hide()
            })

            function updateGridView() {
                $.pjax.reload({ container: '#pjax-admin-grantaccess-index', url: '$url', push: false, replace: false, timeout: false })
            }

            const modalViewerGrantAccessAdd = new ModalViewer({  
                enablePushState: false,
            });
            $(modalViewerGrantAccessAdd).on('onPortalSelectUser', function(event, data) {            
                $.post(UrlHelper.addParam('$urlAddUser', { idUser: data.id }))
                .done(function() {
                    updateGridView()
                    modalViewerGrantAccessAdd.closeModal()
                })            
            })
            $('#btn-add-user').on('click', function() {
                modalViewerGrantAccessAdd.showModal($(this).attr('href'), 'get', { 'UserSearch[excludeIdGroup]': $groupId }, true)
                return false
            })
            $('.btn-user-delete').on('click', function() {
                $.post($(this).attr('href'))
                .done(function() {
                    updateGridView()
                })
                return false
            })

        }())
                
    JS);
    ?>

    <?php Pjax::end() ?>

<?php endif; ?>