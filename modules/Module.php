<?php
namespace app\modules;

use ReflectionClass;
use Yii;
use yii\helpers\FileHelper;
use yii\web\ServerErrorHttpException;

/**
 * @author toatall
 */
class Module extends \yii\base\Module
{

    /**
     * Выполнять проверку миграции модуля
     * @var bool 
     */
    public $checkMigration = true;

    /**
     * При успешной проверке сохранить результат в бессрочный кэш
     * @var bool
     */
    public $cacheSuccessResultMigration = true;

    /**
     * @return string
     */
    public static function getNamespace()
    {
        $r = new ReflectionClass(static::class);
        return $r->getNamespaceName();
    }

    /**
     * @return \yii\db\Connection
     */
    protected static function getDb()
    {
        return Yii::$app->db;
    }

    /**
     * Имя кэша
     * @return stirng
     */
    protected function getCacheKeyName()
    {
        return static::class;
    }

    /**
     * {@inheritdoc}
     */
    public function init()
    {   
        parent::init();     
        if ($this->checkMigration) {
            $this->runCheckMigration();
        }
    }

    /**
     * Проверка применения миграций
     * Если есть модели, наследуемые от AR и в БД отсутствуют таблицы,
     * указанные в моделях, то выводится сообщение о необходимости 
     * выполнения миграций
     */
    protected function runCheckMigration()
    {
        if (Yii::$app->cache->exists($this->getCacheKeyName())) {
            return;
        }

        $namespace = static::getNamespace();
        
        // поиск классов
        $partPath = str_replace('app\\', '', $namespace);
        $path = FileHelper::normalizePath(Yii::getAlias('@app') . '/' . $partPath . '/models/');
        if (!is_dir($path)) {
            return false;
        }       

        // удаление расширений, добавление namespace
        $models = array_map(function($value) use($namespace) {            
            $pathInfo = pathinfo($value);
            return '\\' . $namespace . '\\models\\' . $pathInfo['filename'];
        }, @glob($path . '/*.php'));
       

        $errors = [];
        foreach($models as $model) {            
            if (new $model instanceof \yii\db\ActiveRecord) {
                $table = $model::tableName();
                $query = static::getDb()
                    ->createCommand('select 1 from sys.tables where name=:name', [':name' => $table])
                    ->query();
                if (!$query) {
                    $errors[] = "The table {$table} is not exists!";
                }
            }
        }

        if ($errors) {
            $command = '> php yii migrate --migrationPath=@' . str_replace('\\', '/', $namespace) . '/migrations';
            throw new ServerErrorHttpException(implode("\n", $errors) . "\n\nYou have to run migrations for this module\n" . $command);
        }
        else {
            Yii::$app->cache->set($this->getCacheKeyName(), true, 0);
        }
    }

    
}