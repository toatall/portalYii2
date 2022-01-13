<?php 

namespace app\models\telephone;

use yii\db\Query;

class TelephoneSearch
{
    /**
     * Поиск по ФИО или телефону
     * @param string $term поисковое поле
     * @return array
     */
    public function searchTerm($term)
    {
        $resulQuery = (new Query())
            ->from('{{%telephone_user}} u')
            ->orFilterWhere(['like', 'u.fio', $term])
            ->orFilterWhere(['like', 'u.telephone', $term])
            ->orFilterWhere(['like', 'u.telephone_dop', $term])          
            ->all();

        $result = [];
        foreach ($resulQuery as $item) {
            $result[] = [
                'user' => $item,
                'path' => $this->getPath($item['unid_department']),
            ];
        }
        return $result;
    }


    /**
     * Путь в структуре (рекурсивная)
     * @param string $unid идентификатор (отдела, организации)
     * @return string
     */
    private function getPath($unid)
    {
        $result = [];
        $query = (new Query())
            ->from('{{%telephone_department}}')
            ->where(['unid' => $unid])
            ->one();
        if ($query) {
            $result[] = ['label' => $query['name']];
        }
        if ($query['unid_parent']) {
            $result = array_merge($this->getPath($query['unid_parent']), $result);
        }

        return $result;
    }

   
    /**
     * Алфавитка
     * @param string $organizationUnid
     * @return array|null
     */
    public function search($organizationUnid)
    {
        if ($organizationUnid != null) {        
            return $this->findDepartments($organizationUnid);   
        }
        return null;
    }

    /**
     * Поиск отделов
     * @return string
     */
    private function findDepartments($parentUnid)
    {
        $result = [];
        $query = (new Query())
            ->from('{{%telephone_department}}')
            ->where(['unid_parent' => $parentUnid])
            ->orderBy([
                'index' => SORT_ASC,
                'name' => SORT_ASC,
            ])
            ->all();

        foreach ($query as $item) {
            $department_sub = $this->findDepartments($item['unid']);
            $users = $this->findUsers($item['unid']);

            if (!empty($department_sub) || !empty($users)) {
                $result[$item['name']] = [
                    'department_data' => $item,
                    'department_sub' => $department_sub,
                    'users' => $users,
                ];
            }
        }
        return $result;
    }

    /**
     * Поиск пользователей
     * @return array
     */
    private function findUsers($parentUnid)
    {
        $result = [];
        $query = (new Query())
            ->from('{{%telephone_user}}')
            ->where([
                'unid_department' => $parentUnid,                
            ])
            ->andWhere(['or', ['not', ['telephone' => null]], ['not', ['telephone_dop' => null]]])
            ->orderBy([
                'index' => SORT_ASC,
                'fio' => SORT_ASC,
            ])
            ->all();
        foreach ($query as $item) {
            $result[$item['fio']] = $item;
        }
        return $result;
    }

}