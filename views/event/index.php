<?php

use app\models\Event;
use yii\bootstrap4\Modal;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ListView;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var app\models\EventSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Мероприятия';
$this->params['breadcrumbs'][] = $this->title;
$this->params['buttons'] = [
    'create' => (Yii::$app->user->can('admin') or Yii::$app->user->can('createEvents')) ? Html::a('<i class="fas fa-plus-circle text-success"></i>Добавить', ['create'], ['class' => 'btn btn-app']) : null,
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
                'itemView' => '_event_position',
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
                                'options' => ['width' => '80%'],
                                'format' => 'raw',
                                'headerOptions' => ['style' => 'vertical-align: middle !important; min-width:200px; white-space: nowrap'],
                                'contentOptions' => ['style' => 'vertical-align: middle !important; min-width:200px'],
                                'value' => function ($model) {
                                    return Html::a(Html::encode($model->name), ['event/view', 'id' => $model->id], ['data-pjax' => 0]);
                                }
                            ],
                            [
                                'filter' => Event::getStatusesArray(),
                                'attribute' => 'status',
                                'options' => ['width' => '20%'],
                                'headerOptions' => ['style' => 'text-align: center !important; vertical-align: middle !important; min-width:150px; white-space: nowrap'],
                                'contentOptions' => ['style' => 'text-align: center !important; vertical-align: middle !important; min-width:150px;'],
                                'format' => 'raw',
                                'value' => function ($model, $key, $index, $column) {
                                    /** @var Event $model */
                                    /** @var \yii\grid\DataColumn $column */
                                    $value = $model->{$column->attribute};
                                    switch ($value) {
                                        case Event::STATUS_ACTIVE:
                                            $class = 'success';
                                            break;
                                        case Event::STATUS_INACTIVE:
                                            $class = 'danger';
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
