<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\models\conference\AbstractConference;
use kartik\editable\Editable;

/* @var $this yii\web\View */
/* @var $model app\models\conference\Conference */

$this->title = $model->theme;
$this->params['breadcrumbs'][] = $this->title;

$action = Url::to(['/admin/' . $model->strType() . '/update', 'id'=>$model->id]);


    $labelsConference = [
        'members_people',
        'date_start:date:Дата и время начала',
        'time_start',
        'duration',
        'place',
        'is_confidential:boolean',
        'note',
    ];
    
    
    $labelVksExternal = [
        'date_start:datetime',
        'duration',
        'place',
        'responsible',
        [
            'attribute' => 'responsible',
            'value' => Editable::widget([
                'model' => $model,
                'attribute' => 'responsible',
                'size' => 'md',                  
                'formOptions' => ['action' => yii\helpers\Url::to(['/admin/' . $model->strType() . '/update', 'id'=>$model->id])],
            ]),
            'format' => 'raw',
        ],
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
    ];

    $labels = [];
    if ($model->type_conference == AbstractConference::TYPE_CONFERENCE) {
        $labels = $labelsConference;
    }
    if ($model->type_conference == AbstractConference::TYPE_VKS_UFNS) {
        $labels = $labelVksUfns;
    }
    if ($model->type_conference == AbstractConference::TYPE_VKS_FNS) {
        $labels = $labelVksFns;
    }
    if ($model->type_conference == AbstractConference::TYPE_VKS_EXTERNAL) {
        $labels = $labelVksExternal;
    }

?>
<div class="conference-view">

    <h1 class="mv-hide">
        <?php if ($model->isEditor()): ?>
        <?= Editable::widget([
            'model' => $model,
            'attribute' => 'theme',
            'formOptions' => ['action' => $action],                        
        ]); ?>
        <?php else: ?>
        <?= Html::encode($this->title) ?>        
        <?php endif; ?>
    </h1>
    
    <?php if ($model->isCrossedI()): ?>
        <div class="alert alert-danger"><strong>Внимание!</strong><br />Это событие пересекает другое событие</div>
        <?php elseif ($model->isCrossedMe()): ?>
        <div class="alert alert-danger"><strong>Внимание!</strong><br />Другое событие пересекает это событие</div>
    <?php endif; ?>

    <?php
    
    $conferenceTypes = [
        AbstractConference::TYPE_VKS_UFNS => [
            'view' => 'viewVksUfns',
        ],
        AbstractConference::TYPE_VKS_FNS => [
            'view' => 'viewVksFns',
        ],
        AbstractConference::TYPE_CONFERENCE => [
            'view' => 'viewConference',
        ],
        AbstractConference::TYPE_VKS_EXTERNAL => [
            'view' => 'viewVksExternal',
        ],
    ];
    
    if (isset($conferenceTypes[$model->type_conference])) {
        echo $this->render($conferenceTypes[$model->type_conference]['view'], [
            'model' => $model,
            'action' => $action,
        ]);
    }
    else {
        throw new HttpException(599, 'Не найдено подходящее представление');
    }
        
    ?>

</div>
