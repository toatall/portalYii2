<?php

namespace app\modules\comment\models;

use Yii;

/**
 * Загрузка смайликов из json-файла
 * @author toatall
 */
class Emoji 
{

    /**
     * Загрузка json-файла со смайликами
     * @return array
     */
    protected static function loadEmoji()
    {
        $json = file_get_contents(Yii::getAlias('@app/modules/comment/emoji/emoji.json'));
        $data = json_decode($json, true)[0] ?? [];
        $result = [];
        foreach($data as $group => $emoji) {
            foreach ($emoji as $e) {
                $result[$group][] = $e;
            }
        }        
        return $result;
    }

    /**
     * @return array
     */
    public static function getEmoji()
    {
        return self::loadEmoji();
    }

    /**
     * Подготовка данных для [[items]] \yii\bootstrap5\Tabs
     * @return array
     */
    public static function prepareDataAsTabs()
    {
        $tabs = [];
        foreach(self::getEmoji() as $groupName => $group) {
            $content = [];
            foreach($group as $emoji) {
                $content[] = '<button class="btn btn-light btn-smiley" type="button">' . $emoji . '</button>';
            }
            $tabs[] = [
                'label' => $groupName,
                'content' => implode(PHP_EOL, $content),
            ];
        }
        return $tabs;
    }


}