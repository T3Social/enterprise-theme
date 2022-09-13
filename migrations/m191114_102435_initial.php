<?php


use humhub\components\Migration;
use yii\db\Schema;

/**
 * Class m191114_102435_initial
 */
class m191114_102435_initial extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        try {
            $this->createTable('space_type', [
                'id' => $this->primaryKey(),
                'title' => $this->string(100)->notNull(),
                'item_title' => $this->string(100)->notNull(),
                'sort_key' => $this->integer()->defaultValue(100)->notNull(),
                'show_in_directory' => $this->boolean()->defaultValue(true)->notNull(),
            ]);

            $this->insert('space_type', [
                'id' => 1,
                'title' => 'Spaces',
                'item_title' => 'Space',
                'sort_key' => 100,
                'show_in_directory' => true,
            ]);

            $this->addColumn('space', 'space_type_id', Schema::TYPE_BIGINT);
            $this->update('space', ['space_type_id' => 1]);

        } catch (\Exception $ex) {}
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m191114_102435_initial cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m191114_102435_initial cannot be reverted.\n";

        return false;
    }
    */
}
