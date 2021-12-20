<?php 

namespace app\models\telephone;

use yii\db\Expression;
use yii\db\Query;

class TelephoneSearch
{
   
    public function search($organizationUnid)
    {
        if ($organizationUnid != null) {        
            return $this->findDepartments($organizationUnid);   
        }
        return null;
    }

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