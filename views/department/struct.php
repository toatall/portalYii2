<?php
/** @var yii\web\View $this */
/** @var app\models\department\Department $model */
/** @var array $arrayCard */
/** @var string $heightPhoto */

use app\assets\FancyappsUIAsset;
use app\models\department\Department;
use yii\bootstrap5\Html;


FancyappsUIAsset::register($this);

$heightPhoto = $heightPhoto ?? '15em';

$this->title = 'Структура';
$this->params['breadcrumbs'][] = ['label' => 'Отделы (' . ($model->organization->name ?? null) . ')', 'url' => ['/department/index']];
$this->params['breadcrumbs'][] = ['label' => $model->department_name, 'url' => ['/department/view', 'id'=>$model->id]];
$this->params['breadcrumbs'][] = $this->title;
?>


<div class="content content-color mb-4">

    <?php if (Department::isRoleModerator($model->id_organization)): ?>
        <div class="btn-group mb-3">
            <?= Html::a('<i class="fas fa-plus"></i> <i class="fas fa-user"></i> Добавить сотрудника', ['/admin/department-card/create', 'idDepartment' => $model->id], ['class' => 'btn btn-success btn-sm mv-link']) ?>
        </div>
    <?php endif; ?>

    <?php if (!Yii::$app->request->isAjax): ?>
    <div class="col border-bottom mb-2">
        <p class="display-4">
            <?= $model->department_name . ' (структура)' ?>
        </p>
    </div>
    <?php endif; ?>

    <?php if (is_array($arrayCard) && count($arrayCard) > 0): ?>
    <?php foreach ($arrayCard as $structRow): ?>
        <div class="row">
            <?php foreach ($structRow as $struct): ?>                
                <div class="col-3 mt-2 d-flex align-self-stretch" style="margin: 0 auto;">
                    <div class="card shadow-lg rounded-lg">
                        <div class="card-body">
                            <div class="gallery text-center">
                                <a href="<?= $struct['user_photo'] ?>" target="_blank" class="gallery-item" data-fancybox data-caption="<?= $struct['user_fio'] ?>">
                                    <img src="<?= $struct['user_photo'] ?>" class="img-thumbnail" style="max-width:100%; height: <?= $heightPhoto ?>; margin: 0 auto;" alt="<?= $struct['user_fio'] ?>" />
                                </a>
                            </div>
                        </div>
                        <div class="card-header" style="height: 100%; margin-top:10px; overflow: auto;">
                            <div class="text-center text-muted">
                                <h4 class="head text-uppercase" style="font-weight: bolder;"><?= $struct['user_fio'] ?></h4>
                                <p><?= $struct['user_position'] ?></p>
                                <p><?= $struct['user_rank'] ?></p>
                                <p><?= $struct['user_telephone'] ?></p>
                                <p><?= $struct['user_resp'] ?></p>
                            </div>
                        </div>
                        <?php if (Department::isRoleModerator($model->id_organization)): ?>
                        <div class="card-footer">
                            <div class="btn-group">
                                <?= Html::a('<i class="fas fa-pencil"></i> Изменить', ['/admin/department-card/update', 'id'=>$struct['id']], ['class'=>'btn btn-primary btn-sm mv-link']) ?>
                                <?= Html::a('Удалить', ['/admin/department-card/delete', 'id' => $struct['id']], ['class'=>'btn btn-danger btn-sm btn-delete', 'data-container'=>'#collapse_' . $model->id . ' > div.card-body']) ?>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>            
        </div>
    <?php endforeach; ?>
    <?php else: ?>
        <div class="alert alert-warning">Нет данных</div>
    <?php endif; ?>
</div>
<?php 

$this->registerJs(<<<JS
    
    Fancybox.bind("[data-fancybox]", { });

    $('.btn-delete').on('click', function() {
        if (!confirm('Вы уверены, что хотите удалить?')) {
            return false;
        }

        const url = $(this).attr('href');
        const containerName = $(this).data('container');

        $.ajax({
            url: url,
            method: 'post'
        })
        .done(function(data) {
            if (data.toUpperCase() == 'OK') {
                document.updateContainer(containerName);
            }
        })
        .fail(function (jqXHR) {
            alert(jqXHR.status + ' ' + jqXHR.statusText);
        }); 

        return false;
    });

JS); 

?>
