<?php
namespace app\models\page;

use app\models\news\NewsSearch;

/**
 * Class PageSearch
 * @package app\models\page
 */
class PageSearch extends NewsSearch
{
    /**
     * {@inheritDoc}
     * @return string
     */
    public static function getModule()
    {
        return 'page';
    }
}