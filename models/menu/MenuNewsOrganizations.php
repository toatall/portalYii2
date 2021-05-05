<?php


namespace app\models\menu;


use yii\db\Query;

/**
 * Новости организаций
 * @package app\models\menu
 */
class MenuNewsOrganizations implements ISubMenu
{
    /**
     * @inheritDoc
     */
    public function renderMenu()
    {
        $query = new Query();
        $query->from('{{%organization}}')
            ->orderBy('code asc');
        $resultQuery = $query->all();

        $result = [];
        foreach ($resultQuery as $item) {
            $result[] = [
                'label' => $item['name'],
                'url' => ['/news/index', 'organization'=>$item['code']],
            ];
        }
        return $result;
    }
}