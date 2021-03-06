<?php

use app\models\Employee;
use app\models\EmployeeSearch;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\EmployeeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app','Employees');
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="employee-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app','Create Employee'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?php Pjax::begin()?>
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
                    return $data->genderValue;
                },
                'filter' => EmployeeSearch::getGenderValueList(),
            ],
            'pay',
            [
                'attribute'=>'departments',
                'format'=>'text',
                'content'=>function($data){
                    return $data->departmentsValue;
                },
                'filter' => EmployeeSearch::getDepartmentsValueList(),
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'buttons' => [
                    'delete' => function ($url, $model, $key) {
                        return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                            'title' => Yii::t('yii', 'Delete'),
                            'data-pjax' => '#model-grid',
                            'data-method' => 'post'
                        ]);
                    },
                ],
            ],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
