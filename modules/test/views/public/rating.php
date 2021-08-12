<?php
/** @var app\modules\test\models\Test $model */
/** @var app\modules\test\models\TestResultOpinion $modelOpinion */

use kartik\widgets\StarRating;
use yii\bootstrap4\Html;

$this->title = "Оценка теста \"{$model->name}\"";
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="card shadow mb-4">
    <div class="card-header font-weight-bolder"><?= $this->title ?></div>
    <div class="card-body">

        <div class="alert alert-info">
            
            <?php if ($modelOpinion === null): ?>
                <strong>
                    Пожалуйста, оцените качество обучения по данной теме
                </strong>                                
                <?= Html::beginForm(['/test/public/rating', 'id'=>$model->id], 'post', ['id' => 'form-rating']) ?>
                <?= StarRating::widget([
                    'name' => 'rating',
                    'pluginOptions' => [
                        'step' => 1,
                        'required',
                    ],
                ]) ?>
                <label>Ваши предложения и замечания</label>
                <?= Html::textarea('note', '', ['rows' => 5, 'class' => 'form-control', 'style' => 'width: 500px;']) ?>
                <br />
                <?= Html::submitButton('Оценить', ['class' => 'btn btn-primary']) ?>
            <?php else: ?>
                <p>Вы уже поставили оценку <?= Yii::$app->formatter->asDatetime($modelOpinion->date_create) ?></p>
                <strong>Ваша оценка</strong>
                <?= StarRating::widget([
                    'id' => 'star-rating-' . $model->id,
                    'name' => 'rating',
                    'pluginOptions' => [
                        'step' => 1,
                        'readonly' => true,                                               
                    ],
                    'value' => $modelOpinion->rating,
                ]) ?>
            <?php endif; ?>
            <div id="container-rating-error" class="mt-2 alert alert-danger" style="display: none;"></div>
            <?= Html::endForm() ?>
        </div>

        <?= Html::a('<i class="fas fa-arrow-left"></i> Назад', ['/test/test/index'], ['class' => 'btn btn-primary']) ?>
    </div>
</div>

