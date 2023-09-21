<?php

use app\models\Employee;
use kartik\select2\Select2;
use yii\bootstrap4\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\widgets\MaskedInput;

/** @var yii\web\View $this */
/** @var app\models\User $model */
/** @var yii\widgets\ActiveForm $form */
?>

<?php $form = ActiveForm::begin(['id' => 'user']); ?>

<?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>

<?= $model->scenario != 'userUpdate' ? $form->field($model, 'new_password')->passwordInput() : null ?>

<?= $form->field($model, 'employee_id')->widget(Select2::class,
    [
        'data' => $model->employee ? ArrayHelper::map(Employee::find()->where(['id' => $model->employee_id])->orWhere(['status' => 10])->orderBy('last_name ASC')->all(), 'id', 'employeeFullName') : ArrayHelper::map(Employee::find()->where(['status' => 10])->orderBy('last_name ASC')->all(), 'id', 'employeeFullName'),
        'options' => ['placeholder' => 'Выберите сотрудника...'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]);
?>

<?= $form->field($model, 'roles')->dropDownList($model->getRolesDropdown(), ['prompt' => 'Выберите роль...']); ?>

<?= $form->field($model, 'email')->widget(MaskedInput::class, [
    'clientOptions' => [
        'alias' => 'email'
    ],
]);
?>

<?= !$model->isNewRecord ? $form->field($model, 'status')->dropDownList($model->getStatusesArray(), ['prompt' => 'Выберите статус...']) : null; ?>

<?php ActiveForm::end(); ?>


