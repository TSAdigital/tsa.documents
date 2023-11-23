<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Event $model */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Мероприятия', 'url' => ['index']];
$this->params['buttons'] = [
    'update' => (Yii::$app->user->can('admin') or (Yii::$app->user->can('updateEvents') and $model->user_id == Yii::$app->user->identity->id)) ? Html::a('<i class="fas fa-edit text-primary"></i> Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-app']) : null,
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
                            <td><p class="mb-0"><b><?= $model->getAttributeLabel('name') ?>: </b><?= Html::encode($model->name) ?></p></td>
                        </tr>
                        <?php if($model->description): ?>
                        <tr>
                            <td><p class="mb-0"><b><?= $model->getAttributeLabel('description') ?>: </b><?= Yii::$app->formatter->asNtext($model->description) ?></p></td>
                        </tr>
                        <?php endif; ?>
                        <tr>
                            <td><p class="mb-0"><b><?= $model->getAttributeLabel('start') ?>: </b><?= Html::encode($model->start) ?></p></td>
                        </tr>
                        <?php if($model->end): ?>
                        <tr>
                            <td><p class="mb-0"><b><?= $model->getAttributeLabel('end') ?>: </b><?= Html::encode($model->end) ?></p></td>
                        </tr>
                        <?php endif; ?>
                        <?php if($model->resolution and (Yii::$app->user->can('admin') or Yii::$app->user->can('eventsAdmin'))): ?>
                        <tr>
                            <td><p class="mb-0"><b><?= $model->getAttributeLabel('resolution') ?>: </b><?= !empty($model->getUsers($model->resolution)) ? $model->getUsers($model->resolution) : 'Все сотрудники' ?></p></td>
                        </tr>
                        <?php endif; ?>
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
                                    'attribute' => 'name',
                                    'captionOptions' => ['width' => '170px'],
                                ],
                                [
                                    'attribute' => 'description',
                                    'format' => 'ntext',
                                    'visible' => !empty($model->description)
                                ],
                                'start',
                                [
                                    'attribute' => 'end',
                                    'visible' => !empty($model->end)
                                ],
                                [
                                    'attribute' => 'color',
                                    'value' => $model->getColorName(),
                                    'visible' => false,
                                ],
                                [
                                    'attribute' => 'resolution',
                                    'format' => 'raw',
                                    'value' =>  !empty($model->getUsers($model->resolution)) ? $model->getUsers($model->resolution) : 'Все сотрудники',
                                    'visible' => Yii::$app->user->can('admin') or Yii::$app->user->can('eventsAdmin'),
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