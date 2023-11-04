<?php

/* @var $this yii\web\View */
/* @var $user app\models\User */
/* @var $new_document app\models\Document */
/* @var $new_task app\models\Task */
/* @var $document_favourites app\models\DocumentFavourites */
/* @var $task_favourites app\models\TaskFavourites */
/* @var $news app\models\News */

/* @var integer $documents_count */
/* @var integer $tasks_count */

use yii\helpers\HtmlPurifier;
use yii\widgets\ListView;

$this->title = 'Личный кабинет';
$this->params['breadcrumbs'][] = 'Личный кабинет';
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6">
            <div class="card card-primary card-outline">
                <div class="card-body box-profile">
                    <div class="text-center">
                        <i class="far fa-user" style="font-size: 24px;"></i>
                    </div>
                    <h3 class="profile-username text-center"><?= HtmlPurifier::process($user->employee_name) ?></h3>
                    <p class="text-muted text-center mb-1"><?= HtmlPurifier::process($user->employee ? $user->employee->position->name : $user->getRolesName()) ?></p>
                    <p class="text-muted text-center mb-0"><?= HtmlPurifier::process(Yii::$app->formatter->asEmail($user->email)) ?></p>
                </div>
            </div>
            <div class="accordion" id="accordionAccount">
                <div class="card">
                    <div class="card-header p-2" id="headingOne">
                        <h2 class="mb-0">
                            <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                Документы <span class="badge badge-danger text-bold"><?= ($documents_count > 0) ? $documents_count : false ?></span>
                            </button>
                        </h2>
                    </div>
                    <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionAccount">
                        <div class="card-body p-1">
                            <div class="card-header p-2">
                                <ul class="nav nav-pills ">
                                    <li class="nav-item float-right"><a class="nav-link active" href="#document-new" data-toggle="tab">Новые</a></li>
                                    <li class="nav-item float-right"><a class="nav-link" href="#favourites-document" data-toggle="tab">Избранные</a></li>
                                </ul>
                            </div>
                            <div class="card-body">
                                <div class="tab-content">
                                    <div class="tab-pane active" id="document-new">

                                        <?php
                                        $template = '
                                                {summary}  
                                                <div class="table-responsive">
                                                <table class="table table-striped table-bordered mb-0">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">#</th>
                                                        <th scope="col" class="col-7 col-md-8 col-lg-9" style="white-space: nowrap">Наименование</th>
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
                                            'dataProvider' => $new_document,
                                            'layout' => $template,
                                            'emptyText' => 'Нет новых документов.',
                                            'viewParams' => [
                                                'page_size' => $new_document->pagination->pageSize,
                                                'current_page' => (int) is_numeric(Yii::$app->request->get('page-document-new')) ? Yii::$app->request->get('page-document-new') : 0
                                            ],
                                            'itemView' => '_list-document-new',
                                            'pager' => [
                                                'options' => [
                                                    'id' => 'list-viewed-pagination',
                                                ]
                                            ],
                                        ]);
                                        ?>

                                    </div>
                                    <div class="tab-pane" id="favourites-document">

                                        <?= ListView::widget([
                                            'dataProvider' => $document_favourites,
                                            'layout' => $template,
                                            'emptyText' => 'Нет документов в избранном.',
                                            'viewParams' => [
                                                'page_size' => $document_favourites->pagination->pageSize,
                                                'current_page' => (int) is_numeric(Yii::$app->request->get('page-document')) ? Yii::$app->request->get('page-document') : 0
                                            ],
                                            'itemView' => '_list-document-favourites',
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
                <div class="card">
                    <div class="card-header p-2" id="headingTwo">
                        <h2 class="mb-0">
                            <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                Задачи <span class="badge badge-danger text-bold"><?= ($tasks_count > 0) ? $tasks_count : false ?></span>
                            </button>
                        </h2>
                    </div>
                    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionAccount">
                        <div class="card-body p-1">
                            <div class="card-header p-2">
                                <ul class="nav nav-pills ">
                                    <li class="nav-item float-right"><a class="nav-link active" href="#task-new" data-toggle="tab">Новые</a></li>
                                    <li class="nav-item float-right"><a class="nav-link" href="#favourites-task" data-toggle="tab">Избранные</a></li>
                                </ul>
                            </div>
                            <div class="card-body">
                                <div class="tab-content">
                                    <div class="tab-pane active" id="task-new">

                                        <?= ListView::widget([
                                            'dataProvider' => $new_task,
                                            'layout' => $template,
                                            'emptyText' => 'Нет новых задач.',
                                            'viewParams' => [
                                                'page_size' => $new_task->pagination->pageSize,
                                                'current_page' => (int) is_numeric(Yii::$app->request->get('page-task-new')) ? Yii::$app->request->get('page-task-new') : 0
                                            ],
                                            'itemView' => '_list-task-new',
                                            'pager' => [
                                                'options' => [
                                                    'id' => 'list-viewed-pagination',
                                                ]
                                            ],
                                        ]);
                                        ?>

                                    </div>

                                    <div class="tab-pane" id="favourites-task">

                                        <?= ListView::widget([
                                            'dataProvider' => $task_favourites,
                                            'layout' => $template,
                                            'emptyText' => 'Нет задач в избранном.',
                                            'viewParams' => [
                                                'page_size' => $task_favourites->pagination->pageSize,
                                                'current_page' => (int) is_numeric(Yii::$app->request->get('page-task')) ? Yii::$app->request->get('page-task') : 0
                                            ],
                                            'itemView' => '_list-task-favourites',
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
        <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6">

            <div class="card">
                <div class="card-header p-2">
                    <ul class="nav nav-pills">
                        <li class="nav-item"><a class="nav-link active" href="#news" data-toggle="tab">Новости и объявления</a></li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content">
                        <div class="active tab-pane" id="news">

                            <?= ListView::widget([
                                'dataProvider' => $news,
                                //'layout' => $template,
                                'emptyText' => 'Новости еще не добавляли.',
                                'viewParams' => [
                                    'page_size' => $news->pagination->pageSize,
                                    'current_page' => (int) is_numeric(Yii::$app->request->get('page-news')) ? Yii::$app->request->get('page-news') : 0
                                ],
                                'itemView' => '_list-news',
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