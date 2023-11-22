<?php

use app\models\User;
use kartik\datetime\DateTimePicker;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Event $model */
/** @var yii\widgets\ActiveForm $form */
?>

<?php $form = ActiveForm::begin(['id' => 'event']); ?>

<?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

<?= $form->field($model, 'start', ['enableClientValidation' => false])->widget(DateTimePicker::classname(), [
    'options' => ['placeholder' => 'Укажите дату и время...'],
    'pluginOptions' => [
        'format' => 'dd.mm.yyyy, hh:ii',
        'autoclose' => true,
        'todayBtn' => true,
        'todayHighlight' => true,
    ]
])?>

<?= $form->field($model, 'end', ['enableClientValidation' => false])->widget(DateTimePicker::classname(), [
    'options' => ['placeholder' => 'Укажите дату и время...'],
    'pluginOptions' => [
        'format' => 'dd.mm.yyyy, hh:ii',
        'autoclose' => true,
        'todayBtn' => true,
        'todayHighlight' => true,
    ]
])?>

<?= $form->field($model, 'color')->dropDownList($model->getColorsArray(), ['prompt' => 'Выберите цвет...']) ?>

<?php if(Yii::$app->user->can('admin') or Yii::$app->user->can('eventsAdmin')) : ?>

<?= $form->field($model, 'resolution')->widget(Select2::class, [
    'data' => $model->resolution ? ArrayHelper::map(User::find()->joinWith(['employee', 'userRoles'])->where(['user.id' => $model->resolution])->orWhere(['user.status' => 10])->orderBy('employee.last_name ASC')->all(), 'id', 'employee_name') : ArrayHelper::map(User::find()->joinWith(['employee', 'userRoles'])->andWhere(['user.status' => 10])->orderBy('employee.last_name ASC')->all(), 'id', 'employee_name'),
    'theme' => 'krajee-bs3',
    'options' => ['placeholder' => 'Выберите сотрудника...', 'multiple' => true],
]) ?>

<?php endif; ?>

<?= !$model->isNewRecord ? $form->field($model, 'status')->dropDownList($model->getStatusesArray(), ['prompt' => 'Выберите статус...']) : null; ?>

<?php ActiveForm::end(); ?>
