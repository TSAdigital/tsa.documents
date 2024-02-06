<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%session}}`.
 */
class m240206_120600_create_session_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%session}}', [
            'id' => $this->string()->notNull(),
            'expire' => $this->integer(),
            'data' => $this->binary(),
            'user_id' => $this->integer(),
            'last_write' => $this->string(),
            'PRIMARY KEY ([[id]])',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%session}}');
    }
}
