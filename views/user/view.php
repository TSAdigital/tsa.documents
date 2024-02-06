<?php

use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Modal;
use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\User $model */

$this->title = $model->username;
$this->params['breadcrumbs'][] = ['label' => 'Пользователи', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$this->params['buttons'] = [
    'update' => Html::a('<i class="fas fa-edit text-primary"></i> Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-app']),
    'password' => Html::a('<i class="fas fa-user-lock text-dark"></i>Пароль', '#', ['class' => 'btn btn-app', 'data-toggle' => 'modal', 'data-target' => '#password']),
/*  'delete' => Html::a('<i class="fas fa-trash-alt text-danger"></i> Удалить', ['delete', 'id' => $model->id], [
        'class' => 'btn btn-app',
        'data' => [
            'confirm' => 'Вы уверены, что хотите удалить этого пользователя?',
            'method' => 'post',
        ],
    ]), */
    'undo' => Html::a('<i class="far fa-arrow-alt-circle-left text-muted"></i>Вернуться', ['user/index'], ['class' => 'btn btn-app'])
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
                            <td><p class="mb-0"><b>Имя пользователя: </b><?= Html::encode($model->username) ?></p></td>
                        </tr>
                        <tr>
                            <td><p class="mb-0"><b>Email: </b><?= Html::encode($model->email) ?></p></td>
                        </tr>
                        <tr>
                            <td><p class="mb-0"><b>Сотрудник: </b><?= Html::encode($model->employee ? $model->getEmployee_name() : null) ?></p></td>
                        </tr>
                        <tr>
                            <td><p class="mb-0"><b>Роль: </b><?= Html::encode($model->getRolesName()) ?></p></td>
                        </tr>
                        <?php if(!empty($model->chat_id)) : ?>
                        <tr>
                            <td><p class="mb-0"><b><?= $model->getAttributeLabel('chat_id') ?>: </b><?= Html::encode($model->chat_id) ?></p></td>
                        </tr>
                        <?php endif; ?>
                        <tr>
                            <td><p class="mb-0"><b>Статус: </b><?= Html::encode($model->getStatusName()) ?></p></td>
                        </tr>
                        <tr>
                            <td><p class="mb-0"><b>Запись создана: </b><?= Html::encode(Yii::$app->formatter->asDatetime($model->created_at)) ?></p></td>
                        </tr>
                        <tr>
                            <td><p class="mb-0"><b>Запись обновлена: </b><?= Html::encode(Yii::$app->formatter->asDatetime($model->updated_at)) ?></p></td>
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
                                    'attribute' => 'username',
                                    'captionOptions' => ['width' => '170px'],
                                ],
                                'email:email',
                                [
                                    'attribute' => 'employee_name',
                                    'value' =>  Html::encode($model->employee ? $model->getEmployee_name() : null),
                                ],
                                [
                                    'attribute' => 'active',
                                    'format' => 'datetime',
                                    'visible' => !empty($model->active)
                                ],
                                [
                                    'attribute' => 'roles',
                                    'value' => $model->getRolesName(),
                                ],
                                [
                                    'attribute' => 'chat_id',
                                    'visible' => !empty($model->chat_id),
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

<?php
Modal::begin([
    'title' => 'Новый пароль',
    'id' => 'password',
    'size' => 'modal-md',
    'closeButton' => [
        'id' => 'close-button',
        'class' => 'close',
        'data-dismiss' => 'modal',
    ],
    'clientOptions' => ['backdrop' => false]
]);
?>

<?php $form = ActiveForm::begin(['action' => ['user/password', 'id' => $model->id]]); ?>

<?= $form->field($model, 'new_password')->passwordInput() ?>

    <div class="form-group mb-0">
        <?= Html::submitButton('<i class="far fa-save text-success"></i>Сохранить', ['class' => 'btn btn-app mx-auto btn-block mb-0']) ?>
    </div>

<?php ActiveForm::end(); ?>

<?php Modal::end(); ?>