<?php

use yii\db\Migration;

/**
 * Class m200229_224412_create_table_menus
 */
class m200229_224412_create_table_menus extends Migration
{
    protected $tableName = 'menus';

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
        echo "m200229_224412_create_table_menus cannot be reverted.\n";

        return false;
    }

    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->createTable($this->tableName, [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'category' => $this->string(),
            'price' => $this->integer(),
            'img' => $this->string()->null(),
            'updated_at' => $this->timestamp()->defaultExpression('NULL ON UPDATE CURRENT_TIMESTAMP')->null(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);

        // set indexes
        $this->createIndex("{$this->tableName}_index_name", $this->tableName, 'name');
        $this->createIndex("{$this->tableName}_index_category", $this->tableName, 'category');
        $this->createIndex("{$this->tableName}_index_price", $this->tableName, 'price');
    }

    public function down()
    {
        $this->dropTable($this->tableName);
    }
}
