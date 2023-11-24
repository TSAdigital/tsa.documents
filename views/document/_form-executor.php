<?php

use app\models\DocumentType;
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

<?= $form->field($model, 'name')->textInput(['maxlength' => true, 'disabled' => true]) ?>

<?= $form->field($model, 'date', ['enableClientValidation' => false])->widget(DatePicker::class, [
    'options' => ['placeholder' => 'Ввод даты...', 'disabled' => true],
    'value' => 'dd.mm.yyyy',
    'pluginOptions' => [
        'format' => 'dd.mm.yyyy',
        'autoclose' => true,
        'todayBtn' => true,
        'todayHighlight' => true,
    ]
]) ?>

<?= $form->field($model, 'number')->textInput(['maxlength' => true, 'disabled' => true]) ?>

<?= $form->field($model, 'description')->textarea(['rows' => 6, 'disabled' => true]) ?>

<?= $form->field($model, 'type')->widget(Select2::class,
    [
        'data' => $model->type0 ? ArrayHelper::map(DocumentType::find()->where(['id' => $model->type])->orWhere(['status' => 10])->orderBy('name ASC')->all(), 'id', 'name') : ArrayHelper::map(DocumentType::find()->where(['status' => 10])->orderBy('name ASC')->all(), 'id', 'name'),
        'options' => ['placeholder' => 'Выберите тип документа...'],
        'pluginOptions' => [
            'allowClear' => true,
            'disabled' => true
        ],
    ]);
?>

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

<?= !$model->isNewRecord ? $form->field($model, 'status')->dropDownList($model->getStatusesArray(), ['prompt' => 'Выберите статус...', 'disabled' => true]) : null; ?>

<?= $form->field($model, 'discussion', ['enableClientValidation' => false])->checkbox(['custom' => true, 'switch' => true, 'disabled' => true])->label('Разрешить обсуждения') ?>

<?php ActiveForm::end(); ?>