<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%sign_document}}`.
 */
class m240405_051237_create_sign_document_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%sign_document}}', [
            'id' => $this->primaryKey(),
            'sign' => $this->text()->notNull(),
            'document_id' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        $this->createIndex(
            'idx-sign-document-user-id',
            'sign_document',
            'user_id'
        );

        $this->addForeignKey(
            'fk-sign-document-user-id',
            'sign_document',
            'user_id',
            'user',
            'id',
            'CASCADE'
        );

        $this->createIndex(
            'idx-sign-document-document-id',
            'sign_document',
            'document_id'
        );

        $this->addForeignKey(
            'fk-sign-document-document-id',
            'sign_document',
            'document_id',
            'document',
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
            'fk-sign-document-user-id',
            'sign_document'
        );

        $this->dropIndex(
            'idx-sign-document-user-id',
            'sign_document'
        );

        $this->dropForeignKey(
            'fk-sign-document-document-id',
            'sign_document'
        );

        $this->dropIndex(
            'idx-sign-document-document-id',
            'sign_document'
        );

        $this->dropTable('{{%sign_document}}');
    }
}
