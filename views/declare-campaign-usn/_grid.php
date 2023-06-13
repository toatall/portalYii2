<?php

use app\models\DeclareCampaignUsn;
use yii\data\ArrayDataProvider;
use yii\helpers\Html;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var DeclareCampaignUsn[] $items */

$this->title = 'Декларационная кампания ' . date('Y') . ' года по УСН';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="declare-campaign-usn-index">
 
    <?= GridView::widget([
        'tableOptions' => ['class' => 'table table-bordered'],
        'summary' => false,
        'dataProvider' => new ArrayDataProvider([
            'allModels' => $items,
        ]),
        'columns' => [
            'org_code',
            'date:date',
            'count_np:integer',            
            
            [
                'attribute' => 'count_np_provides_reliabe_declare',
                'format' => 'raw',
                'value' => function(DeclareCampaignUsn $model) {
                    $result = Yii::$app->formatter->asInteger($model->count_np_provides_reliabe_declare);
                    if (($val = $model->previous_count_np_provides_reliabe_declare) !== false && $model->count_np_provides_reliabe_declare > 0) {
                        $newValue = $newValue = $model->count_np_provides_reliabe_declare - $val;;
                        
                        $color = 'primary';
                        if ($newValue > 0) {
                            $color = 'success';
                        }
                        elseif ($newValue < 0) {
                            $color = 'danger';
                        }

                        $result .= '<br /><small>' . Html::tag('span', Yii::$app->formatter->asInteger($newValue, [], [
                            NumberFormatter::POSITIVE_PREFIX => '+',
                            NumberFormatter::NEGATIVE_PREFIX => '-',
                        ]), [
                            'class' => 'badge bg-' . $color,
                            'data-bs-toggle' => 'tooltip',
                            'title' => 'По сравнению с ' . $model->previous_date . ' (' . Yii::$app->formatter->asInteger($val) . ')',
                        ]) . '</small>';                        
                    }
                    return $result;
                }
            ],
            [
                'attribute' => 'count_np_provides_not_required',
                'format' => 'raw',
                'value' => function(DeclareCampaignUsn $model) {
                    $result = Yii::$app->formatter->asInteger($model->count_np_provides_not_required);
                    if (($val = $model->previous_count_np_provides_not_required) !== false && $model->count_np_provides_not_required > 0) {                        
                        
                        $newValue = $newValue = $model->count_np_provides_not_required - $val;
                        
                        $color = 'primary';
                        if ($newValue > 0) {
                            $color = 'success';
                        }
                        elseif ($newValue < 0) {
                            $color = 'danger';
                        }
                        
                        $result .= '<br /><small>' . Html::tag('span', Yii::$app->formatter->asInteger($newValue, [], [
                            NumberFormatter::POSITIVE_PREFIX => '+',
                            NumberFormatter::NEGATIVE_PREFIX => '-',
                        ]), [                            
                            'class' => 'badge bg-' . $color,
                            'data-bs-toggle' => 'tooltip',
                            'title' => 'Прирост по сравнению с ' . $model->previous_date . ' (' . Yii::$app->formatter->asInteger($val) . ')',
                        ]) . '</small>';
                    }
                    return $result;
                }
            ],
            
            [
                'label' => 'Процент налогоплательщиков, представивших либо обосновано не представивших',               
                'value' => function(DeclareCampaignUsn $model) {
                    return Yii::$app->formatter->asPercent(($model->count_np_provides_reliabe_declare + $model->count_np_provides_not_required) / $model->count_np, 2);
                }
            ],
            [
                'format' => 'raw',
                'value' => function($model) {
                    return Html::button('<i class="fas fa-chart-line"></i>', [
                        'class' => 'btn btn-primary btn-sm btn-chart',
                        'data-org' => $model->org_code,
                    ]);
                },
            ],              
        ],
        'rowOptions' => function($model) {
            $result = ['data-id' => md5($model->org_code . $model->deadline), 'data-org-code' => $model->org_code, 'data-deadline' => $model->deadline];
            if ($model->org_code == '8600') {
                $result = array_merge(['class' => 'fw-bold'], $result);
            }
            return $result;
        }
    ]); ?>    
    
</div>
