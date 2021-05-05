<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\models\menu;

/**
 * Вывод подменю
 * @author toatall
 */
interface ISubMenu 
{
    /**
     * Вывод меню для виджета nav
     * return [
     *      [
     *          'label' => 'Example',
     *          'url' => 'http://example.com',
     *      ],
     *      [
     *          'label' => 'News',
     *          'url' => '/news/index',
     *      ],
     * ];
     */
    public function renderMenu();
}