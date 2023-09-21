<?php

use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;

/** @var yii\web\View $this */
/** @var app\models\Document $model */
/** @var app\models\Upload $upload */

$this->title = 'Новый файл для: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Обращения', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = ['label' => 'Файлы', 'url' => ['document/view', 'id' => $model->id, '#' => 'file']];
$this->params['breadcrumbs'][] = 'Новый файл';
$this->params['buttons'] = [
    'upload' => Html::submitButton('<i class="far fa-arrow-alt-circle-up text-success"></i>Загрузить', ['class' => 'btn btn-app', 'form' => 'upload']),
    'undo' => Html::a('<i class="far fa-arrow-alt-circle-left text-muted"></i>Вернуться', ['document/view', 'id' => $model->id, '#' => 'file'], ['class' => 'btn btn-app'])
];
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">

                    <?php $form = ActiveForm::begin(['id' => 'upload', 'options' => ['enctype' => 'multipart/form-data']]) ?>

                    <?= $form->field($upload, 'name')->textInput(['maxlength' => true]) ?>

                    <?= $form->field($upload, 'file')->fileInput() ?>

                    <?php ActiveForm::end() ?>

                </div>
            </div>
        </div>
    </div>
</div>




