<?php

use yii\db\Migration;

/**
 * Handles the creation of table `billing_operations`.
 * Has foreign keys to the tables:
 *
 * - `user`
 */
class m161223_122423_create_billing_operations_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('billing_operations', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'amount' => $this->decimal(18,4)->notNull(),
        ]);

        // creates index for column `user_id`
        $this->createIndex(
            'idx-billing_operations-user_id',
            'billing_operations',
            'user_id'
        );

        // add foreign key for table `user`
        $this->addForeignKey(
            'fk-billing_operations-user_id',
            'billing_operations',
            'user_id',
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
            'fk-billing_operations-user_id',
            'billing_operations'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            'idx-billing_operations-user_id',
            'billing_operations'
        );

        $this->dropTable('billing_operations');
    }
}
