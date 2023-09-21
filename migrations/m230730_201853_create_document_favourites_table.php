<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%document_favourites}}`.
 */
class m230730_201853_create_document_favourites_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%document_favourites}}', [
            'id' => $this->primaryKey(),
            'document_id' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        $this->createIndex(
            'idx-document-favourites-document-id',
            'document_favourites',
            'document_id'
        );

        $this->addForeignKey(
            'fk-document-favourites-document-id',
            'document_favourites',
            'document_id',
            'document',
            'id',
            'CASCADE'
        );

        $this->createIndex(
            'idx-document-favourites-user-id',
            'document_favourites',
            'user_id'
        );

        $this->addForeignKey(
            'fk-document-favourites-user-id',
            'document_favourites',
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
            'fk-document-favourites-user-id',
            'document_favourites'
        );

        $this->dropIndex(
            'idx-document-favourites-user-id',
            'document_favourites'
        );

        $this->dropForeignKey(
            'fk-document-favourites-document-id',
            'document_favourites'
        );

        $this->dropIndex(
            'idx-document-favourites-document-id',
            'document_favourites'
        );

        $this->dropTable('{{%document_favourites}}');
    }
}
