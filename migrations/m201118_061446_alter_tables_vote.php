<?php

use yii\db\Migration;

/**
 * Class m201118_061446_create_table_view_log
 */
class m201118_061446_alter_tables_vote extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%vote_main}}', 'count_answers', $this->integer());
        $this->addColumn('{{%vote_question}}', 'text_html', $this->string('max'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%vote_question}}', 'text_html');
        $this->dropColumn('{{%vote_main}}', 'count_answers');
    }
}
