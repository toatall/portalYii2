<?php
namespace app\models\menu;

/**
 * Мероприятия
 * @package app\models\menu
 */
class MenuMeetings implements ISubMenu
{
    public function renderMenu()
    {
        return [
            [
                'label' => 'Календарь',
                'url' => ['/meeting/calendar/index'],
            ],
            [
                'label' => 'По месту проведения',
                'url' => ['/meeting/calendar/locations'],
            ],
            [
                'label' => \app\modules\meeting\models\VksFns::getTypeLabel(),
                'url' => ['/meeting/' . \app\modules\meeting\models\VksFns::getType() . '/index'],
            ],
            [
                'label' => \app\modules\meeting\models\VksUfns::getTypeLabel(),
                'url' => ['/meeting/' . \app\modules\meeting\models\VksUfns::getType() . '/index'],
            ],
            [
                'label' => \app\modules\meeting\models\VksExternal::getTypeLabel(),
                'url' => ['/meeting/' . \app\modules\meeting\models\VksExternal::getType() . '/index'],
            ],
            [
                'label' => \app\modules\meeting\models\Conference::getTypeLabel(),
                'url' => ['/meeting/' . \app\modules\meeting\models\Conference::getType() . '/index'],
            ],
            [
                'label' => \app\modules\meeting\models\VksKonturTalk::getTypeLabel(),
                'url' => ['/meeting/' . \app\modules\meeting\models\VksKonturTalk::getType() . '/index'],
            ],
        ];
    }
}