<?php
namespace app\helpers\Log;

use Yii;

class LogHelper
{

    /**
     * Функция преобразует лог из БД к читаемому виду
     * @param string $record
     * @return string
     * @uses \app\modules\admin\controllers\ModuleController::actionView()
     */
    public static function getLog($record)
    {
        $explode_array = explode('$',$record);
        $array_str = [];
        foreach ($explode_array as $val) {
            if ($val != '') {
                $array_str[] = str_replace('|', ' - ', $val);
            }
        }

        return Yii::$app->controller->renderPartial('@app/helpers/Log/views/viewLogChange', [
            'array_str'=>array_reverse($array_str),
        ]);
    }

    /**
     * Функция возвращает запись для лога
     * @param string $lastRecord текущий лог
     * @param string $operation выполняемая в данный момент операция
     * @return string
     * @uses NewsController::actionCreate()
     * @uses Tree::beforeSave()
     * @uses DepartmentDataController::actionCreate() (admin)
     * @uses DepartmentDataController::actionUpdate() (admin)
     * @uses DepartmentDataController::actionDelete() (admin)
     * @uses Module::beforeSave()
     * @uses NewsController::actionCreate() (admin)
     * @uses NewsController::actionUpdate() (admin)
     * @uses NewsController::actionDelete() (admin)
     * @uses NewsController::actionRestore() (admin)
     * @uses PageController::actionCreate() (admin)
     * @uses PageController::actionUpdate() (admin)
     * @uses PageController::actionDelete() (admin)
     * @uses TelephoneController::actionCreate() (admin)
     * @uses TelephoneController::actionUpdate() (admin)
     * @uses Conference::beforeSave()
     */
    public static function setLog($lastRecord, $operation)
    {
        return $lastRecord.'$'.date('d.m.Y H:i:s').'|'.$operation.'|'
            .(isset(Yii::$app->user->identity->username) ? Yii::$app->user->identity->username : 'guest');
    }
}