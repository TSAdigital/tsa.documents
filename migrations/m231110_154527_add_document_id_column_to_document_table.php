<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%document}}`.
 */
class m231110_154527_add_document_id_column_to_document_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%document}}', 'document_id', $this->integer());

        $this->createIndex(
            'idx-document-document-id',
            'document',
            'document_id'
        );

        $this->addForeignKey(
            'fk-document-document-id',
            'document',
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
            'fk-document-document-id',
            'document'
        );

        $this->dropIndex(
            'idx-document-document-id',
            'document'
        );

        $this->dropColumn('{{%document}}', 'document_id');
    }
}
