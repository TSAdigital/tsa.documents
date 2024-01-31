<?php

use app\models\Document;
use app\models\DocumentType;
use app\models\View;
use yii\bootstrap4\Modal;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\StringHelper;
use yii\grid\GridView;
use yii\widgets\ListView;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var app\models\DocumentSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Документы';
$this->params['breadcrumbs'][] = $this->title;
$this->params['buttons'] = [
        'create' => (Yii::$app->user->can('admin') or Yii::$app->user->can('createDocuments')) ? Html::a('<i class="fas fa-plus-circle text-success"></i>Добавить', ['create'], ['class' => 'btn btn-app']) : null,
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
                'itemView' => '_list-document',
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
                        'options' => ['class' => 'table-responsive', 'autocomplete' => 'off'],
                        'tableOptions' => ['class' => 'table table-striped'],
                        'columns' => [
                            [
                                'class' => 'yii\grid\SerialColumn',
                                'headerOptions' => ['style' => 'text-align: center !important; vertical-align: middle !important; white-space: nowrap'],
                                'contentOptions' => ['style' => 'text-align: center !important; vertical-align: middle !important; white-space: nowrap']
                            ],
                            [
                                'attribute' => 'number',
                                'options' => ['width' => '10%'],
                                'headerOptions' => ['style' => 'text-align: center !important; vertical-align: middle !important; min-width:120px; white-space: nowrap'],
                                'contentOptions' => ['style' => 'text-align: center !important; vertical-align: middle !important; min-width:120px;', 'class' => 'truncate'],
                            ],
                            [
                                'attribute' => 'name',
                                'options' => ['width' => '55%'],
                                'filterInputOptions' => [
                                    'class' => 'form-control',
                                    'autocomplete' => 'off'
                                ],
                                'format' => 'raw',
                                'headerOptions' => ['style' => 'vertical-align: middle !important; min-width:200px; white-space: nowrap'],
                                'contentOptions' => ['style' => 'vertical-align: middle !important; min-width:200px', 'class' => 'truncate'],
                                'value' => function ($model) {
                                    $user_id = Yii::$app->user->identity->id;

                                    $query = View::findOne(['type' => 'document', 'record_id' => $model->id, 'user_id' => $user_id]);

                                    if($model->resolution != NULL and is_array($model->resolution)){
                                        if(in_array($user_id, $model->resolution) or $model->executor_id == $user_id){
                                            $view = (($query == null and $model->user_id != $user_id) or ($query == null and $model->executor_id == $user_id));
                                        }else{
                                            $view = false;
                                        }
                                    }else{
                                        $view = ($query == null and $model->user_id != $user_id);
                                    }

                                    return ($view ? '<small><i class="fas fa-exclamation-circle text-danger mr-1" data-toggle="tooltip" data-placement="top" title="Новый документ"></i></small>' : null) . Html::a(Html::encode($model->name), ['document/view', 'id' => $model->id], ['data-pjax' => 0]);
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
                                'headerOptions' => ['style' => 'text-align: center !important; vertical-align: middle !important; min-width:120px; white-space: nowrap'],
                                'contentOptions' => ['style' => 'text-align: center !important; vertical-align: middle !important; min-width:120px;'],
                            ],
                            [
                                'attribute' => 'type',
                                'options' => ['width' => '10%'],
                                'filter' => ArrayHelper::map(DocumentType::find()->all(), 'id', 'name'),
                                'headerOptions' => ['style' => 'text-align: center !important; vertical-align: middle !important; min-width:160px; white-space: nowrap'],
                                'contentOptions' => ['style' => 'text-align: center !important; vertical-align: middle !important; min-width:160px;'],
                                'value' => function ($model) { return $model->type0->name; }
                            ],
                            [
                                'filter' => Document::getStatusesArray(),
                                'attribute' => 'status',
                                'options' => ['width' => '15%'],
                                'headerOptions' => ['style' => 'text-align: center !important; vertical-align: middle !important; min-width:165px; white-space: nowrap'],
                                'contentOptions' => ['style' => 'text-align: center !important; vertical-align: middle !important; min-width:165px;'],
                                'format' => 'raw',
                                'value' => function ($model, $key, $index, $column) {
                                    /** @var Document $model */
                                    /** @var \yii\grid\DataColumn $column */
                                    $value = $model->{$column->attribute};
                                    switch ($value) {
                                        case Document::STATUS_ACTIVE:
                                            $class = 'success';
                                            break;
                                        case Document::STATUS_INACTIVE:
                                            $class = 'danger';
                                            break;
                                        case Document::STATUS_DRAFT:
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
