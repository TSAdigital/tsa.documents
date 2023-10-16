<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%document_type}}`.
 */
class m231016_171957_create_document_type_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%document_type}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull()->unique(),
            'status' => $this->smallInteger()->notNull()->defaultValue(10),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        $this->batchInsert('{{%document_type}}', ['id', 'name', 'created_at', 'updated_at'], [
                ['7', 'Без категории', time(), time()],
                ['8', 'Входящий', time(), time()],
                ['9', 'Исходящий', time(), time()],
                ['10', 'Внутренний', time(), time()],
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%document_type}}');
    }
}
