<?php
namespace app\models\page;

use app\models\news\News;

/**
 * Class Page
 * @package app\models\page
 */
class Page extends News
{
    /**
     * Используемый модуль
     * @return string
     */
    public static function getModule()
    {
        return 'page';
    }

    /**
     * {@inheritDoc}
     * @param bool $insert
     * @return bool
     * @throws \yii\base\InvalidConfigException
     */
    public function beforeSave($insert)
    {
        $this->on_general_page = false;
        return parent::beforeSave($insert);
    }

}