<?php

/** @var yii\web\View $this */

use yii\bootstrap4\Tabs;

/** @var array $model */
/** @var array $rightAnswers */
/** @var array $wrongAnswers */

?>

<?= Tabs::widget([
    'items' => [
        ['label' => 'Описание', 'content' => '<div class="card card-body border-top-0">' . $model['note'] . '</div>'],
        ['label' => 'Загадка', 'content' => '<div class="card card-body border-top-0">' . $model['text_question'] . '</div>'],
    ],
]) ?>
<hr />
<div class="card">
    <div class="card-header">Результаты</div>
    <div class="card-body">
        <?= Tabs::widget([
            'items' => [
                [
                    'label' => 'Правильно ответили',
                    'content' => $this->render('_viewRight', [
                        'model' => $rightAnswers,
                    ]),                    
                ],
                [
                    'label' => 'А еще думали что это',
                    'content' => $this->render('_viewWrong', [
                        'model' => $wrongAnswers,
                    ]),
                ],
            ],
        ]) ?>
    </div>
</div>