<?php

namespace app\modules\contest\models\quest;

use app\modules\contest\models\quest\Quest;

class Crossword 
{

    private static $words = [
        'vertical' => [
            1 => [
                'text' => 'СОЦИАЛЬНЫЙ',
                'startCol' => 3,
                'startRow' => 3,   
                'question' => 'Налоговый вычет, предоставляющийся при подтверждении расходов на благотворительность, образование, лечение',          
            ],
            2 => [
                'text' => 'КОСВЕННЫЙ',
                'startCol' => 9,
                'startRow' => 3,         
                'question' => 'Группа налогов, к которой относится таможенная пошлина',    
            ],
            3 => [
                'text' => 'ШТРАФ',
                'startCol' => 11,
                'startRow' => 1,   
                'question' => 'Разновидность налоговой санкции за не предоставленную в установленный срок налоговую декларацию',        
            ],
            4 => [
                'text' => 'ПЕНЯ',
                'startCol' => 14,
                'startRow' => 2,             
                'question' => 'Сумма денег, которую должен заплатить налогоплательщик в случае нарушения сроков уплаты налога',
            ],
            5 => [
                'text' => 'НАЛОГОВЫЙ',
                'startCol' => 18,
                'startRow' => 3,
                'question' => 'Кодекс, содержащий свод законодательных актов о налогообложении',             
            ],
        ],
        'horizontal' => [
            1 => [
                'text' => 'ПРОФЕССИОНАЛЬНЫЙ',
                'startCol' => 1,
                'startRow' => 4,    
                'question' => 'Налоговый вычет - сумма, на которую уменьшается налоговая база по подоходному налогу по доходам, полученным физическими лицами, от предпринимательской деятельности по гражданско-правовым договорам в виде авторского вознаграждения',                   
            ],
            2 => [
                'text' => 'ТИБЕТ',
                'startCol' => 2,
                'startRow' => 6, 
                'question' => 'Страна, в которой взимался налог на уши',            
            ],
            3 => [
                'text' => 'ОЛЬГА',
                'startCol' => 2,
                'startRow' => 8,     
                'question' => 'Родоначальник налоговой системы на Руси',        
            ],
            4 => [
                'text' => 'НАЛОГ',
                'startCol' => 3,
                'startRow' => 10,      
                'question' => 'Основной источник дохода государства',       
            ],
            5 => [
                'text' => 'НАЛОГООБЛОЖЕНИЕ',
                'startCol' => 9,
                'startRow' => 8,   
                'question' => 'Определенный государством в законном порядке процесс установления видов и элементов налогов, сборов, порядка их взимания с определенного круга организаций и физических лиц',         
            ],
        ],
    ];

    /**
     * Входные данные кроссворда
     * @return array
     */
    public static function generateCrossword()
    { 
        $res = [];

        self::generateWordVertical('СОЦИАЛЬНЫЙ', 3, 3, $res, 1);
        self::generateWordVertical('КОСВЕННЫЙ', 9, 3, $res, 2);
        self::generateWordVertical('ШТРАФ', 11, 1, $res, 3);
        self::generateWordVertical('ПЕНЯ', 14, 2, $res, 4);
        self::generateWordVertical('НАЛОГОВЫЙ', 18, 3, $res, 5);

        self::generateWordHorizontal('ПРОФЕССИОНАЛЬНЫЙ', 1, 4, $res, 1);
        self::generateWordHorizontal('ТИБЕТ', 2, 6, $res, 2);
        self::generateWordHorizontal('ОЛЬГА', 2, 8, $res, 3);
        self::generateWordHorizontal('НАЛОГ', 3, 10, $res, 4);
        self::generateWordHorizontal('НАЛОГООБЛОЖЕНИЕ', 9, 8, $res, 5);
        
        ksort($res);
        return $res; 
    }

    public static function getWords()
    {
        return self::$words;
    }

    /**
     * Генерирование строк по вертикали
     * @param string $text - слово
     * @param int $startCol - начальный столбец
     * @param int $startRow - начальная строка
     * @param array $arr - результирующий массив
     * @param int $numQuestion - номер вопроса
     */
    private static function generateWordVertical($text, $startCol, $startRow, &$arr, $numQuestion)
    {
        $indexCol = $startCol;
        $indexRow = $startRow;
        preg_match_all('#.{1}#uis', $text, $out);
        foreach ($out[0] as $char) {
            $arr[$indexRow][$indexCol] = [
                'name' => 'answer[' . $indexRow . '][' . $indexCol . ']',
                'char' => $char,
                'type' => 'vertical',
                'numberQuestion' => $numQuestion,                
            ];
            if ($indexRow == $startRow) {
                $arr[$indexRow-1][$indexCol]['placeholder'] = $numQuestion;
            }
            $indexRow++;
        }
    }

    /**
     * Генерирование строк по горизонтали
     * @param string $text - слово
     * @param int $startCol - начальный столбец
     * @param int $startRow - начальная строка
     * @param array $arr - результирующий массив
     * @param int $numQuestion - номер вопроса
     */
    private static function generateWordHorizontal($text, $startCol, $startRow, &$arr, $numQuestion)
    {
        $indexCol = $startCol;
        $indexRow = $startRow;
        preg_match_all('#.{1}#uis', $text, $out);
        foreach ($out[0] as $char) {
            $arr[$indexRow][$indexCol] = [
                'name' => 'answer[' . $indexRow . '][' . $indexCol . ']',
                'char' => $char,     
                'type' => 'horizontal',
                'numberQuestion' => $numQuestion,           
            ];
            if ($indexCol == $startCol) {
                $arr[$indexRow][$indexCol-1]['placeholder'] = $numQuestion;
            }
            $indexCol++;
        }
    }

          
    public static function checkResult($crossword, $result)
    {
        $words = [];
        foreach($crossword as $row=>$itemArr) {
            foreach ($itemArr as $col=>$item) {
                if (!isset($item['char'])) {
                    continue;
                }
                $char1 = $item['char'];
                $char2 = isset($result[$row][$col]) ? $result[$row][$col] : null;
                $comare = mb_strtoupper($char1, 'utf-8') === mb_strtoupper($char2, 'utf-8');
                if (isset($words[$item['type']][$item['numberQuestion']])) {
                    if ($words[$item['type']][$item['numberQuestion']]) { 
                        $words[$item['type']][$item['numberQuestion']] = $comare;
                    }
                }
                else {
                    $words[$item['type']][$item['numberQuestion']] = $comare;
                }
            }
        }
        return $words;
    }

    /**
     * Сохранение результата
     * @param array $post
     * @param array $check
     */
    public static function saveResult($post, $check)
    {
        $data = [
            'post' => $post,
            'check' => $check,
        ];
        $balls = 0;
        foreach($check as $item) {
            foreach($item as $i) {
                if ($i) {
                    $balls++;
                }
            }
        }
        Quest::saveResult(2, $balls, $data);
    }

}