<?php

use yii\bootstrap5\Html;
use yii\grid\GridView;
use yii\grid\ActionColumn;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Настройка организаций';
$this->params['breadcrumbs'][] = ['label' => 'Исполнение задач', 'url' => ['/executetasks/default/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="execute-tasks-description-organization-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Добавить', ['create'], ['class' => 'btn btn-success']) ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'codeOrg.fullName:text:Организация',            
            [
                'label' => 'Фото',
                'format' => 'raw',
                'value' => function($model) {
                    $image = $model->image;
                    if ($image && file_exists(Yii::getAlias('@webroot') . $image)) {
                        return Html::img($image, ['style' => 'height: 15rem;']);
                    }
                    else {
                        return Html::tag('span', 'Отсутствует', ['class' => 'badge badge-success fa-1x']);
                    }
                },
            ],
            'fio',
            [
                'label' => 'Описание',
                'format' => 'raw',
                'value' => function($model) {
                    /** @var app\modules\executetasks\models\ExecuteTasksDescriptionOrganization $model */
                    $res = '';
                    $res .= Html::tag('strong', 'Должность: ') . $model->post . '<br />';
                    $res .= Html::tag('strong', 'Чин: ') . $model->rank . '<br />';
                    $res .= Html::tag('strong', 'Телефон: ') . $model->telephone;
                    return $res;
                },
            ],
            [
                'label' => 'Системная информация',
                'format' => 'raw',
                'value' => function($model) {
                    /** @var app\modules\executetasks\models\ExecuteTasksDescriptionOrganization $model */
                    $res = '';
                    $res .= Html::tag('strong', 'Дата создания: ') . Yii::$app->formatter->asDatetime($model->date_create) . '<br />';
                    $res .= Html::tag('strong', 'Дата изменения: ') . Yii::$app->formatter->asDatetime($model->date_update) . '<br />';
                    $res .= Html::tag('strong', 'Автор: ') . $model->author;
                    return $res;
                },
            ],           
            [
                'class' => ActionColumn::class,
                'template' => '{update} {delete}',
            ],           
        ],
    ]); ?>


</div>
