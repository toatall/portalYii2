<?php
namespace app\modules\meeting\models\search;

use app\modules\meeting\models\VksExtensionTrait;
use app\modules\meeting\models\VksExternal;
use yii\helpers\ArrayHelper;

class VksExternalSearch extends MeetingSearch
{
    use VksExtensionTrait;    

    /**
     * @inheritDoc
     */
    public static function modelClass()
    {
        return VksExternal::class;
    }    

    /**
     * Описание мероприятия
     * 
     * @inheritDoc
     */
    public function getDescription($short = false)
    {
        $attributes = [ 'extension.format_holding', 'responsible', 'extension.platform', 'duration_as_duration'];
        $result = '';
        
        $result .= sprintf('<span class="badge fa-1x mb-2 %s">%s</span><br />', 
            static::getColor(),
            static::modelClass()::getTypeLabel());

        foreach($attributes as $attribute) {
            $result .= sprintf('<b>%s:</b> %s <br />',
                $this->getAttributeLabel($attribute),
                ArrayHelper::getValue($this, $attribute));              
        }        
        
        return $result;
    }    

}