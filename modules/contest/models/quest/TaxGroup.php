<?php

namespace app\modules\contest\models\quest;

class TaxGroup 
{
    private static $data = [
        ['name' => 'Земельный налог', 'group' => 3],
        ['name' => 'Водный налог', 'group' => 1],
        ['name' => 'НДФЛ', 'group' => 1],
        ['name' => 'Лесной налог', 'group' => 2],
        ['name' => 'Госпошлина', 'group' => 1],
        ['name' => 'Подоходный налог', 'group' => 1],
        ['name' => 'Транспортный налог', 'group' => 2],
        ['name' => 'Налог на имущество физических лиц', 'group' => 3],
        ['name' => 'Акцизы', 'group' => 1],
        ['name' => 'Налог на имущество организаций', 'group' => 2],
    ];

    public static function getData($result)
    {
        $savePos = self::extractPositionWithName($result);
        $res = [];
        foreach(self::$data as $d) {           
            if (isset($savePos[$d['name']])) {
                $d['position'] = $savePos[$d['name']]['position'];
            }
            $res[] = $d;
        }
        return $res;
    }

    private static function extractPositionWithName($result)
    {
        $res = [];
        $data = isset($result['data']) ? unserialize($result['data']) : [];
        foreach ($data as $d) {
            foreach ($d as $i) {
                $res[$i['name']] = [
                    'name' => $i['name'],
                    'position' => $i['position'],
                    'groupId' => $i['groupId'],
                ];
            }
        }
        return $res;
    }

    public static function checkResult($post)
    {
        $balls = 0;
        foreach ($post as $idPost=>$p) {
            foreach($p as $i) {
                foreach(self::$data as $d) {
                    if ($d['group'] == $idPost && $d['name'] == $i['name']) {
                        $balls++;
                    }
                }
            }
        }
        return $balls;
    }

}