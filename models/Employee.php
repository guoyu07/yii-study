<?php

namespace app\models;

use Yii;
use yii\base\Exception;

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
    const GENDER_VALUE_MAN = 0;
    const GENDER_VALUE_WOMAN = 1;

    /**
     * Идентификаторы новых отделов
     * @var array
     */
    public $newDepartmentIds = [];


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
            [['name', 'lastname', 'newDepartmentIds', 'gender'], 'required'],
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
            'id' => Yii::t('app/employee', 'ID'),
            'name' => Yii::t('app/employee', 'Name'),
            'lastname' => Yii::t('app/employee', 'Last name'),
            'patronymic' => Yii::t('app/employee', 'Patronymic'),
            'gender' => Yii::t('app/employee', 'Gender'),
            'pay' => Yii::t('app/employee', 'Pay'),
            'departments' => Yii::t('app/employee', 'Departments'),
        ];
    }

    /**
     * @return $this
     */
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

    /**
     * @param $departmentsIds int[]
     */
    public function setNewDepartmentsIds($departmentsIds)
    {
        $this->newDepartmentIds = (array) $departmentsIds;
    }

    /**
     * @param bool $insert
     * @param array $changedAttributes
     * @throws Exception
     */
    public function afterSave($insert, $changedAttributes)
    {
        $oldDepartmentIds = $this->getDepartmentsIds();
        $newDepartmentIds = $this->newDepartmentIds;

        if ($newDepartmentsIds = array_diff($newDepartmentIds, $oldDepartmentIds)) {
            $newDepartments = Department::findAll($newDepartmentsIds);
            foreach ($newDepartments as $newDepartment) {
                $this->link('departments', $newDepartment);
            }
        }
        
        if ($deleteDepartmentsIds = array_diff($oldDepartmentIds, $newDepartmentIds)) {
            foreach ($this->departments as $department) {
                if (in_array($department->id, $deleteDepartmentsIds))
                $this->unlink('departments', $department);
            }
        }
        
        parent::afterSave($insert, $changedAttributes);
    }

    /**
     * @return bool
     */
    public function beforeDelete()
    {
        foreach ($this->departments as $department) {
            $this->unlink('departments', $department);
        }

        return parent::beforeDelete();
    }

    /**
     * @return array
     */
    public function transactions()
    {

        return [
            'default' => self::OP_INSERT | self::OP_UPDATE | self::OP_DELETE
        ];
    }

    /**
     * @return array
     */
    public function getDepartmentsIds()
    {
        $result = [];
        foreach ($this->departments as $department) {
            $result[] = $department->id;
        }

        return $result;
    }
}
