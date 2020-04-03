<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%logs}}`.
 */
class m200401_141103_create_logs_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('logs', [
            'id' => $this->primaryKey(),
            'ip' => $this->string()->notNull()->comment('IP'),
            'created_at' => $this->integer()->notNull()->comment('Дата и время лога'),
            'url' => $this->text()->comment('URL')->defaultValue(null),
            'os' => $this->string()->comment('Операционная система'),
            'arch' => $this->string()->comment('Архитектура')->defaultValue(null),
            'browser' => $this->string()->comment('Браузер'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('logs');
    }
}
