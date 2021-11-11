<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\models\menu;

use app\models\conference\AbstractConference;
use app\models\vote\VoteMain;
use yii\helpers\Url;

/**
 * Главное меню
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
     * Формирование меню
     * @param $position
     * @param int $id_parent
     * @param array $options
     * @return array
     */
    protected static function build($position, $options, $id_parent=0)
    {        
        $queryAll = (new \yii\db\Query())
           ->from('{{%menu}}')
           ->where(['id_parent'=>$id_parent, 'type_menu'=>$position, 'blocked'=>0])
           ->orderBy('sort_index desc')
           ->all();
        
        $resultArray = array();
        
        foreach ($queryAll as $query)
        {
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
                $subMenu = self::build($position, $options, $query['id']);
            
                if (count($subMenu)>0) {
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
            //VoteMain::activeVotes()
        ];
    }
    
}
