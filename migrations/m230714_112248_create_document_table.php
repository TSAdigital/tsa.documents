<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%certificate}}`.
 */
class m230714_112248_create_document_table extends Migration
{
    /**
     * {@inheritdoc}
     * @throws \yii\base\Exception
     */
    public function safeUp()
    {
        $this->createTable('{{%document}}', [
            'id' => $this->primaryKey(),
            'uniq_id' => $this->string(255)->notNull(),
            'name' => $this->string(255)->notNull(),
            'number' => $this->string(255)->notNull(),
            'date' => $this->date()->notNull(),
            'resolution' => $this->json()->defaultValue(NULL),
            'user_id' => $this->integer()->notNull(),
            'status' => $this->smallInteger()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        $this->createIndex(
            'idx-document-user-id',
            'document',
            'user_id'
        );

        $this->addForeignKey(
            'fk-document-user-id',
            'document',
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
            'fk-document-user-id',
            'document'
        );

        $this->dropIndex(
            'idx-document-user-id',
            'document'
        );

        $this->dropTable('{{%document}}');
    }
}
