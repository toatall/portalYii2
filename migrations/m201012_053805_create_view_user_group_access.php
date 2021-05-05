<?php

use yii\db\Migration;

/**
 * Class m201012_053805_create_view_user_group_access
 */
class m201012_053805_create_view_user_group_access extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // view_user_in_group
        $this->execute('
            create view {{%view_user_in_group}}
            as
            select a.id, a.id_organization, a.name, a.description, a.sort, a.date_create, a.date_edit, b.id_user
            from dbo.p_group AS a 
            right outer join {{%group_user}} AS b ON a.id = b.id_group');

        // view_access_tree
        $this->execute('
            create view {{%view_access_tree}} 
            as
            select * from 
            (
                select a.id, b.id_user, b.id_organization from {{%tree}} a
                    left join {{%access_user}} b on a.id = b.id_tree	
                union
                select a.id, c.id_user, b.id_organization from {{%tree}} a
                    left join {{%access_group}} b on a.id = b.id_tree
                    left join {{%view_user_in_group}} c on c.id = b.id_group 
            ) as t
            where t.id_user is not null');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->execute('drop view {{%view_access_tree}}');
        $this->execute('drop view {{%view_user_in_group}}');
    }

}

