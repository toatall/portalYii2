<?php


namespace app\models\menu;


use yii\db\Query;

/**
 * Новости организаций
 * @package app\models\menu
 */
class MenuDepartments implements ISubMenu
{
    /**
     * @inheritDoc
     */
    public function renderMenu()
    {
        $query = new Query();
        $query->from('{{%department}}')
            ->orderBy('department_index asc');
        $resultQuery = $query->all();

        $result = [];
        foreach ($resultQuery as $item) {
            $result[] = [
                'label' => $item['department_index'] . ' ' . $item['department_name'],
                'url' => ['/department/view', 'id'=>$item['id']],
            ];
        }
        return $result;
    }
}