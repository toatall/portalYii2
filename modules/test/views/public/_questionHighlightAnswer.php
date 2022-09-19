<?php
/** @var yii\web\View $this */
/** @var app\modules\test\models\TestResultQuestion $model */

use yii\bootstrap5\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

$this->title = $model->testQuestion->name;
$question = $model->testQuestion;
$resultAnswers = ArrayHelper::map($model->testResultAnswers, 'id_test_answer', 'id_test_result_question');
?>
<div class="mt-2 text-dark">
    <h5 style="font-weight:bolder;">
        <?php 
            echo $question->name;
            // файл
            if (!empty($question->attach_file)) {
                echo Html::a(basename($question->attach_file), $question->attach_file, ['target'=>'_blank']);
            }
        ?>
    </h5>
    <hr />

    <div class="form-answer list-group" data-url="<?= Url::to(['/test/public/partial-save-highlight-answer', 'idResult'=>$model->id_test_result, 'idQuestion'=>$question->id]) ?>">    
    <?php foreach ($question->testAnswers as $answer): ?>
        <a href="<?= Url::to(['/test/public/partial-save-highlight-answer', 'idResult'=>$model->id_test_result, 'idQuestion'=>$question->id, 'idAnswer'=>$answer->id]) ?>" 
            class="list-group-item list list-group-item-action link-answer" data-result-id="<?= $model->id_test_result ?>" data-question-id="<?= $question->id ?>" data-answer-id="<?= $answer->id ?>">
            <?= $answer->name ?>
        </a>        
    <?php endforeach; ?>
    </div>
       
</div>
