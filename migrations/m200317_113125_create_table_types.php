<?php

use yii\db\Migration;

/**
 * Class m200317_113125_create_table_types
 */
class m200317_113125_create_table_types extends Migration
{
    private $tableName = 'menu_tags';

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
        echo "m200317_113125_create_table_types cannot be reverted.\n";

        return false;
    }

    public function up()
    {
        $this->createTable($this->tableName, [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'menu_id' => $this->integer(),
            'updated_at' => $this->timestamp()->defaultExpression('NULL ON UPDATE CURRENT_TIMESTAMP')->null(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);

        // set indexes
        $this->createIndex("{$this->tableName}_index_name", $this->tableName, 'name');
        $this->addForeignKey("{$this->tableName}_foreign_menu_id", $this->tableName, 'menu_id', 'menus', 'id');
    }

    public function down()
    {
        $this->dropTable($this->tableName);
    }
}
