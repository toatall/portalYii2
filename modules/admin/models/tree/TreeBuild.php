<?php
namespace app\modules\admin\models\tree;

use Yii;
use yii\caching\DbDependency;
use app\models\Tree;


/**
 * Построение дерева в соответствии с правами пользователя
 * Используется кэширование
 * @author
 */
class TreeBuild
{

    /**
     * Генерирование дерева на основе таблицы {{tree}}
     * Используется кэширование: 1 час + проверка последних изменений в таблице {{tree}}
     * @param int $parentId родительский идентификатор (по умолчанию = 0, если необходимо построить дерево с самого начала)
     * @param int $excludeId идентификатор, который следует исключить из запроса
     * @return array
     */
    public static function buildingTree(int $parentId=0, int $excludeId=0)
    {
        $cache = Yii::$app->cache;
        return $cache->getOrSet(__FILE__ . '::' . __LINE__ . '::' . Yii::$app->user->id, function() use ($parentId, $excludeId) {
            return self::buildingTreeQuery($parentId, $excludeId);              
        }, 60 * 60, new DbDependency([
            'sql' => "SELECT MAX(DATEDIFF(s, '1970-01-01 00:00:00', {{date_edit}})) + COUNT({{id}}) FROM {{%tree}}",
        ]));
    }

    /**
     * @param int $parentId
     * @param int $excludeId
     * @return array
     */
    protected static function buildingTreeQuery($parentId = 0, $excludeId = 0)
    {
        $query = Tree::find()
            ->where(['id_parent' => $parentId])
            ->andWhere(['<>', 'id', $excludeId])
            ->andWhere(['in', 'id_organization', [Yii::$app->user->identity->current_organization, '0000']])
            ->orderBy(['sort' => SORT_ASC, 'name' => SORT_ASC, 'date_create' => SORT_ASC])
            ->asArray();
        if (!Yii::$app->user->can('admin')) {
            $query->andWhere(['date_delete' => null]);
        }
        $resultQuery = $query->all();
        
        $result = [];
        foreach($resultQuery as $item) {
            if (Yii::$app->user->can('admin') || TreeAccess::isAccessToTreeNode($item['id'])) {
                $result[$item['id']] = array_merge($item, ['childrens' => self::buildingTreeQuery($item['id'], $excludeId)]);
            }
            else {
                if (($childs = self::buildingTreeQuery($item['id'], $excludeId))) {
                    $result = $result + $childs;
                }
            }
        }
        return $result;
    }


}