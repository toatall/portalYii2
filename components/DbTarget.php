<?php
namespace app\components;

use Exception;
use yii\helpers\VarDumper;
use yii\log\LogRuntimeException;

class DbTarget extends \yii\log\DbTarget
{
    
    public $db = 'dbPgsqlLog';
    
    public function export() 
    {
        try {
            $currentUser = \Yii::$app->user->identity->username ?? 'guest ("' . \Yii::$app->request->userHost .  '")';
            
            $stausCode = \Yii::$app->response->statusCode ?? null;
            $statusText = \Yii::$app->response->statusText ?? null;
            $url = \Yii::$app->request->url ?? null;

            if ($this->db->getTransaction()) {
                // create new database connection, if there is an open transaction
                // to ensure insert statement is not affected by a rollback
                $this->db = clone $this->db;
            }

            $tableName = $this->db->quoteTableName($this->logTable);
            $sql = "INSERT INTO $tableName ([[level]], [[user]], [[category]], [[url]], [[statusCode]], [[statusText]], [[log_time]], [[prefix]], [[message]])
                    VALUES (:level, :user, :category, :url, :statusCode, :statusText, :log_time, :prefix, :message)";
            $command = $this->db->createCommand($sql);
            foreach ($this->messages as $message) {
                list($text, $level, $category, $timestamp) = $message;
                if (!is_string($text)) {
                    // exceptions may not be serializable if in the call stack somewhere is a Closure
                    if ($text instanceof \Exception || $text instanceof \Throwable) {
                        $text = (string) $text;
                    } else {
                        $text = VarDumper::export($text);
                    }
                }
                if ($command->bindValues([
                        ':level' => $level,
                        ':user' => $currentUser,
                        ':category' => $category,
                        ':url' => $url,
                        ':statusCode' => $stausCode,
                        ':statusText' => $statusText,
                        ':log_time' => $timestamp,
                        ':prefix' => $this->getMessagePrefix($message),
                        ':message' => $text,
                    ])->execute() > 0) {
                    continue;
                }
                throw new LogRuntimeException('Unable to export log through database!');
            }        
        }
        catch(Exception $ex) {}                
    }
    
}
