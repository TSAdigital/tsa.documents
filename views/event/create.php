<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Event $model */

$this->title = 'Новое мероприятие';
$this->params['breadcrumbs'][] = ['label' => 'Мероприятия', 'url' => ['index']];
$this->params['buttons'] = [
    'save' => Html::submitButton('<i class="far fa-save text-green"></i>Сохранить', ['class' => 'btn btn-app', 'form' => 'event']),
    'undo' => Html::a('<i class="far fa-arrow-alt-circle-left text-muted"></i>Вернуться', ['index'], ['class' => 'btn btn-app'])
];
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">

                    <?= $this->render('_form', [
                        'model' => $model,
                    ]) ?>

                </div>
            </div>
        </div>
    </div>
</div>
