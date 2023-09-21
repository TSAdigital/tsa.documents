<?php

use app\models\User;
use kartik\form\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;


/** @var yii\web\View $this */
/** @var app\models\Group $model */
/** @var yii\widgets\ActiveForm $form */
?>

<?php $form = ActiveForm::begin(['id' => 'group']); ?>

<?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'user')->widget(Select2::class, [
    'data' => $model->user ? ArrayHelper::map(User::find()->joinWith(['employee', 'userRoles'])->andWhere(['!=', 'auth_assignment.item_name', 'admin'])->andWhere(['!=', 'user.id', Yii::$app->user->identity->id])->orWhere(['user.status' => 10])->andWhere(['!=', 'auth_assignment.item_name', 'admin'])->andWhere(['!=', 'user.id', Yii::$app->user->identity->id])->orderBy('employee.last_name ASC')->all(), 'id', 'employeeNamePosition') : ArrayHelper::map(User::find()->joinWith(['employee', 'userRoles'])->where(['!=', 'user.id', Yii::$app->user->identity->id])->andWhere(['!=', 'auth_assignment.item_name', 'admin'])->andWhere(['user.status' => 10])->orderBy('employee.last_name ASC')->all(), 'id', 'employeeNamePosition'),
    'theme' => 'krajee-bs3',
    'options' => ['placeholder' => 'Выберите сотрудника...', 'multiple' => true],
]) ?>

<?= !$model->isNewRecord ? $form->field($model, 'status')->dropDownList($model->getStatusesArray(), ['prompt' => 'Выберите статус...']) : null; ?>

<?= Yii::$app->user->can('admin') ? $form->field($model, 'visibility', ['enableClientValidation' => false])->checkbox(['custom' => true, 'switch' => true])->label('Публичная группа (будет отображаться у всех пользователей)') : null; ?>

<?php ActiveForm::end(); ?>
