<?php

/* @var $this yii\web\View */
/* @var $result array */
/* @var $model \app\modules\test\models\Test */
/* @var $modelTestOpinion app\modules\test\models\TestResultOpinion */

use yii\helpers\Html;

$persent = round(($result['rightAnswers'] / $result['questions']) * 100, 2);
?>

<h3 style="font-weight: bolder">Вы ответили правильно на <?= $result['rightAnswers'] ?> из <?= $result['questions'] ?> вопросов</h3>
<div class="progress">
    <div class="progress-bar" role="progressbar" aria-valuenow="<?= $persent ?>" aria-valuemax="100" aria-valuemin="0" style="width: <?= $persent ?>%;">
        <strong><?= $persent ?>%</strong>
    </div>
</div>
<hr />
<?= $this->render('rating', [
    'modelTest' => $model,
    'modelTestOpinion' => $modelTestOpinion,    
]) ?>