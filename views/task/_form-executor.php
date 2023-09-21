<?php

use app\models\User;
use kartik\datetime\DateTimePicker;
use kartik\form\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;

/** @var yii\web\View $this */
/** @var app\models\Task $model */
/** @var yii\widgets\ActiveForm $form */
?>

<?php $form = ActiveForm::begin(['id' => 'task']); ?>

<?= $form->field($model, 'name')->textInput(['maxlength' => true, 'disabled' => true]) ?>

<?= $form->field($model, 'date', ['enableClientValidation' => false])->widget(DateTimePicker::classname(), [
    'options' => ['placeholder' => 'Укажите дату и время...', 'disabled' => true],
    'pluginOptions' => [
        'format' => 'dd.mm.yyyy, hh:ii',
        'autoclose' => true,
        'todayBtn' => true,
        'todayHighlight' => true,
    ]
])?>

<?= $form->field($model, 'description')->textarea(['rows' => 6, 'disabled' => true]) ?>

<?= $form->field($model, 'executor_id')->widget(Select2::class,
    [
        'data' => $model->executor ? ArrayHelper::map(User::find()->joinWith(['employee', 'userRoles'])->where(['user.id' => $model->executor_id])->andWhere(['!=', 'auth_assignment.item_name', 'admin'])->orWhere(['user.status' => 10])->orderBy('employee.last_name ASC')->all(), 'id', 'employee_name') : ArrayHelper::map(User::find()->joinWith(['employee', 'userRoles'])->where(['user.status' => 10])->andWhere(['!=', 'auth_assignment.item_name', 'admin'])->andWhere(['!=', 'user.id', Yii::$app->user->identity->id])->orderBy('employee.last_name ASC')->all(), 'id', 'employee_name'),
        'options' => ['placeholder' => 'Выберите куратора...', 'disabled' => true],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]);
?>

<?= $form->field($model, 'resolution')->widget(Select2::class, [
    'data' => $model->resolution ? ArrayHelper::map(User::find()->joinWith(['employee', 'userRoles'])->where(['user.id' => $model->resolution])->andWhere(['!=', 'auth_assignment.item_name', 'admin'])->andWhere(['!=', 'user.id', $model->user_id])->orWhere(['user.status' => 10])->andWhere(['!=', 'auth_assignment.item_name', 'admin'])->andWhere(['!=', 'user.id', $model->user_id])->orderBy('employee.last_name ASC')->all(), 'id', 'employee_name') : ArrayHelper::map(User::find()->joinWith(['employee', 'userRoles'])->where(['!=', 'user.id', $model->user_id])->andWhere(['user.status' => 10])->orderBy('employee.last_name ASC')->all(), 'id', 'employee_name'),
    'theme' => 'krajee-bs3',
    'options' => ['placeholder' => 'Выберите сотрудника...', 'multiple' => true],
]) ?>

<?= $form->field($model, 'priority')->textInput()->dropDownList($model->getPrioritysArray(), ['prompt' => 'Выберите приоритет...', 'disabled' => true]) ?>

<?= !$model->isNewRecord ? $form->field($model, 'status')->dropDownList($model->getStatusesArray(), ['prompt' => 'Выберите статус...', 'disabled' => true]) : null; ?>

<?= $form->field($model, 'discussion', ['enableClientValidation' => false])->checkbox(['custom' => true, 'switch' => true, 'disabled' => true])->label('Разрешить обсуждения') ?>

<?php ActiveForm::end(); ?>