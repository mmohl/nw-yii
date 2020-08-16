<?php

use yii\db\Migration;

/**
 * Class m200816_023020_add_new_column_table_number_to_order
 */
class m200816_023020_add_new_column_table_number_to_order extends Migration
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
        echo "m200816_023020_add_new_column_table_number_to_order cannot be reverted.\n";

        return false;
    }


    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->addColumn('orders', 'table_number', 'tinyint');
    }

    public function down()
    {
        $this->dropColumn('orders', 'table_number');
    }

}
