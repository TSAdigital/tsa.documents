<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%employee}}`.
 */
class m221123_104445_create_employee_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%employee}}', [
            'id' => $this->primaryKey(),
            'last_name' => $this->string(255)->notNull(),
            'first_name' => $this->string(255)->notNull(),
            'middle_name' => $this->string(255),
            'birthdate' => $this->date()->notNull(),
            'position_id' => $this->integer()->notNull(),
            'status' => $this->smallInteger()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        $this->createIndex(
            'idx-position-id',
            'employee',
            'position_id'
        );

        $this->addForeignKey(
            'fk-position-id',
            'employee',
            'position_id',
            'position',
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
            'fk-position-id',
            'employee'
        );

        $this->dropIndex(
            'idx-position-id',
            'employee'
        );

        $this->dropTable('{{%employee}}');
    }
}
