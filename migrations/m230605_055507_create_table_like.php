<?php

use yii\db\Migration;

/**
 * Class m230605_055507_create_table_like
 */
class m230605_055507_create_table_like extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%like}}', [
            'id' => $this->primaryKey(),
            'unique' => $this->string(250)->notNull()->unique(),            
            'count' => $this->integer(),
            'filter_allow' => $this->string(2000),
        ]);

        $this->createTable('{{%like_data}}', [
            'id' => $this->primaryKey(),
            'id_like' => $this->integer(),
            'username' => $this->string(250)->notNull(),
            'date_create' => $this->integer()->notNull(),
        ]);

        $this->execute("
            CREATE TRIGGER tr_like_data ON  {{%like_data}}
                AFTER INSERT,DELETE
            AS
            BEGIN
                DECLARE @id INT
            
                IF EXISTS(SELECT 1 FROM inserted)
                BEGIN
                    SELECT TOP 1 @id = [[id_like]] FROM inserted
                END ELSE
                BEGIN
                    SELECT TOP 1 @id = [[id_like]] FROM deleted
                END
                    
                UPDATE {{%like}} 
                    SET [[count]] = (SELECT COUNT(*) FROM {{%like_data}} WHERE [[id_like]] = @id)
                WHERE [[id]] = @id            
            END
        ");

        $this->addForeignKey('fk__like_data__id_like', '{{%like_data}}', 'id_like',
            '{{%like}}', 'id', 'cascade');
        $this->addForeignKey('fk__like_data__username', '{{%like_data}}', 'username',
            '{{%user}}', 'username');        
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk__like_data__id_like', '{{%like_data}}');
        $this->dropForeignKey('fk__like_data__username', '{{%like_data}}');

        $this->dropTable('{{%like_data}}');
        $this->dropTable('{{%like}}');
    }

}
