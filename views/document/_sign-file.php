<?php

use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Document $model */
/** @var yii\widgets\ActiveForm $form */
?>

<?php $form = ActiveForm::begin([
    'action' => ['index'],
    'method' => 'POST',
    'options' => [
        'data-pjax' => 1
    ],
]); ?>

<?= Select2::widget([
    'name' => 'select',
    'id' => 'select-file-sign',
    'options' => [
        'placeholder' => 'Выберите сертификат ...',
    ],
]); ?>

<div class="row mt-3">
    <div class="col-5"><?= Html::a('<i class="fas fa-signature text-green"></i>Подписать', '#', ['id' => 'sign-file', 'class' => 'add-id btn btn-app mx-auto btn-block mb-0', 'onclick' => 'singFile(this.id)']) ?></div>
    <div class="col-7"><?= Html::a('<i class="fas fa-redo text-dark"></i>Обновить список сертификатов', '#', ['id' => 'select-file-sign', 'class' => 'btn btn-app mx-auto btn-block mb-0', 'onclick' => 'update(this.id)']) ?></div>
</div>

<?php ActiveForm::end(); ?>