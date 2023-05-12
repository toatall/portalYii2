<?php

use yii\db\Migration;

/**
 * Class m220408_051407_create_table_change_legislation
 */
class m220408_051407_create_table_change_legislation extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%change_legislation}}', [
            'id' => $this->primaryKey(),
            'type_doc' => $this->string(250)->notNull(),
            'date_doc' => $this->date(),
            'number_doc' => $this->string(250),
            'name' => $this->string('max')->notNull(),
            'date_doc_1' => $this->date(),
            'date_doc_2' => $this->date(),
            'date_doc_3' => $this->date(),
            'status_doc' => $this->string(250),
            'text' => $this->text(),
            'is_anti_crisis' => $this->boolean(),
            'date_create' => $this->dateTime()->notNull(),
            'date_update' => $this->dateTime()->notNull(),
            'author' => $this->string(250)->notNull(),
            'log_change' => $this->text(),
        ]);
        $this->addForeignKey('fk__change_legislation__author', '{{%change_legislation}}', 'author', '{{%user}}', 'username'); 
        /*
        $this->execute("            
            IF NOT EXISTS(SELECT 1 FROM SYS.FULLTEXT_CATALOGS WHERE NAME = 'Default')
            BEGIN
                CREATE FULLTEXT CATALOG [Default] AS DEFAULT
            END
            IF NOT EXISTS(SELECT 1 FROM SYS.FULLTEXT_INDEXES WHERE OBJECT_ID = OBJECT_ID('{{%change_legislation}}'))
            BEGIN
                DECLARE @INDEX NVARCHAR(MAX)
                SELECT @INDEX = NAME FROM SYS.INDEXES WHERE OBJECT_ID = OBJECT_ID('{{%change_legislation}}')

                EXEC('
                    CREATE FULLTEXT INDEX ON {{%change_legislation}} KEY INDEX [' + @INDEX + '] ON ([Default]) WITH (CHANGE_TRACKING AUTO)
                    ALTER FULLTEXT INDEX ON {{%change_legislation}} ADD ([name])
                    ALTER FULLTEXT INDEX ON {{%change_legislation}} ADD ([text])
                    ALTER FULLTEXT INDEX ON {{%change_legislation}} ENABLE
                ')
            END        
        ");
        $this->execute("
            ALTER TRIGGER [dbo].[tr_change_legislation]
                ON {{%change_legislation}}
                AFTER INSERT,DELETE,UPDATE
            AS 
            BEGIN                
                ALTER FULLTEXT INDEX ON {{%change_legislation}} START UPDATE POPULATION         
            END
        ");
        */
    }   

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        /*
        $this->exec("
            IF EXISTS(SELECT 1 FROM SYS.FULLTEXT_INDEXES WHERE OBJECT_ID = OBJECT_ID('{{%change_legislation}}'))
            BEGIN
                DROP FULLTEXT INDEX ON {{%change_legislation}}
            END

            IF EXISTS(SELECT 1 FROM SYS.FULLTEXT_CATALOGS WHERE NAME = 'Default')
            BEGIN
                DROP FULLTEXT CATALOG [Default]
            END
        ");
        */
        $this->dropForeignKey('fk__change_legislation__author', '{{%change_legislation}}');
        $this->dropTable('{{%change_legislation}}');
    }


}
