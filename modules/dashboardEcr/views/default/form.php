<?php
/** @var \yii\web\View $this */
/** @var \app\modules\dashboardEcr\models\MigrateRegions[] $models */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use yii\widgets\Pjax;

?>

<div class="table-responsive">

    <?php Pjax::begin(['id' => 'pjax-migrate-regions', 'timeout' => false, 'enablePushState' => false, 'scrollTo' => true]) ?>

    <?php $form = ActiveForm::begin([
        'id' => 'form-migrate-regions',
        'options' => [
            'data-pjax' => true,
        ],
    ]) ?>

    <?php $form->errorSummary($models) ?>    

    <table class="table table-bordered">
        <tr>
            <th>Регион</th>
            <th>Кол-во мигрирующих из округа</th>            
            <th>Кол-во мигрирующих в округ</th>            
        </tr>

        <?php foreach($models as $code => $model): ?>
        <tr>
            <td><?= $model->reg_code . ' ' . $model->regionName ?></td>
            <td><?= $form->field($model, "[$code]count_out")->textInput()->label(false) ?></td>
            <td><?= $form->field($model, "[$code]count_in")->textInput()->label(false) ?></td>
        </tr>
        <?php endforeach; ?>
    </table>

    <?php if (Yii::$app->session->getFlash('save-migrate-regions')): ?>
        <div class="alert alert-success">
            <p>Данные успешно сохранены!</p>
            <button data-bs-dismiss="modal" type="button" class="btn btn-success">Закрыть</button>
        </div>
    <?php else: ?>
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    <?php endif; ?>

    <?php $form->end() ?>

    <?php Pjax::end() ?>

</div>
<?php 
$this->registerJs(<<<JS
    $('#pjax-migrate-regions').on('submit', function(){
        $(this).find('button[type="submit"]').prop('disabled', true)
        $(this).find('button[type="submit"]').append(' <span class="spinner-border spinner-border-sm"></span>')
    })
JS); ?>