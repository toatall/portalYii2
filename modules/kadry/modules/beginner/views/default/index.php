<?php

use app\modules\kadry\modules\beginner\models\Beginner;
use yii\bootstrap5\Html;
use app\modules\admin\modules\grantaccess\widgets\GrantAccessWidget;
use yii\bootstrap5\Tabs;

/** @var yii\web\View $this */
/** @var array $data */
/** @var bool $archive */

$archive = $archive ?? false;
$this->title = 'Давайте знакомиться';// . ($archive ? ' (АРХИВ)' : '');
if ($archive) {
    $this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['index']];
    $this->params['breadcrumbs'][] = 'Архив';
}
else {
    $this->params['breadcrumbs'][] = $this->title;
}

$roleModerator = Beginner::isRoleModerator();
$roleAdmin = Yii::$app->user->can('admin');

?>
<div class="beginner-index">
    <p class="display-5 border-bottom">
        <?= $this->title ?>
        <?php if ($archive): ?>
        <sup><span class="badge bg-dark fs-5">Архив</span></sup>
        <?php endif; ?>
    </p>

    <div class="card shadow-sm">
        <?php if ($roleModerator || $roleAdmin): ?>
        <div class="card-header">
            <div class="btn-group">
                <?php if ($roleModerator): ?>
                    <?= Html::a('Добавить', ['create'], ['class' => 'btn btn-success']) ?>
                <?php endif; ?>
                <?php if ($roleAdmin): ?>
                    <?= GrantAccessWidget::widget([
                        'uniques' => [
                            ['id' => Beginner::getRoleModerator(), 'label' => 'Роль редактора'],
                        ],
                    ]) ?>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>
        <div class="card-body">
            <?php
            $tabs = [];
            foreach($data as $label => $items) {
                $labelArray = explode(':', $label);
                $labelText = $labelArray[1] ?? $label;
                $labelCode = $labelArray[0] ?? null;
                $tabs[] = [
                    'label' => $labelText . Html::tag('span', count($items), ['class' => 'badge bg-success rounded-circle ms-2']),
                    'content' => $this->render('list', ['models' => $items]),
                    'headerOptions' => [
                        'class' => 'fw-bold fs-5',
                    ],
                    'active' => ($labelCode == (Yii::$app->user->identity->default_organization ?? '0000') ? true : null),
                ];
            }
            echo Tabs::widget([
                'items' => $tabs,
                'encodeLabels' => false,
            ]);
            ?>
        </div>
        <?php if (!$archive): ?>
        <div class="card-footer">
            <?= Html::a('Архив', ['archive'], ['class' => 'btn btn-dark']) ?>
        </div>
        <?php endif; ?>
    </div>    

</div>