<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%document_task}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%document}}`
 * - `{{%task}}`
 */
class m230728_210456_create_junction_table_for_document_and_task_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%document_task}}', [
            'document_id' => $this->integer(),
            'task_id' => $this->integer(),
            'PRIMARY KEY(document_id, task_id)',
        ]);

        // creates index for column `document_id`
        $this->createIndex(
            '{{%idx-document_task-document_id}}',
            '{{%document_task}}',
            'document_id'
        );

        // add foreign key for table `{{%document}}`
        $this->addForeignKey(
            '{{%fk-document_task-document_id}}',
            '{{%document_task}}',
            'document_id',
            '{{%document}}',
            'id',
            'CASCADE'
        );

        // creates index for column `task_id`
        $this->createIndex(
            '{{%idx-document_task-task_id}}',
            '{{%document_task}}',
            'task_id'
        );

        // add foreign key for table `{{%task}}`
        $this->addForeignKey(
            '{{%fk-document_task-task_id}}',
            '{{%document_task}}',
            'task_id',
            '{{%task}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%document}}`
        $this->dropForeignKey(
            '{{%fk-document_task-document_id}}',
            '{{%document_task}}'
        );

        // drops index for column `document_id`
        $this->dropIndex(
            '{{%idx-document_task-document_id}}',
            '{{%document_task}}'
        );

        // drops foreign key for table `{{%task}}`
        $this->dropForeignKey(
            '{{%fk-document_task-task_id}}',
            '{{%document_task}}'
        );

        // drops index for column `task_id`
        $this->dropIndex(
            '{{%idx-document_task-task_id}}',
            '{{%document_task}}'
        );

        $this->dropTable('{{%document_task}}');
    }
}
