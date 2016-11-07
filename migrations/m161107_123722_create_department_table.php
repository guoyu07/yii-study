<?php

use yii\db\Migration;

/**
 * Handles the creation of table `department`.
 */
class m161107_123722_create_department_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('department', [
            'id' => $this->primaryKey(),
            'name' => $this->string(100)
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('department');
    }
}
