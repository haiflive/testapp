<?php

use yii\db\Migration;

/**
 * Handles the creation of table `invoice`.
 * Has foreign keys to the tables:
 *
 * - `user`
 * - `user`
 */
class m161223_122430_create_invoice_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('invoice', [
            'id' => $this->primaryKey(),
            'owner_id' => $this->integer()->notNull(),
            'for_user_id' => $this->integer()->notNull(),
            'status' => $this->integer()->defaultValue(1),
            'amount' => $this->decimal(18,4)->notNull(),
        ]);

        // creates index for column `owner_id`
        $this->createIndex(
            'idx-invoice-owner_id',
            'invoice',
            'owner_id'
        );

        // add foreign key for table `user`
        $this->addForeignKey(
            'fk-invoice-owner_id',
            'invoice',
            'owner_id',
            'user',
            'id',
            'CASCADE'
        );

        // creates index for column `for_user_id`
        $this->createIndex(
            'idx-invoice-for_user_id',
            'invoice',
            'for_user_id'
        );

        // add foreign key for table `user`
        $this->addForeignKey(
            'fk-invoice-for_user_id',
            'invoice',
            'for_user_id',
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
            'fk-invoice-owner_id',
            'invoice'
        );

        // drops index for column `owner_id`
        $this->dropIndex(
            'idx-invoice-owner_id',
            'invoice'
        );

        // drops foreign key for table `user`
        $this->dropForeignKey(
            'fk-invoice-for_user_id',
            'invoice'
        );

        // drops index for column `for_user_id`
        $this->dropIndex(
            'idx-invoice-for_user_id',
            'invoice'
        );

        $this->dropTable('invoice');
    }
}
