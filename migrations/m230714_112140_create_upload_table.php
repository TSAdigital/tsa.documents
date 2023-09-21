<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%upload}}`.
 */
class m230714_112140_create_upload_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%upload}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull(),
            'type' => $this->string(255)->notNull(),
            'record_id' => $this->integer()->notNull(),
            'dir' => $this->string(255)->notNull(),
            'file_name' => $this->string(255)->notNull(),
            'file_extensions' => $this->string(255)->notNull(),
            'user_id' => $this->integer()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        $this->createIndex(
            'idx-upload-user-id',
            'upload',
            'user_id'
        );

        $this->addForeignKey(
            'fk-upload-user-id',
            'upload',
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
            'fk-upload-user-id',
            'upload'
        );

        $this->dropIndex(
            'idx-upload-user-id',
            'upload'
        );

        $this->dropTable('{{%upload}}');
    }
}
