<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Employee;

/**
 * EmployeeSearch represents the model behind the search form about `\app\models\Employee`.
 */
class EmployeeSearch extends Employee
{
    public $departmentsValue = '';

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'gender', 'pay'], 'integer'],
            [['name', 'lastname', 'patronymic'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = EmployeeSearch::find()
            ->select([
                'GROUP_CONCAT(DISTINCT ' . Department::tableName() . '.name ORDER BY ' . Department::tableName() . '.name ASC SEPARATOR \', \') as departmentsValue',
                static::tableName() . '.*'
            ])->joinWith([
                'departments'
            ], FALSE, 'LEFT JOIN')->groupBy(static::tableName() . '.id');

        // add conditions that should always apply here
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 5,
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            static::tableName() . '.id' => $this->id,
            static::tableName() . '.gender' => $this->gender,
            static::tableName() . '.pay' => $this->pay,
        ]);

        $query->andFilterWhere(['like', static::tableName() . '.name', $this->name])
            ->andFilterWhere(['like', static::tableName() . '.lastname', $this->lastname])
            ->andFilterWhere(['like', static::tableName() . '.patronymic', $this->patronymic]);

        return $dataProvider;
    }

    /**
     * @return string
     */
    public function getGenderValue()
    {
        return (string) static::getGenderValueList()[$this->gender];
    }

    /**
     * @return array
     */
    public static function getGenderValueList()
    {
        return [
            static::GENDER_VALUE_MAN => 'м',
            static::GENDER_VALUE_WOMAN => 'ж'
        ];
    }

    /**
     * @return array
     */
    public static function getDepartmentsValueList()
    {
        $departmentItems = Department::find()->asArray()->all();
        $resultItems = [];
        foreach ($departmentItems as $item) {
            $resultItems[$item['id']] = $item['name'];
        }

        return $resultItems;
    }
}
