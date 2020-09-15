<?php

use yii\db\Migration;

/**
 * Class m200915_000909_add_columns_to_table_order_detail
 */
class m200915_000909_add_columns_to_table_order_detail extends Migration
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
        echo "m200915_000909_add_columns_to_table_order_detail cannot be reverted.\n";

        return false;
    }


    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->addColumn('order_details', 'classname', 'string');
    }

    public function down()
    {
        $this->dropColumn('order_details', 'classname');
    }
}
