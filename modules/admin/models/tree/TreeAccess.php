<?php
namespace app\modules\admin\models\tree;

use yii\db\Query;

/**
 * Работа с правами дерва структуры
 * @author toatall
 */
class TreeAccess 
{

    /**
     * Проверка прав к узлу дерева структуры у текущего пользователя
     * @param int $idTree идентификатор узла дерева структуры
     * @return bool
     */
    public static function isAccessToTreeNode(int $idTree): bool
    {
        if (\Yii::$app->user->isGuest) {
            return false;
        }

        if (\Yii::$app->user->can('admin')) {
            return true;
        }

        return (new Query())
            ->from('{{%view_access_tree}}')
            ->where([
                'id' => $idTree,
                'id_user' => \Yii::$app->user->id,
            ])
            ->scalar() !== false;
    }

    /**
     * Предоставление прав доступа группам к узлу структуры дерева
     * @param int $idTree идентификатор узла структуры дерева
     * @param array|null $groupsId идентификаторы групп
     * @param bool $inheritParent наследование родительских прав
     * @throws \yii\db\Exception
     */
    public static function assignGroupsPermissionsToNodeTree(int $idTree, mixed $groupsId, bool $inheritParent = false)
    {
        $tree = (new Query())
            ->from('{{%tree}}')
            ->where(['id'=>$idTree])
            ->one();
        if ($tree == null) {
            return false;
        }

        // удаление старых записей
        self::removePermissions('{{%access_group}}', [
            'id_tree' => $idTree,
            'id_organization' => self::getUserIdOrganization(),
        ]);

        // наследование прав от родителя
        if ($inheritParent) {
            self::inheritPermissionsGroups($idTree, $tree['id_parent']);
        }        
        // сохранение указанных групп
        else {
            self::addPermissions($groupsId, '{{%access_group}}', $idTree, 'id_group');
        }
    }

    /**
     * Предоставление прав доступа пользователям к узлу структуры дерева
     * @param int $idTree идентификатор узла структуры дерева
     * @param array|null $usersId идентификаторы пользователей
     * @param bool $inheritParent наследование родительских прав
     * @throws \yii\db\Exception
     */
    public static function assignUsersPermissionsToNodeTree(int $idTree, mixed $usersId, bool $inheritParent = false)
    {
        $tree = (new Query())
            ->from('{{%tree}}')
            ->where(['id'=>$idTree])
            ->one();
        if ($tree == null) {
            return false;
        }

        // удаление старых записей
        self::removePermissions('{{%access_user}}', [
            'id_tree' => $idTree,
            'id_organization' => self::getUserIdOrganization(),
        ]);

        // наследование прав от родителя
        if ($inheritParent) {
            self::inheritPermissionsUsers($idTree, $tree['id_parent']);            
        }
        // сохранение указанных групп
        else {           
            self::addPermissions($usersId, '{{%access_user}}', $idTree, 'id_user');
        }
    }

    /**
     * Подключение к БД
     * @return \yii\db\Command
     */
    protected static function getDbCommand()
    {
        return \Yii::$app->db->createCommand();
    }

    /**
     * Идентификатор организации текущего пользователя
     * @return string
     */
    protected static function getUserIdOrganization()
    {
        return \Yii::$app->user->identity->current_organization;
    }

    /**
     * Удаление из таблицы
     * @param string $tableName
     * @param array $params
     * @return int
     */
    protected static function removePermissions($tableName, $parmas)
    {
        $command = self::getDbCommand();
        return $command->delete($tableName, $parmas)->execute();
    }   

    /**
     * Предоставление прав к узлу структуры дерева
     * @param array $ids
     * @param string $tableName
     * @param int $idTree
     * @param string $fieldIdName
     */
    protected static function addPermissions($ids, $tableName, $idTree, $fieldIdName)
    {
        $command = self::getDbCommand();
        if (!is_array($ids)) {
            return;
        }
        foreach($ids as $id) {
            $command->insert($tableName, [
                'id_tree' => $idTree,
                $fieldIdName => $id,
                'id_organization' => self::getUserIdOrganization(),
            ])->execute();
        }
    }

    /**
     * Сохранение наследование прав пользователей от родительского узла структуры дерева
     * @param int $idTree
     * @param int $idParent
     */
    protected static function inheritPermissionsUsers($idTree, $idParent)
    {
        $command = self::getDbCommand();
        $command->setSql("
                insert into {{%access_user}} (id_tree, id_user, id_organization)
                    select $idTree, id_user, id_organization from {{%access_user}}
                    where id_tree=:parent
            ")
            ->bindValue(':parent', $idParent)
            ->execute();
    }

    /**
     * Сохранение наследование прав групп от родительского узла структуры дерева
     * @param int $idTree
     * @param int $idParent
     */
    protected static function inheritPermissionsGroups($idTree, $idParent)
    {
        $command = self::getDbCommand();
        $command->setSql("
                insert into {{%access_group}} (id_tree, id_group, id_organization)
                    select $idTree, id_group, id_organization from {{%access_group}}
                    where id_tree=:parent
            ")
            ->bindValue(':parent', $idParent)
            ->execute();
    }
    

}