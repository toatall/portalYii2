<?php
namespace app\components;

use yii\db\Query;

class DbManager extends \yii\rbac\DbManager
{
    /**
     * {@inheritdoc}
     */
    protected function addRule($rule)
    {
        $time = time();
        if ($rule->createdAt === null) {
            $rule->createdAt = $time;
        }
        if ($rule->updatedAt === null) {
            $rule->updatedAt = $time;
        }
        
        $command = $this->db->createCommand()
            ->insert($this->ruleTable, [
                'name' => $rule->name,
                'data' => serialize($rule),
                'created_at' => $rule->createdAt,
                'updated_at' => $rule->updatedAt,
            ]);
                
        $this->db->pdo->exec($command->rawSql);        
        
        $this->invalidateCache();
        
        return true;
    }
    
    /**
     * {@inheritdoc}
     */
    protected function updateRule($name, $rule)
    {
        if ($rule->name !== $name && !$this->supportsCascadeUpdate()) {
            $this->db->createCommand()
            ->update($this->itemTable, ['rule_name' => $rule->name], ['rule_name' => $name])
            ->execute();
        }
        
        $rule->updatedAt = time();
        
        $command = $this->db->createCommand()
            ->update($this->ruleTable, [
                'name' => $rule->name,
                'data' => serialize($rule),
                'updated_at' => $rule->updatedAt,
            ], [
                'name' => $name,
            ]);
        $this->db->pdo->exec($command->rawSql);
        
        $this->invalidateCache();
        
        return true;
    }
    
    /**
     * {@inheritdoc}
     */
    public function getRule($name)
    {
        if ($this->rules !== null) {
            return isset($this->rules[$name]) ? $this->rules[$name] : null;
        }
        
        $row = (new Query())->select(['cast(data as varchar(max)) [data]'])
        ->from($this->ruleTable)
        ->where(['name' => $name])
        ->one($this->db);
        if ($row === false) {
            return null;
        }
        $data = $row['data'];
        if (is_resource($data)) {
            $data = stream_get_contents($data);
        }
        
        return unserialize($data);
    }
    
}

