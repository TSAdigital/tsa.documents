<?php

use yii\bootstrap4\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Position $model */
/** @var yii\widgets\ActiveForm $form */
?>

<?php $form = ActiveForm::begin(['id' => 'position']); ?>

<?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

<?= !$model->isNewRecord ? $form->field($model, 'status')->dropDownList($model->getStatusesArray(), ['prompt' => 'Выберите статус...']) : null; ?>

<?php ActiveForm::end(); ?>