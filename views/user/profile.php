<?php

/** @var yii\web\View $this */

use kartik\file\FileInput;
use kartik\growl\Growl;
use yii\bootstrap5\Html;
use yii\widgets\DetailView;
use yii\widgets\Pjax;

/** @var app\models\User $model */

$this->title = "Профиль \"{$model->fio}\"";
$photoIsLoad = ($model->photo_file != null);
$photoFile = $model->getPhotoProfile();
if (Yii::$app->request->isAjax) {
    $photoFile .= '?' . time();
}
?>

<div class="col mb-2">
    <p class="display-5"><?= $this->title ?></p>
</div>

<?php Pjax::begin(['id'=>'ajax-profile-photo', 'timeout'=>false, 'enablePushState'=>false]); ?>

<?php
    foreach (Yii::$app->session->getAllFlashes() as $type => $flash) {
        echo Growl::widget([            
            'type' => $type,
            'body' => $flash,            
        ]);
    }

?>

<div class="card">
    <div class="card-body">
        <div class="row">            
            <div class="col-4 text-center">                                
                <?= Html::img($photoFile, ['class' => 'img-thumbnail']) ?>
                <div id="div-file-input" class="row mt-2" style="<?= $photoIsLoad ? 'display: none;' : '' ?>">
                    <div class="col">
                        <?= Html::beginForm(['/user/profile'], 'post', ['data-pjax' => 1, 'enctype' => 'multipart/form-data']) ?>
                        <?= FileInput::widget([
                            'name' => 'photo',
                            'pluginOptions' => [
                                'showPreview' => false,
                                'captionClass' => 'form-control-sm',
                                'browseClass' => 'btn btn-primary btn-sm',                                
                                'uploadClass' => 'btn btn-secondary btn-sm',
                                'removeClass' => 'btn btn-secondary btn-sm',
                                'theme' => 'fa5',
                            ],
                            'options' => [
                                'accept' => 'images/*',
                            ],                            
                        ]) ?>
                        <?= Html::endForm() ?>
                    </div>                                      
                </div>
                <div class="row mt-2" style="<?= $photoIsLoad ? '' : 'display: none;' ?>">
                    <div class="col">
                        <div class="btn-group">
                            <?= Html::button('Загрузить другое фото', ['class' => 'btn btn-primary', 'id'=>'btn-upload-other-photo']) ?>
                            <?= Html::a('Удалить загруженное фото', ['/user/profile', 'deletePhoto'=>true], [
                                'class' => 'btn btn-danger', 
                                'data' => [
                                    'method' => 'post',
                                    'confirm' => 'Вы уверены, что хотите удалить фотографию?',  
                                ],
                                'data-pjax' => 1,
                            ]) ?>
                        </div>
                    </div>  
                </div>
<?php $this->registerJs(<<<JS
    $('#btn-upload-other-photo').on('click', function() {
        $('#div-file-input').show();
        $(this).hide();
    });
JS); ?> 
            </div>
            
            <div class="col">                   
                <?= DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        'username',
                        'fio',
                        'default_organization:text:Код организации',
                        'organization_name',
                        'department',
                        'post',
                        'telephone',
                        'mail_ad',
                        'room_name_ad',
                        'description_ad',
                        'last_login:datetime',
                        'date_create:datetime:Дата регистрации',
                        'date_update_ad:datetime:Дата последнего обновления',
                    ],
                ]) ?>                
                <?= Html::a('Обновить информацию', ['/user/profile', 'updateInformation'=>true], ['class' => 'btn btn-primary float-right']) ?>
                
            </div>
        </div>
    </div>    
</div>
<?php Pjax::end() ?>