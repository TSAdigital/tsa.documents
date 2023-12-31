<?php

use kartik\select2\Select2;
use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Modal;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\widgets\DetailView;
use yii\widgets\ListView;

/** @var yii\web\View $this */
/** @var app\models\Task $model */
/** @var app\models\View $viewed */
/** @var app\models\DocumentTask $documents */
/** @var app\models\DocumentTask $document_task */
/** @var app\models\TaskFavourites $favourites */
/** @var app\models\Discussion $discussion */
/** @var app\models\Discussion $discussions */
/** @var int $discussion_count */
/** @var bool $viewed_button */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Задачи', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$this->params['buttons'] = [
    'favourites' => $favourites ? Html::a('<i class="fas fa-star text-warning"></i>Избранное', ['task/favourites', 'id' => $model->id], ['class' => 'btn btn-app', 'data' => [
        'confirm' => 'Удалить этот документ из избранного?',
        'method' => 'post',
    ]]) : Html::a('<i class="far fa-star text-warning"></i>Избранное', ['task/favourites', 'id' => $model->id], ['class' => 'btn btn-app', 'data' => [
        'confirm' => 'Добавить этот документ в избранное?',
        'method' => 'post',
    ]]),
    'viewed' =>  ($model->user_id != Yii::$app->user->identity->id and $viewed_button) ? Html::a('<i class="far fa-check-circle text-success"></i>Ознакомлен', ['viewed', 'id' => $model->id], ['class' => 'btn btn-app']) : null,
    'publish' =>  ($model->status == $model::STATUS_DRAFT and ($model->user_id == Yii::$app->user->identity->id or Yii::$app->user->can('admin'))) ? Html::a('<i class="far fa-check-circle text-dark"></i>Опубликовать', ['publish', 'id' => $model->id], ['class' => 'btn btn-app']) : null,
    'update' => (Yii::$app->user->can('admin') or (Yii::$app->user->can('updateTasks') and ($model->user_id == Yii::$app->user->identity->id or $model->executor_id == Yii::$app->user->identity->id))) ? Html::a('<i class="fas fa-edit text-primary"></i> Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-app']) : null,
    /*  'delete' =>  Html::a('<i class="fas fa-trash-alt text-danger"></i> Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-app',
            'data' => [
                'confirm' => 'Вы уверены, что хотите удалить этого сотрудника?',
                'method' => 'post',
            ],
        ]), */
    'undo' => Html::a('<i class="far fa-arrow-alt-circle-left text-muted"></i>Вернуться', ['task/index'], ['class' => 'btn btn-app'])
];

?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header p-2">
                    <ul class="nav nav-pills" id="myTab">
                        <li class="nav-item"><a class="nav-link active" href="#base" data-toggle="tab">Основное</a></li>
                        <li class="nav-item"><a class="nav-link" href="#document" data-toggle="tab">Документы</a></li>
                        <li class="nav-item"><a class="nav-link" href="#discussions" data-toggle="tab">Обсуждения <span class="badge badge-danger text-bold"><?= ($discussion_count > 0 and $model->discussion == $model::DISCUSSION_ENABLE) ? $discussion_count : false ?></span></a></li>
                        <li class="nav-item"><a class="nav-link" href="#event" data-toggle="tab">События</a></li>
                    </ul>
                </div>
                <div class="card-body p-2 p-md-3">
                    <div class="tab-content">
                        <div class="active tab-pane" id="base">

                            <div class="d-block d-sm-none">
                                <table class="table table-striped table-bordered mb-0">
                                    <tbody>
                                    <tbody>
                                    <tr>
                                        <td><p class="mb-0"><b><?= $model->getAttributeLabel('name') ?>: </b><?= Html::encode($model->name) ?></p></td>
                                    </tr>
                                    <?php if(!empty($model->description)) : ?>
                                    <tr>
                                        <td><p class="mb-0"><b><?= $model->getAttributeLabel('description') ?>: </b><?= Yii::$app->formatter->asNtext($model->description) ?></p></td>
                                    </tr>
                                    <?php endif; ?>
                                    <tr>
                                        <td><p class="mb-0"><b><?= $model->getAttributeLabel('date') ?>: </b><?= Html::encode($model->date) ?></p></td>
                                    </tr>
                                    <?php if(!empty($model->executor_id)) : ?>
                                    <tr>
                                        <td><p class="mb-0"><b><?= $model->getAttributeLabel('executor_id') ?>: </b><?= $model->executor_id ? Html::a(Html::encode($model->executor->employee_name), ['site/profile', 'id' => $model->executor_id], ['data-pjax' => 0]) : NULL ?></p></td>
                                    </tr>
                                    <?php endif; ?>
                                    <tr>
                                        <td><p class="mb-0"><b><?= $model->getAttributeLabel('resolution') ?>: </b><?= !empty($model->getUsers($model->resolution)) ? $model->getUsers($model->resolution) : 'Все сотрудники' ?></p></td>
                                    </tr>
                                    <tr>
                                        <td><p class="mb-0"><b><?= $model->getAttributeLabel('priority') ?>: </b><?= Html::encode($model->getPriorityName()) ?></p></td>
                                    </tr>
                                    <tr>
                                        <td><p class="mb-0"><b><?= $model->getAttributeLabel('user_id') ?>: </b><?= Html::a(Html::encode(isset($model->user->employee) ? Html::encode($model->user->employee->employeeFullName) : Html::encode($model->user->username)), ['site/profile', 'id' => $model->user_id], ['data-pjax' => 0]) ?></p></td>
                                    </tr>
                                    <tr>
                                        <td><p class="mb-0"><b><?= $model->getAttributeLabel('status') ?>: </b><?= Html::encode($model->getStatusName()) ?></p></td>
                                    </tr>
                                    <tr>
                                        <td><p class="mb-0"><b><?= $model->getAttributeLabel('created_at') ?>: </b><?= Html::encode(Yii::$app->formatter->asDatetime($model->created_at)) ?></p></td>
                                    </tr>
                                    <tr>
                                        <td><p class="mb-0"><b><?= $model->getAttributeLabel('updated_at') ?>:</b><?= Html::encode(Yii::$app->formatter->asDatetime($model->updated_at)) ?></p></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="table-responsive d-none d-sm-block">

                                <?= DetailView::widget([
                                    'model' => $model,
                                    'options' => ['class' => 'table table-striped table-bordered mb-0'],
                                    'attributes' => [
                                        [
                                            'attribute' => 'name',
                                            'captionOptions' => ['width' => '170px'],
                                        ],
                                        [
                                            'attribute' => 'description',
                                            'format' => 'ntext',
                                            'visible' => !empty($model->description)
                                        ],
                                        'date',
                                        [
                                            'attribute' => 'executor_id',
                                            'format' => 'raw',
                                            'value' => $model->executor_id ? Html::a(Html::encode($model->executor->employee_name), ['site/profile', 'id' => $model->executor_id], ['data-pjax' => 0]) : NULL,
                                            'visible' => !empty($model->executor_id)
                                        ],
                                        [
                                            'attribute' => 'resolution',
                                            'format' => 'raw',
                                            'value' =>  !empty($model->getUsers($model->resolution)) ? $model->getUsers($model->resolution) : 'Все сотрудники',
                                        ],
                                        [
                                            'attribute' => 'priority',
                                            'value' => $model->getPriorityName(),
                                        ],
                                        [
                                            'attribute' => 'user_id',
                                            'format' => 'raw',
                                            'value' => Html::a(Html::encode(isset($model->user->employee) ? Html::encode($model->user->employee->employeeFullName) : Html::encode($model->user->username)), ['site/profile', 'id' => $model->user_id], ['data-pjax' => 0]),
                                        ],
                                        [
                                            'attribute' => 'status',
                                            'value' => $model->getStatusName(),
                                        ],
                                        'uniq_id',
                                        'created_at:datetime',
                                        'updated_at:datetime',
                                    ],
                                ]) ?>

                            </div>

                        </div>
                        <div class="tab-pane" id="document">

                            <?php
                            $template = '
                                        {summary}  
                                        <div class="table-responsive">
                                        <table class="table table-striped table-bordered mb-0">
                                        <thead>
                                            <tr>
                                                <th scope="col">#</th>
                                                <th scope="col"  style="width: 95%; white-space: nowrap">Документ</th>
                                                <th scope="col"  style="width: 5%; text-align: center; white-space: nowrap">' . Html::a('<i class="fas fa-plus-circle text-success"></i>', '#', ['data-toggle' => 'modal', 'data-target' => '#documents']) . '</th>                                                   
                                            </tr>
                                        </thead>
                                        <tbody>
                                            {items}
                                            </tbody>
                                        </table>
                                        </div>
                                        {pager}
                                ';

                            $template_user = '
                                        {summary}  
                                        <div class="table-responsive">
                                        <table class="table table-striped table-bordered mb-0">
                                        <thead>
                                            <tr>
                                                <th scope="col">#</th>
                                                <th scope="col" style="width: 100%; white-space: nowrap">Документ</th>                                                                                         
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
                                'dataProvider' => $documents,
                                'layout' => (Yii::$app->user->can('admin') or $model->user_id == Yii::$app->user->identity->id) ? $template : $template_user,
                                'emptyText' => (Yii::$app->user->can('admin') or $model->user_id == Yii::$app->user->identity->id) ? Html::a('<i class="fas fa-plus-circle text-success"></i>Добавить', '#', ['class' => 'btn btn-app mx-auto d-block mb-0', 'data-toggle' => 'modal', 'data-target' => '#documents']) : 'Ничего не найдено.',
                                'viewParams' => [
                                    'task' => $model,
                                    'page_size' => $documents->pagination->pageSize,
                                    'current_page' => (int) is_numeric(Yii::$app->request->get('page-document')) ? Yii::$app->request->get('page-document') : 0
                                ],
                                'itemView' => '_list-document',
                                'pager' => [
                                    'options' => [
                                        'id' => 'list-viewed-pagination',
                                    ]
                                ],
                            ]);
                            ?>

                        </div>
                        <div class="tab-pane" id="discussions">
                            <div class="col-md-12 col-lg-10 col-xl-7">
                                <div class="row">
                                    <div class="col-12">

                                        <?php if($model->discussion == $model::DISCUSSION_ENABLE) : ?>

                                            <?php $form = ActiveForm::begin([
                                                'action' => ['task/discussion-create', 'id' => $model->id],
                                                'fieldConfig' => [
                                                    'template' => '<div class="input-group"><div class="input-group-append"><button class="btn btn-primary discussion-btn" type="submit"><i class="far fa-paper-plane"></i></button></div>{input}{error}</div>',
                                                ]
                                            ]); ?>

                                            <?= $form->field($discussion, 'message')->textarea(['rows' => 1, 'maxlength' => true, 'placeholder' => 'Текст сообщения...']) ?>

                                            <?php ActiveForm::end(); ?>

                                            <?= ListView::widget([
                                                'dataProvider' => $discussions,
                                                'emptyText' => 'Нет сообщений.',
                                                'viewParams' => [
                                                    'task' => $model,
                                                ],
                                                'pager' => [
                                                    'options' => [
                                                        'id' => 'list-viewed-pagination',
                                                    ]
                                                ],
                                                'itemView' => '_list-discussion',
                                            ]);
                                            ?>
                                        <?php else:; ?>
                                            <p class="mb-0">Обсуждения отключены.</p>
                                        <?php endif; ?>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="event">
                            <div class="accordion" id="accordionEvent">
                                <div class="card  pb-0 mb-0">
                                    <div class="card-header p-2" id="headingOne">
                                        <h2 class="mb-0">
                                            <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                                С задачей ознакомились
                                            </button>
                                        </h2>
                                    </div>
                                    <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionEvent">
                                        <div class="card-body">

                                            <?php
                                            $template = '
                                                    {summary}  
                                                    <div class="table-responsive">
                                                    <table class="table table-striped table-bordered mb-0">
                                                    <thead>
                                                        <tr>
                                                            <th scope="col">#</th>
                                                            <th scope="col" class="col-7 col-md-8 col-lg-9" style="white-space: nowrap">Сотрудник</th>
                                                            <th scope="col" class="col-5 col-md-4 col-lg-3" style="text-align: center; white-space: nowrap">Дата</th>                                                                                                  
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
                                                'dataProvider' => $viewed,
                                                'layout' => $template,
                                                'emptyText' => 'Ничего не найдено.',
                                                'viewParams' => [
                                                    'page_size' => $viewed->pagination->pageSize,
                                                    'current_page' => (int) is_numeric(Yii::$app->request->get('page-viewed')) ? Yii::$app->request->get('page-viewed') : 0
                                                ],
                                                'itemView' => '_list-viewed',
                                                'pager' => [
                                                    'options' => [
                                                        'id' => 'list-viewed-pagination',
                                                    ]
                                                ],
                                            ]);
                                            ?>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
Modal::begin([
    'title' => 'Добавить документ',
    'id' => 'documents',
    'size' => 'modal-lg',
    'closeButton' => [
        'id' => 'close-button',
        'class' => 'close',
        'data-dismiss' => 'modal',
    ],
    'options' => [
            'tabindex' => false
    ],
    'clientOptions' => [
            'backdrop' => false
    ]
]);
?>

<?php if(Yii::$app->user->can('admin') or $model->user_id == Yii::$app->user->identity->id): ?>

<?php $form = ActiveForm::begin(['action' => ['task/add-document', 'id' => $model->id]]); ?>

<?= $form->field($document_task, 'document_id')->widget(Select2::classname(),
    [
        'options' => ['placeholder' => 'Выберите документ...'],
        'pluginOptions' => [
            'allowClear' => true,
            'minimumInputLength' => 3,
            'language' => [
                'errorLoading' => new JsExpression("function () { return 'Ждем результатов...'; }"),
            ],
            'ajax' => [
                'url' => Url::to(['document/document-list']),
                'dataType' => 'json',
                'data' => new JsExpression('function(params) { return {q:params.term}; }')
            ],
            'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
            'templateResult' => new JsExpression('function(address_id) { return address_id.text; }'),
            'templateSelection' => new JsExpression('function (address_id) { return address_id.text; }'),
        ],
    ]);
?>

<div class="form-group mb-0">
    <?= Html::submitButton('<i class="fas fa-plus-circle text-success"></i>Добавить', ['class' => 'btn btn-app mx-auto btn-block mb-0']) ?>
</div>

<?php ActiveForm::end(); ?>

<?php endif; ?>

<?php Modal::end(); ?>
