<?php

use yii\bootstrap4\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var app\models\conference\Conference $model */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'ВКС внешние', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="conference-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="btn-group" style="margin-bottom: 20px;">
        <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены, что хотите удалить?',
                'method' => 'post',
            ],
        ]) ?>
    </div>
    
    <?php if (($modelCrossed = $model->isCrossedI())): ?>
    <div class="alert alert-danger">
        <strong>Внимание!</strong><br />
        Данное мероприятние пересекает событие: 
        <a href="<?= Url::to($modelCrossed->getUrlAdmin()) ?>" target="_blank">
        "<?= $modelCrossed::getTypeLabel() ?> (<?= $modelCrossed->place ?>) <?= $modelCrossed->date_start ?>" (продолжительность <?= $modelCrossed->duration ?>)
        </a>        
    </div>
    <?php endif; ?>
    
    <?php if (($modelCrossed = $model->isCrossedMe())): ?>
    <div class="alert alert-danger">
        <strong>Внимание!</strong><br />
        Данное мероприятние пересечено событием: 
        <a href="<?= Url::to($modelCrossed->getUrlAdmin()) ?>" target="_blank">
        "<?= $modelCrossed::getTypeLabel() ?> (<?= $modelCrossed->place ?>) <?= $modelCrossed->date_start ?>" (продолжительность <?= $modelCrossed->duration ?>)
        </a>
    </div>
    <?php endif; ?>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'theme',            
            'date_start',
            'duration',
            'place',
            'responsible',
            'format_holding',
            'members_count',
            'material_translation',
            'members_count_ufns',
            'person_head',
            'link_event:text',
            'is_connect_vks_fns:boolean',
            'platform',
            'full_name_support_ufns',
            'date_test_vks',
            'count_notebooks',
            'is_change_time_gymnastic:boolean',
            'note:text',
            'date_create:datetime',
            'date_edit:datetime',
            'log_change',
        ],
    ]) ?>

</div>
