<?php

use yii\db\Migration;

/**
 * Class m200809_033139_add_column_is_ignored_for_table_orders
 */
class m200809_033139_add_column_is_ignored_for_table_orders extends Migration
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
        echo "m200809_033139_add_column_is_ignored_for_table_orders cannot be reverted.\n";

        return false;
    }

    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        // $this->addColumn('orders', 'is_ignored', 'char')
        $this->addColumn('orders', 'is_ignored', $this->char(1)->defaultValue('0'));
    }

    public function down()
    {
        $this->dropColumn('orders', 'is_ignored');
    }
}
