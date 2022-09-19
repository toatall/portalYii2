<?php
/** @var yii\web\View $this */
/** @var array $modelVote */
/** @var yii\base\DynamicModel $model */

use kartik\form\ActiveForm;
use kartik\widgets\StarRating;
use yii\bootstrap5\Html;

?>
<div id="container-form-vote">

    <?php if ($modelVote['id_vote']): ?>

        <div class="alert alert-info">
            Вы уже проголосовали <?= Yii::$app->formatter->asDateTime($modelVote['date_create']) ?>
        </div>

    <?php else: ?>


    <div class="card mt-3">
        <div class="card-header">
            <h5>Пожалуйста, оцените команду "<?= $modelVote['name'] ?>" по следующим критериям</h5>
        </div>
        <div class="card-body">            
            <div>
                <?php $form = ActiveForm::begin([
                    'id' => 'form-vote',
                    'type' => ActiveForm::TYPE_HORIZONTAL,
                    'errorSummaryCssClass' => 'alert alert-danger',
                ]); ?>

                <?= $form->errorSummary($model) ?>

                <?= $form->field($model, 'rating_trial')->widget(StarRating::class, [
                    'pluginOptions'=>['step'=>1],
                ]) ?>
                <?= $form->field($model, 'rating_name')->widget(StarRating::class, [
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

    <?php endif; ?>

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
                $.pjax.reload({ container: '#pjax-fort-boyard-teams' });
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