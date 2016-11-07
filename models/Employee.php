<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "employee".
 *
 * @property integer $id
 * @property string $name
 * @property string $lastname
 * @property string $patronymic
 * @property integer $gender
 * @property integer $pay
 * @property Department[] $departments
 */
class Employee extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'employee';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'lastname', 'pay', 'departments'], 'required'],
            [['gender', 'pay'], 'integer'],
            [['name', 'lastname', 'patronymic'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'lastname' => 'Lastname',
            'patronymic' => 'Patronymic',
            'gender' => 'Gender',
            'pay' => 'Pay',
            'departments' => 'Departments'
        ];
    }

    public function getDepartments()
    {
        return $this->hasMany(
            Department::className(),
            ['id' => 'department_id']
        )->viaTable(
            'department_employee',
            ['employee_id' => 'id']
        );
    }
}
