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
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'employee';
    }

    /**
     * @return array
     */
    public static function getGenderList()
    {
        return static::getGenderValuesLabels();
    }

    /**
     * @return array
     */
    private static function getGenderValuesLabels()
    {
        return [
            static::GENDER_VALUE_MAN => 'м',
            static::GENDER_VALUE_WOMAN => 'ж'
        ];
    }

    public static function getDepartmentsList()
    {
        $departmentItems = Department::find()->asArray()->all();
        $resultItems = [];
        foreach ($departmentItems as $item) {
            $resultItems[$item['id']] = $item['name'];
        }
        
        return $resultItems;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'lastname', 'departments', 'gender'], 'required'],
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
     * @param Department[] $departments
     */
    public function setDepartments($departments)
    {
        $this->departments = $departments;
    }
    
    /**
     * @return array
     */
    public function fields()
    {
        $fields = parent::fields();

        $fields['gender_label'] = function () {
            return static::getGenderValuesLabels()[$this->gender];
        };

        $fields['departments_label'] = function () {
            $departmentsName = [];

            foreach ($this->departments as $department) {
                $departmentsName[] = $department->name;
            }

            return implode(', ', $departmentsName);
        };

        return $fields;
    }

    /**
     * @param array $data
     * @param null $formName
     * @return bool
     */
    public function load($data, $formName = null)
    {
        $scope = $formName === null ? $this->formName() : $formName;

        if (isset($data[$scope]) && isset($data[$scope]['departments'])) {
            $departmentIds = $data[$scope]['departments'];
            unset($data[$scope]['departments']);
        } elseif(isset($data['departments'])) {
            $departmentIds = $data['departments'];
            unset($data['departments']);
        }
        
        if (isset($departmentIds) && $departmentIds) {
            /** @var Department[] $departments */
            $departments = Department::findAll($departmentIds);
            $this->setDepartments($departments);
        }
        
        return parent::load($data, $formName);
    }

    /**
     * @param bool $insert
     * @param array $changedAttributes
     * @throws Exception
     */
    public function afterSave($insert, $changedAttributes)
    {
        foreach ($this->departments as $department) {
            try {
                $this->link('departments', $department);
            } catch (Exception $e) {
                
                if ($e->errorInfo[1] != 1062) {
                    throw $e;
                }
            }
        }

        parent::afterSave($insert, $changedAttributes);
    }

    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        if (!$insert) {

            $allDeaprtment = Employee::find()
                ->joinWith('departments', false, 'inner JOIN')
                ->where(['employee.id' => $this->id])
                ->select([
                   'department.id'
                ])
            ->all();

            $departmentsDelete = array_map(function ($item) {
                return $item->id;
            }, $allDeaprtment);

            //получим связи котороые надо удалить
            foreach ($this->departments as $department) {
                if (false !== $key = array_search($department->id, $departmentsDelete)) {
                    unset($departmentsDelete[$key]);
                }
            }

            //удалим старые связки с отделом
            foreach ($departmentsDelete as $key => $departmentId) {
                $this->unlink('departments', $allDeaprtment[$key]);
            }
        }

        return parent::beforeSave($insert);
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
}
