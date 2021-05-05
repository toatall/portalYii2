<?php

use yii\db\Migration;

/**
 * Class m210217_044309_alter_table_conference
 */
class m210217_044309_alter_table_conference extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%conference}}', 'date_end', $this->dateTime());
        $this->addColumn('{{%conference}}', 'format_holding', $this->string(50)); // формат проведения
        $this->addColumn('{{%conference}}', 'members_count', $this->string(10)); // количество участников
        $this->addColumn('{{%conference}}', 'material_translation', $this->string(250)); // материалы для трансляции 
        $this->addColumn('{{%conference}}', 'members_count_ufns', $this->string(10)); // количество участников со стороны Управления
        $this->addColumn('{{%conference}}', 'person_head', $this->string(500)); // председательствующий 
        $this->addColumn('{{%conference}}', 'link_event', $this->string(500)); // Ссылка на мероприятие
        $this->addColumn('{{%conference}}', 'is_connect_vks_fns', $this->boolean()); // Признак подключения к ВКС ЦА ФНС России
        $this->addColumn('{{%conference}}', 'platform', $this->string(250)); // Платформа
        $this->addColumn('{{%conference}}', 'full_name_support_ufns', $this->string(500)); // ФИО тех. специалиста Управления
        $this->addColumn('{{%conference}}', 'date_test_vks', $this->dateTime()); // Дата проведения тестового ВКС
        $this->addColumn('{{%conference}}', 'count_notebooks', $this->string(10)); // Количество ноутбуков
        $this->addColumn('{{%conference}}', 'is_change_time_gymnastic', $this->boolean()); // Требуется перенос проведения зарядки (требуется согласование с приемной)   
                
        $this->createTable('{{%conference_location}}', [
            'val' => $this->string(200)->notNull()->unique(),
        ]);
        $this->addPrimaryKey('pk-conference_location-val', '{{%conference_location}}', 'val');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%conference_location}}');
        
        $this->dropColumn('{{%conference}}', 'format_holding');
        $this->dropColumn('{{%conference}}', 'members_count');
        $this->dropColumn('{{%conference}}', 'material_translation');
        $this->dropColumn('{{%conference}}', 'members_count_ufns');
        $this->dropColumn('{{%conference}}', 'person_head');
        $this->dropColumn('{{%conference}}', 'link_event');
        $this->dropColumn('{{%conference}}', 'is_connect_vks_fns');
        $this->dropColumn('{{%conference}}', 'platform');
        $this->dropColumn('{{%conference}}', 'full_name_support_ufns');
        $this->dropColumn('{{%conference}}', 'date_test_vks');
        $this->dropColumn('{{%conference}}', 'count_notebooks');
        $this->dropColumn('{{%conference}}', 'is_change_time_gymnastic');
        $this->dropColumn('{{%conference}}', 'date_end');
    }
    
}
