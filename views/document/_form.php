<?php

use app\models\User;
use kartik\date\DatePicker;
use kartik\form\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;

/** @var yii\web\View $this */
/** @var app\models\Document $model */
/** @var app\models\Group $groups */
/** @var yii\widgets\ActiveForm $form */
?>

<?php $form = ActiveForm::begin(['id' => 'document']); ?>

<?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'date', ['enableClientValidation' => false])->widget(DatePicker::class, [
    'options' => ['placeholder' => 'Ввод даты...'],
    'value' => 'dd.mm.yyyy',
    'pluginOptions' => [
        'format' => 'dd.mm.yyyy',
        'autoclose' => true,
        'todayBtn' => true,
        'todayHighlight' => true,
    ]
]) ?>

<?= $form->field($model, 'number')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'type')->dropDownList($model->getTypesArray(), ['prompt' => 'Выберите тип...']) ?>

<?= $form->field($model, 'executor_id')->widget(Select2::class,
    [
        'data' => $model->executor ? ArrayHelper::map(User::find()->joinWith(['employee', 'userRoles'])->where(['user.id' => $model->executor_id])->andWhere(['!=', 'auth_assignment.item_name', 'admin'])->andWhere(['!=', 'user.id', Yii::$app->user->identity->id])->orWhere(['user.status' => 10])->andWhere(['!=', 'auth_assignment.item_name', 'admin'])->andWhere(['!=', 'user.id', Yii::$app->user->identity->id])->orderBy('employee.last_name ASC')->all(), 'id', 'employee_name') : ArrayHelper::map(User::find()->joinWith(['employee', 'userRoles'])->where(['user.status' => 10])->andWhere(['!=', 'auth_assignment.item_name', 'admin'])->andWhere(['!=', 'user.id', Yii::$app->user->identity->id])->orderBy('employee.last_name ASC')->all(), 'id', 'employee_name'),
        'options' => ['placeholder' => 'Выберите куратора...'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]);
?>

<?= $form->field($model, 'resolution')->widget(Select2::class, [
    'data' => $model->resolution ? ArrayHelper::map(User::find()->joinWith(['employee', 'userRoles'])->where(['user.id' => $model->resolution])->andWhere(['!=', 'auth_assignment.item_name', 'admin'])->andWhere(['!=', 'user.id', Yii::$app->user->identity->id])->orWhere(['user.status' => 10])->andWhere(['!=', 'auth_assignment.item_name', 'admin'])->andWhere(['!=', 'user.id', Yii::$app->user->identity->id])->orderBy('employee.last_name ASC')->all(), 'id', 'employee_name') : ArrayHelper::map(User::find()->joinWith(['employee', 'userRoles'])->where(['!=', 'user.id', Yii::$app->user->identity->id])->andWhere(['!=', 'auth_assignment.item_name', 'admin'])->andWhere(['user.status' => 10])->orderBy('employee.last_name ASC')->all(), 'id', 'employee_name'),
    'theme' => 'krajee-bs3',
    'options' => ['placeholder' => 'Выберите сотрудника...', 'multiple' => true],
]) ?>

<?= !$model->isNewRecord ? $form->field($model, 'status')->dropDownList($model->getStatusesArray(), ['prompt' => 'Выберите статус...']) : null; ?>

<?= $form->field($model, 'discussion', ['enableClientValidation' => false])->checkbox(['custom' => true, 'switch' => true])->label('Разрешить обсуждения') ?>

<?php ActiveForm::end(); ?>