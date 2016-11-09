<?php

/* @var $this yii\web\View */

use app\models\DepartmentSearch;

$this->title = Yii::t('app','Grid');
?>
<div class="site-index">

    <div class="jumbotron">
        <h1><?=$this->title?></h1>

    </div>

    <div class="body-content">

        <div class="row">
            <table class="table table-striped task-table">
                <thead>
                <th></th>
                <?php /** @var DepartmentSearch[] $departments */
                foreach ($departments as $department): ?>
                <th><?= $department->name?></th>
                <?php endforeach ?>
                </thead>
                <tbody>
                <?php /** @var \app\models\EmployeeSearch[] $employees */
                foreach ($employees as $employee) :?>
                <tr>
                    <th>
                        <?= $employee->lastname . ' ' . $employee->name ?>
                    </th>
                    <?php foreach ($departments as $department) :?>
                    <td>
                        <?php if(in_array($department->id , $employee->getDepartmentsIds())): ?>
                        +
                        <?php endif; ?>
                    </td>
                    <?php endforeach ?>
                </tr>
                <?php endforeach ?>
                </tbody>
            </table>
        </div>

    </div>
</div>