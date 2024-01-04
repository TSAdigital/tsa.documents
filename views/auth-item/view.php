<?php

use app\models\AuthItem;
use kartik\select2\Select2;
use yii\bootstrap4\ActiveForm;use yii\bootstrap4\Modal;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\ListView;

/** @var yii\web\View $this */
/** @var app\models\AuthItem $model */
/** @var app\models\AuthItemChild $permission */
/** @var app\models\AuthItemChild $authItemChild */

$this->title = $model->description;
$this->params['breadcrumbs'][] = ['label' => 'Роли', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$this->params['buttons'] = [
    'update' => Html::a('<i class="fas fa-edit text-primary"></i> Изменить', ['update', 'name' => $model->name], ['class' => 'btn btn-app']),
    /*  'delete' =>  Html::a('<i class="fas fa-trash-alt text-danger"></i> Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-app',
            'data' => [
                'confirm' => 'Вы уверены, что хотите удалить этого сотрудника?',
                'method' => 'post',
            ],
        ]), */
    'undo' => Html::a('<i class="far fa-arrow-alt-circle-left text-muted"></i>Вернуться', ['index'], ['class' => 'btn btn-app'])
];
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header p-2">
                    <ul class="nav nav-pills" id="myTab">
                        <li class="nav-item"><a class="nav-link active" href="#base" data-toggle="tab">Основное</a></li>
                        <li class="nav-item"><a class="nav-link" href="#permission" data-toggle="tab">Разрешения</a></li>
                    </ul>
                </div>
                <div class="card-body p-2 p-md-3">
                    <div class="tab-content">
                        <div class="active tab-pane" id="base">

                            <div class="d-block d-sm-none">
                                <table class="table table-striped table-bordered mb-0">
                                    <tbody>
                                    <tr>
                                        <td><p class="mb-0"><b>Наименование: </b><?= Html::encode($model->description) ?></p></td>
                                    </tr>
                                    <tr>
                                        <td><p class="mb-0"><b>Статус: </b><?= Html::encode($model->getStatusName()) ?></p></td>
                                    </tr>
                                    <tr>
                                        <td><p class="mb-0"><b>Запись создана: </b><?= Html::encode(Yii::$app->formatter->asDatetime($model->created_at)) ?></p></td>
                                    </tr>
                                    <tr>
                                        <td><p class="mb-0"><b>Запись обновлена: </b><?= Html::encode(Yii::$app->formatter->asDatetime($model->updated_at)) ?></p></td>
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
                                            'attribute' => 'description',
                                            'captionOptions' => ['width' => '170px'],
                                        ],
                                        [
                                            'attribute' => 'status',
                                            'value' => $model->getStatusName(),
                                        ],
                                        'created_at:datetime',
                                        'updated_at:datetime',
                                    ],
                                ]) ?>

                            </div>

                        </div>
                        <div class="tab-pane" id="permission">

                            <?php
                            $template = '
                                        {summary}  
                                        <div class="table-responsive">
                                        <table class="table table-striped table-bordered mb-0">
                                        <thead>
                                            <tr>
                                                <th scope="col">#</th>
                                                <th scope="col"  style="width: 95%; white-space: nowrap">Разрешение</th>
                                                <th scope="col"  style="width: 5%; text-align: center; white-space: nowrap">' . Html::a('<i class="fas fa-plus-circle text-success"></i>', '#', ['data-toggle' => 'modal', 'data-target' => '#permissions']) . '</th>                                                   
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
                                                <th scope="col" style="width: 100%; white-space: nowrap">Разрешение</th>                                                                                         
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
                                'dataProvider' => $permission,
                                'layout' => Yii::$app->user->can('admin') ? $template : $template_user,
                                'emptyText' => Yii::$app->user->can('admin') ? Html::a('<i class="fas fa-plus-circle text-success"></i>Добавить', '#', ['class' => 'btn btn-app mx-auto d-block mb-0', 'data-toggle' => 'modal', 'data-target' => '#permissions']) : 'Ничего не найдено.',
                                'viewParams' => [
                                    'authItem' => $model,
                                    'page_size' => $permission->pagination->pageSize,
                                    'current_page' => (int) is_numeric(Yii::$app->request->get('page-permission')) ? Yii::$app->request->get('page-permission') : 0
                                ],
                                'itemView' => '_list-permission',
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

<?php
Modal::begin([
    'title' => 'Добавить разрешение',
    'id' => 'permissions',
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

<?php $form = ActiveForm::begin(['action' => ['auth-item/permission-add', 'name' => $model->name]]); ?>

<?= $form->field($authItemChild, 'child')->widget(Select2::class,
    [
        'data' => $model->getPermissionSelect(),
        'options' => ['placeholder' => 'Выберите разрешение...'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]);
?>

<div class="form-group mb-0">
    <?= Html::submitButton('<i class="fas fa-plus-circle text-success"></i>Добавить', ['class' => 'btn btn-app mx-auto btn-block mb-0']) ?>
</div>

<?php ActiveForm::end(); ?>

<?php Modal::end(); ?>

