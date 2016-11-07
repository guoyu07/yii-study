<?php

use yii\db\Migration;

class m161107_135223_create_department_employee_keys extends Migration
{
    public function up()
    {
        $this->addForeignKey(
            'fk-department-department_id',
            'department_employee',
            'department_id',
            'department',
            'id'
        );

        $this->createIndex(
            'idx_department_id_employee_id',
            'department_employee',
            ['department_id', 'employee_id'],
            true
        );

    }

    public function down()
    {
        echo "m161107_135223_create_department_employee_keys cannot be reverted.\n";

        return false;
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
