<?php

use yii\db\Migration;

/**
 * Class m220121_153327_add_administrator_to_users_table
 */
class m221123_104448_add_administrator_to_users_table extends Migration
{
    /**
     * {@inheritdoc}
     * @throws \yii\base\Exception
     */
    public function safeUp()
    {
        $this->insert('{{%user}}', [
            'username' => 'admin',
            'auth_key' => Yii::$app->security->generateRandomString(),
            'password_hash' => password_hash('12345678', PASSWORD_DEFAULT),
            'email' => 'admin@mail.local',
            'status' => 10,
            'created_at' => time(),
            'updated_at' => time(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete('{{%user}}', ['username' => 'admin']);
    }
}
