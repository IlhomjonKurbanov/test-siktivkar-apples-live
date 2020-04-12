<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%apple}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%user}}`
 */
class m200412_202712_create_apple_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%apple}}', [
            'id' => $this->primaryKey(),
            'created_by' => $this->integer()->notNull(),
            'color' => $this->string(10)->notNull(),
            'size' => $this->integer(3)->unsigned()->defaultValue(100),
            'state' => $this->integer(1)->unsigned()->defaultValue(1),
            'created_at' => $this->integer()->unsigned()->notNull(),
            'fall_at' => $this->integer()->unsigned()->notNull(),
            'deleted_at' => $this->integer()->unsigned(),
        ]);

        // creates index for column `created_by`
        $this->createIndex(
            '{{%idx-apple-created_by}}',
            '{{%apple}}',
            'created_by'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-apple-created_by}}',
            '{{%apple}}',
            'created_by',
            '{{%user}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-apple-created_by}}',
            '{{%apple}}'
        );

        // drops index for column `created_by`
        $this->dropIndex(
            '{{%idx-apple-created_by}}',
            '{{%apple}}'
        );

        $this->dropTable('{{%apple}}');
    }
}
