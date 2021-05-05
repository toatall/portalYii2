<?php
/* @var $modelTest \app\modules\test\models\Test */
/* @var $modelTestOpinion app\modules\test\models\TestResultOpinion */
use yii\helpers\Html;
?>

<div class="opinion-index">   
    <div class="panel panel-default">
        <div class="panel-heading">Пожалуйста, оцените качество обучения по данной теме</div>
        <div class="panel-body">
            <?php if ($modelTestOpinion == null): ?>
            <?= Html::beginForm(['/test/default/rating', 'id' => $modelTest->id], 'post', ['id' => 'form-rating']) ?>
            <div class="btn-group" data-toggle="buttons">
                <label class="btn btn-default">
                    <input type="radio" name="rating" value="1" />
                    <i class="fas fa-star" style="color: goldenrod;"></i> 1
                </label>
                <label class="btn btn-default">
                    <input type="radio" name="rating" value="2" />
                    <i class="fas fa-star" style="color: goldenrod;"></i> 2
                </label>
                <label class="btn btn-default">
                    <input type="radio" name="rating" value="3" />
                    <i class="fas fa-star" style="color: goldenrod;"></i> 3
                </label>
                <label class="btn btn-default">
                    <input type="radio" name="rating" value="4" />
                    <i class="fas fa-star" style="color: goldenrod;"></i> 4
                </label>
                <label class="btn btn-default">
                    <input type="radio" name="rating" value="5" />
                    <i class="fas fa-star" style="color: goldenrod;"></i> 5
                </label>
            </div><br /><br />
            
            <label>Ваши предложения и замечания</label>
            <?= Html::textarea('note', '', ['rows' => 5, 'class' => 'form-control', 'style' => 'width: 500px;']) ?>
            <br />
            <?= Html::submitButton('Оценить', ['class' => 'btn btn-primary']) ?>
            <?= Html::endForm() ?>
            <?php else: ?>
            <div class="alert alert-warning">Спасибо, Вы уже оценили!</div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $this->registerJs(<<<JS
    $('#form-rating').on('submit', function() {
        if (!$(this).find('input[type="radio"]').is(':checked')) {
            alert('Вы не выбрали оценку!');
            return false;
        }
        let action = $(this).attr('action');
        let data = $(this).serialize();
        $.ajax({
            url: action,
            method: 'POST',
            data: data
        });
        $('.opinion-index').html('<div class="alert alert-info">Спасибо за Вашу оценку!</div>');
        return false;
    });
JS
);
?>

