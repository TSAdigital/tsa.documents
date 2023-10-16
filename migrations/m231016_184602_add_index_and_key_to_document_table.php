<?php

use yii\db\Migration;

/**
 * Class m231016_180602_add_index_and_key_to_document_table
 */
class m231016_184602_add_index_and_key_to_document_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createIndex(
            'idx-document-type',
            'document',
            'type'
        );

        $this->addForeignKey(
            'fk-document-type',
            'document',
            'type',
            'document_type',
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
            'fk-document-type',
            'document'
        );

        $this->dropIndex(
            'idx-document-type',
            'document'
        );
    }
}
