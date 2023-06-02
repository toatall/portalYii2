<?php
namespace app\modules\meeting\helpers;

use app\modules\meeting\models\Conference;
use app\modules\meeting\models\search\ConferenceSearch;
use app\modules\meeting\models\search\VksExternalSearch;
use app\modules\meeting\models\search\VksFnsSearch;
use app\modules\meeting\models\search\VksKonturTalkSearch;
use app\modules\meeting\models\search\VksUfnsSearch;
use app\modules\meeting\models\VksExternal;
use app\modules\meeting\models\VksFns;
use app\modules\meeting\models\VksKonturTalk;
use app\modules\meeting\models\VksUfns;

class MeetingHelper
{

    /**
     * Привязка классов к типу мероприятия
     *      
     * Фильтрация типов мероприятий, по умолчанию все мероприятия - '.*'
     * '^(vks)' - показывать только типы мероприятий начинающихся с vks-...
     * '^(?!vks)' - показывать только типы мероприятий не начинающихся с vks-...
     * и т.д.
     * @param string $filter
     * 
     * @return array
     */
    public static function mapTypesToClassNames($filter = '.*')
    {
        $map = [
            VksUfns::getType() => [
                'classBase' => VksUfns::class,
                'classSearch' => VksUfnsSearch::class,
            ],
            VksFns::getType() => [
                'classBase' => VksFns::class,
                'classSearch' => VksFnsSearch::class,
            ],
            Conference::getType() => [
                'classBase' => Conference::class,
                'classSearch' => ConferenceSearch::class,
            ],
            VksExternal::getType() => [
                'classBase' => VksExternal::class,
                'classSearch' => VksExternalSearch::class,
            ],
            VksKonturTalk::getType() => [
                'classBase' => VksKonturTalk::class,
                'classSearch' => VksKonturTalkSearch::class,
            ],
        ];
        return array_filter($map, function($value, $key) use ($filter) {
            return preg_match_all("/$filter/", $key) > 0;
        }, ARRAY_FILTER_USE_BOTH);
    }

    /**
     * Список типов мероприятий
     * 
     * @see self::mapTypesToClassNames()
     * @uses \app\modules\meeting\controllers\CalendarController::actionIndex()
     * @uses \app\modules\meeting\controllers\CalendarController::actionLocations()
     * 
     * @return array
     */
    public static function allTypes($filter = '.*')
    {        
        foreach(self::mapTypesToClassNames($filter) as $item) {
            yield $item['classBase']::getType();
        }
    }

    /**
     * Генерирование цветных меток (badge) для checkBoxList фильтрации календаря 
     * 
     * @see self::mapTypesToClassNames()
     * @uses \app\modules\meeting\controllers\CalendarController::actionIndex()
     * @uses \app\modules\meeting\controllers\CalendarController::actionLocations()
     * 
     * @param string $filter
     * @return array
     */
    public static function allTypesLabelsWithBadgeColors($filter = '.*')
    {
        $template = '<span class="badge text-white {color}">{label}</span>';        
        foreach(self::mapTypesToClassNames($filter) as $item) {
            yield $item['classBase']::getType() => strtr($template, [
                '{color}' => $item['classSearch']::getColor(),
                '{label}' => $item['classBase']::getTypeLabel(),
            ]);
        }       
    }

}