<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%view}}`.
 */
class m230720_162534_create_view_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%view}}', [
            'id' => $this->primaryKey(),
            'type' => $this->string(255)->notNull(),
            'record_id' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        $this->createIndex(
            'idx-view-user-id',
            'view',
            'user_id'
        );

        $this->addForeignKey(
            'fk-view-user-id',
            'view',
            'user_id',
            'user',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            'fk-view-user-id',
            'view'
        );

        $this->dropIndex(
            'idx-view-user-id',
            'view'
        );

        $this->dropTable('{{%view}}');
    }
}
