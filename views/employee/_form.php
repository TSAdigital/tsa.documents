<?php

use app\models\Position;
use kartik\date\DatePicker;
use yii\bootstrap4\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;

/** @var yii\web\View $this */
/** @var app\models\Employee $model */
/** @var yii\widgets\ActiveForm $form */
?>

<?php $form = ActiveForm::begin(['id' => 'employee']); ?>

<?= $form->field($model, 'last_name')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'first_name')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'middle_name')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'birthdate')->widget(DatePicker::class, [
    'options' => ['placeholder' => 'Ввод даты...'],
    'value' => 'dd.mm.yyyy',
    'pluginOptions' => [
        'format' => 'dd.mm.yyyy',
        'autoclose' => true,
        'todayBtn' => true,
        'todayHighlight' => true,
    ]
]) ?>

<?= $form->field($model, 'position_id')->widget(Select2::class,
    [
        'data' => $model->position ? ArrayHelper::map(Position::find()->where(['id' => $model->position_id])->orWhere(['status' => 10])->orderBy('name ASC')->all(), 'id', 'name') : ArrayHelper::map(Position::find()->where(['status' => 10])->orderBy('name ASC')->all(), 'id', 'name'),
        'options' => ['placeholder' => 'Выберите должность...'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]);
?>

<?= !$model->isNewRecord ? $form->field($model, 'status')->dropDownList($model->getStatusesArray(), ['prompt' => 'Выберите статус...']) : null; ?>

<?php ActiveForm::end(); ?>