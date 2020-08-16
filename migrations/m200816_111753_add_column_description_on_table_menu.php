<?php

use yii\db\Migration;

/**
 * Class m200816_111753_add_column_description_on_table_menu
 */
class m200816_111753_add_column_description_on_table_menu extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200816_111753_add_column_description_on_table_menu cannot be reverted.\n";

        return false;
    }


    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->addColumn('menus', 'description', 'text');
    }

    public function down()
    {
        $this->dropColumn('menus', 'description');
    }
}
