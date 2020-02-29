<?php

use yii\db\Migration;

/**
 * Class m200229_224738_create_table_categories
 */
class m200229_224738_create_table_categories extends Migration
{
    protected $tableName = 'categories';

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
        echo "m200229_224738_create_table_categories cannot be reverted.\n";

        return false;
    }


    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->createTable($this->tableName, [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'updated_at' => $this->timestamp()
                ->defaultExpression('NULL ON UPDATE CURRENT_TIMESTAMP')
                ->null(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);

        // set indexes
        $this->createIndex("{$this->tableName}_index_name", $this->tableName, 'name');
    }

    public function down()
    {
        $this->dropTable($this->tableName);
    }
}
