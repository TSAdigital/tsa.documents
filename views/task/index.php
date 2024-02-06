<?php

use app\models\Task;
use app\models\View;
use yii\bootstrap4\Modal;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\StringHelper;
use yii\widgets\ListView;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var app\models\TaskSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Задачи';
$this->params['breadcrumbs'][] = $this->title;
$this->params['buttons'] = [
    'create' => (Yii::$app->user->can('admin') or Yii::$app->user->can('createTasks')) ? Html::a('<i class="fas fa-plus-circle text-success"></i>Добавить', ['create'], ['class' => 'btn btn-app']) : null,
    'filter' => Html::a('<i class="fas fa-filter text-dark"></i>Фильтр', '#', ['class' => 'btn btn-app', 'data-toggle' => 'modal', 'data-target' => '#filter']),
];
?>

<?php Pjax::begin(); ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12 d-block d-sm-none">

            <?= ListView::widget([
                'dataProvider' => $dataProvider,
                'emptyText' => '<div class="card card-body p-2">Ничего не найдено.</div>',
                'itemOptions' => [
                    'tag' => false,
                ],
                'itemView' => '_list_task',
                'viewParams' => [
                    'page_size' => $dataProvider->pagination->pageSize,
                    'current_page' => (int) is_numeric(Yii::$app->request->get('page')) ? Yii::$app->request->get('page') : 0
                ],
                'pager' => [
                    'maxButtonCount' => 4,
                    'class' => 'yii\bootstrap4\LinkPager',
                    'options' => ['class' => 'mt-3']
                ],
            ]); ?>

        </div>
        <div class="col-md-12 d-none d-sm-block">
            <div class="card">
                <div class="card-body pb-0">

                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
                        'options' => ['class' => 'table-responsive'],
                        'tableOptions' => ['class' => 'table table-striped'],
                        'columns' => [
                            [
                                'class' => 'yii\grid\SerialColumn',
                                'headerOptions' => ['style' => 'text-align: center !important; vertical-align: middle !important; white-space: nowrap'],
                                'contentOptions' => ['style' => 'text-align: center !important; vertical-align: middle !important; white-space: nowrap']
                            ],
                            [
                                'attribute' => 'name',
                                'options' => ['width' => '75%'],
                                'filterInputOptions' => [
                                    'class' => 'form-control',
                                    'autocomplete' => 'off'
                                ],
                                'format' => 'raw',
                                'headerOptions' => ['style' => 'vertical-align: middle !important; min-width:200px; white-space: nowrap'],
                                'contentOptions' => ['style' => 'vertical-align: middle !important; min-width:200px', 'class' => 'truncate'],
                                'value' => function ($model) {
                                    $user_id = Yii::$app->user->identity->id;

                                    $query = View::findOne(['type' => 'task', 'record_id' => $model->id, 'user_id' => $user_id]);

                                    if($model->resolution != NULL and is_array($model->resolution)){
                                        if(in_array($user_id, $model->resolution) or $model->executor_id == $user_id){
                                            $view = (($query == null and $model->user_id != $user_id) or ($query == null and $model->executor_id == $user_id));
                                        }else{
                                            $view = false;
                                        }
                                    }else{
                                        $view = ($query == null and $model->user_id != $user_id);
                                    }

                                    return ($view ? '<small><i class="fas fa-exclamation-circle text-danger mr-1" data-toggle="tooltip" data-placement="top" title="Новая задача"></i></small>' : null) . Html::a(Html::encode($model->name), ['task/view', 'id' => $model->id], ['data-pjax' => 0]);
                                }
                            ],
                            [
                                'attribute' => 'date',
                                'options' => ['width' => '10%'],
                                'filterInputOptions' => [
                                    'class' => 'form-control',
                                    'style' => 'text-align: center !important; vertical-align: middle !important;',
                                    'autocomplete' => 'off'
                                ],
                                'headerOptions' => ['style' => 'text-align: center !important; vertical-align: middle !important; min-width:165px; white-space: nowrap'],
                                'contentOptions' => ['style' => 'text-align: center !important; vertical-align: middle !important; min-width:165px;'],
                            ],
                            [
                                'filter' => Task::getStatusesArray(),
                                'attribute' => 'status',
                                'options' => ['width' => '15%'],
                                'headerOptions' => ['style' => 'text-align: center !important; vertical-align: middle !important; min-width:150px; white-space: nowrap'],
                                'contentOptions' => ['style' => 'text-align: center !important; vertical-align: middle !important; min-width:150px;'],
                                'format' => 'raw',
                                'value' => function ($model, $key, $index, $column) {
                                    /** @var Task $model */
                                    /** @var \yii\grid\DataColumn $column */
                                    $value = $model->{$column->attribute};
                                    switch ($value) {
                                        case Task::STATUS_ACTIVE:
                                            $class = 'success';
                                            break;
                                        case Task::STATUS_INACTIVE:
                                            $class = 'danger';
                                            break;
                                        case Task::STATUS_DRAFT:
                                            $class = 'dark';
                                            break;
                                        default:
                                            $class = 'default';
                                    };
                                    $html = Html::tag('span', Html::encode($model->getStatusName()), ['class' => 'badge badge-' . $class]);
                                    return empty($value) ? null : $html;
                                },

                            ],

                        ],
                    ]); ?>

                </div>
            </div>
        </div>
    </div>
</div>

<?php
Modal::begin([
    'title' => 'Фильтр',
    'id' => 'filter',
    'size' => 'modal-lg',
    'closeButton' => [
        'id' => 'close-button',
        'class' => 'close',
        'data-dismiss' => 'modal',
    ],
    'clientOptions' => ['backdrop' => false]
]);
?>

<?= $this->render('_search', ['model' => $searchModel]); ?>

<?php Modal::end(); ?>

<?php Pjax::end(); ?>
