<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "department".
 *
 * @property integer $id
 * @property string $name
 *
 * @property Employee[] $employees
 */
class Department extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'department';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [
                ['name'],
                'required',
                'message' => Yii::t('app', 'Enter value') . ' {attribute}.'
            ],
            [['name'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app/department', 'ID'),
            'name' => Yii::t('app/department', 'Name'),
           // 'name' => Yii::t('app/department', 'Count Employees'),
            
        ];
    }

    /**
     * @return $this
     */
    public function getEmployees()
    {
        return $this->hasMany(
            Employee::className(),
            ['id' => 'employee_id']
        )->viaTable(
            'department_employee',
            ['department_id' => 'id']
        );
    }
}
