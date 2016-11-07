<?php

use yii\db\Migration;

/**
 * Handles the creation of table `department_employee`.
 */
class m161107_133720_create_department_employee_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('department_employee', [
            'id' => $this->primaryKey(),
            'department_id' => $this->integer(11),
            'employee_id' => $this->integer(11)
        ]);

    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('department_employee');
    }
}
