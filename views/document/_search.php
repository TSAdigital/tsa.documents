<?php

use app\models\DocumentType;
use app\models\User;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\DocumentSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<?php
$layout = <<< HTML
{input1}
{separator}
{input2}
<div class="input-group-append">
    <span class="input-group-text kv-date-remove">
        <i class="fas fa-times kv-dp-icon"></i>
    </span>
</div>
HTML;
$script = <<< JS
    jQuery('input[id=documentsearch-date_from], input[id=documentsearch-date_to]').attr('autocomplete', 'off');
JS;
$this->registerJs($script);
?>

<?php $form = ActiveForm::begin([
    'action' => ['index'],
    'method' => 'get',
    'options' => [
        'data-pjax' => 1
    ],
]); ?>

<?= $form->field($model, 'name') ?>

<?= $form->field($model, 'number') ?>

<div class="form-group">
    <label class="form-label"><?= $model->getAttributeLabel('date') ?></label>
    <?= DatePicker::widget([
        'model' => $model,
        'attribute' => 'date_from',
        'attribute2' => 'date_to',
        'type' => DatePicker::TYPE_RANGE,
        'separator' => '<i class="fas fa-exchange-alt"></i>',
        'layout' => $layout,
        'pluginOptions' => [
            'format' => 'dd.mm.yyyy',
            'autoclose' => true,
            'todayHighlight' => true,
            'todayBtn' => true
        ]
    ]) ?>
</div>

<?= $form->field($model, 'type')->dropDownList(ArrayHelper::map(DocumentType::find()->all(), 'id', 'name'), ['prompt' => 'Выберите тип документа...']) ?>

<?= $form->field($model, 'status')->dropDownList($model->getStatusesArray(), ['prompt' => 'Выберите статус...']) ?>

<div class="row">
    <div class="col-8"><?= Html::submitButton('<i class="fas fa-search text-primary"></i>Поиск', ['class' => 'btn btn-app mx-auto btn-block mb-0']) ?></div>
    <div class="col-4"><?= Html::a('<i class="fas fa-redo text-dark"></i>Сброс', ['index'], ['class' => 'btn btn-app mx-auto btn-block mb-0']) ?></div>
</div>

<?php ActiveForm::end(); ?>
