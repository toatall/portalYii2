<?php

namespace app\models\menu;

use app\models\conference\AbstractConference;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/**
 * Генерирование меню
 * @author toatall
 */
class MenuBuilder
{

    /**
     * Дополнительное меню слева
     * @var array
     */
    private static $leftMenuAdd = [];

    /**
     * Главное верхнее меню
     * @return array
     */
    public static function buildMain($options = [])
    {
        return self::build(Menu::POSITION_MAIN, $options);        
    }

    /**
     * Главное меню слева
     * @return array
     */
    public static function buildLeft($options = [])
    {
        return self::build(Menu::POSITION_LEFT, $options);
    }

    /**
     * Вывод меню (с кэшированием)
     * @param int $position
     * @param int $id_parent
     * @param array $options
     * @return array
     */
    protected static function build($position, $options, $idParent=0)
    {      
        $cache = Yii::$app->cache;       
        return $cache->getOrSet('menu_' . $position, function() use ($position, $options, $idParent) {
            return self::buildData($position, $options, $idParent);
        }, 0);
    }

    /**
     * Формирование меню
     * @param $position
     * @param int $id_parent
     * @param array $options
     * @return array
     */
    protected static function buildData($position, $options, $id_parent, $level = 0)
    {                
        $queryAll = (new \yii\db\Query())
           ->from('{{%menu}}')
           ->where(['id_parent'=>$id_parent, 'type_menu'=>$position, 'blocked'=>0])
           ->orderBy('sort_index desc')
           ->all();
        
        $resultArray = array();
        
        foreach ($queryAll as $query) {
            $item = [
                'label' => $query['name'],
                'url' => \yii\helpers\Url::to($query['link']),
                'linkOptions' => [
                    'class' => Url::current() == \yii\helpers\Url::to($query['link']) ? 'active' : '',
                ],
            ];
            
            if ($query['target']) {
                $item['linkOptions']['target'] = $query['target'];
            }
            
            if ($query['submenu_code']) {
                $subMenu = $query['submenu_code'];
                if (class_exists($subMenu)) {
                    /** @var $classSubmenu ISubMenu **/
                    $classSubmenu = new $subMenu;
                    if ($classSubmenu instanceof ISubMenu) {
                        $item['items'] = $classSubmenu->renderMenu();
                    }
                    else {
                        \Yii::error("Class {$subMenu} not implements ISubMenu!");
                    }
                }
                else {
                    \Yii::error("Class {$subMenu} not exists!");
                }
            }
            else {
                $subMenu = self::buildData($position, $options, $query['id'], $level + 1);
            
                if (count($subMenu)>0) {
                    if ($level >= 1) {
                        ArrayHelper::setValue($options, 'class', 'dropdown-submenu');
                    }                    
                    $item['items'] = $subMenu;
                    $item['options'] = $options;
                }                
            }
            $resultArray[] = $item;            
        }

        return $resultArray;
    }

    /**
     * Построение дополнительных меню
     * @return array
     */
    public static function buildLeftAdd()
    {
        return self::$leftMenuAdd;
    }


    /**
     * Добавление раздела дополнительного меню
     * @param $node array
     * @return bool
     */
    public static function addLeftAdd($node, $push=false)
    {
        if (!$node) {
            return false;
        }
        if ($push) {
            array_unshift(self::$leftMenuAdd, $node);
        }
        else {
            self::$leftMenuAdd[] = $node;
        }
    }

    /**
     *  дополнительное меню в виде конента
     * @return array
     */
    public static function buildLeftAddMenuContent()
    {
        return [
            AbstractConference::eventsToday(),
        ];
    }
    
}
