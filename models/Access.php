<?php


namespace app\models;


use yii\db\Query;

class Access
{
    /**
     * Проверка прав пользователя для структуры $id_tree
     * @param integer $id_tree идентификатор струтуры
     * @author toatall
     * @uses Menu::getTree()
     * @uses Menu::getMenuDropDownList()
     * @uses NewsController::actionCreate()
     * @uses Tree::getTreeDropDownList()
     * @uses Tree::getTree()
     * @uses Tree::getTreeForMain()
     * @uses ConferenceController::checkAccess()
     * @uses NewsController::actionAdmin() (admin)
     * @uses NewsController::loadModel() (admin)
     * @uses PageController::loadModel() (admin)
     * @uses PageController::loadModelTree() (admin)
     * @uses TelephoneController::actionCreate() (admin)
     * @uses TelephoneController::loadModel() (admin)
     * @uses TelephoneController::actionAdmin() (admin)
     * @uses TreeController::loadModel() (admin)
     * @uses VksFnsController::checkAccess() (admin)
     * @uses VksUfnsController::checkAccess() (admin)
     */
    public static function checkAccessUserForTree($id_tree)
    {
        if (!is_numeric($id_tree))
            return false;

        if (\Yii::$app->user->isGuest) {
            return false;
        }

        if (\Yii::$app->user->can('admin')) {
            return true;
        }

        $query = new Query();
        return $query->from('{{%view_access_tree}}')
            ->where([
                'id' => $id_tree,
                'id_user' => \Yii::$app->user->id,
            ])
            ->scalar();
    }

    /**
     * Сохранение групп
     * @param $idTree
     * @param $groups
     * @return bool
     * @throws \yii\db\Exception
     * @uses \app\models\Tree::afterSave()
     */
    public static function saveTreeGroups($idTree, $groups, $parent=false)
    {
        $tree = (new Query())
            ->from('{{%tree}}')
            ->where(['id'=>$idTree])
            ->one();
        if ($tree == null) {
            return false;
        }

        $command = \Yii::$app->db->createCommand();
        // удаление старых записей
        $command->delete('{{%access_group}}', [
            'id_tree' => $idTree,
            'id_organization' => \Yii::$app->userInfo->current_organization,
        ])->execute();

        // сохранение родительских групп
        if ($parent) {
            $command->setSql("
                insert into {{%access_group}} (id_tree, id_group, id_organization)
                    select $idTree, id_group, id_organization from {{%access_group}}
                    where id_tree=:parent
            ")
            ->bindValue(':parent', $tree['id_parent'])//;var_dump($command->rawSql);die;
            ->execute();
        }
        // сохранение указанных групп
        else {
            if (is_array($groups)) {
                // добавление новых групп
                foreach ($groups as $group) {
                    $command->insert('{{%access_group}}', [
                        'id_tree' => $idTree,
                        'id_group' => $group,
                        'id_organization' => \Yii::$app->userInfo->current_organization,
                    ])->execute();
                }
            }
        }
    }


    /**
     * Сохранение пользователей
     * @param $idTree
     * @param $groups
     * @return bool
     * @throws \yii\db\Exception
     * @uses \app\models\Tree::afterSave()
     */
    public static function saveTreeUsers($idTree, $users, $parent=false)
    {
        $tree = (new Query())
            ->from('{{%tree}}')
            ->where(['id'=>$idTree])
            ->one();
        if ($tree == null) {
            return false;
        }

        $command = \Yii::$app->db->createCommand();
        // удаление старых записей
        $command->delete('{{%access_user}}', [
            'id_tree' => $idTree,
            'id_organization' => \Yii::$app->userInfo->current_organization,
        ])->execute();

        // сохранение родительских групп
        if ($parent) {
            $command->setSql("
                insert into {{%access_user}} (id_tree, id_user, id_organization)
                    select $idTree, id_user, id_organization from {{%access_user}}
                    where id_tree=:parent
            ")
            ->bindValue(':parent', $tree['id_parent'])
            ->execute();
        }
        // сохранение указанных групп
        else {
            if (is_array($users)) {
                // добавление новых групп
                foreach ($users as $user) {
                    $command->insert('{{%access_user}}', [
                        'id_tree' => $idTree,
                        'id_user' => $user,
                        'id_organization' => \Yii::$app->userInfo->current_organization,
                    ])->execute();
                }
            }
        }
    }

    /**
     * Сохранение прав доступа
     * @param $tableName string таблица с правами доступа
     * @param $columnStructName string наименование столбца идентификатора структуры (например, id_tree или id_department)
     * @param $columnAccessName string наименование столбца идентификатора доступа (например, id_user или id_group)
     * @param $idStruct int идентификатор структуры (объекта)
     * @param $idAccessArray int[] массив идетификаторов субъектов доступа
     * @return bool
     * @throws \yii\db\Exception
     */
    protected static function saveAccess($tableName, $columnStructName, $columnAccessName, $idStruct, $idAccessArray)
    {
        // если записи субъектов доступа должны быть массивом
        if (!is_array($idAccessArray)) {
            return false;
        }
        $command = \Yii::$app->db->createCommand();

        // удаление старых записей
        $command->delete($tableName, [
            $columnStructName => $idStruct,
            'id_organization' => \Yii::$app->userInfo->current_organization,
        ])->execute();

        // добавление новых записей
        foreach ($idAccessArray as $accessId) {
            $command->insert($tableName, [
                $columnStructName => $idStruct,
                $columnAccessName => $accessId,
                'id_organization' => \Yii::$app->userInfo->current_organization,
            ])->execute();
        }
    }

    /**
     * Сохранение групп для отдела
     * @param $idDepartment
     * @param $groups
     * @throws \yii\db\Exception
     * @uses \app\models\department\Department::afterSave()
     */
    public static function saveDepartmentGroups($idDepartment, $groups)
    {
        self::saveAccess('{{%access_department_group}}', 'id_department', 'id_group', $idDepartment, $groups);
    }

    /**
     * Сохранение пользователей для отдела
     * @param $idDepartment
     * @param $users
     * @throws \yii\db\Exception
     * @uses \app\models\department\Department::afterSave()
     */
    public static function saveDepartmentUsers($idDepartment, $users)
    {
        self::saveAccess('{{%access_department_user}}', 'id_department', 'id_user', $idDepartment, $users);
    }

}