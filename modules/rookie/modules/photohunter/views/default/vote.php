<?php
/** @var yii\web\View $this */
/** @var app\modules\rookie\modules\photohunter\models\Photos $modelPhotos */
/** @var app\modules\rookie\modules\photohunter\models\PhotosVotes $model */

use kartik\form\ActiveForm;
use kartik\widgets\StarRating;
use yii\bootstrap4\Html;

?>
<div id="container-form-vote">
    <div class="card text-center">
        <div class="card-body">
            <img src="<?= $modelPhotos->thumb ?>" class="img-thumbnail" />
        </div>
        <div class="card-footer">
            <p class="lead">
                <?= $modelPhotos->title ?><br />
                <strong><?= $modelPhotos->description ?></strong>
            </p>    
        </div>
    </div>
    
    <div class="card mt-3">
        <div class="card-header">
            <h5>Пожалуйста, оцените фотографию данную работу по следующим критериям</h5>
        </div>
        <div class="card-body">            
            <div>
                <?php $form = ActiveForm::begin([
                    'id' => 'form-vote',
                    'type' => ActiveForm::TYPE_HORIZONTAL,
                    'errorSummaryCssClass' => 'alert alert-danger',
                ]); ?>

                <?= $form->errorSummary($model) ?>

                <?= $form->field($model, 'mark_creative')->widget(StarRating::class, [
                    'pluginOptions'=>['step'=>1],
                ]) ?>
                <?= $form->field($model, 'mark_art')->widget(StarRating::class, [
                    'pluginOptions'=>['step'=>1],
                ]) ?>
                <?= $form->field($model, 'mark_original')->widget(StarRating::class, [
                    'pluginOptions'=>['step'=>1],
                ]) ?>
                <?= $form->field($model, 'mark_accordance')->widget(StarRating::class, [
                    'pluginOptions'=>['step'=>1],
                ]) ?>
                <?= $form->field($model, 'mark_quality')->widget(StarRating::class, [
                    'pluginOptions'=>['step'=>1],
                ]) ?>

                <hr />
                <div class="form-group">
                    <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>        
        </div>
    </div>

    <div id="container-failed"></div>
</div>


<?php $this->registerJs(<<<JS
    $('#form-vote').on('submit', function() {
        const form = $(this);
        const modal = $('#modal-dialog');
        const modalBody = modal.find('.modal-body');

        $.ajax({
            url: form.attr('action'),
            method: 'post',
            data: form.serialize()
        })
        .done(function(resp) {
            if (resp.toUpperCase() == "OK") {
                modal.modal('hide');
            }
            else {
                modalBody.html(resp);
            }
        })
        .fail(function(err) {
            $('#container-failed').html('<div class="alert alert-danger">' + err.responseText + '</div>');
        });

        return false;        
    });
JS); ?>