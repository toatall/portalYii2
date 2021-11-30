<?php

use yii\bootstrap4\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\User $model */

$this->title = "Профиль {$model->fio}";
?>
<p class="display-4 border-bottom mv-hide"><?= $this->title ?></p>

<div class="card">
    <div class="card-body">
        <div class="row">  
            <div class="col-4 text-center">
                <?= Html::img($model->getPhotoProfile(), ['class' => 'img-thumbnail']) ?>
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
                            'date_create:datetime:Дата регистрации',
                        ],
                    ]) ?>
            </div>
        </div>
    </div>
</div>
<?php $this->registerCss(<<<CSS
    #modal-dialog-main > .modal-dialog {
        max-width: 60% !important;
    }
CSS); ?>