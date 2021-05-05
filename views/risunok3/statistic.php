<?php
/* @var $this yii\web\View */
/* @var $dataProvider \yii\data\ActiveDataProvider */
/* @var $model \app\models\vote\VoteNewyearToy */

use kartik\grid\GridView;
use yii\widgets\Pjax;
use kartik\export\ExportMenu;
$this->title = $model->name;
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="vote-newyear-statistic">
    <div class="mv-hide">
        <h1><?= $this->title ?><br /><small><?= $model->department ?></small></h1>
        <hr />
    </div>

    <?php Pjax::begin(['timeout'=>false, 'enablePushState'=>false]); ?>

    <div class="mv-hide">
    <?= ExportMenu::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'id',
            'username',
            [
                'attribute' => 'ФИО',
                'value' => function(\app\models\vote\VoteNewyearToyAnswer $model) {
                    return $model->getUserInfo($model->username)['ldap_cn'] ?? null;
                },
            ],
            [
                'attribute' => 'НО',
                'value' => function(\app\models\vote\VoteNewyearToyAnswer $model) {
                    return $model->getUserInfo($model->username)['code_org'] ?? null;
                },
            ],
            [
                'attribute' => 'Отдел',
                'value' => function(\app\models\vote\VoteNewyearToyAnswer $model) {
                    return $model->getUserInfo($model->username)['ldap_department'] ?? null;
                },
            ],
            'date_create:datetime',
        ],
    ]) ?>
    </div>

    <?= GridView::widget([
        'id' => 'gridViewAnswers',
        'dataProvider' => $dataProvider,
        'columns' => [
            'id',
            'username',
            [
                'attribute' => 'ФИО',
                'value' => function(\app\models\vote\VoteNewyearToyAnswer $model) {
                    return $model->getUserInfo($model->username)['ldap_cn'] ?? null;
                },
            ],
            [
                'attribute' => 'НО',
                'value' => function(\app\models\vote\VoteNewyearToyAnswer $model) {
                    return $model->getUserInfo($model->username)['code_org'] ?? null;
                },
            ],
            [
                'attribute' => 'Отдел',
                'value' => function(\app\models\vote\VoteNewyearToyAnswer $model) {
                    return $model->getUserInfo($model->username)['ldap_department'] ?? null;
                },
            ],
            'date_create:datetime',
        ],
    ]) ?>

    <?php Pjax::end(); ?>

</div>
