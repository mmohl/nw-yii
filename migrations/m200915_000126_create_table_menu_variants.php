<?php

use yii\db\Migration;

/**
 * Class m200915_000126_create_table_menu_variants
 */
class m200915_000126_create_table_menu_variants extends Migration
{
    protected $tableName = 'menu_variants';
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
        echo "m200915_000126_create_table_menu_variants cannot be reverted.\n";

        return false;
    }


    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->createTable($this->tableName, [
            'id' => $this->primaryKey(),
            'menu_id' => $this->integer(),
            'parent_id' => $this->integer()->null(),
            'level' => $this->tinyInteger(),
            'price' => $this->integer(),
            'label' => $this->string(),
            'is_enabled' => $this->tinyInteger()->defaultValue(1),
            'updated_at' => $this->timestamp()->defaultExpression('NULL ON UPDATE CURRENT_TIMESTAMP')->null(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);

        // set indexes
        $this->createIndex("{$this->tableName}_menu_id", $this->tableName, 'menu_id');
        $this->createIndex("{$this->tableName}_level", $this->tableName, 'level');
        $this->createIndex("{$this->tableName}_parent_id", $this->tableName, 'parent_id');
    }

    public function down()
    {
        $this->dropTable($this->tableName);
    }
}
