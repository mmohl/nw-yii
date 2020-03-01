<?php

use yii\db\Migration;

/**
 * Class m200301_025006_add_column_rounding_on_table_orders
 */
class m200301_025006_add_column_rounding_on_table_orders extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    { }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200301_025006_add_column_rounding_on_table_orders cannot be reverted.\n";

        return false;
    }


    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->addColumn('orders', 'rounding', 'int');
    }

    public function down()
    {
        $this->dropColumn('orders', 'rounding');
    }
}
