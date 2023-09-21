<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%task_favourites}}`.
 */
class m230730_212301_create_task_favourites_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%task_favourites}}', [
            'id' => $this->primaryKey(),
            'task_id' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        $this->createIndex(
            'idx-task-favourites-document-id',
            'task_favourites',
            'task_id'
        );

        $this->addForeignKey(
            'fk-task-favourites-document-id',
            'task_favourites',
            'task_id',
            'task',
            'id',
            'CASCADE'
        );

        $this->createIndex(
            'idx-task-favourites-user-id',
            'task_favourites',
            'user_id'
        );

        $this->addForeignKey(
            'fk-task-favourites-user-id',
            'task_favourites',
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
            'fk-task-favourites-user-id',
            'task_favourites'
        );

        $this->dropIndex(
            'idx-task-favourites-user-id',
            'task_favourites'
        );

        $this->dropForeignKey(
            'fk-task-favourites-document-id',
            'task_favourites'
        );

        $this->dropIndex(
            'idx-task-favourites-document-id',
            'task_favourites'
        );

        $this->dropTable('{{%task_favourites}}');
    }
}
