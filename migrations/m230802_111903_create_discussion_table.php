<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%discussion}}`.
 */
class m230802_111903_create_discussion_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%discussion}}', [
            'id' => $this->primaryKey(),
            'message' => $this->text()->notNull(),
            'type' => $this->string(255)->notNull(),
            'record_id' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        $this->createIndex(
            'idx-discussion-user-id',
            'discussion',
            'user_id'
        );

        $this->addForeignKey(
            'fk-discussion-user-id',
            'discussion',
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
            'fk-discussion-user-id',
            'discussion'
        );

        $this->dropIndex(
            'idx-discussion-user-id',
            'discussion'
        );

        $this->dropTable('{{%discussion}}');
    }
}
