<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%sign_file}}`.
 */
class m240408_162159_create_sign_file_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%sign_file}}', [
            'id' => $this->primaryKey(),
            'sign' => $this->text()->notNull(),
            'file_id' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        $this->createIndex(
            'idx-sign-file-user-id',
            'sign_file',
            'user_id'
        );

        $this->addForeignKey(
            'fk-sign-file-user-id',
            'sign_file',
            'user_id',
            'user',
            'id',
            'CASCADE'
        );

        $this->createIndex(
            'idx-sign-file-file-id',
            'sign_file',
            'file_id'
        );

        $this->addForeignKey(
            'fk-sign-file-file-id',
            'sign_file',
            'file_id',
            'upload',
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
            'fk-sign-file-user-id',
            'sign_file'
        );

        $this->dropIndex(
            'idx-sign-file-user-id',
            'sign_file'
        );

        $this->dropForeignKey(
            'fk-sign-file-file-id',
            'sign_file'
        );

        $this->dropIndex(
            'idx-sign-file-file-id',
            'sign_file'
        );

        $this->dropTable('{{%sign_file}}');
    }
}
