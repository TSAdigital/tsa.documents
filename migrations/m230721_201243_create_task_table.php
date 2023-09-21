<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%task}}`.
 */
class m230721_201243_create_task_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%task}}', [
            'id' => $this->primaryKey(),
            'uniq_id' => $this->string(255)->notNull(),
            'name' => $this->string(255)->notNull(),
            'description' => $this->text(),
            'date' => $this->integer()->notNull(),
            'executor_id' => $this->integer(),
            'resolution' => $this->json()->defaultValue(NULL),
            'priority' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
            'status' => $this->smallInteger()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        $this->createIndex(
            'idx-task-user-id',
            'task',
            'user_id'
        );

        $this->addForeignKey(
            'fk-task-user-id',
            'task',
            'user_id',
            'user',
            'id',
            'CASCADE'
        );

        $this->createIndex(
            'idx-task-executor-id',
            'task',
            'executor_id'
        );

        $this->addForeignKey(
            'fk-task-executor-id',
            'task',
            'executor_id',
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
            'fk-task-user-id',
            'task'
        );

        $this->dropIndex(
            'idx-task-user-id',
            'task'
        );

        $this->dropForeignKey(
            'fk-task-executor-id',
            'task'
        );

        $this->dropIndex(
            'idx-task-executor-id',
            'task'
        );

        $this->dropTable('{{%task}}');
    }
}
