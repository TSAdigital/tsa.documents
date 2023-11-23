<?php

use yii\bootstrap4\Modal;
use yii\helpers\Html;
use yii\widgets\ListView;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var app\models\Event $model */
/** @var app\models\Group $groups */

$this->title = 'Новое мероприятие';
$this->params['breadcrumbs'][] = ['label' => 'Мероприятия', 'url' => ['index']];
$this->params['buttons'] = [
    'groups' => (Yii::$app->user->can('eventsAdmin')  or Yii::$app->user->can('admin')) ? Html::a('<i class="fas fa-users text-dark"></i>Группы', '#', ['class' => 'btn btn-app', 'data-toggle' => 'modal', 'data-target' => '#groups']) : NULL,
    'save' => Html::submitButton('<i class="far fa-save text-green"></i>Сохранить', ['class' => 'btn btn-app', 'form' => 'event']),
    'undo' => Html::a('<i class="far fa-arrow-alt-circle-left text-muted"></i>Вернуться', ['index'], ['class' => 'btn btn-app'])
];
$script = <<< JS
    function modalToggle(id){
        $("#event-resolution").val(id).trigger("change");
        $('#groups').modal('toggle');
    }
JS;
$this->registerJs($script, yii\web\View::POS_END);
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

<?php
Modal::begin([
    'title' => 'Выберите группу',
    'id' => 'groups',
    'size' => 'modal-lg',
    'closeButton' => [
        'id' => 'close-button',
        'class' => 'close',
        'data-dismiss' => 'modal',
    ],
    'clientOptions' => [
        'backdrop' => false
    ]
]);
?>

<?php if(Yii::$app->user->can('eventsAdmin') or Yii::$app->user->can('admin')): ?>

<?php Pjax::begin(); ?>

<?php
$template = '
        {summary}  
        <div class="table-responsive">
        <table class="table table-striped table-bordered mb-0">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col" class="col-12" style="white-space: nowrap">Наименование</th>                                                                                          
            </tr>
        </thead>
        <tbody>
            {items}
            </tbody>
        </table>
        </div>
        {pager}
    ';
?>

<?= ListView::widget([
    'dataProvider' => $groups,
    'layout' => $template,
    'emptyText' => 'Ничего не найдено.',
    'viewParams' => [
        'page_size' => $groups->pagination->pageSize,
        'current_page' => (int) is_numeric(Yii::$app->request->get('page')) ? Yii::$app->request->get('page') : 0
    ],
    'itemView' => '_list-groups',
    'pager' => [
        'options' => [
            'class' => 'mt-2',
        ]
    ],
]);
?>

<?php Pjax::end(); ?>

<?php Modal::end(); ?>

<?php endif; ?>