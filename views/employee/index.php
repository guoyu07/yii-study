<?php

use app\models\Employee;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\EmployeeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Employees';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="employee-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Employee', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'id',
                'filter' => '',
            ],
            'name',
            'lastname',
            'patronymic',
            [
                'attribute'=>'gender',
                'format'=>'text',
                'content'=>function($data){
                    return $data->toArray()['gender_label'];
                },
                'filter' => Employee::getGenderList(),
            ],
            'pay',
            [
                'attribute'=>'departments',
                'format'=>'text',
                'content'=>function($data){
                    return $data->toArray()['departments_label'];
                },
                'filter' => Employee::getDepartmentsList(),
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
