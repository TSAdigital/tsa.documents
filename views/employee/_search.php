<?php

use app\models\Position;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\EmployeeSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<?php $form = ActiveForm::begin([
    'action' => ['index'],
    'method' => 'get',
    'options' => [
        'data-pjax' => 1
    ],
]); ?>

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

<?= $form->field($model, 'position_name')->widget(Select2::class,
    [
        'data' => ArrayHelper::map(Position::find()->all(), 'name', 'name'),
        'options' => ['placeholder' => 'Выберите должность...'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]);
?>

<?= $form->field($model, 'status')->dropDownList($model->getStatusesArray(), ['prompt' => 'Выберите статус...']) ?>

    <div class="row">
        <div class="col-8"><?= Html::submitButton('<i class="fas fa-search text-primary"></i>Поиск', ['class' => 'btn btn-app mx-auto btn-block mb-0']) ?></div>
        <div class="col-4"><?= Html::a('<i class="fas fa-redo text-dark"></i>Сброс', ['index'], ['class' => 'btn btn-app mx-auto btn-block mb-0']) ?></div>
    </div>

<?php ActiveForm::end(); ?>