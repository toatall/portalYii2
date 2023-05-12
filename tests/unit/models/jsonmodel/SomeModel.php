<?php
namespace app\tests\unit\models\jsonmodel;

use app\models\json\JsonModel;


/**
 * @property int $id
 * @property string $name
 * @property string $code
 * @property string $text
 * @property string $date
 */
class SomeModel extends JsonModel
{
    /**
     * Имя файла
     * @var string
     */
    public static $fileName = null;    

    /**
     * {@inheritDoc}
     */
    public static function getJsonFile(): string
    {       
        return self::$fileName ?? '/tests/_data/data.json';
    }

    /**
     * {@inheritDoc}
     */
    public function attributeLabels(): array
    {
        return array_merge(parent::attributeLabels(), 
        [            
            'id' => 'Id',
            'name' => 'Name',
            'text' => 'Text',
        ]);
    }
    
}