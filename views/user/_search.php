<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;

/** @var yii\web\View $this */
/** @var app\models\UserSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<?php $form = ActiveForm::begin([
    'action' => ['index'],
    'method' => 'get',
    'options' => [
        'data-pjax' => 1
    ],
]); ?>

<?= $form->field($model, 'username') ?>

<?= $form->field($model, 'roles')->dropDownList($model->getRoles(), ['prompt' => 'Выберите роль...']); ?>

<?= $form->field($model, 'email')->widget(MaskedInput::class, [
    'clientOptions' => [
        'alias' => 'email'
    ],
]);
?>

<?= $form->field($model, 'status')->dropDownList($model->getStatusesArray(), ['prompt' => 'Выберите статус...']) ?>

<div class="row">
    <div class="col-8"><?= Html::submitButton('<i class="fas fa-search text-primary"></i>Поиск', ['class' => 'btn btn-app mx-auto btn-block mb-0']) ?></div>
    <div class="col-4"><?= Html::a('<i class="fas fa-redo text-dark"></i>Сброс', ['index'], ['class' => 'btn btn-app mx-auto btn-block mb-0']) ?></div>
</div>

<?php ActiveForm::end(); ?>
