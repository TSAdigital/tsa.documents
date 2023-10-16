<?php

use app\models\Document;
use yii\db\Migration;

/**
 * Class m231016_183550_update_type_to_document_table
 */
class m231016_183550_update_type_to_document_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        Document::updateAll(['type' => '7'], ['not', ['type' => [10, 9, 8]]]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m231016_183550_update_type_to_document_table cannot be reverted.\n";
    }
}
