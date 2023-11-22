<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%events}}`.
 */
class m231121_191529_create_event_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%event}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string('255')->notNull(),
            'description' => $this->text(),
            'start' => $this->integer()->notNull(),
            'end' => $this->integer(),
            'color' => $this->string('255'),
            'resolution' => $this->json()->defaultValue(NULL),
            'user_id' => $this->integer()->notNull(),
            'status' => $this->smallInteger()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        $this->createIndex(
            'idx-event-user-id',
            'event',
            'user_id'
        );

        $this->addForeignKey(
            'fk-event-user-id',
            'event',
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
            'fk-event-user-id',
            'event'
        );

        $this->dropIndex(
            'idx-event-user-id',
            'event'
        );

        $this->dropTable('{{%event}}');
    }
}
