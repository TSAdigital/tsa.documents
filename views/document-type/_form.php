<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\DocumentType $model */
/** @var yii\widgets\ActiveForm $form */
?>

<?php $form = ActiveForm::begin(['id' => 'document-type']); ?>

<?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

<?= !$model->isNewRecord ? $form->field($model, 'status')->dropDownList($model->getStatusesArray(), ['prompt' => 'Выберите статус...']) : null; ?>

<?php ActiveForm::end(); ?>
