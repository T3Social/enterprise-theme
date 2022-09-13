<?php


use humhub\components\Migration;
use yii\db\Schema;

/**
 * Class m191114_102435_initial
 */
class m191114_102436_set_space_id_of_unassigned_spaces extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        try {
            $this->update('space', ['space_type_id' => 1], 'space_type_id IS NULL');
        } catch (\Exception $ex) {
            Yii::error($ex);
        }
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
