<?php

use yii\db\Migration;

/**
 * Handles adding position to table `billing_operations`.
 * Has foreign keys to the tables:
 *
 * - `user`
 */
class m161224_023440_add_position_column_to_billing_operations_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('billing_operations', 'reciver_id', $this->integer()->notNull());

        // creates index for column `reciver_id`
        $this->createIndex(
            'idx-billing_operations-reciver_id',
            'billing_operations',
            'reciver_id'
        );

        // add foreign key for table `user`
        $this->addForeignKey(
            'fk-billing_operations-reciver_id',
            'billing_operations',
            'reciver_id',
            'user',
            'id',
            'CASCADE'
        );
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        // drops foreign key for table `user`
        $this->dropForeignKey(
            'fk-billing_operations-reciver_id',
            'billing_operations'
        );

        // drops index for column `reciver_id`
        $this->dropIndex(
            'idx-billing_operations-reciver_id',
            'billing_operations'
        );

        $this->dropColumn('billing_operations', 'reciver_id');
    }
}
