<?php

use yii\db\Migration;

/**
 * Class m200229_222736_create_table_orders
 */
class m200229_222736_create_table_orders extends Migration
{
    protected $tableName = 'orders';

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
        echo "m200229_222736_create_table_orders cannot be reverted.\n";

        return false;
    }


    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->createTable($this->tableName, [
            'id' => $this->primaryKey(),
            'order_code' => $this->string(15)->unique(),
            'date' => $this->date(),
            'is_paid' => $this->char(1)->defaultValue(0),
            'ordered_by' => $this->string(),
            'total_payment' => $this->integer(),
            'updated_at' => $this->timestamp()->defaultExpression('NULL ON UPDATE CURRENT_TIMESTAMP')->null(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);

        //set indexes
        $this->createIndex("{$this->tableName}_index_date", $this->tableName, 'date');
        $this->createIndex("{$this->tableName}_index_is_paid", $this->tableName, 'is_paid');
    }

    public function down()
    {
        $this->dropTable($this->tableName);
    }
}
