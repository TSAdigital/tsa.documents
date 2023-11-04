<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%news}}`.
 */
class m231104_155942_create_news_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%news}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(255)->notNull(),
            'text' => $this->text(),
            'user_id' => $this->integer()->notNull(),
            'status' => $this->smallInteger()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        $this->createIndex(
            'idx-news-user-id',
            'news',
            'user_id'
        );

        $this->addForeignKey(
            'fk-news-user-id',
            'news',
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
            'fk-news-user-id',
            'news'
        );

        $this->dropIndex(
            'idx-news-user-id',
            'news'
        );

        $this->dropTable('{{%news}}');
    }
}
