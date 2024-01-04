<?php

use app\models\User;
use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Modal;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ListView;
use yii\widgets\MaskedInput;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var app\models\UserSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Пользователи';
$this->params['breadcrumbs'][] = $this->title;
$this->params['buttons'] = [
    'create' => Html::a('<i class="fas fa-plus-circle text-success"></i>Добавить', ['create'], ['class' => 'btn btn-app']),
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
                'itemView' => '_list_user',
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
                                'attribute' => 'username',
                                'headerOptions' => ['style' => 'vertical-align: middle !important; min-width:200px; white-space: nowrap'],
                                'contentOptions' => ['style' => 'vertical-align: middle !important; min-width:200px'],
                                'options' => ['width' => '30%'],
                                'format' => 'raw',
                                'value' => function ($model) {
                                    return Html::a($model->username, ['user/view', 'id' => $model->id], ['data-pjax' => 0]);
                                }
                            ],
                            [
                                'attribute' => 'email',
                                'headerOptions' => ['style' => 'vertical-align: middle !important; min-width:200px; white-space: nowrap'],
                                'contentOptions' => ['style' => 'vertical-align: middle !important; min-width:200px'],
                                'options' => ['width' => '30%'],
                                'format' => 'email',
                            ],
                            [
                                'attribute' => 'roles',
                                'format' => 'raw',
                                'filter' => User::getRoles(),
                                'headerOptions' => ['style' => 'text-align: center !important; vertical-align: middle !important; min-width:150px; white-space: nowrap'],
                                'contentOptions' => ['style' => 'text-align: center !important; vertical-align: middle !important; min-width:150px;'],
                                'options' => ['width' => '20%'],
                                'value' => function ($model, $key, $index, $column) {
                                    /** @var User $model */
                                    /** @var \yii\grid\DataColumn $column */
                                    $value = $model->{$column->attribute};
                                    switch ($value) {
                                        case $model->roles == 'admin':
                                            $class = 'danger';
                                            break;
                                        case $model->roles == 'editor':
                                            $class = 'primary';
                                            break;
                                        default:
                                            $class = 'secondary';
                                    };
                                    $html = Html::tag('span', Html::encode($model->getRolesName()), ['class' => 'badge bg-' . $class]);
                                    return empty($value) ? null : $html;
                                },
                            ],
                            [
                                'filter' => User::getStatusesArray(),
                                'attribute' => 'status',
                                'options' => ['width' => '20%'],
                                'headerOptions' => ['style' => 'text-align: center !important; vertical-align: middle !important; min-width:150px; white-space: nowrap'],
                                'contentOptions' => ['style' => 'text-align: center !important; vertical-align: middle !important; min-width:150px;'],
                                'format' => 'raw',
                                'value' => function ($model, $key, $index, $column) {
                                    /** @var User $model */
                                    /** @var \yii\grid\DataColumn $column */
                                    $value = $model->{$column->attribute};
                                    switch ($value) {
                                        case User::STATUS_ACTIVE:
                                            $class = 'success';
                                            break;
                                        case User::STATUS_INACTIVE:
                                            $class = 'danger';
                                            break;
                                        default:
                                            $class = 'default';
                                    };
                                    $html = Html::tag('span', Html::encode($model->getStatusName()), ['class' => 'badge bg-' . $class]);
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