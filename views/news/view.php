<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\News $model */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Новости', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$this->params['buttons'] = [
    'update' => (Yii::$app->user->can('admin') or (Yii::$app->user->can('updateNews') and $model->user_id == Yii::$app->user->identity->id)) ? Html::a('<i class="fas fa-edit text-primary"></i> Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-app']) : null,
    /*  'delete' =>  Html::a('<i class="fas fa-trash-alt text-danger"></i> Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-app',
            'data' => [
                'confirm' => 'Вы уверены, что хотите удалить этого сотрудника?',
                'method' => 'post',
            ],
        ]), */
    'undo' => Html::a('<i class="far fa-arrow-alt-circle-left text-muted"></i>Вернуться', ['index'], ['class' => 'btn btn-app'])
];
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body d-block d-sm-none p-2">
                    <table class="table table-striped table-bordered mb-0">
                        <tbody>
                        <tr>
                            <td><p class="mb-0"><b><?= $model->getAttributeLabel('title') ?>: </b><?= Html::encode($model->title) ?></p></td>
                        </tr>
                        <tr>
                            <td><p class="mb-0"><b><?= $model->getAttributeLabel('text') ?>: </b><?= Yii::$app->formatter->asNtext($model->text) ?></p></td>
                        </tr>
                        <tr>
                            <td><p class="mb-0"><b><?= $model->getAttributeLabel('user_id') ?>: </b><?= Html::a(Html::encode(isset($model->user->employee) ? Html::encode($model->user->employee->employeeFullName) : Html::encode($model->user->username)), ['site/profile', 'id' => $model->user_id], ['data-pjax' => 0]) ?></p></td>
                        </tr>
                        <tr>
                            <td><p class="mb-0"><b><?= $model->getAttributeLabel('status') ?>: </b><?= Html::encode($model->getStatusName()) ?></p></td>
                        </tr>
                        <tr>
                            <td><p class="mb-0"><b><?= $model->getAttributeLabel('created_at') ?>: </b><?= Html::encode(Yii::$app->formatter->asDatetime($model->created_at)) ?></p></td>
                        </tr>
                        <tr>
                            <td><p class="mb-0"><b><?= $model->getAttributeLabel('updated_at') ?>: </b><?= Html::encode(Yii::$app->formatter->asDatetime($model->updated_at)) ?></p></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="card-body d-none d-sm-block pb-1">
                    <div class="table-responsive">

                        <?= DetailView::widget([
                            'model' => $model,
                            'attributes' => [
                                [
                                    'attribute' => 'title',
                                    'captionOptions' => ['width' => '170px'],
                                ],
                                [
                                    'attribute' => 'text',
                                    'format' => 'ntext',
                                ],
                                [
                                    'attribute' => 'user_id',
                                    'format' => 'raw',
                                    'value' => Html::a(Html::encode(isset($model->user->employee) ? Html::encode($model->user->employee->employeeFullName) : Html::encode($model->user->username)), ['site/profile', 'id' => $model->user_id], ['data-pjax' => 0]),
                                ],
                                [
                                    'attribute' => 'status',
                                    'value' => $model->getStatusName(),
                                ],
                                'created_at:datetime',
                                'updated_at:datetime',
                            ],
                        ]) ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
