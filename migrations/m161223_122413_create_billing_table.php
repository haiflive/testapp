<?php

use yii\db\Migration;

/**
 * Handles the creation of table `billing`.
 * Has foreign keys to the tables:
 *
 * - `user`
 */
class m161223_122413_create_billing_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('billing', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'balance' => $this->decimal(18,4)->defaultValue(0),
        ]);

        // creates index for column `user_id`
        $this->createIndex(
            'idx-billing-user_id',
            'billing',
            'user_id'
        );

        // add foreign key for table `user`
        $this->addForeignKey(
            'fk-billing-user_id',
            'billing',
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
            'fk-billing-user_id',
            'billing'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            'idx-billing-user_id',
            'billing'
        );

        $this->dropTable('billing');
    }
}
