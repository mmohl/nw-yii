<?php

use yii\db\Migration;

/**
 * Class m200229_224051_create_table_order_details
 */
class m200229_224051_create_table_order_details extends Migration
{
    protected $tableName = 'order_details';

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
        echo "m200229_224051_create_table_order_details cannot be reverted.\n";

        return false;
    }


    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->createTable($this->tableName, [
            'id' => $this->primaryKey(),
            'order_id' => $this->integer(),
            'name' => $this->string(),
            'qty' => $this->smallInteger(),
            'price' => $this->integer(),
            'updated_at' => $this->timestamp()->defaultExpression('NULL ON UPDATE CURRENT_TIMESTAMP')->null(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);

        // set foreign key
        $this->addForeignKey("{$this->tableName}_foreign_order_id", $this->tableName, 'order_id', 'orders', 'id');
    }

    public function down()
    {
        $this->dropTable($this->tableName);
    }
}
