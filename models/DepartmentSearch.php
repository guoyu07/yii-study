<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Department;

/**
 * DepartmentSearch represents the model behind the search form about `\app\models\Department`.
 */
class DepartmentSearch extends Department
{
    public $countEmployees = 0;
    public $maxPay = 0;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['name'], 'safe'],
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
        $query = DepartmentSearch::find()->select([
            'count(DISTINCT ' .Employee::tableName() . '.id) as countEmployees',
            'max(' . Employee::tableName(). '.pay) as maxPay',
            static::tableName() . '.*'
        ])->joinWith([
            'employees'
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
        ]);

        $query->andFilterWhere(['like', static::tableName() . '.name', $this->name]);

        return $dataProvider;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'countEmployees' => Yii::t('app/department', 'Count employees'),
            'maxPay' => Yii::t('app/department', 'Max pay'),
        ]);
    }
}
