<?php

namespace app\modules\contest\models\quest;

use Yii;
use yii\db\Expression;
use yii\db\Query;

class Linked 
{
    private static $lists = [
        'left' => [
            [
                'id' => 1,
                'name' => 'Это норма обложения с единицы налоговой базы',
            ],
            [
                'id' => 2,
                'name' => 'Это мера ответственности за нарушение налогового законодательства, применяемая в виде штрафа',
            ],
            [
                'id' => 3,
                'name' => 'Определяет объект налогообложения в денежном или физическом (техническом) выражении',
            ],
            [
                'id' => 4,
                'name' => 'Это право налогоплательщика на частичное или полное освобождение от уплаты налога, предусмотренное налоговым законодательством',
            ],
            [
                'id' => 5,
                'name' => 'Сумма, на которую уменьшается налоговая база по подоходному налогу в определённых законом случаях',
            ],
            [
                'id' => 6,
                'name' => 'Это документ, содержащий информацию о расчёте суммы налога к уплате, отправляемый налоговым органом налогоплательщику',
            ],
            [
                'id' => 7,
                'name' => 'Физическое либо юридическое лицо, имеющее регистрацию в России и полностью подчиняется ее национальному законодательству',
            ],
            [
                'id' => 8,
                'name' => 'Это документ установленной формы, оформляемый налогоплательщиком и подтверждающий информацию об объекте налогообложения, налоговой базе и других фактах, связанных с исчислением и уплатой налога',
            ],
            [
                'id' => 9,
                'name' => 'Время, за которое необходимо заплатить налог',
            ],
            [
                'id' => 10,
                'name' => 'Это организация, которой законодательно вверено исчислять, удерживать и уплачивать налог за налогоплательщика с выплачиваемого ему доход',
            ]		            
        ],
        'right' => [
            [
                'id' => 1,
                'name' => 'Налоговый агент',
                'linkId' => 10,
            ],
            [
                'id' => 2,
                'name' => 'Налоговая ставка',
                'linkId' => 1,
            ],
            [
                'id' => 3,
                'name' => 'Налоговое уведомление',
                'linkId' => 6,
            ],
            [
                'id' => 4,
                'name' => 'Налоговая декларация',
                'linkId' => 8,
            ],
            [
                'id' => 5,
                'name' => 'Налоговые санкции',
                'linkId' => 2,
            ],
            [
                'id' => 6,
                'name' => 'Налоговый период',
                'linkId' => 9,
            ],
            [
                'id' => 7,
                'name' => 'Налоговая база',
                'linkId' => 3,
            ],
            [
                'id' => 8,
                'name' => 'Налоговая льгота',
                'linkId' => 4,
            ],
            [
                'id' => 9,
                'name' => 'Налоговый вычет',
                'linkId' => 5,
            ],
            [
                'id' => 10,
                'name' => 'Резиденты',
                'linkId' => 7,
            ],    
        ],
    ]; 

    /**
     * @return array
     */
    public static function getListA()
    {
        return self::$lists['left'];
    }

    /**
     * @return array
     */
    public static function getListB()
    {
        return self::$lists['right'];
    }

    /**
     * Проверка результата теста
     * @param array $result
     * @return int - подсчет количества баллов 
     */
    public static function checkResult($result)
    {
        $balls = 0;
        foreach($result as $item) {
            $row = self::findById($item['idB']);
            if (!empty($row) && $row['linkId'] == $item['idA']) {
                $balls++;
            }
        }
        self::saveResult($balls, $result);
        return $balls;
    }

    /**
     * Сохранение результатов
     * @param int $balls 
     * @param array $res
     */
    protected static function saveResult($balls, $res)
    {
        $query = Quest::findResult(1);
        if (!$query) {
            Yii::$app->db->createCommand()
                ->insert('{{%contest_quest}}', [
                    'step' => 1,
                    'balls' => $balls,
                    'data' => serialize($res),
                    'username' => Yii::$app->user->identity->username,
                    'date_create' => new Expression('getdate()'),                
                ])
                ->execute();
        }
    }

    /**
     * Поиск записи в self::getListB по id
     * @param int $id
     * @return array
     */
    protected static function findById($id)
    {
        $result = [];
        foreach (self::getListB() as $item) {
            if ($item['id'] == $id) {
                $result = $item;
                break;
            }
        }
        return $result;
    }

}