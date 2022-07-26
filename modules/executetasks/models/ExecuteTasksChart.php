<?php

namespace app\modules\executetasks\models;

use Yii;
use yii\db\Query;
use yii\helpers\Url;

class ExecuteTasksChart
{

    /**
     * @return array|null
     */
    public static function getDataByPeriod($period, $periodYear)
    {
        $query = (new Query())
            ->select("
                 t.*
                ,dep.id department_id
                ,dep.department_index
                ,dep.short_name department_name
                ,dep.department_name department_name_full
                ,org.short_name org_name
                ,org.name org_name_full
            ")
            ->from('{{%execute_tasks}} t')
            ->leftJoin('{{%department}} dep', 'dep.id = t.id_department')
            ->leftJoin('{{%organization}} org', 'org.code = t.org_code')
            ->where([
                't.period' => $period,
                't.period_year' => $periodYear,
            ])
            ->all(); 
        return $query;
    }

    /**
     * @return int
     */
    public static function getTotal($data)
    {       
        if (!$data) {
            return 0;
        }
        $totalAll = 0;
        $totalFinish = 0;
        foreach($data as $item) {
            $totalAll += $item['count_tasks'];
            $totalFinish += $item['finish_tasks'];            
        }
        if ($totalAll > 0) {
            return round($totalFinish / $totalAll * 100);
        }
        return 0;
    }

    /**
     * @param array $data
     * @return array|null
     */
    public static function getTotalWithIndex($data)
    {
        if (!$data) {
            return null;
        }

        $queryIndexes = (new Query())
            ->from('{{%execute_tasks_department}}')
            ->indexBy('id_department')
            ->all();      
        $totals = [];        
        foreach ($queryIndexes as $item) {
            $totals[$item['type_index']] = [
                'all' => 0,
                'finish' => 0,
            ];
        }
        
        if (!$totals) {
            return null;
        }
        
        foreach($data as $item) {
            if (isset($queryIndexes[$item['id_department']])) {              
                $totals[$queryIndexes[$item['id_department']]['type_index']]['all'] += $item['count_tasks'];
                $totals[$queryIndexes[$item['id_department']]['type_index']]['finish'] += $item['finish_tasks'];
            }
        }

        return $totals;
    }

    /**
     * Формирование массива для графика по отделам
     * @param array $data
     * @param string $period
     * @param string $periodYear
     * @return array
     */
    public static function getDepartments($data, $period=null, $periodYear=null, $idOrganization=null)
    {
        if (!$data) {
            return null;
        }
        if ($idOrganization == null) {
            $idOrganization = '8600';
        }
        $result = [];
        foreach($data as $item) {
            if ($idOrganization != null && $idOrganization != $item['org_code']) {
                continue;
            }
            $dep = $item['department_index'] . ' ' . $item['department_name'];
            if (!isset($result[$dep])) {
                $result[$dep]['all'] = 0;
                $result[$dep]['finish'] = 0;
            }
            $result[$dep]['all'] += $item['count_tasks'];
            $result[$dep]['finish'] += $item['finish_tasks'];
            $result[$dep]['url'] =  Url::to([
                '/executetasks/default/data-organization', 
                'idDepartment' => $item['department_id'],
                'idOrganization' => $idOrganization,
                'period' => $period,
                'periodYear' => $periodYear,
            ]);
            $result[$dep]['full_name'] = $item['department_index'] . ' ' . $item['department_name_full'];
        }      
        return $result;
    }

    /**
     * Формирование массива для графика по организациям
     * @param array $data
     * @param string|null $period
     * @param string|null $periodYear
     * @param int|null $idDepartment для фильтрации данных
     * @return array
     */
    public static function getOrganizations($data, $period=null, $periodYear=null, $idDepartment=null)
    {
        if (!$data) {
            return null;
        }
        $result = [];
        foreach($data as $item) {            
            if ($idDepartment != null && $idDepartment != $item['id_department']) {
                continue;
            }
            $org = $item['org_code'] .  ' ' . $item['org_name'];
            if ($item['org_code'] == '8600') {
                continue;
            }
            if (!isset($result[$org])) {
                $result[$org]['all'] = 0;
                $result[$org]['finish'] = 0;
            }
            $result[$org]['all'] += $item['count_tasks'];
            $result[$org]['finish'] += $item['finish_tasks'];
            $result[$org]['url'] =  Url::to([
                '/executetasks/default/data-department', 
                'idOrganization' => $item['org_code'],
                'idDepartment' => $idDepartment,
                'period' => $period,
                'periodYear' => $periodYear,
            ]);
            $result[$org]['full_name'] = $item['org_code'] . ' ' . $item['org_name_full'];
        }
        uasort($result, function($a, $b) {
            // $valA = 0;
            // if ($a['all'] > 0) {
            //     $valA = $a['finish'] / $a['all'];
            // }
            // $valB = 0;
            // if ($b['all'] > 0) {
            //     $valB = $b['finish'] / $b['all'];
            // }
            // if ($valA == $valB) {
            //     return 0;
            // }
            // return ($valA > $valB) ? -1 : 1;
            if ($a['finish'] == $b['finish']) {
                return 0;
            }
            return ($a['finish'] > $b['finish']) ? -1 : 1;

        });
        return $result;
    }


    /**
     * @param array $provider
     * @param string $label
     * @return int
     */
    public static function getTotalDataProvider($provider, $label) 
    {
        $total = 0;
        foreach($provider as $item) {
            $total += $item[$label];
        }
        return $total;
    }


    // public static function getLeadersDepartment($data)
    // {
    //     $deps = self::getDepartments($data);
    //     $result = [];
    //     foreach($deps as $dep=>$item) {
    //         if ($item['all'] > 0) {
    //             $persent = round($item['finish'] / $item['all'] * 100);
    //         }
    //         else {
    //             $persent = 0;
    //         }
    //         $result[$persent . '_' . $dep] = ['name' => $dep, 'per' => $persent];
    //     }
    //     krsort($result);        
    //     return $result;
    // }

    // public static function getLeadersOrganization($data)
    // {
    //     $orgs = self::getOrganizations($data);
    //     $result = [];
    //     foreach($orgs as $org=>$item) {
    //         if ($item['all'] > 0) {
    //             $persent = round($item['finish'] / $item['all'] * 100);
    //         }
    //         else {
    //             $persent = 0;
    //         }
    //         $result[$persent . '_' . $org] = ['name' => $org, 'per' => $persent];
    //     }
    //     krsort($result);        
    //     return $result;
    // }




}