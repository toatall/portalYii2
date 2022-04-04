<?php
/** @var yii\web\View $this */

use app\modules\quiz\models\QuizQuestion;
use kartik\rating\StarRating;
use yii\bootstrap4\Html;

/** @var app\modules\quiz\models\Quiz $model */

$this->title = $model->name;
?>

<p class="display-4 border-bottom"><?= $this->title ?></p>

<?php if ($model->getResultMy()->exists()): ?>
    <div class="alert alert-info">
        Спасибо!
    </div>
<?php else: ?>
    <?= Html::beginForm() ?>
        <?php foreach ($model->quizQuestions as $qustion): ?>
            <div class="card mt-2">
                <div class="card-header">
                    <?= $qustion->name ?>
                </div>
                <div class="card-body">
                    <?php if ($qustion->type_question === QuizQuestion::TYPE_STARS): ?>   
                        <?= StarRating::widget([
                            'name' => 'Quiz[' . $qustion->id . ']',
                            'pluginOptions' => [
                                'max' => 5,
                                'step' => 1,
                            ],
                        ]) ?>       
                    <?php elseif ($qustion->type_question === QuizQuestion::TYPE_INPUT): ?>
                        <?= Html::textarea('Quiz[' . $qustion->id .']', null, ['class' => 'form-control']) ?>
                    <?php endif; ?>
                </div>
            </div>            
        <?php endforeach; ?>
        <div class="card card-footer mt-2">
            <div class="row col">
                <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
            </div>
        </div>
    <?= Html::endForm() ?>
<?php endif; ?>

