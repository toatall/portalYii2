<?php
/** @var \yii\web\View $this */
/** @var string $unique */
/** @var \app\modules\admin\modules\grantaccess\models\GrantAccessGroup $model */

use yii\helpers\Url;
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

    <button class="btn btn-primary btn-sm" id="btn-update-form">
        <i class="fas fa-pencil"></i>
        Редактировать роль
    </button>

    <div class="mt-3">
        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-header fw-bold">Пользователи</div>
                    <div id="div-users" class="card-body" data-url="<?= Url::to(['/admin/grantaccess/default/users', 'unique' => $model->unique ?? null]) ?>"></div>
                </div>
            </div>
            <div class="col">
                <div class="card">
                    <div class="card-header fw-bold">Роли ActiveDirectory</div>
                    <div id="div-ad-groups" class="card-body" data-url="<?= Url::to(['/admin/grantaccess/ad-group/index', 'unique' => $model->unique ?? null]) ?>"></div>
                </div>
            </div>
        </div>               
    </div>

    <?php 
    $this->registerJs(<<<JS
        
        $('#btn-update-form').on('click', function(){
            $('#div-form').toggle()
        })
        $('#btn-form-cancel').on('click', function() {
            $('#div-form').hide()
        })
        
        function loadContentByAjax(idDiv) {
            $(idDiv).html('<span class="spinner-border"></span>')
            const url = $(idDiv).data('url')
            $.get(url)
            .done(function(data) {
                $(idDiv).html(data)
            })
            .fail(function(err){
                $(idDiv).html('<div class="alert alert-danger">' + err.responseText + '</div>')                
            })
        }

        loadContentByAjax('#div-users')
        loadContentByAjax('#div-ad-groups')

    JS);?>

<?php endif; ?>