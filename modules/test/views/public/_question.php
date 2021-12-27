<?php
/** @var yii\web\View $this */
/** @var app\modules\test\models\TestResultQuestion $model */
/** @var int $countQuestions */
/** @var int $indexQuestion */

use yii\bootstrap4\Html;
use app\modules\test\models\TestQuestion;
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
        
    <div class="form-answer" data-url="<?= Url::to(['/test/public/partial-save-answer', 'idResult'=>$model->id_test_result, 'idQuestion'=>$question->id]) ?>">
    <?php if ($question->type_question == TestQuestion::TYPE_QUSTION_INPUT): ?>        
        <?php foreach ($question->parseInputAnsewrs() as $id => $q): 
            $inputId = "answer-{$question->id}-{$id}";
            ?>
            <div class="form-input mb-3">
                <?= isset($q->label) ? Html::label($q->label, $inputId, ['class' => 'form-input-label']) : '' ?>
                <?= Html::input('text', "Test[{$question->id}][$id]", null, ['id'=>$inputId, 'class'=>'form-control']) ?>                
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <?php foreach ($question->testAnswers as $answer): ?>
            <div class="form-check">
                <?php 
                    if ($question->type_question == TestQuestion::TYPE_QUESTION_RADIO) {
                        echo Html::radio('Test[' . $question->id . ']', isset($resultAnswers[$answer->id]),
                            ['value'=>$answer->id, 'class'=>'form-check-input', 'id'=>'answer-'.$answer->id, 'data-id-question'=>$question->id]);
                    }
                    else {
                        echo Html::checkbox('Test[' . $question->id . '][]', isset($resultAnswers[$answer->id]), 
                            ['value'=>$answer->id, 'class'=>'form-check-input', 'id'=>'answer-'.$answer->id, 'data-id-question'=>$question->id]);
                    }
                    // Файл
                    if (!empty($answer->attach_file)) {
                        echo '<br />' . Html::a(basename($answer->attach_file), $answer->attach_file, ['target'=>'_blank']);
                    }
                ?>
                <label class="form-check-label" for="answer-<?= $answer->id ?>" style="cursor: pointer;"><?= $answer->name ?></label>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
    </div>    

    <hr />
    <div class="btn-group">       
        <?php
            // кнопка назад
            if ($indexQuestion > 0) {
                echo Html::button('<i class="fas fa-arrow-circle-left"></i> Назад', ['class'=>'btn btn-primary btn-previous']);
            }
            // кнопка вперед
            if ($indexQuestion < ($countQuestions-1)) {
                echo Html::button('<i class="fas fa-arrow-circle-right"></i> Далее', ['class'=>'btn btn-primary btn-next']);
            }
            // кнопка назад
            if ($indexQuestion == ($countQuestions-1)) {
                echo Html::submitButton('<i class="fas fa-share-square"></i> Завершить', ['class'=>'btn btn-success']);
            }
        ?>
    </div>
    
</div>
