<?php

namespace app\models\menu;

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
    protected static function build($position, $options)
    {      
        $data = Yii::$app->cache->getOrSet('menu_' . $position, function() use ($position) {
            return self::buildMenuFromDb($position); 
        }, 0);
        return self::buildData($data, $options);
    }

    /**
     * Подготовка массива для виджета Nav
     * @param array $dataItems массив данных из БД
     * @param array $options дополнительные опции меню
     * @param int $level уровень вложенности
     * @return array
     */
    public static function buildData($dataItems, $options, $level = 0) 
    {
        if (!is_array($dataItems)) {
            return [];
        }

        $result = [];
        foreach($dataItems as $dataItem) {
            $item = [
                'label' => $dataItem['name'],
                'url' => Url::to($dataItem['link']),
                'linkOptions' => [
                    'class' => Url::current() == Url::to($dataItem['link']) ? 'active' : '',
                ],
            ];
            
            if ($dataItem['target']) {
                $item['linkOptions']['target'] = $dataItem['target'];
            }
            
            if ($dataItem['submenu_code']) {
                $subMenu = $dataItem['submenu_code'];
                if (class_exists($subMenu)) {
                    /** @var $classSubmenu ISubMenu **/
                    $classSubmenu = new $subMenu;
                    if ($classSubmenu instanceof ISubMenu) {
                        $item['items'] = $classSubmenu->renderMenu();
                    }
                    else {
                        Yii::error("Class {$subMenu} not implements ISubMenu!");
                    }
                }
                else {
                    Yii::error("Class {$subMenu} not exists!");
                }
            }
            else {
                $subMenu = self::buildData($dataItem['childs'] ?? false, $options, $level + 1);
            
                if (count($subMenu) > 0) {
                    if ($level >= 1) {
                        ArrayHelper::setValue($options, 'class', 'dropdown-submenu');
                    }                    
                    $item['items'] = $subMenu;
                    $item['options'] = $options;
                }                
            }
            $result[] = $item; 
        }
        return $result;
    }

    /**
     * Получение данных из БД о меню
     * @param int $position категория меню
     * @param int $idParent идентификатор родителя
     * @return array|bool
     */
    public static function buildMenuFromDb($position, $idParent = 0)
    {
        $reults = [];
        $items = (new \yii\db\Query())
           ->from('{{%menu}}')
           ->where(['id_parent'=>$idParent, 'type_menu'=>$position, 'blocked'=>0])
           ->orderBy('sort_index desc')
           ->all();
        if ($items === null) {
            return false;
        }

        foreach($items as $item) {            
            $row = $item;
            if (($childs = self::buildMenuFromDb($position, $item['id'])) !== false) {
                $row['childs'] = $childs;
            }
            $reults[] = $row;
        }
        return $reults;
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
     * Дополнительное меню в виде конента
     * @return array
     */
    public static function buildLeftAddMenuContent()
    {
        return [                
            \app\modules\meeting\widgets\MenuTodayMeetings::widget([]),      
        ];
    }
    
}
