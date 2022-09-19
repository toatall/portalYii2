<?php
namespace app\modules\kadry\widgets;

use app\modules\kadry\models\education\Education;
use app\modules\kadry\models\education\EducationData;
use Yii;
use yii\bootstrap5\Html;
use yii\bootstrap5\Widget;

/**
 * Вывод данных по курсу
 */
class EducationDetailView extends Widget
{
    /**
     * @var Education
     */
    public $model;

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        echo Html::beginTag('div', ['class' => 'col']);

        // картинка
        $thumb = $this->model->getThumbnailImage() ?? null;        
        if ($thumb != null && file_exists(Yii::getAlias('@webroot') . $thumb)) {
            echo Html::beginTag('div', ['class' => 'text-center']);
            echo Html::img($thumb, ['class' => 'img-thumbnail w-75']);
            echo Html::endTag('div');
        }

        // описание
        echo Html::beginTag('div', ['class' => 'text-justify mt-2']);
            echo $this->model->description_full;
        echo Html::endTag('div');
        
        // продолжительность
        $duration = $this->model->duration ?? null;
        if ($duration) {
            echo Html::tag('p', 'Продолжительность изучения: <i class="far fa-clock"></i> ' . $duration);
        }
        
        echo Html::beginTag('div', ['class' => 'progress', 'style'=>'height:0.7rem;']);            
            echo Html::beginTag('div', [
                'class' => 'progress-bar bg-success',
                'role' => 'progressbar',
                'style' => 'width: ' . (isset($this->model->educationUser->percent) ? $this->model->educationUser->percent  : 0) . '%;',
            ]);            
            echo Html::endTag('div');
        echo Html::endTag('div');
        
        // данные курса
        echo $this->buildParts($this->model->getEducationData()->where(['id_parent' => null])->all() ?? null);
        
        echo Html::endTag('div');
    }

    /**
     * Построение содержимого
     * @param EducationData[] $educationDatas
     */
    protected function buildParts($educationDatas, $level=0)    
    {       
        $out = '';        
        if (!empty($educationDatas) && is_array($educationDatas)) {
            foreach ($educationDatas as $educationData) {                
                $persent = isset($educationData->educationUserDatas->percent) ? $educationData->educationUserDatas->percent : 0;
                $out .= Html::beginTag('div', ['class' => 'card mt-3']);
                    $out .= Html::beginTag('div', ['class' => 'card-header' . ($persent >= 100 ? ' text-success' : '')]);
                        $out .= Html::tag('h4', $educationData->name ?? null);
                    $out .= Html::endTag('div');
                    $out .= Html::beginTag('div', ['class' => 'bg-success', 'style'=>'height: 0.4rem; width: ' . $persent . '%']);
                    $out .= Html::endTag('div');
                    $out .= Html::beginTag('div', ['class' => 'card-body']);
                        if (isset($educationData->educationDataFiles) && !empty($educationData->educationDataFiles) && is_array($educationData->educationDataFiles)) {
                            $out .= Html::beginTag('div', ['class' => 'list-group']);
                            foreach ($educationData->educationDataFiles as $file) {                              
                                $out .= Html::a($this->getIconByType(null) . ' ' . $file->title, ['download', 'id' => $file->id], [
                                    'class' => 'link-download list-group-item list-group-item-action' . ($file->educationUserDataFiles !== null ? ' list-group-item-success' : ''),
                                    'target' => '_blank',
                                    'data-pjax' => 0,
                                ]);
                            }
                            $out .= Html::endTag('div');
                        }
                        $out .= $this->buildParts($educationData->educationChildrenDatas, $level + 1);
                    $out .= Html::endTag('div');
                $out .= Html::endTag('div');
            }
        }
        return $out;
    }

    /**
     * @param string $type
     */
    protected function getIconByType($type)
    {
        return '<i class="far fa-file"></i>';
    }


}