<?php

use yii\db\Migration;

/**
 * Class m200820_115515_add_column_menu_id_on_table_order_detail
 */
class m200820_115515_add_column_menu_id_on_table_order_detail extends Migration
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
        echo "m200820_115515_add_column_menu_id_on_table_order_detail cannot be reverted.\n";

        return false;
    }


    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->addColumn('order_details', 'menu_id', 'int');
    }

    public function down()
    {
        $this->dropColumn('order_details', 'menu_id');
    }
}
