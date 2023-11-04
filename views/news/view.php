<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\News $model */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Новости', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$this->params['buttons'] = [
    'update' => Html::a('<i class="fas fa-edit text-primary"></i> Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-app']),
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
        <div class="col-md-12">
            <div class="card">
                <div class="card-body d-block d-sm-none p-2">
                    <table class="table table-striped table-bordered mb-0">
                        <tbody>
                        <tr>
                            <td><p class="mb-0"><b>Тема: </b><?= Html::encode($model->title) ?></p></td>
                        </tr>
                        <tr>
                            <td><p class="mb-0"><b>Текст: </b><?= Yii::$app->formatter->asNtext($model->text) ?></p></td>
                        </tr>
                        <tr>
                            <td><p class="mb-0"><b>Автор: </b><?= Yii::$app->formatter->asNtext($model->user->getEmployee_name()) ?></p></td>
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
                <div class="card-body d-none d-sm-block pb-1">
                    <div class="table-responsive">

                        <?= DetailView::widget([
                            'model' => $model,
                            'attributes' => [
                                [
                                    'attribute' => 'title',
                                    'captionOptions' => ['width' => '170px'],
                                ],
                                [
                                    'attribute' => 'text',
                                    'format' => 'ntext',
                                ],
                                [
                                    'attribute' => 'user_id',
                                    'value' => $model->user->getEmployee_name(),
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
            </div>
        </div>
    </div>
</div>
