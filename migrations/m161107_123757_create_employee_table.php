<?php

use yii\db\Migration;

/**
 * Handles the creation of table `employee`.
 */
class m161107_123757_create_employee_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('employee', [
            'id' => $this->primaryKey(),
            'name' => $this->string(100)->notNull(),
            'lastname' => $this->string(100)->notNull(),
            'patronymic' => $this->string(100),
            'gender' => $this->boolean()->notNull(),
            'pay' => $this->integer(9)
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('employee');
    }
}
